@extends('cashier.layout')

@section('title', 'Pesanan Kasir - Al Afghani Juice')

@section('content')
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Pesanan Kasir</h1>
        <p class="text-gray-600">Kelola pembayaran dan pesanan yang siap diambil.</p>
    </div>
    <a href="{{ route('cashier.dashboard') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>
</div>

<div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-xl bg-white p-5 shadow-md">
        <p class="text-sm text-gray-500">Pesanan Hari Ini</p>
        <p class="text-2xl font-bold text-gray-800">0</p>
    </div>
    <div class="rounded-xl bg-white p-5 shadow-md">
        <p class="text-sm text-gray-500">Selesai Hari Ini</p>
        <p class="text-2xl font-bold text-gray-800">0</p>
    </div>
    <div class="rounded-xl bg-white p-5 shadow-md">
        <p class="text-sm text-gray-500">Pendapatan Hari Ini</p>
        <p class="text-2xl font-bold text-gray-800">Rp 0</p>
    </div>
    <div class="rounded-xl bg-white p-5 shadow-md">
        <p class="text-sm text-gray-500">Menunggu Konfirmasi</p>
        <p class="text-2xl font-bold text-gray-800">0</p>
    </div>
</div>

<div class="space-y-8">
    <section>
        <h2 class="mb-4 flex items-center gap-2 text-xl font-semibold text-gray-800">
            <i class="fas fa-clock text-orange-500"></i>
            Menunggu Konfirmasi ({{ $pendingOrders->count() }})
        </h2>
        @if($pendingOrders->isEmpty())
            <div class="rounded-xl bg-white p-8 text-center shadow-md">
                <i class="fas fa-inbox mb-2 text-4xl text-gray-300"></i>
                <p class="text-gray-500">Belum ada pesanan menunggu konfirmasi.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($pendingOrders as $order)
                    @include('cashier.partials.order-card', ['order' => $order])
                @endforeach
            </div>
        @endif
    </section>

    <section>
        <h2 class="mb-4 flex items-center gap-2 text-xl font-semibold text-gray-800">
            <i class="fas fa-check-circle text-green-500"></i>
            Siap Diambil ({{ $readyOrders->count() }})
        </h2>
        @if($readyOrders->isEmpty())
            <div class="rounded-xl bg-white p-8 text-center shadow-md">
                <i class="fas fa-check-circle mb-2 text-4xl text-gray-300"></i>
                <p class="text-gray-500">Belum ada pesanan siap diambil.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($readyOrders as $order)
                    @include('cashier.partials.order-card', ['order' => $order])
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection

@section('scripts')
<script>
    setInterval(function() {
        window.location.reload();
    }, 10000);
</script>
@endsection
