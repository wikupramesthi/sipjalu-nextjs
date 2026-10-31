<?php

namespace App\Http\Requests\ManagementAccess;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
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
            'name' => ['required', 'string', Rule::unique('roles')->ignore($this->role)],
            'guard_name' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'permissions.*' => ['nullable', 'string'],
        ];
    }

    public function messages() : array
    {
        return [
            'name.required' => 'A name is required',
            'name.unique' => 'A name is already in use',
            'string' => 'This field must be a valid string',
        ];
    }
}
