@extends('layouts.app')

@section('title', 'Tambah Rekening Bank - Admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tambah Rekening Bank</h1>
                <p class="text-gray-600">Tambahkan rekening bank baru untuk pembayaran</p>
            </div>
            <a href="{{ route('admin.bank-accounts.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <form action="{{ route('admin.bank-accounts.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Bank *</label>
                    <input type="text" name="bank_name" id="bank_name" required
                           value="{{ old('bank_name') }}"
                           placeholder="Contoh: Bank Central Asia (BCA)"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('bank_name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor Rekening *</label>
                    <input type="text" name="account_number" id="account_number" required
                           value="{{ old('account_number') }}"
                           placeholder="Contoh: 1234567890"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('account_number')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="account_holder" class="block text-sm font-medium text-gray-700 mb-2">Atas Nama *</label>
                    <input type="text" name="account_holder" id="account_holder" required
                           value="{{ old('account_holder') }}"
                           placeholder="Contoh: John Doe"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('account_holder')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="account_type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Rekening *</label>
                    <select name="account_type" id="account_type" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Pilih tipe rekening</option>
                        <option value="savings" {{ old('account_type') === 'savings' ? 'selected' : '' }}>Tabungan</option>
                        <option value="current" {{ old('account_type') === 'current' ? 'selected' : '' }}>Giro</option>
                    </select>
                    @error('account_type')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi (Opsional)</label>
                <textarea name="description" id="description" rows="3"
                          placeholder="Tambahkan deskripsi atau catatan tentang rekening ini..."
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mt-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked
                           class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                    <span class="ml-2 text-sm text-gray-700">Rekening aktif dan tersedia untuk pembayaran</span>
                </label>
            </div>
            
            <div class="flex justify-end mt-8">
                <button type="submit" 
                        class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Rekening Bank
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
