<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::where('laundry_id', Auth::user()->laundry_id);

        if (Auth::user()->role !== 'admin') {
            $query->where('branch', Auth::user()->branch);
        }

        if ($request->filled('search')) {
            $query->where('description', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('month')) {
            $query->whereMonth('date', date('m', strtotime($request->month)))
                ->whereYear('date', date('Y', strtotime($request->month)));
        }

        if ($request->filled('branch')) {
            $query->where('branch', $request->branch);
        }

        $expenses = $query->orderByDesc('date')->paginate(15);

        return view('expenses.index', compact('expenses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'date' => ['required', 'date'],
            'branch' => ['required', 'in:Daasebre,Nyamekrom,KTU'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        Expense::create([
            'laundry_id' => Auth::user()->laundry_id,
            'user_id' => Auth::user()->id,
            'amount' => $request->amount,
            'date' => $request->date,
            'branch' => $request->branch,
            'description' => $request->description,
        ]);

        return redirect()->route('expenses.index')->with('toast', ['type' => 'success', 'message' => 'Expense added successfully!']);
    }

    public function update(Request $request, Expense $expense)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Only administrators can edit expenses.');
        }

        if ($expense->laundry_id !== Auth::user()->laundry_id) {
            abort(403, 'You cannot edit this expense.');
        }

        $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'date' => ['required', 'date'],
            'branch' => ['required', 'in:Daasebre,Nyamekrom,KTU'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $expense->update([
            'amount' => $request->amount,
            'date' => $request->date,
            'branch' => $request->branch,
            'description' => $request->description,
        ]);

        return redirect()->route('expenses.index')->with('toast', ['type' => 'success', 'message' => 'Expense updated successfully!']);
    }

    public function destroy(Expense $expense)
    {
        if ($expense->laundry_id !== Auth::user()->laundry_id) {
            abort(403, 'You cannot delete this expense.');
        }

        $expense->delete();

        return redirect()->route('expenses.index')->with('toast', ['type' => 'success', 'message' => 'Expense deleted successfully!']);
    }
}
