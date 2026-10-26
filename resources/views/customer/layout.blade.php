<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Al Afghani Juice')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }
        .cart-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #FF3366;
            color: white;
            border-radius: 9999px;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: bold;
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.1); } }
        .juice-gradient { background: linear-gradient(135deg, #FF6B6B 0%, #FF9F43 50%, #FFD93D 100%); }
        .product-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .product-card:hover { transform: translateY(-6px); box-shadow: 0 25px 50px rgba(0,0,0,0.15); }
        .btn-juice { background: linear-gradient(135deg, #FF6B6B 0%, #FF9F43 100%); transition: all 0.3s; }
        .btn-juice:hover { transform: scale(1.02); box-shadow: 0 10px 25px rgba(255,107,107,0.4); }
        
        .floating-bubbles { 
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100vh; 
            pointer-events: none; 
            z-index: 0; 
            overflow: hidden; 
        }
        .bubble { 
            position: absolute; 
            bottom: -60px; 
            border-radius: 50%; 
            animation: floatUp linear infinite; 
        }
        @keyframes floatUp { 
            0% { transform: translateY(0) scale(0.5); opacity: 0; } 
            15% { opacity: 0.7; } 
            85% { opacity: 0.7; } 
            100% { transform: translateY(-110vh) scale(1.2); opacity: 0; } 
        }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="min-h-screen relative bg-gray-50">
    <!-- Floating Bubbles Background -->
    <div class="floating-bubbles" id="bubbles"></div>

    <!-- MAIN CONTENT -->
    <main class="max-w-7xl mx-auto px-4 py-6 relative z-10">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3 animate-slide-down" id="success-alert">
                <i class="fas fa-check-circle text-xl"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3 animate-slide-down" id="error-alert">
                <i class="fas fa-exclamation-circle text-xl"></i>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="relative z-10 bg-gray-900 text-white py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8 mb-8">
                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center gap-2"><i class="fas fa-leaf text-orange-400"></i>Al Afghani Juice</h3>
                    <p class="text-gray-400">Minuman segar & sehat untuk hari yang lebih bahagia</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-3">Jam Buka</h4>
                    <p class="text-gray-400">Senin - Minggu: 07:00 - 21:00</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-3">Alamat</h4>
                    <p class="text-gray-400">📍 Jl. Cijagra No. 13, RT/RW 02/11, Cilampeni, Kec. Katapang, Kabupaten Bandung, Jawa Barat 40971 <br></p>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-gray-500">
                <p>&copy; {{ date('Y') }} Al Afghani Juice. Dibuat dengan ❤️</p>
            </div>
        </div>
    </footer>

    <script>
        function createBubbles() {
            const container = document.getElementById('bubbles');
            if (!container) return;
            const colors = [
                'rgba(255,107,107,0.25)', 
                'rgba(255,159,67,0.25)', 
                'rgba(255,217,61,0.25)', 
                'rgba(116,185,255,0.25)', 
                'rgba(255,154,162,0.25)',
                'rgba(199,206,234,0.25)'
            ];
            
            for (let i = 0; i < 35; i++) {
                const bubble = document.createElement('div');
                bubble.className = 'bubble';
                bubble.style.left = Math.random() * 100 + '%';
                const size = Math.random() * 45 + 15; 
                bubble.style.width = size + 'px';
                bubble.style.height = size + 'px';
                bubble.style.background = colors[Math.floor(Math.random() * colors.length)];
                bubble.style.animationDelay = (Math.random() * 10) + 's';
                bubble.style.animationDuration = (Math.random() * 6 + 6) + 's';
                container.appendChild(bubble);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            createBubbles();
            localStorage.removeItem('cart');
        });
    </script>
    @yield('scripts')
</body>
</html>