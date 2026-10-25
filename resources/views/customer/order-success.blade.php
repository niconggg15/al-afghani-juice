@extends('customer.layout')

@section('title', 'Pesanan Berhasil - Al Afghani Juice')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Success Animation -->
    <div class="text-center py-12 animate-fade-in">
        <div class="relative inline-block mb-6">
            <div class="w-28 h-28 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center mx-auto shadow-2xl">
                <i class="fas fa-check text-5xl text-white"></i>
            </div>
            <!-- Ripple effect -->
            <div class="absolute inset-0 bg-green-400 rounded-full animate-ping opacity-75"></div>
            <div class="absolute inset-0 bg-green-400 rounded-full animate-ping opacity-50" style="animation-delay: 0.5s"></div>
        </div>
        <h1 class="text-4xl font-extrabold text-gray-800 mb-2">Pesanan Berhasil! 🎉</h1>
        <p class="text-lg text-gray-600">Terima kasih telah memesan di <span class="font-bold text-orange-500">Al Afghani Juice</span></p>
    </div>

    <!-- Order Details Card -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-6 animate-slide-up">
        <div class="bg-gradient-to-r from-orange-500 to-yellow-500 px-6 py-4">
            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fas fa-receipt"></i>Detail Pesanan
            </h2>
        </div>
        
        <div class="p-6 space-y-5">
            <!-- Order Info Grid -->
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-gray-50 rounded-xl">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Nomor Pesanan</p>
                    <p class="font-extrabold text-xl text-orange-500">{{ $order->order_number }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-xl">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Tanggal</p>
                    <p class="font-semibold text-gray-800">{{ $order->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-xl">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Nama</p>
                    <p class="font-semibold text-gray-800">{{ $order->customer_name }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-xl">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Telepon</p>
                    <p class="font-semibold text-gray-800">{{ $order->customer_phone }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-xl">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Pembayaran</p>
                    <p class="font-semibold capitalize text-gray-800 flex items-center gap-1">
                        @if($order->payment_method === 'qris')
                            <i class="fas fa-qrcode text-blue-500"></i>
                        @else
                            <i class="fas fa-money-bill-wave text-orange-500"></i>
                        @endif
                        {{ $order->payment_method }}
                    </p>
                </div>
                <div class="p-4 bg-gray-50 rounded-xl">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Status</p>
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-semibold {{ 
                        $order->status === 'pending' ? 'bg-orange-100 text-orange-700' : 
                        ($order->status === 'confirmed' ? 'bg-blue-100 text-blue-700' : 
                        ($order->status === 'preparing' ? 'bg-purple-100 text-purple-700' : 
                        ($order->status === 'ready' ? 'bg-green-100 text-green-700' : 
                        ($order->status === 'completed' ? 'bg-gray-100 text-gray-700' : 'bg-red-100 text-red-700')))) 
                    }}">
                        <i class="fas fa-{{ $order->status === 'pending' ? 'clock' : ($order->status === 'completed' ? 'check-circle' : 'utensils') }}"></i>
                        Menunggu Konfirmasi Kasir
                    </span>
                </div>
            </div>
            
            <!-- Items -->
            <div class="border-t border-gray-200 pt-5">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-glass-whiskey text-orange-500"></i>Item Pesanan</h3>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                        <div class="flex gap-4 p-4 bg-gray-50 rounded-xl">
                            <div class="w-14 h-14 flex-shrink-0 rounded-xl overflow-hidden" style="background: linear-gradient(135deg, #FFE0E0 0%, #FFF5E0 100%)">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-glass-whiskey text-xl text-orange-300"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 truncate">{{ $item->product->name }}</p>
                                <p class="text-sm text-gray-500">x{{ $item->quantity }} @ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                @if($item->notes)
                                    <p class="text-xs text-orange-500 mt-1 flex items-center gap-1"><i class="fas fa-sticky-note"></i>{{ $item->notes }}</p>
                                @endif
                            </div>
                            <span class="font-bold text-gray-800 self-center">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Price Breakdown -->
            <div class="border-t border-gray-200 pt-5 space-y-3">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal</span>
                    <span class="font-medium text-gray-800">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>PPN (0%)</span>
                    <span class="font-medium text-gray-800">Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xl font-extrabold border-t border-gray-200 pt-3" style="color: #FF6B6B">
                    <span>Total</span>
                    <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- QRIS Payment if selected -->
    @if($order->payment_method === 'qris' && $order->payment)
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 animate-slide-up" style="animation-delay: 0.1s">
            <div class="text-center mb-5">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-purple-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-qrcode text-white text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Pembayaran QRIS</h3>
                <p class="text-gray-500">Scan QR Code di kasir atau via e-wallet</p>
            </div>
        </div>
    @endif

    <!-- Action Button (Mengerti & Tutup / Kembali ke Menu) -->
    <div class="space-y-3 animate-fade-in" style="animation-delay: 0.2s">
        <a href="{{ route('customer.menu') }}" class="btn-juice w-full py-4 rounded-xl text-center block font-semibold text-lg shadow-lg hover:shadow-xl transition-all inline-flex items-center justify-center gap-2" style="background-color: #FF6B6B; color: white;">
            <i class="fas fa-check-circle"></i>Mengerti & Tutup
        </a>
        
        <div class="p-4 bg-gradient-to-r from-orange-50 to-yellow-50 rounded-xl border border-orange-100 text-center">
            <p class="text-sm text-orange-700">
                <i class="fas fa-info-circle mr-1"></i>
                Silakan sebutkan nomor pesanan: <strong class="text-orange-800">{{ $order->order_number }}</strong> ke kasir
            </p>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function copyOrderNumber() {
        navigator.clipboard.writeText('{{ $order->order_number }}').then(() => {
            alert('Nomor pesanan disalin!');
        });
    }
    
    document.querySelectorAll('.font-extrabold.text-xl.text-orange-500').forEach(el => {
        el.style.cursor = 'pointer';
        el.title = 'Klik untuk salin';
        el.addEventListener('click', copyOrderNumber);
    });
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    .animate-slide-up { animation: slideUp 0.5s ease-out; }
</style>
@endsection