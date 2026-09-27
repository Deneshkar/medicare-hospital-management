<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AiChatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'patient';
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:1000'],
        ];
    }
}
