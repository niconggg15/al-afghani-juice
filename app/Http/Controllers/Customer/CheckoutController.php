<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('customer.menu')->with('error', 'Keranjang kosong');
        }

        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $total = $subtotal;

        return view('customer.checkout', compact('cart', 'subtotal', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'payment_method' => 'required|in:cash,qris',
            'notes' => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong');
        }

        try {
            $order = DB::transaction(function () use ($request, $cart) {
                $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
                $total = $subtotal;

                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'customer_name' => $request->customer_name,
                    'customer_phone' => $request->customer_phone,
                    'payment_method' => $request->payment_method,
                    'status' => 'pending',
                    'subtotal' => $subtotal,
                    'tax' => 0,
                    'total' => $total,
                    'notes' => $request->notes,
                ]);

                foreach ($cart as $item) {
                    $product = Product::findOrFail($item['id']);

                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stok {$product->name} tidak mencukupi");
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                        'notes' => $item['notes'] ?? null,
                    ]);

                    $product->decrement('stock', $item['quantity']);
                }

                Payment::create([
                    'order_id' => $order->id,
                    'method' => $request->payment_method,
                    'amount' => $total,
                    'status' => 'pending',
                ]);

                session()->forget('cart');

                return $order;
            });

            return redirect()->route('customer.order.success', $order->id)
                ->with('success', 'Pesanan berhasil dibuat');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        $order->load('items.product', 'payment');
        return view('customer.order-success', compact('order'));
    }
}