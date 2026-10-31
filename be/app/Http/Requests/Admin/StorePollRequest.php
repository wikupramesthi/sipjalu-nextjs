<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePollRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'question' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
            'options' => ['required', 'array', 'min:2'],
            'options.*' => ['required', 'string', 'max:100'],
        ];
    }
}
