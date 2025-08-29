@extends('layouts.app')

@section('title', 'Events - EventTick')

@section('content')
<!-- Hero Section -->
<div class="gradient-bg text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-6xl font-bold mb-6">Temukan Event Menarik</h1>
        <p class="text-xl md:text-2xl mb-8 text-purple-100">Jangan lewatkan acara seru yang sedang berlangsung di sekitar Anda</p>
        
        <!-- Search Bar -->
        <div class="max-w-2xl mx-auto">
            <form action="{{ route('events.search') }}" method="GET" class="flex">
                <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Cari event, lokasi, atau kategori..." 
                       class="flex-1 px-6 py-4 text-gray-900 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 px-8 py-4 rounded-r-lg transition-colors">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Events Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    @if(isset($query))
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Hasil Pencarian: "{{ $query }}"</h2>
            <a href="{{ route('events.index') }}" class="text-purple-600 hover:text-purple-700">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke semua event
            </a>
        </div>
    @else
        <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Event Terbaru</h2>
    @endif

    @if($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($events as $event)
                <a href="{{ route('events.show', $event) }}" class="block">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden clickable-card no-select">
                        @if($event->image)
                            <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" 
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-6xl text-white opacity-50"></i>
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                           {{ $event->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    <i class="fas fa-users mr-1"></i>{{ $event->available_seats }} tersisa
                                </span>
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $event->title }}</h3>
                            <p class="text-gray-600 mb-4 line-clamp-2">{{ strlen($event->description) > 100 ? substr($event->description,0,100).'...' : $event->description }}</p>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-map-marker-alt mr-2 text-purple-500"></i>
                                    {{ $event->venue }}
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-calendar mr-2 text-purple-500"></i>
                                    {{ $event->event_date->format('d M Y, H:i') }}
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-ticket-alt mr-2 text-purple-500"></i>
                                    Rp {{ number_format($event->price, 0, ',', '.') }}
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-purple-600 font-medium transition-colors">
                                    Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                                </span>
                                
                                @if($event->is_available)
                                    <span class="text-green-600 text-sm font-medium">
                                        <i class="fas fa-check-circle mr-1"></i>Tersedia
                                    </span>
                                @else
                                    <span class="text-red-600 text-sm font-medium">
                                        <i class="fas fa-times-circle mr-1"></i>Habis
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-12">
            {{ $events->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada event ditemukan</h3>
            <p class="text-gray-500">Coba ubah kata kunci pencarian atau lihat event lainnya.</p>
        </div>
    @endif
</div>
@endsection
