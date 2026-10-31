<?php

namespace App\Models\ManagementAccess;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon', 'route', 'status', 'permission_name', 'menu_group_id', 'position'];

    protected $casts = ['status' => 'boolean'];
}
