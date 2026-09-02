<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBorrowingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'returned_at' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'returned_at.date' => 'Returned date must be a valid date.',
        ];
    }
}
