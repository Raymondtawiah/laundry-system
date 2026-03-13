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
        // Handle period filter
        $period = $request->get('period', 'month');
        
        switch ($period) {
            case 'week':
                $startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
                $endDate = Carbon::now()->endOfWeek()->format('Y-m-d');
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear()->format('Y-m-d');
                $endDate = Carbon::now()->endOfYear()->format('Y-m-d');
                break;
            default:
                // Custom date range
                $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
                $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        }

        // Handle status filter from clicking on stat boxes
        $statusFilter = $request->get('status');
        $paymentFilter = $request->get('payment');
        $deliveryFilter = $request->get('delivery');

        // Handle client payment status filter
        $clientPaymentFilter = $request->get('client_payment');

        $query = Order::where('branch', $branch)
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);

        // Apply status filter if provided
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        // Apply payment filter if provided
        if ($paymentFilter) {
            $query->where('payment_status', $paymentFilter);
        }

        // Apply delivery filter if provided
        if ($deliveryFilter) {
            $query->where('delivery_type', $deliveryFilter);
        }

        $orders = $query->with(['customer', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Basic stats
        $allOrders = Order::where('branch', $branch)
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->get();

        $stats = [
            'total_orders' => $allOrders->count(),
            'completed_orders' => $allOrders->where('status', Order::STATUS_COMPLETED)->count(),
            'pending_orders' => $allOrders->where('status', Order::STATUS_PENDING)->count(),
            'in_progress_orders' => $allOrders->where('status', Order::STATUS_IN_PROGRESS)->count(),
            'ready_orders' => $allOrders->where('status', Order::STATUS_READY)->count(),
            'cancelled_orders' => $allOrders->where('status', Order::STATUS_CANCELLED)->count(),
            'total_revenue' => $allOrders->sum('total_amount'),
            'total_paid' => $allOrders->sum('amount_paid'),
            'total_unpaid' => $allOrders->sum('balance'),
            'deliveries' => $allOrders->where('delivery_type', 'doorstep')->count(),
        ];

        // Customer payment breakdown
        $customerStats = $this->getCustomerPaymentStats($branch, $startDate, $endDate);
        
        // Get client list with payment status
        $clientList = $this->getClientPaymentList($branch, $startDate, $endDate, $clientPaymentFilter);

        return view('reports.branch', compact('orders', 'stats', 'branch', 'startDate', 'endDate', 'period', 'customerStats', 'statusFilter', 'paymentFilter', 'clientPaymentFilter', 'clientList'));
    }

    /**
     * Get customer payment statistics for a branch
     */
    private function getCustomerPaymentStats($branch, $startDate, $endDate)
    {
        // Get all orders for the branch in the date range
        $orders = Order::where('branch', $branch)
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->get();

        // Get unique customers with their payment status
        $customerOrders = $orders->groupBy('customer_id');

        $fullPayment = 0;    // Customers who fully paid
        $partPayment = 0;   // Customers with partial payment
        $withBalance = 0;   // Customers with remaining balance
        $pending = 0;       // Customers with pending orders

        foreach ($customerOrders as $customerId => $customerOrderList) {
            $hasPending = $customerOrderList->where('status', 'pending')->count() > 0;
            $hasCompleted = $customerOrderList->where('status', 'completed')->count() > 0;
            $totalBalance = $customerOrderList->sum('balance');
            $totalPaid = $customerOrderList->sum('amount_paid');
            $totalAmount = $customerOrderList->sum('total_amount');

            if ($hasPending) {
                $pending++;
            }

            if ($totalPaid >= $totalAmount && $totalAmount > 0) {
                $fullPayment++;
            } elseif ($totalPaid > 0 && $totalPaid < $totalAmount) {
                $partPayment++;
            }

            if ($totalBalance > 0) {
                $withBalance++;
            }
        }

        return [
            'full_payment' => $fullPayment,
            'part_payment' => $partPayment,
            'with_balance' => $withBalance,
            'pending' => $pending,
        ];
    }

    /**
     * Get list of clients with their payment status
     */
    private function getClientPaymentList($branch, $startDate, $endDate, $filter = null)
    {
        $orders = Order::where('branch', $branch)
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->with('customer')
            ->get();

        // Group by customer and calculate payment status
        $customerData = [];
        
        foreach ($orders->groupBy('customer_id') as $customerId => $customerOrders) {
            $customer = $customerOrders->first()->customer;
            if (!$customer) continue;
            
            $totalAmount = $customerOrders->sum('total_amount');
            $totalPaid = $customerOrders->sum('amount_paid');
            $totalBalance = $customerOrders->sum('balance');
            $orderCount = $customerOrders->count();
            $hasPending = $customerOrders->where('status', 'pending')->count() > 0;
            $hasPartial = $customerOrders->where('payment_status', 'partial')->count() > 0;
            
            // Determine payment status
            if ($totalBalance > 0 && $totalPaid > 0) {
                $status = 'partial';
            } elseif ($totalBalance > 0) {
                $status = 'unpaid';
            } else {
                $status = 'paid';
            }
            
            // Apply filter
            if ($filter && $filter !== $status) {
                if (!($filter === 'with_balance' && $totalBalance > 0)) {
                    continue;
                }
            }
            
            $customerData[] = [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'total_orders' => $orderCount,
                'total_amount' => $totalAmount,
                'total_paid' => $totalPaid,
                'total_balance' => $totalBalance,
                'status' => $status,
                'has_pending' => $hasPending,
                'has_partial' => $hasPartial,
            ];
        }
        
        // Sort by balance (highest first)
        usort($customerData, function($a, $b) {
            return $b['total_balance'] - $a['total_balance'];
        });
        
        return $customerData;
    }
}
