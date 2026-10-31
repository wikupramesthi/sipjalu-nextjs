<?php

namespace App\Models\ManagementAccess;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuGroup extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'status', 'permission_name', 'icon', 'position'];

    protected $casts = ['status' => 'boolean'];

    public function items()
    {
        return $this->hasMany(MenuItem::class);
    }
}
