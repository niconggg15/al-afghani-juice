@extends('admin.layout')

@section('title', 'Detail Produk - ' . $product->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-800">Detail Produk</h1>
        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/3 p-6 bg-gray-50 border-r border-gray-200">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover rounded-lg">
                @else
                    <div class="w-full h-64 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-glass-whiskey text-6xl text-green-300"></i>
                    </div>
                @endif
            </div>
            
            <div class="md:w-2/3 p-6">
                <div class="flex items-center gap-2 mb-3">
                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">{{ $product->category->name }}</span>
                    @if($product->is_featured)
                        <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">Unggulan</span>
                    @endif
                    <span class="px-2 py-1 text-xs rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                
                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $product->name }}</h2>
                
                <div class="flex items-baseline gap-4 mb-4">
                    <span class="text-3xl font-bold text-green-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    <span class="text-gray-500">Stok: {{ $product->stock }} {{ $product->isLowStock() ? '( Rendah )' : '' }}</span>
                </div>
                
                <p class="text-gray-600 mb-6">{{ $product->description ?? 'Tidak ada deskripsi' }}</p>
                
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Stok Minimum</p>
                        <p class="font-medium">{{ $product->min_stock }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Dibuat</p>
                        <p class="font-medium">{{ $product->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Diupdate</p>
                        <p class="font-medium">{{ $product->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="p-6 border-t border-gray-200">
            <div class="flex gap-4">
                <a href="{{ route('admin.products.edit', $product) }}" class="btn-green px-6 py-2">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                    @method('DELETE')
                    @csrf
                    <button type="submit" onclick="return confirm('Hapus produk ini?')" class="px-6 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 flex items-center gap-2">
                        <i class="fas fa-trash"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection