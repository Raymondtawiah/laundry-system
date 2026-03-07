<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $laundryId = Auth::user()->laundry_id;
        
        // Get recent orders (last 5)
        $recentOrders = Order::with(['customer'])
            ->where('laundry_id', $laundryId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get stats
        $totalOrders = Order::where('laundry_id', $laundryId)->count();
        $pendingOrders = Order::where('laundry_id', $laundryId)->where('status', 'pending')->count();
        $completedToday = Order::where('laundry_id', $laundryId)
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->count();
        $totalRevenue = Order::where('laundry_id', $laundryId)
            ->where('status', 'completed')
            ->sum('total_amount');
        
        return view('dashboard', compact(
            'recentOrders',
            'totalOrders',
            'pendingOrders',
            'completedToday',
            'totalRevenue'
        ));
    }
}
