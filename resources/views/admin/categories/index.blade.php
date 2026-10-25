@extends('admin.layout')

@section('title', 'Kategori - Al Afghani Juice')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Kategori</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn-green px-6 py-2">
        <i class="fas fa-plus mr-2"></i>Tambah Kategori
    </a>
</div>

<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urutan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-10 h-10 object-cover rounded">
                            @else
                                <div class="w-10 h-10 bg-green-100 rounded flex items-center justify-center">
                                    <i class="fas fa-tag text-green-600"></i>
                                </div>
                            @endif
                            <span class="font-medium">{{ $category->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600 max-w-xs truncate">{{ $category->description ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $category->sort_order }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $category->products_count ?? $category->products()->count() }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-sm hover:bg-blue-200">
                                Edit
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                                @method('DELETE')
                                @csrf
                                <button type="submit" onclick="return confirm('Hapus kategori ini?')" class="px-3 py-1 bg-red-100 text-red-700 rounded text-sm hover:bg-red-200">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    @if($categories->total() > 0)
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $categories->links() }}
        </div>
    @endif
</div>

@if($categories->isEmpty())
    <div class="text-center py-12">
        <i class="fas fa-tags text-4xl text-gray-300 mb-2"></i>
        <p class="text-gray-500 mb-4">Belum ada kategori</p>
        <a href="{{ route('admin.categories.create') }}" class="btn-green px-6 py-2">
            <i class="fas fa-plus mr-2"></i>Tambah Kategori Pertama
        </a>
    </div>
@endif
@endsection