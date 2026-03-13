<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SettingsController extends Controller
{
    public function profile()
    {
        return view('settings.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }

    public function password()
    {
        return view('settings.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('user-password.edit')->with('success', 'Password updated successfully!');
    }

    public function appearance()
    {
        return view('settings.appearance');
    }

    public function twoFactor()
    {
        $user = Auth::user();
        return view('settings.two-factor', compact('user'));
    }

    public function enableTwoFactor(Request $request)
    {
        $user = Auth::user();
        $user->createTwoFactorSecret();
        
        return redirect()->route('two-factor.show')->with('success', 'Two-factor authentication enabled. Please confirm with your code.');
    }

    public function disableTwoFactor(Request $request)
    {
        $user = Auth::user();
        $user->disableTwoFactorAuthentication();
        
        return redirect()->route('two-factor.show')->with('success', 'Two-factor authentication disabled.');
    }
}
