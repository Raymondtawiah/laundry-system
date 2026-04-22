<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderAuthorizationService
{
    public function canViewOrders(): bool
    {
        return in_array(Auth::user()->role, ['admin', 'staff']);
    }

    public function canCreateOrders(): bool
    {
        return in_array(Auth::user()->role, ['admin', 'staff']);
    }

    public function canDeleteOrder(Order $order): bool
    {
        return Auth::user()->role === 'admin' && $this->ownsOrder($order);
    }

    public function canAccessOrder(Order $order): bool
    {
        if ($order->laundry_id !== Auth::user()->laundry_id) {
            return false;
        }

        if (Auth::user()->role === 'staff' && $order->branch !== Auth::user()->branch) {
            return false;
        }

        return true;
    }

    public function canAccessBranch(Order $order): bool
    {
        return Auth::user()->role !== 'staff' || $order->branch === Auth::user()->branch;
    }

    public function ownsOrder(Order $order): bool
    {
        return $order->laundry_id === Auth::user()->laundry_id;
    }

    public function getUserBranchConstraint($query)
    {
        if (Auth::user()->role === 'staff' && Auth::user()->branch) {
            return $query->where('branch', Auth::user()->branch);
        }

        if (Auth::user()->role === 'staff') {
            return $query->whereNull('id');
        }

        return $query;
    }
}
