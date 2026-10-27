<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $pendingOrders = Order::with(['items.product', 'payment', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $readyOrders = Order::with(['items.product', 'payment', 'user'])
            ->where('status', 'ready')
            ->latest()
            ->get();

        $todayStats = [
            'total_orders' => Order::where(function ($query) {
                $query->whereDate('created_at', today())
                    ->orWhere(function ($completed) {
                        $completed->where('status', 'completed')
                            ->whereDate('completed_at', today());
                    });
            })->count(),
            'completed_orders' => Order::whereDate('completed_at', today())->where('status', 'completed')->count(),
            'total_revenue' => Order::whereDate('completed_at', today())->where('status', 'completed')->sum('total'),
            'pending_count' => Order::where('status', 'pending')->count(),
        ];

        return view('cashier.orders.index', compact('pendingOrders', 'readyOrders', 'todayStats'));
    }

    public function history(Request $request)
    {
        $filters = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $archiveBefore = now()->subHours(24);
        $historyQuery = Order::with(['payment', 'user'])
            ->where(function ($query) use ($archiveBefore) {
                $query->where(function ($completed) use ($archiveBefore) {
                    $completed->where('status', 'completed')
                        ->whereNotNull('completed_at')
                        ->where('completed_at', '<=', $archiveBefore);
                })->orWhere(function ($cancelled) use ($archiveBefore) {
                    $cancelled->where('status', 'cancelled')
                        ->where('created_at', '<=', $archiveBefore);
                });
            });

        if (!empty($filters['date_from'])) {
            $historyQuery->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $historyQuery->whereDate('created_at', '<=', $filters['date_to']);
        }

        $completedOrdersQuery = Order::where('status', 'completed')
            ->whereNotNull('completed_at');
        $recentCompletedQuery = (clone $completedOrdersQuery)
            ->where('completed_at', '>', $archiveBefore);

        if (!empty($filters['date_from'])) {
            $completedOrdersQuery->whereDate('completed_at', '>=', $filters['date_from']);
            $recentCompletedQuery->whereDate('completed_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $completedOrdersQuery->whereDate('completed_at', '<=', $filters['date_to']);
            $recentCompletedQuery->whereDate('completed_at', '<=', $filters['date_to']);
        }

        $completedRevenue = $completedOrdersQuery->sum('total');
        $recentCompletedOrders = $recentCompletedQuery
            ->with(['payment', 'user'])
            ->latest('completed_at')
            ->limit(10)
            ->get();
        $recentCompletedCount = (clone $recentCompletedQuery)->count();
        $recentCompletedRevenue = (clone $recentCompletedQuery)->sum('total');

        $orders = $historyQuery->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('cashier.orders.history', compact(
            'orders',
            'completedRevenue',
            'recentCompletedOrders',
            'recentCompletedCount',
            'recentCompletedRevenue',
            'filters'
        ));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'payment', 'user']);
        return view('cashier.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,ready,completed,cancelled',
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'completed' && !$order->completed_at) {
            $data['completed_at'] = now();
        }

        $order->update($data);

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    public function confirmPayment(Order $order)
    {
        // Ubah status ke 'ready' (Siap Diambil) setelah pembayaran dikonfirmasi
        $order->update([
            'status' => 'ready',
            'confirmed_at' => now(),
        ]);

        if ($order->payment) {
            $order->payment->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        }

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi, pesanan kini Siap Diambil!');
    }

    public function complete(Order $order)
    {
        $order->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Pesanan telah diselesaikan!');
    }
}