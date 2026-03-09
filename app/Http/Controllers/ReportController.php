<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Access denied. Admin only.');
            }
            return $next($request);
        });
    }

    /**
     * Display the business report for all branches.
     */
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Get branches - use predefined or from database
        $dbBranches = User::whereNotNull('branch')
            ->where('branch', '!=', '')
            ->distinct()
            ->pluck('branch');
        
        // Pre-defined branches (with database fallback)
        $branches = $dbBranches->isNotEmpty() ? $dbBranches : collect(['Daasebre', 'Nyamekrom', 'KTU']);

        // Get branch statistics
        $branchStats = $branches->map(function ($branch) use ($startDate, $endDate) {
            $orders = Order::where('branch', $branch)
                ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
            
            $totalOrders = (clone $orders)->count();
            $completedOrders = (clone $orders)->where('status', Order::STATUS_COMPLETED)->count();
            $pendingOrders = (clone $orders)->where('status', Order::STATUS_PENDING)->count();
            $inProgressOrders = (clone $orders)->where('status', Order::STATUS_IN_PROGRESS)->count();
            $readyOrders = (clone $orders)->where('status', Order::STATUS_READY)->count();
            $cancelledOrders = (clone $orders)->where('status', Order::STATUS_CANCELLED)->count();
            
            $totalRevenue = (clone $orders)->sum('total_amount');
            $totalPaid = (clone $orders)->where('payment_status', Order::PAYMENT_PAID)->sum('amount_paid');
            $totalUnpaid = (clone $orders)->whereIn('payment_status', [Order::PAYMENT_UNPAID, Order::PAYMENT_PARTIAL])->sum('balance');
            
            $customers = Customer::where('branch', $branch)
                ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                ->count();

            return [
                'branch' => $branch,
                'total_orders' => $totalOrders,
                'completed_orders' => $completedOrders,
                'pending_orders' => $pendingOrders,
                'in_progress_orders' => $inProgressOrders,
                'ready_orders' => $readyOrders,
                'cancelled_orders' => $cancelledOrders,
                'total_revenue' => $totalRevenue,
                'total_paid' => $totalPaid,
                'total_unpaid' => $totalUnpaid,
                'new_customers' => $customers,
            ];
        });

        // Calculate overall totals
        $overallStats = [
            'total_orders' => $branchStats->sum('total_orders'),
            'completed_orders' => $branchStats->sum('completed_orders'),
            'pending_orders' => $branchStats->sum('pending_orders'),
            'in_progress_orders' => $branchStats->sum('in_progress_orders'),
            'ready_orders' => $branchStats->sum('ready_orders'),
            'cancelled_orders' => $branchStats->sum('cancelled_orders'),
            'total_revenue' => $branchStats->sum('total_revenue'),
            'total_paid' => $branchStats->sum('total_paid'),
            'total_unpaid' => $branchStats->sum('total_unpaid'),
            'total_customers' => $branchStats->sum('new_customers'),
        ];

        // Get order status breakdown for chart
        $statusBreakdown = [
            'completed' => $overallStats['completed_orders'],
            'pending' => $overallStats['pending_orders'],
            'in_progress' => $overallStats['in_progress_orders'],
            'ready' => $overallStats['ready_orders'],
            'cancelled' => $overallStats['cancelled_orders'],
        ];

        // Get payment status breakdown
        $paymentBreakdown = Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->selectRaw("
                SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) as paid,
                SUM(CASE WHEN payment_status = 'partial' THEN 1 ELSE 0 END) as partial,
                SUM(CASE WHEN payment_status = 'unpaid' THEN 1 ELSE 0 END) as unpaid
            ")
            ->first();

        return view('reports.index', compact(
            'branchStats',
            'overallStats',
            'statusBreakdown',
            'paymentBreakdown',
            'startDate',
            'endDate',
            'branches'
        ));
    }

    /**
     * Display detailed report for a specific branch.
     */
    public function branch(Request $request, $branch)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $orders = Order::where('branch', $branch)
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->with(['customer', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_orders' => $orders->count(),
            'completed_orders' => $orders->where('status', Order::STATUS_COMPLETED)->count(),
            'pending_orders' => $orders->where('status', Order::STATUS_PENDING)->count(),
            'in_progress_orders' => $orders->where('status', Order::STATUS_IN_PROGRESS)->count(),
            'ready_orders' => $orders->where('status', Order::STATUS_READY)->count(),
            'cancelled_orders' => $orders->where('status', Order::STATUS_CANCELLED)->count(),
            'total_revenue' => $orders->sum('total_amount'),
            'total_paid' => $orders->sum('amount_paid'),
            'total_unpaid' => $orders->sum('balance'),
        ];

        return view('reports.branch', compact('orders', 'stats', 'branch', 'startDate', 'endDate'));
    }
}
