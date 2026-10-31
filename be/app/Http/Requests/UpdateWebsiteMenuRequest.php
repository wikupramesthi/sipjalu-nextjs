<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWebsiteMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => $this->boolean('status'),
            'position' => $this->input('position') !== '' && $this->input('position') !== null ? (int) $this->input('position') : 0,
            'description' => $this->input('description') !== '' ? $this->input('description') : null,
        ]);
    }

    public function rules(): array
    {
        $menuParam = $this->route('website_menu') ?? $this->route('websiteMenu');
        $menuId = $menuParam instanceof \Illuminate\Database\Eloquent\Model ? $menuParam->getKey() : $menuParam;

        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:website_menus,slug,' . $menuId,
            'location' => 'required|in:header,footer,sidebar,mobile',
            'status' => 'boolean',
            'position' => 'integer|min:0',
            'description' => 'nullable|string',
        ];
    }
}