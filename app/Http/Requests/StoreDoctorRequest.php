<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'department_id' => ['required', 'exists:departments,id'],
            'specialization' => ['required', 'string', 'max:255'],
            'license_number' => ['required', 'string', 'unique:doctors,license_number'],
            'experience_years' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
