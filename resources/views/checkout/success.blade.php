@extends('layouts.app')

@section('title', 'Pembayaran Berhasil - EventTick')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Success Message -->
    <div class="text-center mb-8">
        <div class="mx-auto h-20 w-20 bg-green-100 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-check text-4xl text-green-600"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Pemesanan Berhasil Dibuat!</h1>
        <p class="text-lg text-gray-600">Silakan lakukan pembayaran sesuai instruksi di bawah ini</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Payment Instructions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Instruksi Pembayaran</h2>
            
            @if($booking->payments->first()->payment_method === 'bank_transfer')
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="font-medium text-blue-900 mb-3">Transfer Bank</h3>
                    
                    @if($booking->payments->first()->bankAccount)
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-blue-800">Bank:</span>
                                <span class="font-medium text-blue-900">{{ $booking->payments->first()->bankAccount->bank_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-800">No. Rekening:</span>
                                <span class="font-medium text-blue-900">{{ $booking->payments->first()->bankAccount->account_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-800">Atas Nama:</span>
                                <span class="font-medium text-blue-900">{{ $booking->payments->first()->bankAccount->account_holder }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-blue-800">Jumlah Transfer:</span>
                                <span class="font-bold text-blue-900">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <h4 class="font-medium text-yellow-900 mb-2">Langkah Pembayaran:</h4>
                    <ol class="text-sm text-yellow-800 space-y-1">
                        <li>1. Buka aplikasi mobile banking atau internet banking Anda</li>
                        <li>2. Pilih menu "Transfer" atau "Kirim Uang"</li>
                        <li>3. Masukkan nomor rekening tujuan</li>
                        <li>4. Masukkan jumlah transfer sesuai total pembayaran</li>
                        <li>5. Masukkan catatan/berita transfer (opsional)</li>
                        <li>6. Konfirmasi dan lakukan transfer</li>
                        <li>7. Simpan bukti transfer</li>
                    </ol>
                </div>
            @else
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <h3 class="font-medium text-green-900 mb-3">E-Wallet</h3>
                    <p class="text-green-800">Silakan lakukan pembayaran melalui aplikasi e-wallet yang Anda pilih. Setelah pembayaran berhasil, upload bukti pembayaran di bawah ini.</p>
                </div>
            @endif

            <!-- Booking Details -->
            <div class="border-t pt-4">
                <h3 class="font-medium text-gray-900 mb-3">Detail Pemesanan</h3>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span>Booking Code:</span>
                        <span class="font-medium">{{ $booking->booking_code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Payment Code:</span>
                        <span class="font-medium">{{ $booking->payments->first()->payment_code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Event:</span>
                        <span class="font-medium">{{ $booking->event->title }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Jumlah Ticket:</span>
                        <span class="font-medium">{{ $booking->quantity }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total Pembayaran:</span>
                        <span class="font-bold text-purple-600">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Payment Proof -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Upload Bukti Pembayaran</h2>
            
            @if($booking->payments->first()->payment_proof)
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        <span class="font-medium text-green-900">Bukti Pembayaran Sudah Diupload</span>
                    </div>
                    
                    <div class="mb-4">
                        <img src="{{ $booking->payments->first()->payment_proof_url }}" 
                             alt="Bukti Pembayaran" 
                             class="w-full h-48 object-cover rounded-lg border border-gray-200">
                    </div>
                    
                    <div class="text-sm text-green-700">
                        <p>Bukti pembayaran Anda telah diupload dan sedang diverifikasi oleh tim kami.</p>
                        <p class="mt-1">Status: <span class="font-medium">Menunggu Verifikasi</span></p>
                    </div>
                </div>
            @else
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        <span class="font-medium text-blue-900">Upload Bukti Pembayaran</span>
                    </div>
                    <p class="text-blue-800 text-sm">Setelah melakukan pembayaran, upload bukti pembayaran untuk verifikasi.</p>
                </div>
                
                <form action="{{ route('checkout.upload-proof', $booking) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">Bukti Pembayaran *</label>
                        <input type="file" name="payment_proof" id="payment_proof" accept="image/*" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, JPEG (Max: 2MB)</p>
                    </div>
                    
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors">
                        <i class="fas fa-upload mr-2"></i>Upload Bukti Pembayaran
                    </button>
                </form>
            @endif

            <!-- Next Steps -->
            <div class="border-t pt-4 mt-6">
                <h3 class="font-medium text-gray-900 mb-3">Langkah Selanjutnya</h3>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        <span>Upload bukti pembayaran</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock text-yellow-600 mr-2"></i>
                        <span>Tim kami verifikasi pembayaran (1-2 jam)</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-ticket-alt text-purple-600 mr-2"></i>
                        <span>Ticket aktif dan siap digunakan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="text-center mt-8">
        <a href="{{ route('bookings.show', $booking) }}" 
           class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-purple-700 bg-purple-100 hover:bg-purple-200 transition-colors mr-4">
            <i class="fas fa-eye mr-2"></i>Lihat Detail Booking
        </a>
        
        <a href="{{ route('events.index') }}" 
           class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 transition-colors">
            <i class="fas fa-calendar mr-2"></i>Lihat Event Lainnya
        </a>
    </div>
</div>
@endsection
