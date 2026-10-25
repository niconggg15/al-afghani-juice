<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengelompokkan pesanan berdasarkan statusnya
        $pendingOrders = Order::with(['items.product', 'payment'])->where('status', 'pending')->latest()->get();
        $preparingOrders = Order::with(['items.product', 'payment'])->whereIn('status', ['confirmed', 'preparing'])->latest()->get();
        $readyOrders = Order::with(['items.product', 'payment'])->where('status', 'ready')->latest()->get();
        
        // Statistik hari ini dalam bentuk variabel individu
        $totalOrdersToday = Order::whereDate('created_at', today())->count();
        $completedOrdersToday = Order::whereDate('created_at', today())->where('status', 'completed')->count();
        $revenueToday = Order::whereDate('created_at', today())->where('status', 'completed')->sum('total');

        // Statistik hari ini dalam bentuk array (untuk mengantisipasi $todayStats di view)
        $todayStats = [
            'total_orders' => $totalOrdersToday,
            'completed_orders' => $completedOrdersToday,
            'total_revenue' => $revenueToday,
            'pending_count' => $pendingOrders->count(),
        ];

        return view('cashier.dashboard', compact(
            'pendingOrders', 
            'preparingOrders', 
            'readyOrders', 
            'totalOrdersToday', 
            'completedOrdersToday', 
            'revenueToday',
            'todayStats'
        ));
    }
}