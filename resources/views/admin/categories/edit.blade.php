@extends('admin.layout')

@section('title', 'Edit Kategori - ' . $category->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Edit Kategori</h1>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ $category->name }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ $category->description }}</textarea>
            </div>
            
            @if($category->image)
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Saat Ini</label>
                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-24 h-24 object-cover rounded-lg">
                </div>
            @endif
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Ganti Gambar</label>
                <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <p class="text-sm text-gray-500 mt-1">Maksimal 2MB. Format: JPG, PNG, WebP</p>
            </div>
            
            <div class="grid sm:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                    <input type="number" name="sort_order" value="{{ $category->sort_order }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="is_active" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="1" {{ $category->is_active ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !$category->is_active ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Warna Kategori</label>
                <div class="flex items-center gap-3">
                    <input type="color" name="color" value="{{ $category->color ?? '#FF6B6B' }}" class="w-12 h-10 border border-gray-300 rounded-lg cursor-pointer">
                    <input type="text" name="color" value="{{ $category->color ?? '#FF6B6B' }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="#FF6B6B" id="color-input">
                </div>
                <p class="text-sm text-gray-500 mt-1">Pilih warna untuk identitas kategori (format: #RRGGBB)</p>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" class="btn-green px-6 py-2">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center justify-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection