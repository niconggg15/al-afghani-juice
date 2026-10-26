@extends('admin.layout')

@section('title', 'Dashboard Admin - Al Afghani Juice')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard Admin</h1>
    <p class="text-gray-600">Kelola kategori dan produk</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Kategori</p>
                <p class="text-3xl font-bold text-gray-800">{{ \App\Models\Category::count() }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-tags text-2xl text-blue-600"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Produk</p>
                <p class="text-3xl font-bold text-gray-800">{{ \App\Models\Product::count() }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-box text-2xl text-green-600"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Produk Aktif</p>
                <p class="text-3xl font-bold text-gray-800">{{ \App\Models\Product::where('is_active', true)->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-2xl text-yellow-600"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Stok Rendah</p>
                <p class="text-3xl font-bold text-gray-800">{{ \App\Models\Product::whereRaw('stock <= min_stock')->where('is_active', true)->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Aksi Cepat</h2>
        </div>
        <div class="grid grid-cols-2 gap-4">
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.categories.create') }}" class="p-4 border border-gray-200 rounded-lg hover:border-green-500 hover:bg-green-50 transition-colors text-center">
                    <i class="fas fa-plus text-3xl text-green-600 mb-2"></i>
                    <p class="font-medium">Tambah Kategori</p>
                </a>
                <a href="{{ route('admin.products.create') }}" class="p-4 border border-gray-200 rounded-lg hover:border-green-500 hover:bg-green-50 transition-colors text-center">
                    <i class="fas fa-plus text-3xl text-blue-600 mb-2"></i>
                    <p class="font-medium">Tambah Produk</p>
                </a>
            @endif
            <a href="{{ route('admin.categories.index') }}" class="p-4 border border-gray-200 rounded-lg hover:border-green-500 hover:bg-green-50 transition-colors text-center">
                <i class="fas fa-list text-3xl text-purple-600 mb-2"></i>
                <p class="font-medium">Kelola Kategori</p>
            </a>
            <a href="{{ route('admin.products.index') }}" class="p-4 border border-gray-200 rounded-lg hover:border-green-500 hover:bg-green-50 transition-colors text-center">
                <i class="fas fa-boxes text-3xl text-orange-600 mb-2"></i>
                <p class="font-medium">Kelola Produk</p>
            </a>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-lg font-semibold mb-4">Produk Stok Rendah</h2>
        @php
            $lowStock = \App\Models\Product::whereRaw('stock <= min_stock')->where('is_active', true)->limit(5)->get();
        @endphp
        
        @if($lowStock->isEmpty())
            <p class="text-gray-500 text-center py-4">Tidak ada produk dengan stok rendah</p>
        @else
            <div class="space-y-3">
                @foreach($lowStock as $product)
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                        <div>
                            <p class="font-medium">{{ $product->name }}</p>
                            <p class="text-sm text-gray-500">Stok: {{ $product->stock }} (Min: {{ $product->min_stock }})</p>
                        </div>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-sm text-green-600 hover:text-green-800">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection