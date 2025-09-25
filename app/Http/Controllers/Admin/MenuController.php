<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function menu()
    {
        $menu = Menu::all();
        $role = Role::all();
        return view('admin.menu.index', compact('menu', 'role'));
    }


    public function getRoleAccess($roleId)
    {
        $role = Role::with(['menus', 'permissions'])->findOrFail($roleId);

        $menus = Menu::whereNull('parent_id')->with('children')->get();
        $permissions = \App\Models\Permission::all();

        return response()->json([
            'role' => $role,
            'menus' => $menus,
            'permissions' => $permissions,
        ]);
    }

    public function updateRoleAccess(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);

        $role->menus()->sync($request->menu_ids ?? []);
        $role->permissions()->sync($request->permission_ids ?? []);

        return response()->json(['message' => 'Role access updated successfully']);
    }
}
