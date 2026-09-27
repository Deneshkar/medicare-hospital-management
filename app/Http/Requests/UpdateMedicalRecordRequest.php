<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicalRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'symptoms' => ['sometimes', 'nullable', 'string'],
            'diagnosis' => ['sometimes', 'nullable', 'string'],
            'treatment' => ['sometimes', 'nullable', 'string'],
            'doctor_notes' => ['sometimes', 'nullable', 'string'],
            'follow_up_date' => ['sometimes', 'nullable', 'date', 'after_or_equal:today'],
        ];
    }
}
