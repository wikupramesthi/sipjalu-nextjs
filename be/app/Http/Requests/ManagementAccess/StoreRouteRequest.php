<?php

namespace App\Http\Requests\ManagementAccess;

use Illuminate\Foundation\Http\FormRequest;

class StoreRouteRequest extends FormRequest
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
            'route' => ['required', 'string'],
            'permission_name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'boolean'],
        ];
    }

    public function messages() : array
    {
        return [
            'permission_name.required' => 'A permission name is required',
            'route.required' => 'A route is required',
            'string' => 'This field must be a valid string',
        ];
    }
}
