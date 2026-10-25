@extends('cashier.layout')

@section('title', 'Detail Pesanan - ' . $order->order_number)

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Detail Pesanan</h1>
        <p class="text-gray-600">{{ $order->order_number }}</p>
    </div>
    <a href="{{ route('cashier.orders.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="grid lg:grid-cols-3 gap-8">
    <!-- Order Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Customer Info -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Informasi Pelanggan</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Nama</p>
                    <p class="font-medium">{{ $order->customer_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Telepon</p>
                    <p class="font-medium">{{ $order->customer_phone }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Metode Bayar</p>
                    <p class="font-medium capitalize">{{ $order->payment_method }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tanggal</p>
                    <p class="font-medium">{{ $order->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
            @if($order->notes)
                <div class="mt-4">
                    <p class="text-sm text-gray-500">Catatan</p>
                    <p class="font-medium">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Item Pesanan</h2>
            <div class="space-y-3">
                @foreach($order->items as $item)
                    <div class="flex gap-4 p-3 border border-gray-200 rounded-lg">
                        @if($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded">
                        @else
                            <div class="w-16 h-16 bg-green-100 rounded flex items-center justify-center">
                                <i class="fas fa-glass-whiskey text-2xl text-green-300"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="font-medium">{{ $item->product->name }}</h3>
                            <p class="text-sm text-gray-500">Rp {{ number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }}</p>
                            @if($item->notes)
                                <p class="text-xs text-gray-400 mt-1">{{ $item->notes }}</p>
                            @endif
                        </div>
                        <span class="font-semibold text-gray-800 self-center">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-4 border-t border-gray-200 pt-4 space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Subtotal</span>
                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <!-- PPN sudah dihapus -->
                <div class="flex justify-between text-lg font-bold text-green-600 border-t border-gray-200 pt-2">
                    <span>Total</span>
                    <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Status History & Konfirmasi Pembayaran -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Ubah Status</h2>
            <form action="{{ route('cashier.orders.update-status', $order) }}" method="POST" class="flex gap-4 items-end flex-wrap">
                @method('PUT')
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Baru</label>
                    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>Preparing</option>
                        <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Ready</option>
                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="btn-green px-6 py-2">
                    <i class="fas fa-save mr-2"></i>Update Status
                </button>
            </form>
            
            @if($order->status !== 'completed' && $order->payment && $order->payment->status !== 'paid')
                <form action="{{ route('cashier.orders.confirm-payment', $order) }}" method="POST" class="mt-4 pt-4 border-t border-gray-200">
                    @csrf
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="return confirm('Konfirmasi pembayaran sudah diterima?')">
                        <i class="fas fa-money-check-alt mr-2"></i>Konfirmasi Pembayaran
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status Card -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Status Saat Ini</h2>
            <div class="text-center">
                <span class="px-4 py-2 text-lg rounded-full {{ 
                    $order->status === 'pending' ? 'bg-orange-100 text-orange-800' : 
                    ($order->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : 
                    ($order->status === 'preparing' ? 'bg-purple-100 text-purple-800' : 
                    ($order->status === 'ready' ? 'bg-green-100 text-green-800' : 
                    ($order->status === 'completed' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800')))) 
                }} capitalize">{{ $order->status }}</span>
            </div>
            
            <div class="mt-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Dibuat</span>
                    <span>{{ $order->created_at->format('d M Y H:i') }}</span>
                </div>
                @if($order->confirmed_at)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Dikonfirmasi</span>
                        <span>{{ $order->confirmed_at->format('d M Y H:i') }}</span>
                    </div>
                @endif
                @if($order->completed_at)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Selesai</span>
                        <span>{{ $order->completed_at->format('d M Y H:i') }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Payment Info -->
        @if($order->payment)
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4">Pembayaran</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Metode</span>
                        <span class="capitalize">{{ $order->payment->method }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Jumlah</span>
                        <span class="font-semibold text-green-600">Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status</span>
                        <span class="px-2 py-1 text-xs rounded-full {{ 
                            $order->payment->status === 'paid' ? 'bg-green-100 text-green-800' : 
                            ($order->payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') 
                        }}">{{ $order->payment->status }}</span>
                    </div>
                    @if($order->payment->paid_at)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Dibayar</span>
                            <span>{{ $order->payment->paid_at->format('d M Y H:i') }}</span>
                        </div>
                    @endif
                    @if($order->payment->transaction_id)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Transaksi ID</span>
                            <span class="text-xs">{{ $order->payment->transaction_id }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection