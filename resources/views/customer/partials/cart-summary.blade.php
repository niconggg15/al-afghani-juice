<div class="bg-white rounded-2xl shadow-xl p-6 sticky top-24 animate-slide-in">
    <div class="flex items-center gap-3 mb-5">
        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center">
            <i class="fas fa-calculator text-white"></i>
        </div>
        <h2 class="text-lg font-bold text-gray-800">Ringkasan</h2>
    </div>
    
    <div class="space-y-3 mb-5">
        <div class="flex justify-between text-gray-600">
            <span>Subtotal ({{ array_sum(array_column($cart, 'quantity')) }} item)</span>
            <span class="font-medium text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
        </div>
        <div class="border-t border-gray-200 pt-3 flex justify-between text-xl">
            <span class="font-semibold text-gray-800">Total Bayar</span>
            <span class="font-extrabold" style="color: #FF6B6B">Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>
    </div>
    
    <div class="p-4 bg-gradient-to-r from-orange-50 to-yellow-50 rounded-xl border border-orange-100 mb-5">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-shield-alt text-orange-500 text-sm"></i>
            </div>
            <div>
                <p class="font-medium text-orange-700 text-sm">Pembayaran Aman</p>
                <p class="text-xs text-orange-600 mt-0.5">Dikonfirmasi langsung oleh kasir</p>
            </div>
        </div>
    </div>
    
    <div class="p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl border border-blue-100 mb-5">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-qrcode text-blue-500 text-sm"></i>
            </div>
            <div>
                <p class="font-medium text-blue-700 text-sm">QRIS Tersedia</p>
                <p class="text-xs text-blue-600 mt-0.5">Pilih QRIS untuk bayar via e-wallet</p>
            </div>
        </div>
    </div>
    
    <a href="{{ route('customer.checkout') }}" class="btn-juice w-full py-4 rounded-xl text-center block font-semibold text-lg shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
        <i class="fas fa-credit-card"></i>Lanjut ke Pembayaran
    </a>
    
    <a href="{{ route('customer.menu') }}" class="w-full py-3 rounded-xl text-center block font-medium text-gray-600 hover:text-orange-500 transition-colors flex items-center justify-center gap-2 mt-3">
        <i class="fas fa-arrow-left"></i>Kembali Belanja
    </a>
</div>