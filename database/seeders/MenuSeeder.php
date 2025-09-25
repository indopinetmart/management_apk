<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Role;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // Menu utama
        $dashboard = Menu::create(['name'=>'Dashboard', 'route'=>'/dashboard']);
        $users = Menu::create(['name'=>'Users', 'route'=>'/users']);
        $settings = Menu::create(['name'=>'Settings', 'route'=>'/settings']);

        // Sub-menu
        $addUser = Menu::create(['name'=>'Add User', 'route'=>'/users/add', 'parent_id'=>$users->id]);
        $userList = Menu::create(['name'=>'User List', 'route'=>'/users/list', 'parent_id'=>$users->id]);

        // Kaitkan menu ke role
        $superadmin = Role::where('name','superadmin')->first();
        $admin = Role::where('name','admin')->first();

        // Superadmin boleh akses semua menu
        $superadmin->menus()->attach([$dashboard->id, $users->id, $settings->id, $addUser->id, $userList->id]);

        // Admin hanya boleh akses dashboard & user list
        $admin->menus()->attach([$dashboard->id, $users->id, $userList->id]);
    }
}
