<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteMenuItem extends Model
{
    use HasFactory;

    protected $fillable = ['website_menu_id', 'parent_id', 'name', 'url', 'route', 'route_params', 'icon', 'target_blank', 'status', 'position', 'description'];

    protected $casts = [
        'status' => 'boolean',
        'target_blank' => 'boolean',
        'route_params' => 'array',
    ];

    public function menu()
    {
        return $this->belongsTo(WebsiteMenu::class);
    }

    public function parent()
    {
        return $this->belongsTo(WebsiteMenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(WebsiteMenuItem::class, 'parent_id')->where('status', true)->orderBy('position');
    }

    public function allChildren()
    {
        return $this->hasMany(WebsiteMenuItem::class, 'parent_id')->orderBy('position');
    }

    public function getLinkAttribute()
    {
        if ($this->route) {
            return route($this->route, $this->route_params ?? []);
        }
        return $this->url;
    }
}