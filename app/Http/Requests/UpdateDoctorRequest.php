<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $doctorId = $this->route('doctor')->id;

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', Rule::unique('users', 'email')->ignore($this->route('doctor')->user_id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'department_id' => ['sometimes', 'exists:departments,id'],
            'specialization' => ['sometimes', 'string', 'max:255'],
            'license_number' => ['sometimes', 'string', Rule::unique('doctors', 'license_number')->ignore($doctorId)],
            'experience_years' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
