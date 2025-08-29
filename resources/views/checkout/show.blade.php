@extends('layouts.app')

@section('title', 'Checkout - EventTick')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-purple-600">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="{{ route('events.show', $event) }}" class="text-gray-700 hover:text-purple-600">{{ $event->title }}</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-500">Checkout</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Checkout</h1>
                
                <form action="{{ route('checkout.process', $event) }}" method="POST" id="checkoutForm">
                    @csrf
                    <input type="hidden" name="quantity" value="{{ $quantity }}">
                    
                    <!-- Customer Information -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Customer</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                                <input type="text" name="customer_name" id="customer_name" required
                                       value="{{ auth()->user()->name }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            </div>
                            
                            <div>
                                <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" name="customer_email" id="customer_email" required
                                       value="{{ auth()->user()->email }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            </div>
                            
                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon *</label>
                                <input type="tel" name="customer_phone" id="customer_phone" required
                                       placeholder="08123456789"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Metode Pembayaran</h2>
                        
                        <div class="space-y-4">
                            <div class="border border-gray-200 rounded-lg p-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="payment_method" value="bank_transfer" checked
                                           class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300"
                                           onchange="togglePaymentMethod()">
                                    <div class="ml-3">
                                        <div class="flex items-center">
                                            <i class="fas fa-university text-blue-600 mr-2"></i>
                                            <span class="font-medium text-gray-900">Transfer Bank</span>
                                        </div>
                                        <p class="text-sm text-gray-500">Transfer ke rekening yang tersedia</p>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="border border-gray-200 rounded-lg p-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="payment_method" value="ewallet"
                                           class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300"
                                           onchange="togglePaymentMethod()">
                                    <div class="ml-3">
                                        <div class="flex items-center">
                                            <i class="fas fa-mobile-alt text-green-600 mr-2"></i>
                                            <span class="font-medium text-gray-900">E-Wallet</span>
                                        </div>
                                        <p class="text-sm text-gray-500">DANA, OVO, GoPay, dll</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Account Selection -->
                    <div id="bankAccountSection" class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Pilih Rekening Bank</h2>
                        
                        @if($bankAccounts->count() > 0)
                            <div class="space-y-3">
                                @foreach($bankAccounts as $bankAccount)
                                    <label class="flex items-center cursor-pointer border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                        <input type="radio" name="bank_account_id" value="{{ $bankAccount->id }}"
                                               class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300">
                                        <div class="ml-3 flex-1">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <div class="font-medium text-gray-900">{{ $bankAccount->bank_name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $bankAccount->account_number }}</div>
                                                    <div class="text-sm text-gray-500">{{ $bankAccount->account_holder }}</div>
                                                </div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ ucfirst($bankAccount->account_type) }}
                                                </span>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 rounded-lg">
                                <i class="fas fa-exclamation-triangle text-4xl text-yellow-500 mb-4"></i>
                                <p class="text-gray-600">Tidak ada rekening bank yang tersedia</p>
                            </div>
                        @endif
                    </div>

                    <!-- Payment Details -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Detail Pembayaran</h2>
                        
                        <div>
                            <label for="payment_details" class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan (Opsional)</label>
                            <textarea name="payment_details" id="payment_details" rows="3"
                                      placeholder="Tambahkan catatan atau instruksi khusus..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-4 px-8 rounded-lg transition-colors text-lg">
                            <i class="fas fa-credit-card mr-2"></i>Lanjutkan ke Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h3>
                
                <div class="space-y-4 mb-6">
                    <div class="flex items-center">
                        @if($event->image)
                            <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" 
                                 class="w-16 h-16 object-cover rounded-lg mr-3">
                        @else
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-400 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-calendar-alt text-white"></i>
                            </div>
                        @endif
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $event->title }}</h4>
                            <p class="text-sm text-gray-500">{{ $event->venue }}</p>
                        </div>
                    </div>
                    
                    <div class="border-t pt-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Jumlah Ticket:</span>
                            <span>{{ $quantity }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Harga per Ticket:</span>
                            <span>Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t pt-2">
                            <div class="flex justify-between text-lg font-bold text-gray-900">
                                <span>Total:</span>
                                <span class="text-purple-600">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-purple-600 mr-2"></i>
                        <div class="text-sm text-purple-800">
                            <p class="font-medium">Pesanan akan dibuat setelah pembayaran</p>
                            <p class="text-purple-600">Upload bukti pembayaran untuk verifikasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePaymentMethod() {
    const bankTransfer = document.querySelector('input[value="bank_transfer"]');
    const bankAccountSection = document.getElementById('bankAccountSection');
    
    if (bankTransfer.checked) {
        bankAccountSection.style.display = 'block';
    } else {
        bankAccountSection.style.display = 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    togglePaymentMethod();
});
</script>
@endsection
