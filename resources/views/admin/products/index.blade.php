@extends('admin.layout')

@section('title', 'Produk - Al Afghani Juice')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Produk</h1>
    <a href="{{ route('admin.products.create') }}" class="btn-green px-6 py-2">
        <i class="fas fa-plus mr-2"></i>Tambah Produk
    </a>
</div>

<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unggulan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($product->image && !str_contains($product->image, '<svg') && strlen($product->image) > 5)
                                <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                     class="w-10 h-10 object-cover rounded-xl shadow-xs">
                                
                                <!-- Fallback mini gelas jika gambar di storage gagal dimuat -->
                                <div class="w-10 h-10 bg-gradient-to-br from-orange-100 to-orange-200 rounded-xl items-center justify-center p-1 relative border border-orange-200 shadow-xs" style="display: none;">
                                    <div class="w-5 h-6 bg-white/90 rounded-b-md border border-orange-300 relative flex items-center justify-center">
                                        <div class="absolute -top-1.5 right-0.5 w-0.5 h-3 bg-red-400 rounded-full transform rotate-12"></div>
                                    </div>
                                </div>
                            @else
                                <!-- Mini Ilustrasi Gelas Seragam untuk Admin -->
                                <div class="w-10 h-10 bg-gradient-to-br from-orange-100 to-orange-200 rounded-xl flex items-center justify-center p-1 relative border border-orange-200 shadow-xs">
                                    <div class="w-5 h-6 bg-white/90 rounded-b-md border border-orange-300 relative flex items-center justify-center">
                                        <div class="absolute -top-1.5 right-0.5 w-0.5 h-3 bg-red-400 rounded-full transform rotate-12"></div>
                                    </div>
                                </div>
                            @endif
                            <span class="font-medium text-gray-800">{{ $product->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $product->category->name ?? '-' }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-800">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <span class="{{ $product->isLowStock() ? 'text-red-600 font-semibold' : 'text-gray-700' }}">
                            {{ $product->stock }}
                            @if($product->isLowStock())
                                <span class="ml-1 text-xs text-red-500">(Min: {{ $product->min_stock }})</span>
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $product->is_featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $product->is_featured ? 'Ya' : 'Tidak' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-semibold hover:bg-blue-200 transition-colors">
                                Edit
                            </a>
                            <a href="{{ route('admin.products.show', $product) }}" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition-colors">
                                Detail
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                                @method('DELETE')
                                @csrf
                                <button type="submit" onclick="return confirm('Hapus produk ini?')" class="px-3 py-1 bg-red-100 text-red-700 rounded-lg text-sm font-semibold hover:bg-red-200 transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    @if(method_exists($products, 'total') && $products->total() > 0)
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $products->links() }}
        </div>
    @endif
</div>

@if($products->isEmpty())
    <div class="text-center py-12 bg-white rounded-3xl shadow-sm border border-gray-100 mt-6">
        <i class="fas fa-box text-4xl text-gray-300 mb-2"></i>
        <p class="text-gray-500 mb-4">Belum ada produk</p>
        <a href="{{ route('admin.products.create') }}" class="btn-green px-6 py-2">
            <i class="fas fa-plus mr-2"></i>Tambah Produk Pertama
        </a>
    </div>
@endif
@endsection