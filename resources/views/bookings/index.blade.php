@extends('layouts.app')

@section('title', 'My Bookings - EventTick')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Bookings</h1>
        <p class="text-gray-600">Kelola semua pemesanan ticket Anda</p>
    </div>

    @if($bookings->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($bookings as $booking)
                <a href="{{ route('bookings.show', $booking) }}" class="block">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden clickable-card no-select">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm font-medium text-gray-500">#{{ $booking->booking_code }}</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                           {{ $booking->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                              ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                            
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $booking->event->title }}</h3>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-map-marker-alt mr-2 text-purple-500"></i>
                                    {{ $booking->event->venue }}
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-calendar mr-2 text-purple-500"></i>
                                    {{ $booking->event->event_date->format('d M Y, H:i') }}
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-ticket-alt mr-2 text-purple-500"></i>
                                    {{ $booking->quantity }} ticket
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-money-bill mr-2 text-purple-500"></i>
                                    Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                                </div>
                            </div>
                            
                            <div class="border-t pt-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">
                                        {{ $booking->created_at->format('d M Y, H:i') }}
                                    </span>
                                    <span class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-purple-700 bg-purple-100 transition-colors">
                                        Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $bookings->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <div class="mx-auto h-24 w-24 flex items-center justify-center rounded-full bg-gray-100 mb-6">
                <i class="fas fa-ticket-alt text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum ada pemesanan</h3>
            <p class="text-gray-500 mb-6">Mulai dengan memesan ticket untuk event yang menarik</p>
            <a href="{{ route('events.index') }}" 
               class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                <i class="fas fa-search mr-2"></i>Lihat Events
            </a>
        </div>
    @endif
</div>
@endsection
