<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
use App\Notifications\AppointmentBooked;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    protected string $apiKey;

    protected string $model = 'openai/gpt-oss-20b';

    protected string $baseUrl = 'https://api.groq.com/openai/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key');
    }

    public function chat(array $conversationHistory, ?Patient $patient = null): string
    {
        $messages = [
            ['role' => 'system', 'content' => $this->systemPrompt()],
        ];

        if ($patient) {
            $messages[] = ['role' => 'system', 'content' => $this->databaseContext($patient)];
        }

        $messages = array_merge($messages, $conversationHistory);

        $payload = [
            'model' => $this->model,
            'messages' => $messages,
            'temperature' => 0.4,
            'max_tokens' => 500,
        ];

        if ($patient) {
            $payload['tools'] = $this->tools();
            $payload['tool_choice'] = 'auto';
        }

        for ($round = 0; $round < 3; $round++) {
            $payload['messages'] = $messages;

            $response = Http::withToken($this->apiKey)
                ->timeout(30)
                ->post($this->baseUrl, $payload);

            if ($response->failed()) {
                Log::error('Groq API error', ['status' => $response->status(), 'body' => $response->body()]);

                throw new \RuntimeException('The AI assistant is currently unavailable. Please try again later.');
            }

            $message = $response->json('choices.0.message', []);
            $toolCalls = $message['tool_calls'] ?? [];

            if (empty($toolCalls) || ! $patient) {
                return $message['content'] ?? 'I could not generate a reply. Please try again.';
            }

            $messages[] = [
                'role' => 'assistant',
                'content' => $message['content'] ?? null,
                'tool_calls' => $toolCalls,
            ];

            foreach ($toolCalls as $call) {
                $messages[] = [
                    'role' => 'tool',
                    'tool_call_id' => $call['id'],
                    'content' => $this->executeTool(
                        $call['function']['name'] ?? '',
                        json_decode($call['function']['arguments'] ?? '{}', true) ?? [],
                        $patient
                    ),
                ];
            }
        }

        return 'I could not complete that request. Please try again.';
    }

    protected function tools(): array
    {
        return [
            [
                'type' => 'function',
                'function' => [
                    'name' => 'check_availability',
                    'description' => 'Check which time slots of a doctor are already booked on a given date.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'doctor' => ['type' => 'string', 'description' => 'Doctor name or specialization, e.g. "Dr.VK" or "oncology"'],
                            'date' => ['type' => 'string', 'description' => 'Date as YYYY-MM-DD'],
                        ],
                        'required' => ['doctor', 'date'],
                    ],
                ],
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'book_appointment',
                    'description' => 'Book an appointment for the patient you are talking to. Only call this when the doctor, date and time are all known.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'doctor' => ['type' => 'string', 'description' => 'Doctor name or specialization, e.g. "Dr.VK" or "oncology"'],
                            'date' => ['type' => 'string', 'description' => 'Date as YYYY-MM-DD, must be today or later'],
                            'time' => ['type' => 'string', 'description' => 'Time as 24-hour HH:MM, e.g. "10:00"'],
                        ],
                        'required' => ['doctor', 'date', 'time'],
                    ],
                ],
            ],
        ];
    }

    protected function executeTool(string $name, array $args, Patient $patient): string
    {
        return json_encode(match ($name) {
            'check_availability' => $this->checkAvailability($args),
            'book_appointment' => $this->bookAppointment($args, $patient),
            default => ['ok' => false, 'error' => "Unknown tool: {$name}"],
        });
    }

    protected function findDoctor(string $query): ?Doctor
    {
        $query = mb_strtolower(trim($query));

        return Doctor::with(['user', 'department'])->get()->first(
            fn ($d) => str_contains(mb_strtolower($d->user->name), $query)
                || str_contains(mb_strtolower($d->specialization), $query)
                || str_contains($query, mb_strtolower($d->specialization))
        );
    }

    protected function checkAvailability(array $args): array
    {
        $doctor = $this->findDoctor($args['doctor'] ?? '');

        if (! $doctor) {
            return ['ok' => false, 'error' => 'No matching doctor found. Ask the patient which doctor they mean.'];
        }

        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $args['date'] ?? '')) {
            return ['ok' => false, 'error' => 'Date must be in YYYY-MM-DD format.'];
        }

        $booked = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $args['date'])
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->orderBy('appointment_time')
            ->pluck('appointment_time')
            ->map(fn ($t) => substr((string) $t, 0, 5))
            ->all();

        return [
            'ok' => true,
            'doctor' => $doctor->user->name,
            'date' => $args['date'],
            'available' => (bool) $doctor->is_available,
            'booked_times' => $booked,
        ];
    }

    protected function bookAppointment(array $args, Patient $patient): array
    {
        $doctor = $this->findDoctor($args['doctor'] ?? '');

        if (! $doctor) {
            return ['ok' => false, 'error' => 'No matching doctor found. Ask the patient which doctor they mean.'];
        }

        if (! $doctor->is_available) {
            return ['ok' => false, 'error' => "{$doctor->user->name} is currently unavailable. Suggest another doctor."];
        }

        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $args['date'] ?? '') || $args['date'] < today()->format('Y-m-d')) {
            return ['ok' => false, 'error' => 'Date must be YYYY-MM-DD and today or later.'];
        }

        if (! preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $args['time'] ?? '')) {
            return ['ok' => false, 'error' => 'Time must be 24-hour HH:MM, e.g. "10:00".'];
        }

        $taken = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $args['date'])
            ->where('appointment_time', $args['time'].':00')
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->exists();

        if ($taken) {
            return ['ok' => false, 'error' => 'That slot is already booked. Check availability and suggest a free time.'];
        }

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'appointment_date' => $args['date'],
            'appointment_time' => $args['time'],
            'reason' => 'Booked via AI assistant',
            'status' => 'pending',
        ]);

        $patient->user->notify(new AppointmentBooked($appointment));

        return [
            'ok' => true,
            'appointment_id' => $appointment->id,
            'doctor' => $doctor->user->name,
            'date' => $args['date'],
            'time' => $args['time'],
            'status' => 'pending',
        ];
    }

    protected function systemPrompt(): string
    {
        return <<<'PROMPT'
You are the MediCare AI Patient Assistant, a helpful assistant for a hospital's patient portal.

You can help with:
- General health information and understanding common medical terminology
- Explaining hospital services and departments
- Booking appointments using the check_availability and book_appointment tools
- Explaining how to access their medical records, prescriptions, and lab reports on this platform

BOOKING RULES YOU MUST ALWAYS FOLLOW:
- You can ONLY check availability or book through the provided tools. NEVER claim you checked a schedule or booked a slot unless the tool result confirms it.
- Only call book_appointment when the doctor, the date and the time are ALL known. If anything is missing, ask the patient first.
- Resolve relative dates yourself using today's date from the live data (e.g. "tomorrow" means tomorrow's YYYY-MM-DD date).
- Every booking is for the patient you are talking to, and is created with status "pending".
- If a tool reports an error (unknown doctor, past date, slot taken), relay it honestly and offer an alternative — never pretend the booking succeeded.

