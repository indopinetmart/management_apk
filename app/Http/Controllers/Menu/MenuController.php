<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
    {
        $roleId = Auth::user()->role_id; // pastikan user punya relasi role_id

        $menus = Menu::whereNull('parent_id')
            ->whereHas('roles', function ($q) use ($roleId) {
                $q->where('roles.id', $roleId);
            })
            ->with(['children' => function ($q) use ($roleId) {
                $q->whereHas('roles', function ($q) use ($roleId) {
                    $q->where('roles.id', $roleId);
                });
            }])
            ->get();

        return response()->json($menus);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'route' => 'nullable|string',
            'parent_id' => 'nullable|exists:menus,id'
        ]);

        $menu = Menu::create($request->all());
        return response()->json($menu);
    }
}
