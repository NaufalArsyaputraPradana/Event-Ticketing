@extends('layouts.app')

@section('title', 'Detail Booking - Admin')

@section('content')
    <!-- Detail Booking -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Detail Booking #{{ $booking->booking_code }}</h1>
                    <p class="text-gray-600">Kelola pemesanan dan verifikasi pembayaran</p>
                </div>
                <a href="{{ route('admin.bookings.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Informasi Pemesanan -->
        @php($firstPayment = $booking->payments->first())
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pemesanan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Event Details</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between"><span class="text-gray-600">Judul Event:</span><span
                                        class="font-medium">{{ $booking->event->title }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">Lokasi:</span><span
                                        class="font-medium">{{ $booking->event->venue }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">Tanggal:</span><span
                                        class="font-medium">{{ $booking->event->event_date->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Customer Details</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between"><span class="text-gray-600">Nama:</span><span
                                        class="font-medium">{{ $booking->user->name }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">Email:</span><span
                                        class="font-medium">{{ $booking->user->email }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-600">Tanggal Booking:</span><span
                                        class="font-medium">{{ $booking->created_at->format('d M Y, H:i') }}</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="border-t pt-4 mt-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <div class="text-2xl font-bold text-gray-900">{{ $booking->quantity }}</div>
                                <div class="text-sm text-gray-600">Jumlah Ticket</div>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <div class="text-2xl font-bold text-purple-600">Rp
                                    {{ number_format($booking->total_amount, 0, ',', '.') }}</div>
                                <div class="text-sm text-gray-600">Total Pembayaran</div>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg"><span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $booking->status === 'paid' ? 'bg-green-100 text-green-800' : ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">{{ ucfirst($booking->status) }}</span>
                                <div class="text-sm text-gray-600 mt-1">Status</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verifikasi Pembayaran -->
                @if ($booking->payment_status === 'pending')
                    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Verifikasi Pembayaran</h3>
                        <div class="flex space-x-4">
                            <form action="{{ route('admin.bookings.approve-payment', $booking) }}" method="POST"
                                class="inline">@csrf @method('PATCH')
                                <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors"
                                    onclick="confirmApprove(event)"><i class="fas fa-check mr-2"></i>Setujui
                                    Pembayaran</button>
                            </form>
                            <form action="{{ route('admin.bookings.reject-payment', $booking) }}" method="POST"
                                class="inline">@csrf @method('PATCH')
                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors"
                                    onclick="confirmReject(event)"><i class="fas fa-times mr-2"></i>Tolak
                                    Pembayaran</button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Bukti Pembayaran -->
                @if ($firstPayment && $firstPayment->payment_proof)
                    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Bukti Pembayaran</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0"><img src="{{ $firstPayment->payment_proof_url }}"
                                        alt="Bukti Pembayaran"
                                        class="w-48 h-48 object-cover rounded-lg border border-gray-200 shadow-sm"></div>
                                <div class="flex-1">
                                    <div class="mb-4">
                                        <h4 class="font-medium text-gray-900 mb-2">Detail Bukti Pembayaran</h4>
                                        <div class="space-y-2 text-sm text-gray-600">
                                            <div class="flex justify-between"><span>Payment Code:</span><span
                                                    class="font-medium">{{ $firstPayment->payment_code }}</span></div>
                                            <div class="flex justify-between"><span>Metode Pembayaran:</span><span
                                                    class="font-medium">{{ ucfirst($firstPayment->payment_method) }}</span>
                                            </div>
                                            <div class="flex justify-between"><span>Jumlah:</span><span
                                                    class="font-medium">Rp
                                                    {{ number_format($firstPayment->amount, 0, ',', '.') }}</span></div>
                                            <div class="flex justify-between"><span>Status:</span><span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $firstPayment->status === 'success' ? 'bg-green-100 text-green-800' : ($firstPayment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">{{ ucfirst($firstPayment->status) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex space-x-3">
                                        <a href="{{ $firstPayment->payment_proof_url }}" target="_blank"
                                            class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium"><i
                                                class="fas fa-external-link-alt mr-1"></i>Lihat Gambar Lengkap</a>
                                        <a href="{{ $firstPayment->payment_proof_url }}"
                                            download="payment_proof_{{ $booking->booking_code }}.jpg"
                                            class="inline-flex items-center text-sm text-green-600 hover:text-green-800 font-medium"><i
                                                class="fas fa-download mr-1"></i>Download Gambar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Riwayat Pembayaran -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Pembayaran</h3>
                    @if ($booking->payments->count() > 0)
                        @foreach ($booking->payments as $payment)
                            <div class="border border-gray-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $payment->payment_code }}</div>
                                        <div class="text-sm text-gray-600">{{ $payment->payment_method }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-bold text-gray-900">Rp
                                            {{ number_format($payment->amount, 0, ',', '.') }}</div>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment->status === 'success' ? 'bg-green-100 text-green-800' : ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">{{ ucfirst($payment->status) }}</span>
                                    </div>
                                </div>
                                @if ($payment->paid_at)
                                    <div class="mt-2 text-sm text-gray-500">Dibayar pada:
                                        {{ $payment->paid_at->format('d M Y, H:i') }}</div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-center py-4">Belum ada riwayat pembayaran</p>
                    @endif
                </div>
            </div>

            <!-- Tickets -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tickets ({{ $booking->tickets->count() }})</h3>
                    @if ($booking->tickets->count() > 0)
                        <div class="space-y-4">
                            @foreach ($booking->tickets as $ticket)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="text-center mb-3">
                                        <div class="text-sm font-medium text-gray-900 mb-2">#{{ $ticket->ticket_code }}
                                        </div>
                                        <div class="inline-block"><img src="{{ $ticket->qr_code_url }}" alt="QR Code"
                                                class="w-24 h-24 mx-auto"></div>
                                    </div>
                                    <div class="text-center mb-3"><span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ticket->status === 'active' ? 'bg-green-100 text-green-800' : ($ticket->status === 'used' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">{{ ucfirst($ticket->status) }}</span>
                                    </div>
                                    @if ($ticket->status === 'active')
                                        <form action="{{ route('admin.tickets.mark-used', $ticket) }}" method="POST"
                                            class="text-center">@csrf @method('PATCH')
                                            <button type="submit"
                                                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors"
                                                onclick="confirmMarkUsed(event)"><i class="fas fa-check mr-1"></i>Mark as
                                                Used</button>
                                        </form>
                                    @endif
                                    @if ($ticket->status === 'used' && $ticket->used_at)
                                        <div class="text-center mt-2 text-xs text-gray-500">Digunakan pada:
                                            {{ $ticket->used_at->format('d M Y, H:i') }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Belum ada ticket</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- JavaScript untuk konfirmasi -->
@push('scripts')
    <script>
        function confirmApprove(e) {
            e.preventDefault();
            const f = e.target.closest('form');
            Swal.fire({
                icon: 'question',
                title: 'Setujui pembayaran?',
                showCancelButton: true,
                confirmButtonText: 'Setujui',
                confirmButtonColor: '#16a34a'
            }).then(r => {
                if (r.isConfirmed) f.submit();
            });
        }

        function confirmReject(e) {
            e.preventDefault();
            const f = e.target.closest('form');
            Swal.fire({
                icon: 'warning',
                title: 'Tolak pembayaran?',
                showCancelButton: true,
                confirmButtonText: 'Tolak',
                confirmButtonColor: '#f59e0b'
            }).then(r => {
                if (r.isConfirmed) f.submit();
            });
        }

        function confirmMarkUsed(e) {
            e.preventDefault();
            const f = e.target.closest('form');
            Swal.fire({
                icon: 'question',
                title: 'Tandai tiket ini sudah digunakan?',
                showCancelButton: true,
                confirmButtonText: 'Tandai',
                confirmButtonColor: '#3b82f6'
            }).then(r => {
                if (r.isConfirmed) f.submit();
            });
        }
    </script>
@endpush
