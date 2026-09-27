<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\AiChatRequest;
use App\Models\AiConversation;
use App\Services\GroqService;
use Illuminate\Http\Request;

class AiAssistantController extends Controller
{
    public function __construct(protected GroqService $groqService) {}

    public function index(Request $request)
    {
        abort_if($request->user()->role !== 'patient', 403);

        $history = AiConversation::where('patient_id', $request->user()->patient->id)
            ->oldest('id')
            ->get(['role', 'message', 'created_at']);

        return view('ai.index', compact('history'));
    }

    public function chat(AiChatRequest $request)
    {
        $patient = $request->user()->patient;

        AiConversation::create([
            'patient_id' => $patient->id,
            'role' => 'user',
            'message' => $request->message,
        ]);

        $history = AiConversation::where('patient_id', $patient->id)
            ->latest('id')
            ->take(20)
            ->get()
            ->reverse()
            ->map(fn ($m) => ['role' => $m->role, 'content' => $m->message])
            ->values()
            ->toArray();

        try {
            $reply = $this->groqService->chat($history, $patient);
        } catch (\RuntimeException $e) {
            return redirect()->route('web.ai.index')
                ->with('error', $e->getMessage());
        }

        AiConversation::create([
            'patient_id' => $patient->id,
            'role' => 'assistant',
            'message' => $reply,
        ]);

        return redirect()->route('web.ai.index')
            ->with('success', 'Reply received.');
    }

    public function clear(Request $request)
    {
        abort_if($request->user()->role !== 'patient', 403);

        AiConversation::where('patient_id', $request->user()->patient->id)->delete();

        return redirect()->route('web.ai.index')
            ->with('success', 'Chat history cleared.');
    }
}
