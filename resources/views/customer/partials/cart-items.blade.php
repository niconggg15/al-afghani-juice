<div class="bg-white rounded-2xl shadow-xl overflow-hidden">
    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-lg font-bold text-gray-800">Daftar Pesanan</h2>
        <span class="px-3 py-1 bg-orange-50 text-orange-600 rounded-full text-sm font-medium">{{ count($cart) }} Item</span>
    </div>
    
    <div class="divide-y divide-gray-100">
        @foreach($cart as $item)
            <div class="p-5 flex gap-4 items-center cart-item animate-slide-in" data-id="{{ $item['id'] }}">
                <!-- Product Image -->
                <div class="relative w-20 h-20 flex-shrink-0 rounded-xl overflow-hidden" style="background: linear-gradient(135deg, #FFE0E0 0%, #FFF5E0 100%)">
                    @if($item['image'])
                        <img src="{{ str_starts_with($item['image'], 'http') ? $item['image'] : (str_starts_with($item['image'], 'images/') ? asset($item['image']) : asset('storage/' . $item['image'])) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-glass-whiskey text-2xl text-orange-300"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Product Info -->
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-800 truncate">{{ $item['name'] }}</h3>
                    <p class="text-sm text-gray-500">Rp {{ number_format($item['price'], 0, ',', '.') }} @</p>
                    @if(!empty($item['notes']))
                        <p class="text-xs text-orange-500 mt-1 flex items-center gap-1"><i class="fas fa-sticky-note"></i>{{ $item['notes'] }}</p>
                    @endif
                </div>
                
                <!-- Quantity Control -->
                <div class="flex items-center gap-2 bg-gray-50 rounded-xl px-3 py-2">
                    <form action="{{ route('customer.cart.update', $item['id']) }}" method="POST" class="flex items-center gap-2">
                        @method('PUT')
                        @csrf
                        <button type="button" onclick="updateQty(this, -1, {{ $item['id'] }}, {{ $item['stock'] ?? 99 }})" class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-orange-500 hover:border-orange-300 transition-all">
                            <i class="fas fa-minus text-sm"></i>
                        </button>
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['stock'] ?? 99 }}" class="w-14 text-center border-0 bg-transparent focus:ring-0 focus:outline-none font-semibold text-gray-800" readonly>
                        <button type="button" onclick="updateQty(this, 1, {{ $item['id'] }}, {{ $item['stock'] ?? 99 }})" class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-orange-500 hover:border-orange-300 transition-all">
                            <i class="fas fa-plus text-sm"></i>
                        </button>
                        <input type="hidden" name="quantity" id="qty-{{ $item['id'] }}" value="{{ $item['quantity'] }}">
                    </form>
                </div>
                
                <!-- Price & Remove -->
                <div class="flex flex-col items-end gap-2">
                    <span class="font-bold text-gray-800 text-lg">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                    <form action="{{ route('customer.cart.remove', $item['id']) }}" method="POST" class="inline">
                        @method('DELETE')
                        @csrf
                        <button type="submit" onclick="return confirmRemove(this, '{{ $item['name'] }}')" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="mt-4 flex justify-end">
    <form action="{{ route('customer.cart.clear') }}" method="POST">
        @method('DELETE')
        @csrf
        <button type="submit" onclick="return confirm('Yakin mau kosongkan keranjang?')" class="px-4 py-2 text-sm text-gray-500 hover:text-red-500 flex items-center gap-1 transition-colors">
            <i class="fas fa-trash-alt"></i>Kosongkan
        </button>
    </form>
</div>