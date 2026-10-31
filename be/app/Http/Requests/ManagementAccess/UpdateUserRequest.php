<?php

namespace App\Http\Requests\ManagementAccess;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc', 'max:255', Rule::unique('users', 'email')->ignore($this->route('user'))],
            'role' => ['required', 'string', Rule::exists('roles', 'name')],
            'verified' => ['nullable', 'boolean'],
        ];
    }

    public function messages() : array
    {
        return [
            'name.required' => 'A name is required',
            'email.required' => 'A email is required',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'A email has already in use',
            'role.required' => 'A role is required',
            'string' => 'This field must be a valid string',
        ];
    }
}
