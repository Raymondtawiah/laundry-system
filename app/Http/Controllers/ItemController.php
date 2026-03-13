<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * Show the add item form.
     */
    public function create()
    {
        // Only admin can add items
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Only administrators can add items.');
        }
        
        return view('items.create');
    }

    /**
     * Store a newly created item.
     */
    public function store(Request $request)
    {
        // Only admin can add items
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Only administrators can add items.');
        }
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'category' => ['required', 'string', 'max:100'],
        ]);

        // Get the authenticated admin
        $admin = auth()->user();

        // Create item linked to admin's laundry
        $item = Item::create([
            'laundry_id' => $admin->laundry_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category ?? 'general',
            'is_active' => true,
        ]);

        return redirect()->route('items.index')->with('toast', ['type' => 'success', 'message' => 'Item added successfully!']);
    }

    /**
     * Show the items list.
     */
    public function index(Request $request)
    {
        // Only admin can view items
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Only administrators can view items.');
        }
        
        $query = Item::where('laundry_id', Auth::user()->laundry_id);
        
        // Search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        $items = $query->orderBy('name')->paginate(15);
        
        // Get unique categories for the dropdown
        $categories = Item::where('laundry_id', Auth::user()->laundry_id)
                    ->distinct()
                    ->pluck('category')
                    ->sort()
                    ->toArray();
        
        return view('items.index', compact('items', 'categories'));
    }

    /**
     * Delete an item.
     */
    public function destroy(Item $item)
    {
        // Only admin can delete items
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Only administrators can delete items.');
        }
        
        // Make sure the item belongs to the same laundry
        if ($item->laundry_id !== Auth::user()->laundry_id) {
            abort(403, 'You cannot delete this item.');
        }
        
        $item->delete();
        
        return redirect()->route('items.index')->with('success', 'Item deleted successfully!');
    }

    /**
     * Show the edit item form.
     */
    public function edit(Item $item)
    {
        // Only admin can edit items
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Only administrators can edit items.');
        }
        
        // Make sure the item belongs to the same laundry
        if ($item->laundry_id !== Auth::user()->laundry_id) {
            abort(403, 'You cannot edit this item.');
        }
        
        return view('items.edit', compact('item'));
    }

    /**
     * Update an item.
     */
    public function update(Request $request, Item $item)
    {
        // Only admin can update items
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Only administrators can update items.');
        }
        
        // Make sure the item belongs to the same laundry
        if ($item->laundry_id !== Auth::user()->laundry_id) {
            abort(403, 'You cannot update this item.');
        }
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'category' => ['required', 'string', 'max:100'],
            'is_active' => ['boolean'],
        ]);

        // Update item
        $item->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('items.index')->with('toast', ['type' => 'success', 'message' => 'Item updated successfully!']);
    }
}
