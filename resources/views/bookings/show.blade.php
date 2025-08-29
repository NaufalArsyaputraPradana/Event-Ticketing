@extends('layouts.app')

@section('title', 'Detail Booking - EventTick')

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
                    <a href="{{ route('bookings.index') }}" class="text-gray-700 hover:text-purple-600">My Bookings</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-500">{{ $booking->booking_code }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Booking Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Booking #{{ $booking->booking_code }}</h1>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium 
                               {{ $booking->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                  ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Details</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-purple-600 mr-3"></i>
                                <span class="text-gray-900">{{ $booking->event->title }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-purple-600 mr-3"></i>
                                <span class="text-gray-900">{{ $booking->event->venue }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock text-purple-600 mr-3"></i>
                                <span class="text-gray-900">{{ $booking->event->event_date->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Details</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Jumlah Ticket:</span>
                                <span class="font-semibold">{{ $booking->quantity }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Harga per Ticket:</span>
                                <span class="font-semibold">Rp {{ number_format($booking->event->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-t pt-2">
                                <div class="flex justify-between">
                                    <span class="text-lg font-semibold">Total:</span>
                                    <span class="text-xl font-bold text-purple-600">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Status -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Pembayaran</h3>
                    
                    @if($booking->payment_status === 'paid')
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
                                <div>
                                    <p class="text-green-800 font-medium">Pembayaran Berhasil</p>
                                    <p class="text-green-600 text-sm">Pembayaran Anda telah diverifikasi pada {{ $booking->paid_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    @elseif($booking->payment_status === 'pending')
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-yellow-600 text-xl mr-3"></i>
                                <div>
                                    <p class="text-yellow-800 font-medium">Menunggu Verifikasi</p>
                                    <p class="text-yellow-600 text-sm">Tim kami sedang memverifikasi pembayaran Anda</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Show Uploaded Payment Proof -->
                        @if($booking->payments->first() && $booking->payments->first()->payment_proof)
                            <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                                <h4 class="font-medium text-blue-900 mb-3">Bukti Pembayaran yang Diupload</h4>
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <img src="{{ $booking->payments->first()->payment_proof_url }}" 
                                             alt="Bukti Pembayaran" 
                                             class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                                    </div>
                                    <div>
                                        <p class="text-sm text-blue-800 mb-2">Bukti pembayaran telah diupload dan sedang diverifikasi</p>
                                        <a href="{{ $booking->payments->first()->payment_proof_url }}" 
                                           target="_blank" 
                                           class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-external-link-alt mr-1"></i>
                                            Lihat Gambar Lengkap
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Upload Payment Proof -->
                        @if(!$booking->payments->first() || !$booking->payments->first()->payment_proof)
                            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-3">Upload Bukti Pembayaran</h4>
                                <form action="{{ route('bookings.confirm-payment', $booking) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-4">
                                        <input type="file" name="payment_proof" accept="image/*" required
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                        <p class="text-sm text-gray-500 mt-1">Upload bukti transfer bank (JPG, PNG)</p>
                                    </div>
                                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                                        Upload Bukti Pembayaran
                                    </button>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-times-circle text-red-600 text-xl mr-3"></i>
                                <div>
                                    <p class="text-red-800 font-medium">Pembayaran Gagal</p>
                                    <p class="text-red-600 text-sm">Pembayaran Anda tidak dapat diproses</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tickets -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Your Tickets</h3>
                
                @if($booking->tickets->count() > 0)
                    <div class="space-y-4">
                        @foreach($booking->tickets as $ticket)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="text-center mb-4">
                                    <div class="text-sm font-medium text-gray-900 mb-2">
                                        Ticket #{{ $ticket->formatted_ticket_number }}
                                    </div>
                                    <div class="text-xs text-gray-500 mb-2">{{ $ticket->ticket_code }}</div>
                                    
                                    @if($ticket->qr_code)
                                        <div class="inline-block">
                                            <img src="{{ $ticket->qr_code_url }}" alt="QR Code" class="w-32 h-32 mx-auto">
                                        </div>
                                    @else
                                        <div class="w-32 h-32 mx-auto bg-gray-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-qrcode text-4xl text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Customer Info -->
                                <div class="text-center mb-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $ticket->customer_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $ticket->customer_email }}</div>
                                    <div class="text-xs text-gray-500">{{ $ticket->customer_phone }}</div>
                                </div>
                                
                                <div class="text-center mb-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                               {{ $ticket->status === 'active' ? 'bg-green-100 text-green-800' : 
                                                  ($ticket->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                                  ($ticket->status === 'used' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')) }}">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </div>
                                
                                @if($ticket->status === 'used')
                                    <div class="text-center mt-2 text-sm text-gray-500">
                                        Digunakan pada {{ $ticket->used_at->format('d M Y, H:i') }}
                                    </div>
                                @endif
                                
                                <!-- Action Buttons -->
                                <div class="text-center mt-3 space-y-2">
                                    @if($ticket->status === 'active')
                                        <a href="{{ route('tickets.download', $ticket) }}" 
                                           class="inline-flex items-center w-full justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 transition-colors">
                                            <i class="fas fa-download mr-2"></i>Download Ticket
                                        </a>
                                        <a href="{{ route('tickets.show', $ticket) }}" 
                                           class="inline-flex items-center w-full justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-eye mr-2"></i>Lihat Detail
                                        </a>
                                    @elseif($ticket->status === 'pending')
                                        <div class="text-xs text-yellow-600">
                                            <i class="fas fa-clock mr-1"></i>Menunggu verifikasi pembayaran
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h4 class="font-medium text-blue-900 mb-2">Cara Menggunakan Ticket</h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Tunjukkan QR code saat check-in</li>
                            <li>• Atau tunjukkan ticket yang sudah didownload</li>
                            <li>• Ticket berlaku untuk 1 orang</li>
                            <li>• Simpan ticket dengan aman</li>
                        </ul>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-ticket-alt text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600">Belum ada ticket yang dibuat</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
