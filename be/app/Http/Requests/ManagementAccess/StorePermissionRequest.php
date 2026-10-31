<?php

namespace App\Http\Requests\ManagementAccess;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize() : bool
    {
        return $this->user()->hasAnyRole(['super-admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules() : array
    {
        return [
            'name' => ['required', 'string'],
            'guard_name' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'roles.*' => ['nullable', 'string'],
        ];
    }

    public function messages() : array
    {
        return [
            'name.required' => 'A name is required',
            'string' => 'This field must be a valid string',
            // 'starts_with' => 'Icon must start with "bx-" or "bxs-"',
        ];
    }
}
