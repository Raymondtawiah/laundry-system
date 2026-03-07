<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    /**
     * Show the staff list.
     */
    public function index()
    {
        // Only admin can view staff list
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Only administrators can view staff members.');
        }
        
        $staff = User::where('laundry_id', Auth::user()->laundry_id)
                    ->where('role', 'staff')
                    ->get();
        
        return view('staff.index', compact('staff'));
    }

    /**
     * Show the staff registration form.
     */
    public function create()
    {
        // Only admin can access staff registration
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Only administrators can register staff members.');
        }
        
        return view('staff.register');
    }

    /**
     * Store a newly created staff member.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Get the authenticated admin
        $admin = auth()->user();

        // Create staff member linked to admin's laundry
        $staff = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'staff',
            'is_approved' => true,
            'laundry_id' => $admin->laundry_id,
        ]);

        return redirect()->route('dashboard')->with('success', 'Staff member registered successfully!');
    }

    /**
     * Delete a staff member.
     */
    public function destroy(User $staff)
    {
        // Only admin can delete staff
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Only administrators can delete staff members.');
        }
        
        // Make sure the staff belongs to the same laundry
        if ($staff->laundry_id !== Auth::user()->laundry_id) {
            abort(403, 'You cannot delete this staff member.');
        }
        
        $staff->delete();
        
        return redirect()->route('staff.index')->with('success', 'Staff member deleted successfully!');
    }
}
