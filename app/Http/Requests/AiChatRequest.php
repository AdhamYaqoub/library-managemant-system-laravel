<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AiChatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => [
                'required',
                'string',
                'min:2',
                'max:2000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' =>
                'Message is required.',

            'message.string' =>
                'Message must be a string.',

            'message.min' =>
                'Message must contain at least 2 characters.',

            'message.max' =>
                'Message cannot exceed 2000 characters.',
        ];
    }
}