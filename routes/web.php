<?php

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\TambahMenuController;
use App\Http\Controllers\App_Ipm\AppController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\VerifyEmailController;
use App\Http\Controllers\Api\AuthorizeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\App_ipm\ProfileController;
use App\Http\Controllers\Auth\SessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;


// ================================
// Splash Page
Route::get('/', [AuthController::class, 'splash_page'])->name('splash.page')->middleware('ip.trusted');
// Login page

// Login page dengan middleware
Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('ip.trusted');
// ================================





// ================================
// Verifikasi Email Page
Route::get('/verify-email', [VerifyEmailController::class, 'showPage'])->name('email.verify.page');
// API verifikasi email
Route::post('/verify-email-api', [VerifyEmailController::class, 'verify'])->name('email.verify.api');
// API Token check (SPA)
Route::get('/authorize/check', [AuthorizeController::class, 'check'])->name('authorize.check');
// API JSON
Route::get('/reset-sessions', [SessionController::class, 'reset'])->name('reset.sessions.api');
// Halaman logout + loader
Route::get('/logout-reset-session', [SessionController::class, 'logoutResetPage'])
    ->name('logout.reset.page');
// ================================


// ================================
// Auth Routes

Route::prefix('auth')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login_page', [AuthController::class, 'login_page'])->name('login.page')->middleware('ip.trusted');
        Route::post('/login_post', [AuthController::class, 'login_post'])->name('login.post')->middleware('ip.trusted');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});


// ================================

// ================================
// PENGATURAN USER
// ================================
Route::middleware(['check.session'])->group(function () {
    Route::get('/app_ipm', [AppController::class, 'app_ipm'])->name('app.ipm');
    Route::post('/app_ipm/accept-terms', [AppController::class, 'acceptTerms'])->name('app_ipm.accept_terms');
    Route::post('/app_ipm/save-profile', [AppController::class, 'saveProfile'])->name('app_ipm.save_profile');
    Route::get('/check-verification', [AppController::class, 'checkVerification'])->name('app_ipm.check_verification');
    Route::post('/upload-ktp', [AppController::class, 'uploadKTP'])->name('ktp.upload');
    Route::post('/upload-lokasi', [AppController::class, 'uploadLokasi'])->name('lokasi.upload');
    Route::post('/selfie/upload', [AppController::class, 'uploadselfie'])->name('selfie.upload');
    Route::post('/verifikasi-upload', [AppController::class, 'uploadfaremuk'])->name('verifikasi.upload');
    Route::get('/verifikasi-check', [AppController::class, 'checkVerifikasi'])->name('verifikasi.check');

    // API Wilayah
    Route::prefix('api')->group(function () {
        Route::get('/provinces', [AppController::class, 'getProvinces']);
        Route::get('/cities/{province_id}', [AppController::class, 'getCities']);
        Route::get('/districts/{city_id}', [AppController::class, 'getDistricts']);
        Route::get('/villages/{district_id}', [AppController::class, 'getVillages']);
    });

    //User Data
    Route::get('/users/list', [UserController::class, 'index'])->middleware('check.permission:view_user')->name('user.list.page');
    Route::post('/usersStore', [UserController::class, 'store'])->middleware('check.permission:create_user')->name('user.store.ajax');
    Route::get('/fetchdelete/{id}', [UserController::class, 'fetchDelete'])->middleware('check.permission:delete_user')->name('user.fetch.delete.ajax');
    Route::delete('/hapusUser/{id}', [UserController::class, 'hapusUser'])->middleware('check.permission:delete_user')->name('user.delete.ajax');
    Route::get('/users/userEditAjax/{id}', [UserController::class, 'userEditAjax'])->middleware('check.permission:edit_user')->name('user.edit.ajax');
    Route::put('/users/userUpdateAjax/{id}', [UserController::class, 'userUpdateAjax'])->middleware('check.permission:edit_user')->name('user.update.ajax');
    Route::put('/users/nonAktif/{id}', [UserController::class, 'nonaktif'])->middleware('check.permission:aktif_nonaktif_user')->name('users.nonaktif');
    Route::put('/users/Aktif/{id}', [UserController::class, 'aktif'])->middleware('check.permission:aktif_nonaktif_user')->name('users.aktif');
});
// ================================

// ================================
// MENU SETTINGS
// ================================
Route::middleware(['check.session'])->group(function () {
    Route::get('/setting/menu', [MenuController::class, 'menu'])->middleware('check.permission:view_menu_setting')->name('setting.menu.page');
    Route::get('/{role}/access', [MenuController::class, 'getRoleAccess']);
    Route::post('/{role}/access', [MenuController::class, 'updateRoleAccess']);
});
// ================================

// ================================
// TAMABH MENU & permissions
// ================================
Route::middleware(['check.session'])->group(function () {
    //ROUTE MENU
    Route::get('setting/tambah_menu', [TambahMenuController::class, 'view_page'])->middleware('check.permission:view_menu_setting')->name('setting.tambahmenu.page');
    Route::post('/setting/menu/store', [TambahMenuController::class, 'menu_store'])->name('setting.menu_store');
    Route::get('/setting/menuEditAjax/{id}', [TambahMenuController::class, 'menuEditAjax'])->name('setting.menuedit.ajax');
    Route::put('/setting/menuUpdateAjax/{id}', [TambahMenuController::class, 'menuUpdateAjax'])->name('setting.menuupdate.ajax');
    Route::get('/setting/fetchdelete/{id}', [TambahMenuController::class, 'fetchDelete'])->name('setting.fetchdelete.ajax');
    Route::delete('/setting/hapusMenu/{menu}', [TambahMenuController::class, 'hapusMenu'])->name('setting.menudelete.ajax');
    //ROUTE ROLE MENU
    Route::post('/setting/permission/store', [TambahMenuController::class, 'permissions_store'])->name('setting.permission_store');
    Route::get('/setting/permissionsEditAjax/{id}', [TambahMenuController::class, 'permissionsEditAjax'])->name('setting.permissionsedit.ajax');
    Route::put('/setting/permissionsUpdateAjax/{id}', [TambahMenuController::class, 'permissionsUpdateAjax'])->name('setting.permissionupdate.ajax');
    Route::get('/setting/permissionsfetchdelete/{id}', [TambahMenuController::class, 'fetchDeletePermissions'])->name('setting.Permissionsfetchdelete.ajax');
    Route::delete('/setting/hapusPermissions/{permissions}', [TambahMenuController::class, 'hapusPermissions'])->name('setting.permissionsdelete.ajax');
});
// ================================


// ================================
// PROFILE SETTINGS
// ================================
Route::middleware(['check.session'])->group(function () {
    //ROUTE MENU
    Route::get('/profile', [ProfileController::class, 'profile_page'])->name('profile.page');
    Route::post('/profile/update-photo', [ProfileController::class, 'updatePhoto'])->name('user.updatePhoto');
});
// ================================


// ================================
// DASHBOARD MANAGEMENT
// ================================
Route::middleware(['check.session'])->group(function () {
    //ROUTE MENU
    Route::get('/dashboard', [DashboardController::class, 'admin_dashboard_page'])->name('admin.dashboard.page');
});
// ================================
