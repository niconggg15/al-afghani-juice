@extends('admin.layout')

@section('title', 'Dashboard Kasir - Al Afghani Juice')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard Kasir</h1>
    <p class="text-gray-600">Kelola pesanan yang masuk</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Pesanan Hari Ini</p>
                <p class="text-3xl font-bold text-gray-800">{{ $todayStats['total_orders'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-bag text-2xl text-blue-600"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Pesanan Selesai</p>
                <p class="text-3xl font-bold text-gray-800">{{ $todayStats['completed_orders'] }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-2xl text-green-600"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Pendapatan Hari Ini</p>
                <p class="text-3xl font-bold text-gray-800">Rp {{ number_format($todayStats['total_revenue'], 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-coins text-2xl text-yellow-600"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Menunggu Konfirmasi</p>
                <p class="text-3xl font-bold text-gray-800">{{ $todayStats['pending_count'] }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-2xl text-orange-600"></i>
            </div>
        </div>
    </div>
</div>

<!-- Orders Tabs -->
<div class="space-y-8">
    <!-- Pending Orders -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-clock text-orange-500"></i>
                Menunggu Konfirmasi ({{ $pendingOrders->count() }})
            </h2>
        </div>
        
        @if($pendingOrders->isEmpty())
            <div class="bg-white rounded-xl shadow-md p-8 text-center">
                <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                <p class="text-gray-500">Tidak ada pesanan menunggu</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($pendingOrders as $order)
                    @include('cashier.partials.order-card', ['order' => $order, 'showConfirm' => true])
                @endforeach
            </div>
        @endif
    </div>

    <!-- Preparing Orders -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-utensils text-blue-500"></i>
                Sedang Disiapkan ({{ $preparingOrders->count() }})
            </h2>
        </div>
        
        @if($preparingOrders->isEmpty())
            <div class="bg-white rounded-xl shadow-md p-8 text-center">
                <i class="fas fa-utensils text-4xl text-gray-300 mb-2"></i>
                <p class="text-gray-500">Tidak ada pesanan dalam persiapan</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($preparingOrders as $order)
                    @include('cashier.partials.order-card', ['order' => $order])
                @endforeach
            </div>
        @endif
    </div>

    <!-- Ready Orders -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-check-circle text-green-500"></i>
                Siap Diambil ({{ $readyOrders->count() }})
            </h2>
        </div>
        
        @if($readyOrders->isEmpty())
            <div class="bg-white rounded-xl shadow-md p-8 text-center">
                <i class="fas fa-check-circle text-4xl text-gray-300 mb-2"></i>
                <p class="text-gray-500">Tidak ada pesanan siap</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($readyOrders as $order)
                    @include('cashier.partials.order-card', ['order' => $order])
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Refresh otomatis setiap 10 detik agar pesanan baru dari pelanggan langsung muncul
    setInterval(function() {
        window.location.reload();
    }, 10000);
</script>
@endsection