STRICT RULES YOU MUST ALWAYS FOLLOW:
- You must NEVER attempt to diagnose a disease or medical condition.
- You must NEVER replace a doctor's judgment or tell a patient what treatment to take.
- If a patient describes symptoms that could be serious or urgent (e.g. chest pain, difficulty breathing, severe bleeding, loss of consciousness, signs of stroke), you must clearly and immediately advise them to seek emergency medical care or contact a doctor right away, rather than continuing to discuss the symptoms casually.
- Keep answers concise, warm, and easy to understand for a non-medical audience.
- If you are unsure or a question falls outside hospital-navigation or general health education, say so honestly and suggest the patient consult hospital staff or their doctor.
- This portal is a website. Never mention a mobile app or any feature that is not described here.
PROMPT;
    }

    protected function databaseContext(Patient $patient): string
    {
        $patient->loadMissing('user');

        $doctors = Doctor::with(['user', 'department'])->orderBy('id')->take(50)->get()
            ->map(fn ($d) => '- '.$d->user->name.' ('.$d->specialization.', '.($d->department->name ?? 'no department').')'
                .($d->is_available ? '' : ' [currently unavailable]'));

        $departments = Department::orderBy('name')->pluck('name');

        $upcoming = $patient->appointments()->with('doctor.user')
            ->where('appointment_date', '>=', today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('appointment_date')
            ->take(5)
            ->get()
            ->map(fn ($a) => '- '.$a->appointment_date->format('M j, Y').' at '.$a->appointment_time
                .' with '.$a->doctor->user->name.' ('.$a->status.')');

        $pendingInvoices = $patient->invoices()->where('payment_status', 'pending')->get(['invoice_number', 'total']);

        $recentPrescriptions = $patient->prescriptions()->with('items')->latest()->take(3)->get()
            ->map(fn ($p) => '- #'.$p->id.' ('.$p->created_at->format('M j, Y').'): '
                .$p->items->map(fn ($i) => $i->medicine_name.' '.$i->dosage)->join(', '));

        $lines = [
            'LIVE HOSPITAL DATA — use this to answer. When asked about doctors, departments, or this patient\'s own care, answer ONLY from the data below. Never invent doctors, departments, or appointments.',
            '',
            'Talking to patient: '.$patient->user->name,
            'Today is: '.today()->format('Y-m-d (l)'),
            '',
            'Doctors (name, specialization, department):',
            $doctors->isEmpty() ? '(none registered)' : $doctors->join("\n"),
            '',
            'Departments: '.($departments->isEmpty() ? '(none)' : $departments->join(', ')),
            '',
            'Patient\'s upcoming appointments:',
            $upcoming->isEmpty() ? '(none)' : $upcoming->join("\n"),
            '',
            'Patient\'s pending invoices: '.($pendingInvoices->isEmpty()
                ? '(none)'
                : $pendingInvoices->map(fn ($i) => $i->invoice_number.' ('.number_format($i->total, 2).')')->join(', ')),
            '',
            'Patient\'s recent prescriptions:',
            $recentPrescriptions->isEmpty() ? '(none)' : $recentPrescriptions->join("\n"),
        ];

        return implode("\n", $lines);
    }
}
