@extends('layouts.app')

@section('title', $event->title . ' - EventTick')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                    <a href="{{ route('events.index') }}" class="text-gray-700 hover:text-purple-600">Events</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-500">{{ $event->title }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Event Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                @if($event->image)
                    <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" 
                         class="w-full h-96 object-cover">
                @else
                    <div class="w-full h-96 bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-8xl text-white opacity-50"></i>
                    </div>
                @endif
                
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium 
                                   {{ $event->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($event->status) }}
                        </span>
                        <span class="text-lg font-semibold text-purple-600">
                            Rp {{ number_format($event->price, 0, ',', '.') }}
                        </span>
                    </div>
                    
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $event->title }}</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="flex items-center">
                            <div class="bg-purple-100 p-3 rounded-lg mr-4">
                                <i class="fas fa-map-marker-alt text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Lokasi</p>
                                <p class="font-semibold text-gray-900">{{ $event->venue }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="bg-purple-100 p-3 rounded-lg mr-4">
                                <i class="fas fa-calendar text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal & Waktu</p>
                                <p class="font-semibold text-gray-900">{{ $event->event_date->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="bg-purple-100 p-3 rounded-lg mr-4">
                                <i class="fas fa-users text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Kapasitas</p>
                                <p class="font-semibold text-gray-900">{{ $event->capacity }} orang</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="bg-purple-100 p-3 rounded-lg mr-4">
                                <i class="fas fa-ticket-alt text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Sisa Kursi</p>
                                <p class="font-semibold text-gray-900">{{ $event->available_seats }} tersisa</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi Event</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $event->description }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Booking Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Pesan Ticket</h3>
                
                @if($event->is_available)
                    @auth
                        <form action="{{ route('checkout.show', $event) }}" method="GET" id="bookingForm">
                            <div class="mb-4">
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Ticket
                                </label>
                                <select name="quantity" id="quantity" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    @for($i = 1; $i <= min(10, $event->available_seats); $i++)
                                        <option value="{{ $i }}">{{ $i }} ticket</option>
                                    @endfor
                                </select>
                            </div>
                            
                            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-600">Harga per ticket:</span>
                                    <span class="font-semibold">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-600">Jumlah:</span>
                                    <span id="selected-quantity">1</span>
                                </div>
                                <div class="border-t pt-2">
                                    <div class="flex justify-between items-center">
                                        <span class="font-semibold text-lg">Total:</span>
                                        <span class="font-bold text-xl text-purple-600" id="total-amount">
                                            Rp {{ number_format($event->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                                <i class="fas fa-credit-card mr-2"></i>Lanjutkan ke Pembayaran
                            </button>
                        </form>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-lock text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600 mb-4">Silakan login untuk memesan ticket</p>
                            <a href="{{ route('login') }}" 
                               class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                                Login
                            </a>
                        </div>
                    @endauth
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-times-circle text-4xl text-red-400 mb-4"></i>
                        <p class="text-gray-600 mb-2">Maaf, ticket sudah habis</p>
                        <p class="text-sm text-gray-500">Event ini sudah penuh atau tidak tersedia</p>
                    </div>
                @endif
                
                <div class="mt-6 pt-6 border-t">
                    <h4 class="font-semibold text-gray-900 mb-3">Informasi Penting</h4>
                    <ul class="text-sm text-gray-600 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            Pembayaran via transfer bank
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            Ticket akan dikirim setelah pembayaran
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                            Tidak ada refund setelah pembayaran
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantitySelect = document.getElementById('quantity');
    const selectedQuantity = document.getElementById('selected-quantity');
    const totalAmount = document.getElementById('total-amount');
    const pricePerTicket = {{ $event->price }};
    
    function updateTotal() {
        const quantity = parseInt(quantitySelect.value);
        const total = pricePerTicket * quantity;
        
        selectedQuantity.textContent = quantity + ' ticket';
        totalAmount.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
    
    quantitySelect.addEventListener('change', updateTotal);
    updateTotal();
});
</script>
@endpush
@endsection
