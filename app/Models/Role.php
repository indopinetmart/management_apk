<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name','description'];

    public function menus()
    {
        return $this->belongsToMany(Menu::class,'role_menus');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'role_permissions');
    }

    public function users()
    {
         return $this->hasMany(User::class, 'role_id', 'id');
    }
}

