<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'category' => 'required|max:255',
            'publish_year' => 'required|integer|min:1800|max:'.date('Y'),
        ];
    }
}
