@extends('customer.layout')

@section('title', 'Checkout - Al Afghani Juice')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800">Checkout</h1>
            <p class="text-gray-500 mt-1">Lengkapi data pemesanan</p>
        </div>
        <a href="{{ route('customer.cart') }}" class="px-4 py-2 bg-white/80 backdrop-blur-sm rounded-xl border border-gray-200 hover:border-orange-300 transition-all text-gray-600 font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
</div>

@if(empty($cart))
    <div class="text-center py-20">
        <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-orange-100 to-yellow-100 rounded-full flex items-center justify-center">
            <i class="fas fa-shopping-cart text-5xl text-orange-300"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-700 mb-3">Keranjang Kosong</h2>
        <a href="{{ route('customer.menu') }}" class="btn-juice px-8 py-4 rounded-xl text-lg font-semibold inline-flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>Ke Menu
        </a>
    </div>
@else
    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Order Items & Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-yellow-400 rounded-xl flex items-center justify-center">
                            <i class="fas fa-list text-white"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">Detail Pesanan</h2>
                    </div>
                    <span class="px-3 py-1 bg-orange-50 text-orange-600 rounded-full text-sm font-medium">{{ count($cart) }} Item</span>
                </div>
                
                <div class="divide-y divide-gray-100">
                    @foreach($cart as $item)
                        <div class="p-5 flex gap-4 items-center">
                            <div class="relative w-16 h-16 flex-shrink-0 rounded-xl overflow-hidden" style="background: linear-gradient(135deg, #FFE0E0 0%, #FFF5E0 100%)">
                                @if(!empty($item['image']))
                                    <img src="{{ str_starts_with($item['image'], 'http') ? $item['image'] : asset($item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-glass-whiskey text-xl text-orange-300"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-800 truncate">{{ $item['name'] }}</h3>
                                <p class="text-sm text-gray-500">Rp {{ number_format($item['price'], 0, ',', '.') }} x {{ $item['quantity'] }}</p>
                            </div>
                            <span class="font-bold text-gray-800">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Customer Info Form -->
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <h2 class="text-lg font-bold text-gray-800">Informasi Pemesan</h2>
                </div>
                
                <form action="{{ route('customer.checkout.process') }}" method="POST" id="checkout-form">
                    @csrf
                    
                    <div class="grid sm:grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="customer_name" required autocomplete="name" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400 focus:border-orange-400 transition-all bg-gray-50 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon <span class="text-red-500">*</span></label>
                            <input type="tel" name="customer_phone" required autocomplete="tel" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400 focus:border-orange-400 transition-all bg-gray-50 focus:bg-white">
                        </div>
                    </div>
                    
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Metode Pembayaran <span class="text-red-500">*</span></label>
                        <div class="grid sm:grid-cols-2 gap-3">
                            <label class="payment-option relative cursor-pointer group">
                                <input type="radio" name="payment_method" value="cash" required class="sr-only" checked>
                                <div class="p-5 border-2 border-gray-200 rounded-xl text-center transition-all group-has-[:checked]:border-orange-400 group-has-[:checked]:bg-orange-50 relative overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-br from-orange-400 to-yellow-400 opacity-0 group-has-[:checked]:opacity-5 transition-opacity"></div>
                                    <div class="relative z-10">
                                        <i class="fas fa-money-bill-wave text-4xl text-orange-400 mb-3"></i>
                                        <p class="font-bold text-gray-800">Tunai</p>
                                        <p class="text-xs text-gray-500 mt-1">Bayar di tempat</p>
                                    </div>
                                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-transparent group-has-[:checked]:bg-orange-400 transition-colors"></div>
                                </div>
                            </label>
                            <label class="payment-option relative cursor-pointer group">
                                <input type="radio" name="payment_method" value="qris" class="sr-only">
                                <div class="p-5 border-2 border-gray-200 rounded-xl text-center transition-all group-has-[:checked]:border-blue-400 group-has-[:checked]:bg-blue-50 relative overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-purple-500 opacity-0 group-has-[:checked]:opacity-5 transition-opacity"></div>
                                    <div class="relative z-10">
                                        <i class="fas fa-qrcode text-4xl text-blue-400 mb-3"></i>
                                        <p class="font-bold text-gray-800">QRIS</p>
                                        <p class="text-xs text-gray-500 mt-1">Scan QR Code</p>
                                    </div>
                                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-transparent group-has-[:checked]:bg-blue-400 transition-colors"></div>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Pesanan (Opsional)</label>
                        <textarea name="notes" rows="3" placeholder="Contoh: Kurang es, tambah gula, tidak pakai sedotan, dll" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-400 focus:border-orange-400 transition-all bg-gray-50 focus:bg-white resize-none"></textarea>
                    </div>
                    
                    <button type="submit" class="btn-juice w-full py-4 rounded-xl text-lg font-semibold shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i>Buat Pesanan Sekarang
                    </button>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-xl p-6 sticky top-24 animate-slide-in">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calculator text-white"></i>
                    </div>
                    <h2 class="text-lg font-bold text-gray-800">Ringkasan</h2>
                </div>
                
                <div class="space-y-3 mb-5">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal ({{ collect($cart)->sum('quantity') }} item)</span>
                        <span class="font-medium text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3 flex justify-between text-xl">
                        <span class="font-semibold text-gray-800">Total Bayar</span>
                        <span class="font-extrabold" style="color: #FF6B6B">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                <div class="p-4 bg-gradient-to-r from-orange-50 to-yellow-50 rounded-xl border border-orange-100 mb-5">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-shield-alt text-orange-500 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-orange-700 text-sm">Pembayaran Aman</p>
                            <p class="text-xs text-orange-600 mt-0.5">Dikonfirmasi langsung oleh kasir</p>
                        </div>
                    </div>
                </div>
                
                <!-- QRIS Payment Section (show when QRIS selected) -->
                <div id="qris-section" class="hidden p-5 bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl border border-blue-100 mb-5 animate-fade-in">
                    <h3 class="font-bold text-blue-700 mb-4 flex items-center gap-2">
                        <i class="fas fa-qrcode"></i> QRIS Payment
                    </h3>
                    <div class="text-center">
                        <div class="w-48 h-48 mx-auto mb-4 bg-white rounded-xl p-3 border border-blue-200">
                            <img src="{{ asset('images/qris-placeholder.svg') }}" alt="QRIS Code" class="w-full h-full object-contain" onerror="this.src='https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=AlAfghaniJuice'">
                        </div>
                        <p class="text-blue-700 font-medium">Scan QR Code untuk bayar</p>
                        <p class="text-xs text-blue-500 mt-1">Total: <span class="font-bold">Rp {{ number_format($total, 0, ',', '.') }}</span></p>
                        <div class="mt-3 p-3 bg-blue-100 rounded-lg text-xs text-blue-700">
                            <i class="fas fa-info-circle mr-1"></i> Pembayaran akan dikonfirmasi oleh kasir
                        </div>
                    </div>
                </div>

                <!-- Cash Payment Section (show when Cash selected) -->
                <div id="cash-section" class="p-5 bg-gradient-to-br from-orange-50 to-yellow-50 rounded-xl border border-orange-100 mb-5 animate-fade-in">
                    <h3 class="font-bold text-orange-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-money-bill-wave"></i> Pembayaran Tunai
                    </h3>
                    <div class="text-center">
                        <div class="w-20 h-20 mx-auto mb-3 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-hand-holding-usd text-3xl text-orange-500"></i>
                        </div>
                        <p class="text-orange-700 font-medium">Bayar di tempat</p>
                        <p class="text-xs text-orange-500 mt-1">Total: <span class="font-bold">Rp {{ number_format($total, 0, ',', '.') }}</span></p>
                        <div class="mt-3 p-3 bg-orange-100 rounded-lg text-xs text-orange-700">
                            <i class="fas fa-clock mr-1"></i> Menunggu pembayaran di kasir
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('customer.cart') }}" class="w-full py-3 rounded-xl text-center block font-medium text-gray-600 hover:text-orange-500 transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-arrow-left"></i>Kembali ke Keranjang
                </a>
            </div>
        </div>
    </div>
