<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'exists:patients,id'],
            'appointment_id' => ['nullable', 'exists:appointments,id'],
            'symptoms' => ['nullable', 'string'],
            'diagnosis' => ['nullable', 'string'],
            'treatment' => ['nullable', 'string'],
            'doctor_notes' => ['nullable', 'string'],
            'follow_up_date' => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }
}
