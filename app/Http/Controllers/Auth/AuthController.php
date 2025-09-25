<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login()
    {
        return view('welcome');
    }

    public function splash_page()
    {
        if (Auth::check()) {
            return redirect()->route('app.ipm');
        }
        return view('welcome');
    }

    public function login_page()
    {
        return view('auth.login');
    }

    public function login_post(Request $request)
    {
        // Log::info('REQUEST LOGIN:', $request->all());
        // Log::info("STEP 1 - Validasi input login OK", $request->only('email'));

        // $user = \App\Models\User::where('email', $request->email)->first();
        // Log::info("STEP 2 - Cek user dari database", ['user' => $user?->id]);

        // $check = ['blocked' => false]; // dummy, kalau mau log RateLimiter nanti ambil dari AuthService
        // Log::info("STEP 3 - RateLimiter check", ['blocked' => $check['blocked'] ?? null]);

        // Log::info("STEP 4 - Attempt login hasil", ['success' => Auth::check()]);

        return $this->authService->login($request);
    }

    public function logout(Request $request)
    {
        return $this->authService->logout($request);
    }
}
