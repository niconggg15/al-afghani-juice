@extends('customer.layout')

@section('title', 'Menu - Al Afghani Juice')

@section('content')
<div class="space-y-6">
    <!-- Hero Banner Utama -->
    <div class="relative">
        <div class="juice-gradient rounded-2xl p-6 md:p-10 text-white relative overflow-hidden shadow-xl">
            <div class="absolute inset-0 opacity-10">
                <i class="fas fa-leaf text-9xl absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></i>
            </div>
            
            <!-- Header Banner -->
            <div class="relative z-10 flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/25 rounded-full flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-leaf text-xl"></i>
                    </div>
                    <span class="text-xl font-bold">Al Afghani Juice</span>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="px-4 py-2.5 bg-white/25 hover:bg-white/35 backdrop-blur-sm rounded-full text-white text-sm font-bold transition-all flex items-center gap-2 shadow-md hover:scale-105 active:scale-95">
                        <i class="fas fa-user-shield text-base"></i>
                        <span>Login Admin</span>
                    </a>

                    <a href="{{ route('customer.cart.index') }}" id="cart-icon-btn" class="relative p-3 rounded-full bg-white/25 hover:bg-white/35 backdrop-blur-sm transition-all shadow-md hover:scale-105 active:scale-95">
                        <i class="fas fa-shopping-cart text-xl text-white"></i>
                        @php
                            $cart = session('cart', []);
                            $totalQty = is_array($cart) ? array_sum(array_column($cart, 'quantity')) : 0;
                        @endphp
                        <span id="cart-count" class="cart-badge" style="{{ $totalQty <= 0 ? 'display: none;' : '' }}">
                            {{ $totalQty }}
                        </span>
                    </a>
                </div>
            </div>

            <!-- Judul Banner -->
            <div class="relative z-10 mb-6">
                <h1 class="text-3xl md:text-5xl font-extrabold mb-3 drop-shadow-lg">Menu Al Afghani Juice</h1>
                <p class="text-base md:text-lg opacity-90 max-w-xl">Minuman segar & sehat buat hari yang lebih bahagia. Pilih favoritmu, Princess!</p>
            </div>

            <!-- Filter Kategori -->
            <div class="relative z-10 flex gap-2 overflow-x-auto pb-2 hide-scrollbar">
                <button class="category-tab-btn px-4 py-2 bg-white text-orange-500 rounded-full whitespace-nowrap text-sm font-semibold shadow-sm active" data-category="all">
                    <i class="fas fa-th-large mr-1"></i>Semua
                </button>
                <button class="category-tab-btn px-4 py-2 bg-white/80 backdrop-blur-sm text-gray-700 hover:bg-white hover:text-orange-500 rounded-full whitespace-nowrap text-sm font-medium transition-all" data-category="jus buah">
                    <i class="fas fa-glass-whiskey mr-1"></i>Jus Buah
                </button>
                <button class="category-tab-btn px-4 py-2 bg-white/80 backdrop-blur-sm text-gray-700 hover:bg-white hover:text-orange-500 rounded-full whitespace-nowrap text-sm font-medium transition-all" data-category="es jeruk">
                    <i class="fas fa-lemon mr-1"></i>Es Jeruk
                </button>
                <button class="category-tab-btn px-4 py-2 bg-white/80 backdrop-blur-sm text-gray-700 hover:bg-white hover:text-orange-500 rounded-full whitespace-nowrap text-sm font-medium transition-all" data-category="capcin">
                    <i class="fas fa-coffee mr-1"></i>Capcin
                </button>
                <button class="category-tab-btn px-4 py-2 bg-white/80 backdrop-blur-sm text-gray-700 hover:bg-white hover:text-orange-500 rounded-full whitespace-nowrap text-sm font-medium transition-all" data-category="pop ice">
                    <i class="fas fa-ice-cream mr-1"></i>Pop Ice
                </button>
            </div>
        </div>
    </div>

    <!-- Grid Produk Minuman -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5" id="products-grid">
        @foreach($products as $product)
            @php
                $itemName = strtolower($product->name);
                $catName = $product->category->name ?? 'Jus Buah';
                $catLower = strtolower($catName);

                $badgeBg = 'bg-[#FF6B6B]';
                $btnBg = 'bg-[#FF6B6B]';
                $btnHover = 'hover:bg-[#FF5252]';
                $priceColor = 'text-[#FF6B6B]';

                if (str_contains($catLower, 'capcin')) {
                    $badgeBg = 'bg-[#64B5F6]';
                    $btnBg = 'bg-[#64B5F6]';
                    $btnHover = 'hover:bg-[#42A5F5]';
                    $priceColor = 'text-[#64B5F6]';
                } elseif (str_contains($catLower, 'es jeruk')) {
                    $badgeBg = 'bg-[#FF9800]';
                    $btnBg = 'bg-[#FF9800]';
                    $btnHover = 'hover:bg-[#F57C00]';
                    $priceColor = 'text-[#FF9800]';
                } elseif (str_contains($catLower, 'pop ice')) {
                    $badgeBg = 'bg-[#B39DDB]';
                    $btnBg = 'bg-[#B39DDB]';
                    $btnHover = 'hover:bg-[#9575CD]';
                    $priceColor = 'text-[#B39DDB]';
                }
            @endphp

            <article class="product-card bg-white rounded-3xl shadow-md border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300" 
                     data-category-id="{{ $product->category_id }}" 
                     data-category-name="{{ strtolower($catName . ' ' . $product->name) }}">
                
                <div class="relative h-56 overflow-hidden flex items-center justify-center bg-gray-50/50">
                    <div class="w-full h-full flex items-center justify-center overflow-hidden">
                        @if($product->image && !str_contains($product->image, '<svg') && strlen($product->image) > 5)
                            <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                 class="w-full h-full object-cover scale-110 translate-y-3 transition-transform duration-300 hover:scale-115">
                            
                            <div class="w-full h-full items-center justify-center relative bg-gradient-to-br from-orange-50 to-orange-100/50" style="display: none;">
                                <div class="w-32 h-36 bg-white/40 backdrop-blur-xs rounded-3xl border-2 border-white/60 relative flex items-center justify-center shadow-inner">
                                    <div class="absolute -top-3 right-8 w-2 h-16 bg-red-400 rounded-full transform rotate-12 shadow-xs"></div>
                                    <div class="w-24 h-24 rounded-2xl bg-orange-400/90 flex items-center justify-center shadow-md relative overflow-hidden">
                                        <i class="fas fa-glass-whiskey text-white/90 text-2xl"></i>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="w-full h-full flex items-center justify-center relative bg-gradient-to-br from-orange-50 to-orange-100/50">
                                <div class="w-32 h-36 bg-white/40 backdrop-blur-xs rounded-3xl border-2 border-white/60 relative flex items-center justify-center shadow-inner">
                                    <div class="absolute -top-3 right-8 w-2 h-16 bg-red-400 rounded-full transform rotate-12 shadow-xs"></div>
                                    <div class="w-24 h-24 rounded-2xl bg-orange-400/90 flex items-center justify-center shadow-md relative overflow-hidden">
                                        <i class="fas fa-glass-whiskey text-white/90 text-2xl"></i>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="absolute top-4 left-4 z-20">
                        <span class="px-3 py-1.5 text-xs font-bold rounded-full {{ $badgeBg }} text-white shadow-xs">
                            {{ $catName }}
                        </span>
                    </div>
                    
                    @if($product->is_featured)
                        <div class="absolute top-4 right-4 z-20">
                            <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-[#FFC837] text-gray-900 flex items-center gap-1 shadow-xs">
                                <i class="fas fa-star text-xs"></i> Unggulan
                            </span>
                        </div>
                    @endif
                </div>
                
                <div class="p-5">
                    <h3 class="font-extrabold text-gray-800 text-xl mb-1">{{ $product->name }}</h3>
                    <p class="text-xs text-gray-400 mb-5 line-clamp-2 leading-relaxed">
                        {{ $product->description ?? strtolower($product->name) . ' segar nikmat menyegarkan.' }}
                    </p>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-black {{ $priceColor }}">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        
                        <form action="{{ route('customer.cart.add', $product->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button 
                                type="submit"
                                class="w-11 h-11 rounded-2xl {{ $btnBg }} {{ $btnHover }} text-white flex items-center justify-center font-bold transition-all shadow-md hover:scale-105 active:scale-95"
                            >
                                <i class="fas fa-plus text-lg"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Script Filter Kategori
    document.querySelectorAll('.category-tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const selectedCat = btn.dataset.category.toLowerCase();

            document.querySelectorAll('.category-tab-btn').forEach(b => {
                b.classList.remove('active', 'bg-white', 'text-orange-500', 'font-semibold', 'shadow-sm');
                b.classList.add('bg-white/80', 'text-gray-700', 'font-medium');
            });
            btn.classList.add('active', 'bg-white', 'text-orange-500', 'font-semibold', 'shadow-sm');
            btn.classList.remove('bg-white/80', 'text-gray-700', 'font-medium');

            document.querySelectorAll('.product-card').forEach(card => {
                const cardCatName = card.dataset.categoryName || '';
                if (selectedCat === 'all' || cardCatName.includes(selectedCat)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection