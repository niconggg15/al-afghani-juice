@extends('customer.layout')

@section('title', 'Keranjang - Al Afghani Juice')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800">Keranjang Belanja</h1>
            <p class="text-gray-500 text-sm mt-1">Periksa pesananmu sebelum melanjutkan pembayaran</p>
        </div>
        <a href="{{ route('customer.menu') }}" class="text-orange-500 font-semibold hover:underline flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Tambah Minuman
        </a>
    </div>

    @if(empty($cart))
        <div class="bg-white rounded-3xl p-12 text-center shadow-lg max-w-lg mx-auto">
            <div class="w-24 h-24 mx-auto mb-6 bg-orange-50 rounded-full flex items-center justify-center">
                <i class="fas fa-shopping-basket text-4xl text-orange-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Keranjangmu Kosong</h3>
            <p class="text-gray-500 mb-6">Yuk, pilih minuman segar favoritmu dulu di halaman menu!</p>
            <a href="{{ route('customer.menu') }}" class="btn-juice px-8 py-3 text-white font-bold rounded-xl inline-block shadow-lg">
                Lihat Menu
            </a>
        </div>
    @else
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Daftar Items Keranjang -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                        <span class="font-bold text-gray-800 text-lg">Daftar Pesanan</span>
                        <span class="px-3 py-1 bg-orange-100 text-orange-600 font-bold rounded-full text-xs">
                            {{ count($cart) }} Item
                        </span>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @foreach($cart as $id => $item)
                            @php
                                $itemName = strtolower($item['name']);
                                
                                $bgGradient = 'from-orange-400 to-orange-500';
                                if (str_contains($itemName, 'alpukat')) {
                                    $bgGradient = 'from-emerald-400 to-green-600';
                                } elseif (str_contains($itemName, 'mangga')) {
                                    $bgGradient = 'from-amber-300 to-yellow-500';
                                } elseif (str_contains($itemName, 'naga') || str_contains($itemName, 'strawberry')) {
                                    $bgGradient = 'from-pink-500 to-rose-600';
                                } elseif (str_contains($itemName, 'jeruk')) {
                                    $bgGradient = 'from-orange-400 to-amber-500';
                                } elseif (str_contains($itemName, 'capcin') || str_contains($itemName, 'cappucino')) {
                                    $bgGradient = 'from-amber-700 to-stone-800';
                                } elseif (str_contains($itemName, 'pop ice')) {
                                    $bgGradient = 'from-sky-400 to-indigo-500';
                                }

                                $imgUrl = ($item['image'] && !str_contains($item['image'], 'svg') && !str_contains($item['image'], 'glass') && !str_contains($item['image'], 'cup')) ? asset('storage/' . $item['image']) : null;
                            @endphp

                            <div class="py-4 flex items-center gap-4">
                                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br {{ $bgGradient }} p-1.5 flex items-center justify-center shrink-0 shadow-md relative overflow-hidden group">
                                    @if($imgUrl)
                                        <img src="{{ $imgUrl }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover rounded-xl">
                                    @else
                                        <div class="w-full h-full bg-white/20 backdrop-blur-xs rounded-xl flex items-center justify-center relative border border-white/30">
                                            <div class="w-8 h-10 bg-white/40 rounded-b-xl border-2 border-white/80 relative flex items-center justify-center shadow-inner">
                                                <div class="absolute -top-3 right-1 w-1 h-5 bg-white rounded-full transform rotate-12 shadow-xs"></div>
                                                <i class="fas fa-glass-whiskey text-white text-xs drop-shadow-xs"></i>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-gray-800 text-lg truncate">{{ $item['name'] }}</h4>
                                    <p class="text-base font-semibold text-orange-500 mt-1">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                </div>

                                <!-- Pengatur Quantity -->
                                <div class="flex items-center gap-3 bg-gray-100 rounded-full p-1 border border-gray-200">
                                    <form action="{{ route('customer.cart.update', $id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ max(1, $item['quantity'] - 1) }}">
                                        <button type="submit" class="w-9 h-9 rounded-full bg-white text-gray-600 flex items-center justify-center shadow-xs hover:bg-gray-200 transition-all active:scale-95">
                                            <i class="fas fa-minus text-xs"></i>
                                        </button>
                                    </form>

                                    <span class="font-black text-gray-900 w-8 text-center text-lg">{{ $item['quantity'] }}</span>

                                    <form action="{{ route('customer.cart.update', $id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                        <button type="submit" class="w-9 h-9 rounded-full bg-white text-gray-600 flex items-center justify-center shadow-xs hover:bg-gray-200 transition-all active:scale-95">
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Total Harga Per Item & Tombol Hapus -->
                                <div class="text-right ml-2 flex flex-col items-end gap-1">
                                    <p class="font-black text-gray-800 text-xl">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                                    <form action="{{ route('customer.cart.remove', $id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 text-xs transition-colors p-1 rounded-full hover:bg-red-50">
                                            <i class="fas fa-trash-alt mr-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100 flex justify-between items-center">
                        <form action="{{ route('customer.cart.clear') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-gray-400 hover:text-red-500 transition-colors">
                                <i class="fas fa-broom mr-1"></i>Kosongkan Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Form Ringkasan & Checkout Langsung ke Backend -->
            <div class="lg:col-span-1">
                <form action="{{ route('customer.checkout.process') }}" method="POST" id="checkout-form" class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 sticky top-6 space-y-5">
                    @csrf
                    <h3 class="font-bold text-gray-800 text-lg border-b border-gray-100 pb-3">Ringkasan Pesanan</h3>

                    <!-- Input Nama Pembeli -->
                    <div class="space-y-1.5">
                        <label for="customer_name" class="block font-bold text-gray-700 text-sm">
                            Nama Pembeli <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-user text-sm"></i>
                            </span>
                            <input 
                                type="text" 
                                id="customer_name" 
                                name="customer_name" 
                                placeholder="Ketik nama kamu di sini..." 
                                required
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 focus:outline-none focus:border-orange-400 focus:bg-white transition-all shadow-xs"
                            >
                        </div>
                    </div>

                    <!-- Hidden field untuk telepon & metode pembayaran agar lolos validasi controller -->
                    <input type="hidden" name="customer_phone" value="-">
                    <input type="hidden" name="payment_method" value="cash">

                    <!-- Subtotal & Total Bayar -->
                    <div class="space-y-3 text-sm pt-2 border-t border-gray-100">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal ({{ collect($cart)->sum('quantity') }} item)</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-gray-100 pt-3 flex justify-between items-center">
                            <span class="font-bold text-gray-800 text-base">Total Bayar</span>
                            <span class="text-2xl font-black text-orange-500">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Metode Pembayaran Info -->
                    <div class="space-y-3">
                        <label class="block font-bold text-gray-800 text-sm">Metode Pembayaran</label>
                        <div class="flex items-center justify-between p-4 rounded-2xl border-2 border-orange-300 bg-orange-50/50">
                            <div>
                                <p class="font-bold text-gray-800 text-sm">Tunai (Cash / Kasir)</p>
                                <p class="text-xs text-gray-500">Bayar langsung di kasir kedai</p>
                            </div>
                            <i class="fas fa-money-bill-wave text-green-500 text-xl"></i>
                        </div>
                    </div>

                    <!-- Tombol Submit Form -->
                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-orange-400 to-orange-500 text-white font-extrabold rounded-2xl shadow-lg hover:from-orange-500 hover:to-orange-600 transition-all transform active:scale-95 text-center flex items-center justify-center gap-2">
                        <i class="fas fa-cash-register"></i> Selesaikan Pesanan
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Validasi sederhana sebelum form dikirim
    document.getElementById('checkout-form')?.addEventListener('submit', function(e) {
        const nameInput = document.getElementById('customer_name');
        if (nameInput && nameInput.value.trim() === '') {
            e.preventDefault();
            nameInput.classList.add('border-red-500', 'bg-red-50');
            nameInput.focus();
        }
    });
</script>
@endsection