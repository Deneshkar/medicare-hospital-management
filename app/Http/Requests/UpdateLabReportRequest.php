<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLabReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['sometimes', Rule::in(['requested', 'processing', 'completed', 'cancelled'])],
            'result' => ['sometimes', 'nullable', 'string'],
            'doctor_notes' => ['sometimes', 'nullable', 'string'],
            'report_file' => ['sometimes', 'nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }
}
