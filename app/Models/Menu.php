<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;


class Menu extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public $timestamps  = true;

    public function parent()
    {
        return $this->hasOne(Menu::class, 'id', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    // ASSESSORS
    // public function getRoleNamesAttribute()
    // {
    //     $menu_roles = $this->roles->pluck('name');

    //     foreach ($menu_roles as $role) {
    //         $role_names[] = $role;
    //     }

    //     return !empty($role_names) > 0 ? implode(', ', $role_names) : '-';
    // }
}
