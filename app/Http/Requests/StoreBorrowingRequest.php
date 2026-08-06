<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBorrowingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'book_id' => 'required|exists:books,id',
            'member_id' => 'required|exists:members,id',
        ];
    }

    public function messages(): array
    {
        return [
            'book_id.required' => 'Book ID is required.',
            'book_id.exists' => 'The selected book does not exist.',

            'member_id.required' => 'Member ID is required.',
            'member_id.exists' => 'The selected member does not exist.',
        ];
    }
}