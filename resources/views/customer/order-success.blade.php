@extends('customer.layout')

@section('title', 'Pesanan Berhasil - Al Afghani Juice')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    <!-- Success Alert -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3 shadow-sm">
            <i class="fas fa-check-circle text-2xl"></i>
            <div>
                <p class="font-bold">Berhasil!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100 space-y-6">
        <!-- Header Nota -->
        <div class="text-center pb-6 border-b border-gray-100">
            <div class="w-16 h-16 mx-auto mb-3 bg-orange-100 rounded-full flex items-center justify-center text-orange-500 text-2xl">
                <i class="fas fa-receipt"></i>
            </div>
            <h2 class="text-2xl font-black text-gray-800">Detail Pesanan</h2>
            <p class="text-gray-400 text-sm mt-1">Terima kasih telah memesan di Al Afghani Juice!</p>
        </div>

        <!-- Info Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Nomor Pesanan</p>
                <p class="text-lg font-black text-orange-500">{{ $order->order_number }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Tanggal</p>
                <p class="text-base font-bold text-gray-800">{{ $order->created_at->format('d M Y H:i') }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 sm:col-span-2">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Nama Pembeli</p>
                <p class="text-base font-bold text-gray-800">{{ $order->customer_name }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 sm:col-span-2">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Pembayaran</p>
                <p class="text-base font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-money-bill-wave text-orange-400"></i> Tunai (Cash / Kasir)
                </p>
            </div>
        </div>

        <!-- Status Pesanan -->
        <div class="p-4 bg-orange-50/50 rounded-2xl border border-orange-100 flex items-center justify-between">
            <span class="text-sm font-bold text-gray-700">Status Pesanan</span>
            <span class="px-4 py-1.5 bg-orange-100 text-orange-600 font-bold rounded-full text-xs flex items-center gap-1.5 shadow-xs">
                <i class="fas fa-clock"></i> Menunggu Konfirmasi Kasir
            </span>
        </div>

        <!-- Item Pesanan -->
        <div>
            <h3 class="font-bold text-gray-800 text-base mb-3 flex items-center gap-2">
                <i class="fas fa-box-open text-orange-400"></i> Item Pesanan
            </h3>
            <div class="divide-y divide-gray-100 border border-gray-100 rounded-2xl overflow-hidden">
                @foreach($order->items as $item)
                    @php
                        $imgUrl = null;
                        if (!empty($item->product->image) && !str_contains($item->product->image, 'svg') && !str_contains($item->product->image, 'glass') && !str_contains($item->product->image, 'cup')) {
                            $imgUrl = str_starts_with($item->product->image, 'http') ? $item->product->image : asset($item->product->image);
                        }
                    @endphp
                    <div class="p-4 flex items-center gap-4 bg-gray-50/30">
                        <div class="w-16 h-16 rounded-xl bg-orange-100 p-1 flex items-center justify-center shrink-0 overflow-hidden">
                            @if($imgUrl)
                                <img src="{{ $imgUrl }}" alt="{{ $item->product->name ?? 'Produk' }}" class="w-full h-full object-cover rounded-lg">
                            @else
                                <i class="fas fa-glass-whiskey text-orange-400 text-xl"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-gray-800 text-base truncate">{{ $item->product->name ?? 'Minuman' }}</h4>
                            <p class="text-xs text-gray-400">x{{ $item->quantity }} @ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                        <span class="font-bold text-gray-800 text-base">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Ringkasan Harga -->
        <div class="space-y-2 pt-4 border-t border-gray-100 text-sm">
            <div class="flex justify-between text-gray-600">
                <span>Subtotal</span>
                <span class="font-semibold text-gray-800">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="border-t border-gray-100 pt-3 flex justify-between items-center text-lg">
                <span class="font-bold text-gray-800">Total Bayar</span>
                <span class="font-black text-orange-500 text-2xl">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Tombol Aksi (Teks diubah jadi Kembali) -->
        <div class="pt-4 flex gap-3">
            <a href="{{ route('customer.menu') }}" class="btn-juice flex-1 py-4 text-center text-white font-bold rounded-2xl shadow-lg block">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection