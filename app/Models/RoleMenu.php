<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleMenu extends Model
{
    protected $table = 'role_menus'; // nama tabel
    public $timestamps = false; // kalau tabel pivot tidak ada timestamps
    protected $fillable = ['role_id', 'menu_id'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class,  'menu_id');
    }
}
