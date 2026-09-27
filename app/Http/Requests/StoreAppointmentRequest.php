<?php

namespace App\Http\Requests;

use App\Models\Appointment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => ['nullable', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required', 'date_format:H:i'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $taken = Appointment::where('doctor_id', $this->doctor_id)
                ->where('appointment_date', $this->appointment_date)
                ->where('appointment_time', $this->appointment_time)
                ->whereNotIn('status', ['cancelled', 'no_show'])
                ->exists();

            if ($taken) {
                $validator->errors()->add('appointment_time', 'This time slot is already booked for the selected doctor.');
            }
        });
    }
}
