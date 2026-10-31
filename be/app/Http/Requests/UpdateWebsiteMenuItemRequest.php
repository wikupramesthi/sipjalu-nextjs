<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWebsiteMenuItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $menuParam = $this->route('website_menu') ?? $this->route('websiteMenu');
        $menuId = $menuParam instanceof \Illuminate\Database\Eloquent\Model ? $menuParam->id : $menuParam;
        $this->merge([
            'target_blank' => $this->boolean('target_blank'),
            'status' => $this->boolean('status'),
            'website_menu_id' => $menuId ?? $this->input('website_menu_id'),
            'parent_id' => $this->input('parent_id') !== '' ? $this->input('parent_id') : null,
            'url' => $this->input('url') !== '' ? $this->input('url') : null,
            'route' => $this->input('route') !== '' ? $this->input('route') : null,
            'route_params' => $this->input('route_params') !== '' ? $this->input('route_params') : null,
            'icon' => $this->input('icon') !== '' ? $this->input('icon') : null,
            'description' => $this->input('description') !== '' ? $this->input('description') : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'parent_id' => 'nullable|exists:website_menu_items,id',
            'name' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'route' => 'nullable|string|max:255',
            'route_params' => 'nullable|json',
            'icon' => 'nullable|string|max:100',
            'target_blank' => 'required|boolean',
            'status' => 'required|boolean',
            'position' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ];
    }
}