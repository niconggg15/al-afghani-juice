@extends('admin.layout')

@section('title', 'Tambah Kategori - Al Afghani Juice')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header dengan Tombol Kembali -->
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-white rounded-xl border border-gray-200 hover:border-orange-300 text-gray-600 font-medium flex items-center gap-2 shadow-sm transition-all">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Tambah Kategori</h1>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex gap-4">
                <button type="submit" class="btn-green px-6 py-2">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center justify-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection