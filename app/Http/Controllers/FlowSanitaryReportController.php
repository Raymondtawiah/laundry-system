<?php

namespace App\Http\Controllers;

use App\Models\FlowSanitaryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FlowSanitaryReportController extends Controller
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
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $transactionType = $request->get('transaction_type');

        $query = FlowSanitaryTransaction::with(['flowSanitary', 'user'])
            ->whereHas('flowSanitary', function ($q) {
                $q->where('laundry_id', Auth::user()->laundry_id);
            });

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        if ($transactionType) {
            $query->where('transaction_type', $transactionType);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(50);

        // Calculate totals
        $totalSales = $query->clone()->where('transaction_type', 'sale')->sum('total_amount');
        $totalAdditions = $query->clone()->where('transaction_type', 'addition')->sum('total_amount');
        $totalItemsSold = $query->clone()->where('transaction_type', 'sale')->sum('quantity');
        $totalItemsAdded = $query->clone()->where('transaction_type', 'addition')->sum('quantity');

        return view('flow-sanitary.reports.index', compact(
            'transactions',
            'startDate',
            'endDate',
            'transactionType',
            'totalSales',
            'totalAdditions',
            'totalItemsSold',
            'totalItemsAdded'
        ));
    }
}
