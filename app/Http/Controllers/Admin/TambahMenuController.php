<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleMenu;
use App\Models\RolePermission;
use Illuminate\Http\Request;

class TambahMenuController extends Controller
{
    /**
     * Tampilkan halaman daftar menu
     */
    public function view_page()
    {
        // Ambil semua menu beserta relasi children (jika ada)
        $menus = Menu::with('children', 'parent')->get();
        $permissions = Permission::all();
        // Ambil role_menus lengkap dengan relasi role & menu
        $role_menus = RoleMenu::with(['role', 'menu'])->get();
        $role_permissions = RolePermission::with(['role', 'permission'])->get();
        $role = Role::all();

        // Map untuk menambahkan index atau format khusus jika perlu
        $menus = $menus->map(function ($menu, $index) {
            return [
                'index'      => $index + 1,
                'id'         => $menu->id,
                'name'       => $menu->name,
                'icon'       => $menu->icon,
                'route'      => $menu->route,
                'parent_id'  => $menu->parent_id,
                'parent_name' => $menu->parent ? $menu->parent->name : '-', // ✅ Tambahkan ini
                'created_at' => $menu->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $menu->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        // Map untuk menambahkan index atau format khusus jika perlu
        $permissions = $permissions->map(function ($permissions, $index) {
            return [
                'index'      => $index + 1,
                'id'         => $permissions->id,
                'name'       => $permissions->name,
                'description' => $permissions->description,
                'created_at' => $permissions->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $permissions->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        // Map role_menus supaya nama role & menu langsung tersedia
        $role_menus = $role_menus->map(function ($rm, $index) {
            return [
                'index'      => $index + 1,
                'id'         => $rm->id,
                'role_id'    => $rm->role_id,
                'role_name'  => $rm->role ? $rm->role->name : 'N/A',
                'menu_id'    => $rm->menu_id,
                'menu_name'  => $rm->menu ? $rm->menu->name : 'N/A',
                'created_at' => $rm->created_at ? $rm->created_at->format('Y-m-d H:i:s') : null,
                'updated_at' => $rm->updated_at ? $rm->updated_at->format('Y-m-d H:i:s') : null,
            ];
        });

        $role_permissions = $role_permissions->map(function ($rp, $index) {
            return [
                'index'            => $index + 1,
                'id'               => $rp->id,
                'role_id'          => $rp->role_id,
                'role_name'        => $rp->role ? $rp->role->name : 'N/A',
                'permission_id'    => $rp->permission_id,
                'permission_name'  => $rp->permission ? $rp->permission->name : 'N/A',
                'created_at'       => $rp->created_at ? $rp->created_at->format('Y-m-d H:i:s') : null,
                'updated_at'       => $rp->updated_at ? $rp->updated_at->format('Y-m-d H:i:s') : null,
            ];
        });

        return view('admin.tambah_menu.index', compact('menus', 'permissions', 'role_menus', 'role', 'role_permissions'));
    }


    // =============================================
    // TAMBAH MENU SETTING
    // =============================================

    public function menu_store(Request $request)
    {
        try {
            // ✅ Validasi data input
            $validator = Validator::make($request->all(), [
                'name_menu' => 'required|string|max:100',
                'icon'      => 'required|string|max:100',
                'parent_id' => 'nullable|integer|exists:menus,id',
                'route'     => 'required|string|max:150',
            ]);

            // Jika validasi gagal
            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'validation',
                    'message' => $validator->errors()->all(),
                ]);
            }

            // ✅ Simpan data menu baru
            $menu = new \App\Models\Menu();
            $menu->name      = $request->name_menu;
            $menu->icon      = $request->icon;
            $menu->parent_id = $request->parent_id ?: null; // Jika kosong set NULL
            $menu->route     = $request->route;
            $menu->save();

            // ✅ Log untuk debugging di Laravel
            Log::info('Menu baru berhasil disimpan', [
                'id'   => $menu->id,
                'name' => $menu->name,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Menu berhasil ditambahkan!',
                'data'    => $menu
            ]);
        } catch (\Exception $e) {
            // ✅ Tangani error & tampilkan log di Laravel
            Log::error('Gagal menyimpan menu', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function menuEditAjax($id)
    {
        try {
            // Cari data menu berdasarkan ID
            $menu = Menu::find($id);

            if (!$menu) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Menu tidak ditemukan'
                ], 404);
            }

            // Ambil semua menu untuk dropdown parent_id
            $menus = Menu::all(['id', 'name']);

            return response()->json([
                'status' => 'success',
                'result' => $menu,
                'menus'  => $menus
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengambil data menu', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Update data menu via AJAX
     */
    public function menuUpdateAjax(Request $request, $id)
    {
        try {
            // Validasi data input
            $validator = Validator::make($request->all(), [
                'name_menu' => 'required|string|max:100',
                'icon'      => 'required|string|max:100',
                'parent_id' => 'nullable|integer|exists:menus,id',
                'route'     => 'required|string|max:150',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'validation',
                    'message' => $validator->errors()->all()
                ]);
            }

            // Cari menu berdasarkan ID
            $menu = Menu::find($id);

            if (!$menu) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Menu tidak ditemukan'
                ], 404);
            }

            // Update data menu
            $menu->name      = $request->name_menu;
            $menu->icon      = $request->icon;
            $menu->parent_id = $request->parent_id ?: null;
            $menu->route     = $request->route;
            $menu->save();

            Log::info('Menu berhasil diperbarui', [
                'id'   => $menu->id,
                'name' => $menu->name,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Menu berhasil diperbarui!',
                'data'    => $menu
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui menu', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function fetchDelete($id)
    {
        try {
            $menu = Menu::find($id);

            if (!$menu) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Menu tidak ditemukan.'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'result' => $menu
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function hapusMenu(Menu $menu)
    {
        try {
            Log::info('Memeriksa relasi roles untuk menu id: ' . $menu->id);

            if ($menu->roles()->exists()) {
                Log::warning('Menu id ' . $menu->id . ' masih digunakan.');
                return response()->json([
                    'status' => 'error',
                    'type' => 'menu_in_use',
                    'message' => 'Menu ini masih digunakan oleh user.'
                ], 400);
            }

            $menu->delete();
            Log::info('Menu id ' . $menu->id . ' berhasil dihapus.');
            return response()->json([
                'status' => 'success',
                'message' => 'Menu berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error hapusMenu menu id ' . $menu->id . ': ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // =============================================
    // END TAMBAH MENU SETTING
    // =============================================


    // =============================================
    // TAMBAH PERMISSIONS SETTING
    // =============================================

    public function permissions_store(Request $request)
    {
        try {
            // ✅ Validasi data input
            $validator = Validator::make($request->all(), [
                'name_permissions' => 'required|string|max:100',
                'description'      => 'required|string|max:100',

            ]);

            // Jika validasi gagal
            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'validation',
                    'message' => $validator->errors()->all(),
                ]);
            }

            // ✅ Simpan data menu baru
            $permissions = new \App\Models\Permission();
            $permissions->name             = $request->name_permissions;
            $permissions->description      = $request->description;
            $permissions->save();

            // ✅ Log untuk debugging di Laravel
            Log::info('Permissions baru berhasil disimpan', [
                'id'   => $permissions->id,
                'name' => $permissions->name,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Menu berhasil ditambahkan!',
                'data'    => $permissions
            ]);
        } catch (\Exception $e) {
            // ✅ Tangani error & tampilkan log di Laravel
            Log::error('Gagal menyimpan menu', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function permissionsEditAjax($id)
    {
        try {
            // Cari data permission berdasarkan ID
            $permission = Permission::find($id);

            if (!$permission) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Data permission tidak ditemukan.'
                ], 404);
            }

            // Karena modal tidak punya dropdown, kita hanya kirim data 1 permission saja
            return response()->json([
                'status' => 'success',
                'result' => $permission
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengambil data permission', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function permissionsUpdateAjax(Request $request, $id)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'name_permissions_edit' => 'required|string|max:100',
                'description_edit'      => 'required|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'validation',
                    'message' => $validator->errors()->all()
                ]);
            }

            // Cari permission berdasarkan ID
            $permission = Permission::find($id);

            if (!$permission) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Permission tidak ditemukan'
                ], 404);
            }

            // Update data permission
            $permission->update([
                'name'        => $request->name_permissions_edit,
                'description' => $request->description_edit,
            ]);

            Log::info('Permission berhasil diperbarui', [
                'id'   => $permission->id,
                'name' => $permission->name,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Permission berhasil diperbarui!',
                'data'    => $permission
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui permission', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function fetchDeletePermissions($id)
    {
        try {
            $permissions = Permission::find($id);

            if (!$permissions) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Permissions tidak ditemukan.'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'result' => $permissions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function hapusPermissions(Permission $permissions)
    {
        try {
            Log::info('Memeriksa relasi roles untuk menu id: ' . $permissions->id);

            if ($permissions->roles()->exists()) {
                Log::warning('Menu id ' . $permissions->id . ' masih digunakan.');
                return response()->json([
                    'status' => 'error',
                    'type' => 'menu_in_use',
                    'message' => 'Menu ini masih digunakan oleh user.'
                ], 400);
            }

            $permissions->delete();
            Log::info('Menu id ' . $permissions->id . ' berhasil dihapus.');
            return response()->json([
                'status' => 'success',
                'message' => 'Menu berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error hapusMenu menu id ' . $permissions->id . ': ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
