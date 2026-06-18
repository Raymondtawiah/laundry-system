<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\DefaultAdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        private DefaultAdminService $adminService
    ) {}

    /**
     * Display the login view.
     */
    public function create()
    {
        return view('pages.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $user = Auth::user();
        if ($user && ! $this->adminService->isDefaultAdmin($user)) {
            $user->is_verified = false;
            $user->verification_code = null;
            $user->verification_code_expires_at = null;
            $user->save();
        }

        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    /**
     * Destroy an authenticated session - also unverify the user
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        if ($user && ! $this->adminService->isDefaultAdmin($user)) {
            $user->is_verified = false;
            $user->verification_code = null;
            $user->verification_code_expires_at = null;
            $user->save();
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
