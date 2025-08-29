@extends('layouts.app')

@section('title', 'Ticket Detail - EventTick')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                    <span class="text-gray-500">Ticket Detail</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="text-center mb-8">
        <div class="mx-auto h-20 w-20 bg-purple-100 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-ticket-alt text-4xl text-purple-600"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Ticket Detail</h1>
        <p class="text-lg text-gray-600">{{ $ticket->event->title }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Ticket</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Ticket Code:</span>
                    <span class="font-mono font-medium text-gray-900">{{ $ticket->ticket_code }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Ticket Number:</span>
                    <span class="font-medium text-gray-900">{{ $ticket->formatted_ticket_number }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Status:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                               {{ $ticket->status === 'active' ? 'bg-green-100 text-green-800' : 
                                  ($ticket->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                  ($ticket->status === 'used' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')) }}">
                        {{ ucfirst($ticket->status) }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Event:</span>
                    <span class="font-medium text-gray-900">{{ $ticket->event->title }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Venue:</span>
                    <span class="font-medium text-gray-900">{{ $ticket->event->venue }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Date:</span>
                    <span class="font-medium text-gray-900">{{ $ticket->event->event_date->format('d M Y, H:i') }}</span>
                </div>
                @if($ticket->used_at)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Used At:</span>
                        <span class="font-medium text-gray-900">{{ $ticket->used_at->format('d M Y, H:i') }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Customer</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Nama:</span>
                    <span class="font-medium text-gray-900">{{ $ticket->customer_name }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Email:</span>
                    <span class="font-medium text-gray-900">{{ $ticket->customer_email }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Phone:</span>
                    <span class="font-medium text-gray-900">{{ $ticket->customer_phone }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Booking Code:</span>
                    <span class="font-mono font-medium text-gray-900">{{ $ticket->booking->booking_code }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4 text-center">QR Code Ticket</h2>
        <div class="text-center">
            @if($ticket->qr_code)
                <div class="inline-block p-4 bg-white border-2 border-gray-200 rounded-lg">
                    <img src="{{ $ticket->qr_code_url }}" alt="QR Code" class="w-48 h-48">
                </div>
                <p class="text-sm text-gray-500 mt-2">Scan QR code ini untuk verifikasi ticket</p>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-qrcode text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600">QR Code sedang dibuat...</p>
                </div>
            @endif
        </div>
    </div>

    <div class="text-center mt-8">
        @if($ticket->status === 'active')
            <a href="{{ route('tickets.download', $ticket) }}" class="inline-flex items-center px-6 py-3 text-base font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 transition-colors mr-4">
                <i class="fas fa-download mr-2"></i>Download Ticket
            </a>
        @endif
        <a href="{{ route('bookings.show', $ticket->booking) }}" class="inline-flex items-center px-6 py-3 text-base font-medium rounded-md text-purple-700 bg-purple-100 hover:bg-purple-200 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Booking
        </a>
    </div>
</div>
@endsection
