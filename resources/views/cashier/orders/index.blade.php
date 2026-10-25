@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-extrabold text-gray-800">Kategori</h1>
        <a href="{{ route('admin.categories.create') }}" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-800 hover:bg-gray-50 font-bold rounded-2xl text-sm transition-all flex items-center gap-2 shadow-xs">
            <i class="fas fa-plus"></i> Tambah Kategori
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-800 rounded-2xl font-bold">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider bg-gray-50/50">
                    <th class="p-4 pl-6">NAMA</th>
                    <th class="p-4">DESKRIPSI</th>
                    <th class="p-4">URUTAN</th>
                    <th class="p-4">STATUS</th>
                    <th class="p-4">PRODUK</th>
                    <th class="p-4 pr-6 text-center">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm font-semibold text-gray-700">
                @forelse($categories as $category)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="p-4 pl-6 flex items-center gap-3">
                            <div class="w-9 h-9 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-tag"></i>
                            </div>
                            <span class="font-extrabold text-gray-800">{{ $category->name }}</span>
                        </td>
                        <td class="p-4 text-gray-500 font-normal">{{ $category->description ?? '-' }}</td>
                        <td class="p-4 font-bold">{{ $category->order ?? 1 }}</td>
                        <td class="p-4">
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-600 font-bold rounded-full text-xs">
                                Aktif
                            </span>
                        </td>
                        <td class="p-4 font-bold text-gray-800">{{ $category->products_count ?? 0 }}</td>
                        <td class="p-4 pr-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="px-3 py-1.5 bg-blue-100 text-blue-600 hover:bg-blue-200 font-bold rounded-xl text-xs transition-all">
                                    Edit
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin mau hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-100 text-red-500 hover:bg-red-200 font-bold rounded-xl text-xs transition-all">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-gray-400">
                            <i class="fas fa-tags text-4xl mb-3 block"></i>
                            Belum ada kategori. Silakan jalankan seeder atau tambah manual!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection