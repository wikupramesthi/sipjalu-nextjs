<?php

namespace App\Http\Requests\ManagementAccess;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
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
            'email' => ['required', 'string', 'email:rfc', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(12)->letters()->mixedCase()->numbers()],
            'role' => ['required', 'string', \Illuminate\Validation\Rule::exists('roles', 'name')],
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
            'password.required' => 'Password wajib diisi minimal 12 karakter, kombinasi huruf besar/kecil dan angka.',
            'role.required' => 'A role is required',
            'string' => 'This field must be a valid string',
        ];
    }
}
