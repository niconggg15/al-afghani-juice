@extends('admin.layout')

@section('title', 'Kategori - Al Afghani Juice')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-white rounded-xl border border-gray-200 hover:border-orange-300 text-gray-600 font-medium flex items-center gap-2 shadow-sm transition-all">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Kategori</h1>
    </div>
    @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.categories.create') }}" class="btn-green px-6 py-2">
            <i class="fas fa-plus mr-2"></i>Tambah Kategori
        </a>
    @endif
</div>

<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Produk</th>
                @if(auth()->user()->role === 'admin')
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $category->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $category->description ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $category->products_count ?? $category->products->count() }} Produk</td>
                    @if(auth()->user()->role === 'admin')
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-semibold hover:bg-blue-200 transition-colors">
                                    Edit
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" onclick="return confirm('Hapus kategori ini?')" class="px-3 py-1 bg-red-100 text-red-700 rounded-lg text-sm font-semibold hover:bg-red-200 transition-colors">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($categories->isEmpty())
    <div class="text-center py-12 bg-white rounded-3xl shadow-sm border border-gray-100 mt-6">
        <i class="fas fa-tags text-4xl text-gray-300 mb-2"></i>
        <p class="text-gray-500 mb-4">Belum ada kategori</p>
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.categories.create') }}" class="btn-green px-6 py-2">
                <i class="fas fa-plus mr-2"></i>Tambah Kategori Pertama
            </a>
        @endif
    </div>
@endif
@endsection