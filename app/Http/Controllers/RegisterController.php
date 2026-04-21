<?php

namespace App\Http\Controllers;

use App\Models\Laundry;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Show the registration form for admin.
     */
    public function create()
    {
        // Check if any admin already exists - only the first admin can register
        $registrationClosed = User::where('role', 'admin')->exists();

        return view('pages.auth.register', compact('registrationClosed'));
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        // Check if any admin already exists - only the first admin can register
        if (User::where('role', 'admin')->exists()) {
            abort(403, 'Registration is closed. Please contact the admin to create your account.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $laundryName = $request->input('laundry_name', 'My Laundry');

        $laundry = Laundry::create([
            'name' => $laundryName,
            'slug' => Str::slug($laundryName),
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'is_approved' => true,
            'laundry_id' => $laundry->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
