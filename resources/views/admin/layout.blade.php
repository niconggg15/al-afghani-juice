<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Al Afghani Juice - Kasir')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <header class="bg-green-600 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold flex items-center gap-2">
                <i class="fas fa-leaf text-2xl"></i>
                Al Afghani Juice - Kasir
            </a>
            
            <!-- Navbar Kanan Atas (Menu Pelanggan & Logout) -->
            <nav class="flex items-center gap-5">
                <a href="{{ route('customer.menu') }}" class="text-sm hover:text-green-200 font-medium flex items-center gap-1.5 transition-colors">
                    <i class="fas fa-utensils"></i> Menu
                </a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-sm hover:text-green-200 font-medium flex items-center gap-1.5 transition-colors">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </nav>
        </div>
        
        <!-- Navigasi Bawah Area Hijau (Hanya Dashboard & Pesanan Kasir) -->
        <nav class="bg-green-700 px-4 py-2">
            <div class="max-w-7xl mx-auto flex gap-6 overflow-x-auto text-sm font-medium">
                <a href="{{ route('admin.dashboard') }}" class="text-white hover:text-green-200 whitespace-nowrap flex items-center gap-1.5">
                    <i class="fas fa-chart-line text-xs"></i> Dashboard
                </a>

                @if(auth()->user()->role !== 'analyst')
                    <a href="{{ route('cashier.dashboard') }}" class="text-white hover:text-green-200 whitespace-nowrap flex items-center gap-1.5">
                        <i class="fas fa-cash-register text-xs"></i> Pesanan Kasir
                    </a>
                @endif
            </div>
        </nav>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>