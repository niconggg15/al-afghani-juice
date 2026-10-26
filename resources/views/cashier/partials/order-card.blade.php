<div class="bg-white rounded-xl shadow-md overflow-hidden border-l-4 {{ 
    $order->status === 'pending' ? 'border-orange-500' : 
    ($order->status === 'ready' ? 'border-green-500' : 'border-gray-400') 
}}">
    <div class="p-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-3">
            <div>
                <p class="font-semibold text-gray-800">{{ $order->order_number }}</p>
                <p class="text-sm text-gray-500">{{ $order->customer_name }} - {{ $order->customer_phone }}</p>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 text-sm rounded-full inline-block {{ 
                    $order->status === 'pending' ? 'bg-orange-100 text-orange-800' : 
                    ($order->status === 'ready' ? 'bg-green-100 text-green-800' : 
                    ($order->status === 'completed' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800')) 
                }} font-medium">
                    {{ $order->status === 'pending' ? 'Menunggu Konfirmasi' : ($order->status === 'ready' ? 'Siap Diambil' : ($order->status === 'completed' ? 'Selesai' : 'Dibatalkan')) }}
                </span>
                
                <span class="font-semibold text-lg text-green-600">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-2 mb-3">
            @foreach($order->items as $item)
                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">
                    {{ $item->product->name ?? 'Produk' }} x{{ $item->quantity }}
                </span>
            @endforeach
        </div>
        
        <div class="flex flex-wrap gap-2 items-center">
            {{-- Tombol jika status masih Pending (Menunggu Konfirmasi) --}}
            @if($order->status === 'pending')
                <form action="{{ route('cashier.orders.confirm-payment', $order) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn-green px-4 py-2 text-sm font-medium" onclick="return confirm('Konfirmasi pembayaran untuk {{ $order->order_number }}?')">
                        <i class="fas fa-check mr-1"></i>Konfirmasi Bayar
                    </button>
                </form>
            @endif
            
            {{-- Tombol langsung "Selesai" jika status sudah Ready (Siap Diambil) --}}
            @if($order->status === 'ready')
                <form action="{{ route('cashier.orders.update-status', $order) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-all shadow-xs" onclick="return confirm('Selesaikan pesanan {{ $order->order_number }}?')">
                        <i class="fas fa-check-circle mr-1"></i>Selesai
                    </button>
                </form>
            @endif
            
            <a href="{{ route('cashier.orders.show', $order) }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 font-medium">
                <i class="fas fa-eye mr-1"></i>Detail
            </a>
        </div>
    </div>
</div>