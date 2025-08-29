@extends('layouts.app')

@section('title', 'Kelola Bookings - Admin')

@section('content')
    <!-- Kelola Bookings -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Kelola Bookings</h1>
            <p class="text-gray-600">Kelola semua pemesanan dan verifikasi pembayaran</p>
        </div>

        <!-- Daftar Bookings -->
        @if ($bookings->count() > 0)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Booking</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bukti Pembayaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($bookings as $booking)
                                @php($payment = $booking->payments->first())
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $booking->booking_code }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->created_at->format('d M Y, H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $booking->event->title }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->event->venue }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->quantity }}
                                        ticket</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp
                                        {{ number_format($booking->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="space-y-1">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $booking->status === 'paid' ? 'bg-green-100 text-green-800' : ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">{{ ucfirst($booking->status) }}</span>
                                            <div class="block">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : ($booking->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">{{ ucfirst($booking->payment_status) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($payment && $payment->payment_proof)
                                            <div class="flex items-center space-x-2">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"><i
                                                        class="fas fa-image mr-1"></i>Sudah Upload</span>
                                                <a href="{{ $payment->payment_proof_url }}" target="_blank"
                                                    class="text-blue-600 hover:text-blue-900 text-xs"
                                                    title="Lihat Bukti Pembayaran"><i class="fas fa-eye"></i></a>
                                            </div>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"><i
                                                    class="fas fa-times mr-1"></i>Belum Upload</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.bookings.show', $booking) }}"
                                                class="text-blue-600 hover:text-blue-900"><i class="fas fa-eye"></i></a>

                                            @if ($booking->payment_status === 'pending')
                                                <form action="{{ route('admin.bookings.approve-payment', $booking) }}"
                                                    method="POST" class="inline">@csrf @method('PATCH')
                                                    <button type="submit" class="text-green-600 hover:text-green-900"
                                                        onclick="confirmApprove(event)"><i
                                                            class="fas fa-check"></i></button>
                                                </form>
                                                <form action="{{ route('admin.bookings.reject-payment', $booking) }}"
                                                    method="POST" class="inline">@csrf @method('PATCH')
                                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                                        onclick="confirmReject(event)"><i class="fas fa-times"></i></button>
                                                </form>
                                            @endif

                                            @if ($booking->status !== 'cancelled')
                                                <form action="{{ route('admin.bookings.cancel', $booking) }}"
                                                    method="POST" class="inline">@csrf @method('PATCH')
                                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                                        onclick="confirmCancel(event)"><i class="fas fa-ban"></i></button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">{{ $bookings->links() }}</div>
        @else
            <!-- Pesan jika belum ada pemesanan -->
            <div class="text-center py-16">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum ada pemesanan</h3>
                <p class="text-gray-500">Pemesanan akan muncul di sini setelah user melakukan booking</p>
            </div>
        @endif
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

        function confirmCancel(e) {
            e.preventDefault();
            const f = e.target.closest('form');
            Swal.fire({
                icon: 'error',
                title: 'Batalkan pemesanan?',
                text: 'Tindakan ini tidak bisa dibatalkan',
                showCancelButton: true,
                confirmButtonText: 'Batalkan',
                confirmButtonColor: '#ef4444'
            }).then(r => {
                if (r.isConfirmed) f.submit();
            });
        }
    </script>
@endpush
