<?php
namespace App\Http\Requests\ManagementAccess;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuItemRequest extends FormRequest
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
            'route' => ['required', 'string'],
            'permission_name' => ['required', 'string'],
            // 'icon' => ['sometimes', 'required', 'string', 'starts_with:ri-'],
            'status' => ['sometimes', 'required', 'boolean'],
        ];
    }

    public function messages() : array
    {
        return [
            'name.required' => 'A name is required',
            'permission_name.required' => 'A permission name is required',
            'route.required' => 'A route is required',
            'string' => 'This field must be a valid string',
            // 'starts_with' => 'Icon must start with "bx-" or "bxs-"',
        ];
    }
}
