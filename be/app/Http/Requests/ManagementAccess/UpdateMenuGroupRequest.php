<?php

namespace App\Http\Requests\ManagementAccess;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuGroupRequest extends FormRequest
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
            'permission_name' => ['required', 'string'],
            'status' => ['nullable', 'boolean'],
            'icon' => ['sometimes', 'required', 'string', 'starts_with:bx-,bxs-'],
            'position' => ['nullable', 'numeric'],
        ];
    }

    public function messages() : array
    {
        return [
            'name.required' => 'A name is required',
            'permission_name.required' => 'A permission name is required',
            'icon.required' => 'A icon is required',
            'string' => 'This field must be a valid string',
            'starts_with' => 'Icon must start with "bx-" or "bxs-"',
        ];
    }
}
