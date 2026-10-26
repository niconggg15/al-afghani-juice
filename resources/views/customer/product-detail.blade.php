@extends('customer.layout')

@section('title', $product->name . ' - Al Afghani Juice')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Product Detail -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
        @if($product->image)
            <img src="{{ str_starts_with($product->image, 'http') ? $product->image : (str_starts_with($product->image, 'images/') ? asset($product->image) : asset('storage/' . $product->image)) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover">
        @else
            <div class="w-full h-64 bg-green-100 flex items-center justify-center">
                <i class="fas fa-glass-whiskey text-6xl text-green-300"></i>
            </div>
        @endif
        
        <div class="p-6">
            <div class="flex items-center gap-2 mb-3">
                @if($product->is_featured)
                    <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">Unggulan</span>
                @endif
                <span class="text-sm text-gray-500">{{ $product->category->name }}</span>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-800 mb-3">{{ $product->name }}</h1>
            
            <div class="flex items-baseline gap-4 mb-4">
                <span class="text-3xl font-bold text-green-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                <span class="text-gray-500">{{ $product->stock > 0 ? 'Tersedia' : 'Habis' }} (Stok: {{ $product->stock }})</span>
            </div>
            
            <p class="text-gray-600 mb-6">{{ $product->description ?? 'Tidak ada deskripsi' }}</p>
            
            <form action="{{ route('customer.cart.add') }}" method="POST" class="flex gap-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                    <button type="button" onclick="changeQty(-1)" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700">
                        <i class="fas fa-minus"></i>
                    </button>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-16 text-center border-x border-gray-300 focus:outline-none">
                    <button type="button" onclick="changeQty(1)" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                
                <button type="submit" {{ $product->stock <= 0 ? 'disabled' : '' }} class="btn-green flex-1 py-3 rounded-lg {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-cart-plus mr-2"></i>Tambah ke Keranjang
                </button>
            </form>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Produk Serupa</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($relatedProducts as $related)
                    <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        @if($related->image)
                            <img src="{{ str_starts_with($related->image, 'http') ? $related->image : (str_starts_with($related->image, 'images/') ? asset($related->image) : asset('storage/' . $related->image)) }}" alt="{{ $related->name }}" class="w-full h-40 object-cover">
                        @else
                            <div class="w-full h-40 bg-green-100 flex items-center justify-center">
                                <i class="fas fa-glass-whiskey text-4xl text-green-300"></i>
                            </div>
                        @endif
                        
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-1">{{ $related->name }}</h3>
                            <p class="text-xl font-bold text-green-600 mb-3">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                            
                            <form action="{{ route('customer.cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $related->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" {{ $related->stock <= 0 ? 'disabled' : '' }} class="btn-green w-full py-2 rounded-lg text-sm {{ $related->stock <= 0 ? 'opacity-50' : '' }}">
                                    <i class="fas fa-plus mr-1"></i>Tambah
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    @endif
</div>

@section('scripts')
<script>
    function changeQty(delta) {
        const input = document.getElementById('quantity');
        const max = parseInt(input.max);
        let val = parseInt(input.value) + delta;
        if (val < 1) val = 1;
        if (val > max) val = max;
        input.value = val;
    }
</script>
@endsection