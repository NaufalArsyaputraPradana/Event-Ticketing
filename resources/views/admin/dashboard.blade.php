@extends('layouts.app')

@section('title', 'Admin Dashboard - EventTick')

@section('content')
    <!-- Dashboard Admin -->
    @php(
    $stats = [
        'events' => \App\Models\Event::count(),
        'bookings' => \App\Models\Booking::count(),
        'pendingPayments' => \App\Models\Booking::where('payment_status', 'pending')->count(),
        'users' => \App\Models\User::count(),
    ])

    <!-- Dashboard Admin -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="text-gray-600">Kelola event, pemesanan, dan pembayaran</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-calendar text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Events</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['events'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-ticket-alt text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Bookings</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['bookings'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Pending Payments</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['pendingPayments'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-users text-purple-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['users'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions (Consistent clickable cards) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="{{ route('admin.events.create') }}" class="block">
                <div class="bg-white rounded-xl shadow-lg p-6 clickable-card no-select">
                    <div class="text-center">
                        <div class="bg-purple-100 p-4 rounded-full w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                            <i class="fas fa-plus text-purple-600 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Buat Event Baru</h3>
                        <p class="text-gray-600 mb-4">Tambahkan event baru ke dalam sistem</p>
                        <span
                            class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">Buat
                            Event</span>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.events.index') }}" class="block">
                <div class="bg-white rounded-xl shadow-lg p-6 clickable-card no-select">
                    <div class="text-center">
                        <div class="bg-blue-100 p-4 rounded-full w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                            <i class="fas fa-list text-blue-600 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Kelola Events</h3>
                        <p class="text-gray-600 mb-4">Lihat dan edit semua event yang ada</p>
                        <span
                            class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">Kelola
                            Events</span>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.bookings.index') }}" class="block">
                <div class="bg-white rounded-xl shadow-lg p-6 clickable-card no-select">
                    <div class="text-center">
                        <div class="bg-green-100 p-4 rounded-full w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                            <i class="fas fa-credit-card text-green-600 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Kelola Bookings</h3>
                        <p class="text-gray-600 mb-4">Verifikasi pembayaran dan kelola pemesanan</p>
                        <span
                            class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">Kelola
                            Bookings</span>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.bank-accounts.index') }}" class="block">
                <div class="bg-white rounded-xl shadow-lg p-6 clickable-card no-select">
                    <div class="text-center">
                        <div class="bg-indigo-100 p-4 rounded-full w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                            <i class="fas fa-university text-indigo-600 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Kelola Rekening Bank</h3>
                        <p class="text-gray-600 mb-4">Kelola rekening bank untuk pembayaran</p>
                        <span
                            class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">Kelola
                            Rekening</span>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.tickets.scan') }}" class="block">
                <div class="bg-white rounded-xl shadow-lg p-6 clickable-card no-select">
                    <div class="text-center">
                        <div class="bg-orange-100 p-4 rounded-full w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                            <i class="fas fa-qrcode text-orange-600 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Scan QR Code Tiket</h3>
                        <p class="text-gray-600 mb-4">Scan dan validasi tiket untuk entry event</p>
                        <span
                            class="inline-block bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">Scan
                            Tiket</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Recent Activities -->
        <div class="mt-12">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Aktivitas Terbaru</h3>
                <div class="space-y-4">
                    @php($recentBookings = \App\Models\Booking::with(['user', 'event'])->latest()->take(5)->get())
                    @forelse($recentBookings as $booking)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="bg-purple-100 p-2 rounded-lg mr-3"><i
                                        class="fas fa-ticket-alt text-purple-600"></i></div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $booking->user->name }}</p>
                                    <p class="text-sm text-gray-600">Memesan {{ $booking->quantity }} ticket untuk
                                        {{ $booking->event->title }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' : ($booking->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">{{ ucfirst($booking->payment_status) }}</span>
                                <p class="text-xs text-gray-500 mt-1">{{ $booking->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-600">Belum ada aktivitas</p>
                        </div>
                    @endforelse
                </div>
                @if ($recentBookings->count() > 0)
                    <div class="mt-6 text-center">
                        <a href="{{ route('admin.bookings.index') }}"
                            class="text-purple-600 hover:text-purple-700 font-medium">Lihat Semua Aktivitas <i
                                class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
