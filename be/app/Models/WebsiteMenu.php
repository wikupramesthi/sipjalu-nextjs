<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteMenu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'location', 'status', 'position', 'description'];

    protected $casts = ['status' => 'boolean'];

    public function items()
    {
        return $this->hasMany(WebsiteMenuItem::class)->whereNull('parent_id')->orderBy('position');
    }

    public function activeItems()
    {
        return $this->hasMany(WebsiteMenuItem::class)->whereNull('parent_id')->where('status', true)->orderBy('position');
    }

    public function allItems()
    {
        return $this->hasMany(WebsiteMenuItem::class)->orderBy('position');
    }
}