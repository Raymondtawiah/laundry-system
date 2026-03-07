<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Show the add customer form.
     */
    public function create()
    {
        // Only admin and staff can add customers
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'staff') {
            abort(403, 'Only administrators and staff can add customers.');
        }
        
        return view('customers.create');
    }

    /**
     * Store a newly created customer.
     */
    public function store(Request $request)
    {
        // Only admin and staff can add customers
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'staff') {
            abort(403, 'Only administrators and staff can add customers.');
        }
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:customers'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        // Get the authenticated user's laundry
        $laundryId = Auth::user()->laundry_id;

        // Create customer linked to laundry
        $customer = Customer::create([
            'laundry_id' => $laundryId,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'notes' => $request->notes,
        ]);

        return redirect()->route('customers.index')->with('toast', ['type' => 'success', 'message' => 'Customer added successfully!']);
    }

    /**
     * Show the customers list.
     */
    public function index()
    {
        // Only admin and staff can view customers
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'staff') {
            abort(403, 'Only administrators and staff can view customers.');
        }
        
        $customers = Customer::where('laundry_id', Auth::user()->laundry_id)
                    ->orderBy('name')
                    ->get();
        
        return view('customers.index', compact('customers'));
    }

    /**
     * Delete a customer.
     */
    public function destroy(Customer $customer)
    {
        // Only admin can delete customers
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Only administrators can delete customers.');
        }
        
        // Make sure the customer belongs to the same laundry
        if ($customer->laundry_id !== Auth::user()->laundry_id) {
            abort(403, 'You cannot delete this customer.');
        }
        
        $customer->delete();
        
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
    }
}
