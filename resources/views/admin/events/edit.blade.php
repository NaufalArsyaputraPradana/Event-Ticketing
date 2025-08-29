@extends('layouts.app')

@section('title', 'Edit Event - Admin')

@section('content')
    <!-- Edit Event -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Edit Event</h1>
            <p class="text-gray-600">Edit informasi event yang sudah ada</p>
        </div>

        <!-- Form Edit Event -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Event <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Masukkan judul event" value="{{ old('title', $event->title) }}">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Event <span
                                class="text-red-500">*</span></label>
                        <textarea name="description" id="description" rows="4" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Masukkan deskripsi lengkap event">{{ old('description', $event->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="venue" class="block text-sm font-medium text-gray-700 mb-2">Lokasi <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="venue" id="venue" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Masukkan lokasi event" value="{{ old('venue', $event->venue) }}">
                        @error('venue')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal & Waktu Event
                            <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="event_date" id="event_date" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}">
                        @error('event_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">Kapasitas <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="capacity" id="capacity" required min="1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Masukkan kapasitas maksimal" value="{{ old('capacity', $event->capacity) }}">
                        @error('capacity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Harga per Ticket <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                            <input type="number" name="price" id="price" required min="0" step="1000"
                                class="w-full pl-12 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                placeholder="0" value="{{ old('price', $event->price) }}">
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Gambar Event</label>
                        @if ($event->image)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">Gambar saat ini:</p>
                                <img src="{{ Storage::url($event->image) }}" alt="Current image"
                                    class="w-32 h-32 object-cover rounded-lg border">
                            </div>
                        @endif
                        <input type="file" name="image" id="image" accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB. Biarkan kosong jika tidak
                            ingin mengubah gambar.</p>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t">
                    <a href="{{ route('admin.events.index') }}"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">Batal</a>
                    <button type="submit"
                        class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors"><i
                            class="fas fa-save mr-2"></i>Update Event</button>
                </div>
            </form>
        </div>
    </div>
@endsection
