<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * Usage in route:
     * - Route::get('/users', ...)->middleware('permission:view_users');
     * - Route::get('/reports', ...)->middleware('permission:view_reports|export_reports');
     * - Route::get('/admin', ...)->middleware('permission:manage_users,all');
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permissions  Daftar permission dipisah | atau ,
     * @param  string  $mode  ("any" atau "all"), default "any"
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $permissions, string $mode = 'any'): Response
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Pisahkan permission dengan | atau ,
        $permissionArray = preg_split('/[|,]/', $permissions);

        $hasAccess = false;

        if ($mode === 'all') {
            // harus punya semua permission
            $hasAccess = $user->hasAllPermissions($permissionArray);
        } else {
            // default: cukup salah satu
            $hasAccess = $user->hasAnyPermission($permissionArray);
        }

        if (!$hasAccess) {
            // Bisa pilih: redirect, abort, atau view custom
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Forbidden'], 403);
            }

            // return abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
            return response()->view('errors.no_access', [], 403);
        }

        return $next($request);
    }
}