@endif

@section('scripts')
<script>
    document.querySelectorAll('.payment-option input').forEach(input => {
        input.addEventListener('change', (e) => {
            document.querySelectorAll('.payment-option div').forEach(div => {
                div.classList.remove('ring-2', 'ring-orange-400', 'ring-blue-400');
            });
            const selectedDiv = e.target.closest('div');
            if (e.target.value === 'cash') {
                selectedDiv.classList.add('ring-2', 'ring-orange-400');
            } else {
                selectedDiv.classList.add('ring-2', 'ring-blue-400');
            }
            
            const qrisSection = document.getElementById('qris-section');
            const cashSection = document.getElementById('cash-section');
            if (e.target.value === 'qris') {
                qrisSection.classList.remove('hidden');
                cashSection.classList.add('hidden');
            } else {
                qrisSection.classList.add('hidden');
                cashSection.classList.remove('hidden');
            }
        });
    });
    
    document.getElementById('checkout-form')?.addEventListener('submit', (e) => {
        const btn = e.target.querySelector('button[type="submit"]');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
        btn.disabled = true;
    });
    
    document.addEventListener('DOMContentLoaded', () => {
        const checked = document.querySelector('.payment-option input:checked');
        if (checked) {
            checked.dispatchEvent(new Event('change'));
        }
    });
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.3s ease-out; }
</style>
@endsection