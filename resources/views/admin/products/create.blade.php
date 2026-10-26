@extends('admin.layout')

@section('title', 'Tambah Produk - Al Afghani Juice')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header dengan Tombol Kembali -->
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-white rounded-xl border border-gray-200 hover:border-orange-300 text-gray-600 font-medium flex items-center gap-2 shadow-sm transition-all">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Tambah Produk</h1>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                <select name="category_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('description') }}</textarea>
            </div>
            
            <div class="grid sm:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga <span class="text-red-500">*</span></label>
                    <input type="number" name="price" value="{{ old('price') }}" required min="0" step="100" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" required min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Stok Minimum <span class="text-red-500">*</span></label>
                <input type="number" name="min_stock" value="{{ old('min_stock', 5) }}" required min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                @error('min_stock')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Produk</label>
                <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <p class="text-sm text-gray-500 mt-1">Maksimal 2MB. Format: JPG, PNG, WebP</p>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid sm:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700">Aktif</span>
                    </label>
                </div>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700">Unggulan</span>
                    </label>
                </div>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" class="btn-green px-6 py-2">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
                <a href="{{ route('admin.products.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center justify-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection