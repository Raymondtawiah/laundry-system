<?php

namespace App\Http\Controllers;

use App\Models\FlowSanitary;
use App\Models\FlowSanitaryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FlowSanitaryController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Access denied. Admin only.');
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = FlowSanitary::where('laundry_id', Auth::user()->laundry_id);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(item_name) LIKE ?', ['%'.strtolower($search).'%'])
                    ->orWhereRaw('LOWER(item_code) LIKE ?', ['%'.strtolower($search).'%']);
            });
        }

        $items = $query->orderBy('item_code')->paginate(20);

        return view('flow-sanitary.index', compact('items', 'search'));
    }

    public function create()
    {
        return view('flow-sanitary.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_code' => 'required|string|max:50|unique:flow_sanitaries,item_code,NULL,id,laundry_id,'.Auth::user()->laundry_id,
            'item_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ]);

        FlowSanitary::create([
            'item_code' => $request->item_code,
            'item_name' => $request->item_name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'laundry_id' => Auth::user()->laundry_id,
        ]);

        return redirect()->route('flow-sanitary.index')->with('toast', [
            'type' => 'success',
            'message' => 'Item created successfully!',
        ]);
    }

    public function edit(FlowSanitary $flowSanitary)
    {
        if ($flowSanitary->laundry_id !== Auth::user()->laundry_id) {
            abort(403);
        }

        return view('flow-sanitary.edit', compact('flowSanitary'));
    }

    public function update(Request $request, FlowSanitary $flowSanitary)
    {
        if ($flowSanitary->laundry_id !== Auth::user()->laundry_id) {
            abort(403);
        }

        $request->validate([
            'item_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $flowSanitary->update([
            'item_name' => $request->item_name,
            'price' => $request->price,
        ]);

        return redirect()->route('flow-sanitary.index')->with('toast', [
            'type' => 'success',
            'message' => 'Item updated successfully!',
        ]);
    }

    public function sell(Request $request, FlowSanitary $flowSanitary)
    {
        if ($flowSanitary->laundry_id !== Auth::user()->laundry_id) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:'.$flowSanitary->quantity,
        ]);

        $totalAmount = $request->quantity * $flowSanitary->price;

        // Create transaction record
        FlowSanitaryTransaction::create([
            'flow_sanitary_id' => $flowSanitary->id,
            'transaction_type' => 'sale',
            'quantity' => $request->quantity,
            'unit_price' => $flowSanitary->price,
            'total_amount' => $totalAmount,
            'user_id' => Auth::id(),
            'notes' => $request->notes,
        ]);

        // Update quantity
        $flowSanitary->decrement('quantity', $request->quantity);

        return redirect()->route('flow-sanitary.index')->with('toast', [
            'type' => 'success',
            'message' => "Sold {$request->quantity} units of {$flowSanitary->item_name} for GH₵".number_format($totalAmount, 2),
        ]);
    }

    public function addStock(Request $request, FlowSanitary $flowSanitary)
    {
        if ($flowSanitary->laundry_id !== Auth::user()->laundry_id) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $totalAmount = $request->quantity * $flowSanitary->price;

        // Create transaction record
        FlowSanitaryTransaction::create([
            'flow_sanitary_id' => $flowSanitary->id,
            'transaction_type' => 'addition',
            'quantity' => $request->quantity,
            'unit_price' => $flowSanitary->price,
            'total_amount' => $totalAmount,
            'user_id' => Auth::id(),
            'notes' => $request->notes,
        ]);

        // Update quantity
        $flowSanitary->increment('quantity', $request->quantity);

        return redirect()->route('flow-sanitary.index')->with('toast', [
            'type' => 'success',
            'message' => "Added {$request->quantity} units to {$flowSanitary->item_name}",
        ]);
    }
}
