<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.product', 'payment', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('cashier.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'payment', 'user']);
        return view('cashier.orders.show', compact('order'));
    }

    public function confirm(Order $order)
    {
        $order->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        return back()->with('success', 'Pesanan berhasil dikonfirmasi!');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,completed,cancelled',
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'confirmed' && !$order->confirmed_at) {
            $data['confirmed_at'] = now();
        }

        if ($request->status === 'completed' && !$order->completed_at) {
            $data['completed_at'] = now();
        }

        $order->update($data);

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    public function confirmPayment(Order $order)
    {
        $order->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        if ($order->payment) {
            $order->payment->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        }

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi dan pesanan selesai!');
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