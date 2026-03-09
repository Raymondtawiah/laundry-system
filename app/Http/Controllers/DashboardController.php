<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $laundryId = Auth::user()->laundry_id;
        $userRole = Auth::user()->role;
        $userBranch = Auth::user()->branch;
        $branch = $request->get('branch', '');
        
        // Determine effective branch for filtering
        $effectiveBranch = '';
        if ($userRole === 'staff') {
            $effectiveBranch = $userBranch;
        } elseif ($userRole === 'admin' && $branch) {
            $effectiveBranch = $branch;
        }
        
        // Build base query with branch filter
        $baseQuery = Order::where('laundry_id', $laundryId)
            ->when($effectiveBranch, function($query) use ($effectiveBranch) {
                $query->where('branch', $effectiveBranch);
            });
        
        // Get recent orders (last 5)
        $recentOrders = Order::with(['customer'])
            ->where('laundry_id', $laundryId)
            ->when($effectiveBranch, function($query) use ($effectiveBranch) {
                $query->where('branch', $effectiveBranch);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get stats (filtered by branch)
        $totalOrders = (clone $baseQuery)->count();
        $pendingOrders = (clone $baseQuery)->where('status', 'pending')->count();
        $completedToday = (clone $baseQuery)->where('status', 'completed')->whereDate('created_at', today())->count();
        $totalRevenue = (clone $baseQuery)->sum('amount_paid');
        
        return view('dashboard', compact(
            'recentOrders',
            'totalOrders',
            'pendingOrders',
            'completedToday',
            'totalRevenue',
            'branch'
        ));
    }
}
