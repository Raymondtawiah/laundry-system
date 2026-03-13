<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
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
        
        // Set user as unverified on every login
        // This ensures they need to verify each time they log in
        $user = Auth::user();
        if ($user) {
            $user->is_verified = false;
            $user->verification_code = null;
            $user->verification_code_expires_at = null;
            $user->save();
        }

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session - also unverify the user
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        
        // Set user as unverified when logging out
        // This will require verification on next login
        if ($user) {
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
