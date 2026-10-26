@extends('admin.layout')

@section('title', 'Riwayat Pesanan - Al Afghani Juice')

@section('content')
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Riwayat Pesanan</h1>
        <p class="text-gray-600">Pantau pendapatan, pesanan yang menunggu arsip, dan riwayat berdasarkan tanggal.</p>
    </div>
    <a href="{{ route('cashier.dashboard') }}" class="inline-flex items-center gap-2 self-start rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div class="rounded-xl bg-white p-5 shadow-md">
        <p class="text-sm text-gray-500">Jumlah Pesanan Diarsipkan</p>
        <p class="text-2xl font-bold text-gray-800">{{ number_format($orders->total(), 0, ',', '.') }} pesanan</p>
    </div>
    <div class="rounded-xl bg-white p-5 shadow-md">
        <p class="text-sm text-gray-500">Total Pendapatan Pesanan Selesai</p>
        <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($completedRevenue, 0, ',', '.') }}</p>
    </div>
    <div class="rounded-xl bg-yellow-50 p-5 shadow-md">
        <p class="text-sm text-yellow-800">Selesai, Menunggu Arsip (&lt;24 Jam)</p>
        <p class="text-2xl font-bold text-yellow-900">{{ number_format($recentCompletedCount, 0, ',', '.') }} pesanan</p>
        <p class="mt-1 text-sm font-semibold text-yellow-800">Rp {{ number_format($recentCompletedRevenue, 0, ',', '.') }}</p>
    </div>
</div>

<form method="GET" action="{{ route('cashier.orders.history') }}" class="mb-6 rounded-xl bg-white p-5 shadow-md">
    <div class="grid grid-cols-1 items-end gap-4 sm:grid-cols-3">
        <label class="text-sm font-medium text-gray-700">
            Dari tanggal
            <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
        </label>
        <label class="text-sm font-medium text-gray-700">
            Sampai tanggal
            <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
        </label>
        <div class="flex gap-2">
            <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 font-semibold text-white hover:bg-green-700">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
            <a href="{{ route('cashier.orders.history') }}" class="rounded-lg border border-gray-300 px-4 py-2 font-semibold text-gray-700 hover:bg-gray-50">Reset</a>
        </div>
    </div>
    @if($errors->any())
        <p class="mt-3 text-sm text-red-600">{{ $errors->first() }}</p>
    @endif
</form>

@if($recentCompletedOrders->isNotEmpty())
    <div class="mb-6 overflow-hidden rounded-xl border border-yellow-200 bg-yellow-50 shadow-md">
        <div class="border-b border-yellow-200 px-5 py-4">
            <h2 class="font-bold text-yellow-900">Pesanan Selesai, Menunggu Arsip 24 Jam</h2>
            <p class="mt-1 text-sm text-yellow-800">Pendapatannya sudah dihitung. Pesanan ini akan masuk ke tabel riwayat setelah 24 jam.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-700">
                <thead class="text-xs uppercase tracking-wide text-yellow-900">
                    <tr>
                        <th class="px-5 py-3">Nomor Pesanan</th>
                        <th class="px-5 py-3">Pelanggan</th>
                        <th class="px-5 py-3">Waktu Selesai</th>
                        <th class="px-5 py-3 text-right">Total</th>
                        @if(auth()->user()->role !== 'analyst')
                            <th class="px-5 py-3 text-right">Detail</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-yellow-200">
                    @foreach($recentCompletedOrders as $order)
                        <tr>
                            <td class="whitespace-nowrap px-5 py-3 font-semibold">{{ $order->order_number }}</td>
                            <td class="px-5 py-3">{{ $order->customer_name ?? $order->user?->name ?? 'Pelanggan' }}</td>
                            <td class="whitespace-nowrap px-5 py-3">{{ $order->completed_at->format('d/m/Y H:i') }}</td>
                            <td class="whitespace-nowrap px-5 py-3 text-right font-semibold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            @if(auth()->user()->role !== 'analyst')
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('cashier.orders.show', $order) }}" class="font-semibold text-green-700 hover:text-green-900">Lihat</a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($recentCompletedCount > $recentCompletedOrders->count())
            <p class="border-t border-yellow-200 px-5 py-3 text-sm text-yellow-800">
                Menampilkan 10 pesanan terbaru dari {{ number_format($recentCompletedCount, 0, ',', '.') }} pesanan yang menunggu arsip.
            </p>
        @endif
    </div>
@endif

<div class="overflow-hidden rounded-xl bg-white shadow-md">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-5 py-4">Nomor Pesanan</th>
                    <th class="px-5 py-4">Pelanggan</th>
                    <th class="px-5 py-4">Tanggal Pesanan</th>
                    <th class="px-5 py-4">Tanggal Selesai</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4 text-right">Total</th>
                    @if(auth()->user()->role !== 'analyst')
                        <th class="px-5 py-4 text-right">Detail</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="whitespace-nowrap px-5 py-4 font-semibold">{{ $order->order_number }}</td>
                        <td class="px-5 py-4">{{ $order->customer_name ?? $order->user?->name ?? 'Pelanggan' }}</td>
                        <td class="whitespace-nowrap px-5 py-4">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="whitespace-nowrap px-5 py-4">{{ $order->completed_at?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td class="px-5 py-4">
                            @if($order->status === 'completed')
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Selesai</span>
                            @else
                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Dibatalkan</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-right font-semibold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        @if(auth()->user()->role !== 'analyst')
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('cashier.orders.show', $order) }}" class="font-semibold text-green-700 hover:text-green-900">Lihat</a>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role === 'analyst' ? 6 : 7 }}" class="px-5 py-12 text-center text-gray-500">
                            <i class="fas fa-clock mb-2 block text-3xl text-gray-300"></i>
                            Belum ada riwayat pesanan untuk rentang tanggal ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
        <div class="border-t border-gray-100 px-5 py-4">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
