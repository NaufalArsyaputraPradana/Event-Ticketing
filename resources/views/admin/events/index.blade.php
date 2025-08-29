@extends('layouts.app')

@section('title', 'Kelola Events - Admin')

@section('content')
    <!-- Kelola Events -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Kelola Events</h1>
                <p class="text-gray-600">Kelola semua event yang ada dalam sistem</p>
            </div>
            <a href="{{ route('admin.events.create') }}"
                class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>Buat Event Baru
            </a>
        </div>

        <!-- Daftar Events -->
        @if ($events->count() > 0)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kapasitas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($events as $event)
                                <tr class="clickable-row cursor-pointer no-select"
                                    onclick="window.location.href='{{ route('admin.events.edit', $event) }}'">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if ($event->image)
                                                <img class="h-12 w-12 rounded-lg object-cover mr-4"
                                                    src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}">
                                            @else
                                                <div
                                                    class="h-12 w-12 rounded-lg bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center mr-4">
                                                    <i class="fas fa-calendar-alt text-white"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $event->title }}</div>
                                                <div class="text-sm text-gray-500">{{ $event->venue }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $event->event_date->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $event->status === 'published' ? 'bg-green-100 text-green-800' : ($event->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">{{ ucfirst($event->status) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center">
                                            <span
                                                class="mr-2">{{ $event->available_seats }}/{{ $event->capacity }}</span>
                                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                                @php($percentage = $event->capacity > 0 ? (($event->capacity - $event->available_seats) / $event->capacity) * 100 : 0)
                                                <div class="bg-purple-600 h-2 rounded-full"
                                                    style="width: {{ $percentage }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp
                                        {{ number_format($event->price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.events.edit', $event) }}"
                                                class="text-blue-600 hover:text-blue-900" onclick="event.stopPropagation();"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.events.toggle-status', $event) }}" method="POST"
                                                class="inline" onclick="event.stopPropagation();">@csrf @method('PATCH')
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-900"
                                                    onclick="confirmToggleEvent(event)"><i
                                                        class="fas fa-toggle-on"></i></button>
                                            </form>
                                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                                                class="inline" onclick="event.stopPropagation();">@csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                    onclick="confirmDeleteEvent(event)"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">{{ $events->links() }}</div>
        @else
            <!-- Pesan jika belum ada event -->
            <div class="text-center py-16">
                <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum ada event</h3>
                <p class="text-gray-500 mb-6">Mulai dengan membuat event pertama Anda</p>
                <a href="{{ route('admin.events.create') }}"
                    class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Buat Event Pertama
                </a>
            </div>
        @endif
    </div>
@endsection

<!-- JavaScript untuk konfirmasi -->
@push('scripts')
    <script>
        function confirmToggleEvent(e) {
            e.preventDefault();
            e.stopPropagation();
            const form = e.target.closest('form');
            Swal.fire({
                icon: 'question',
                title: 'Ubah status event?',
                showCancelButton: true,
                confirmButtonText: 'Ya'
            }).then(r => {
                if (r.isConfirmed) form.submit();
            });
        }

        function confirmDeleteEvent(e) {
            e.preventDefault();
            e.stopPropagation();
            const form = e.target.closest('form');
            Swal.fire({
                icon: 'warning',
                title: 'Hapus event?',
                text: 'Tindakan ini tidak bisa dibatalkan',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Hapus'
            }).then(r => {
                if (r.isConfirmed) form.submit();
            });
        }
    </script>
@endpush
