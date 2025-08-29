@extends('layouts.app')

@section('title', 'Kelola Rekening Bank - Admin')

@section('content')
    <!-- Kelola Rekening Bank -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Kelola Rekening Bank</h1>
                <p class="text-gray-600">Kelola semua rekening bank yang tersedia untuk pembayaran</p>
            </div>
            <a href="{{ route('admin.bank-accounts.create') }}"
                class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>Tambah Rekening Bank
            </a>
        </div>

        <!-- Daftar Rekening Bank -->
        @if ($bankAccounts->count() > 0)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr class="align-middle">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap align-middle">Bank</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap align-middle">No. Rekening</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap align-middle">Atas Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap align-middle">Tipe</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap align-middle">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap align-middle">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($bankAccounts as $bankAccount)
                                <tr class="hover:bg-gray-50 align-middle">
                                    <td class="px-6 py-4 whitespace-nowrap align-middle">
                                        <div class="flex items-center">
                                            <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                                <i class="fas fa-university text-blue-600"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $bankAccount->bank_name }}</div>
                                                @if ($bankAccount->description)
                                                    <div class="text-sm text-gray-500">{{ Str::limit($bankAccount->description, 50) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 align-middle">{{ $bankAccount->account_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 align-middle">{{ $bankAccount->account_holder }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap align-middle">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bankAccount->account_type === 'savings' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">{{ ucfirst($bankAccount->account_type) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap align-middle">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bankAccount->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $bankAccount->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium align-middle">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('admin.bank-accounts.edit', $bankAccount) }}" class="text-blue-600 hover:text-blue-900" onclick="event.stopPropagation();">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.bank-accounts.toggle-status', $bankAccount) }}" method="POST" class="inline" onclick="event.stopPropagation();">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-900" onclick="confirmToggle(event)">
                                                    <i class="fas fa-toggle-on"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.bank-accounts.destroy', $bankAccount) }}" method="POST" class="inline" onclick="event.stopPropagation();">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="confirmDelete(event)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">{{ $bankAccounts->links() }}</div>
        @else
            <!-- Pesan jika belum ada rekening bank -->
            <div class="text-center py-16">
                <i class="fas fa-university text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum ada rekening bank</h3>
                <p class="text-gray-500 mb-6">Mulai dengan menambahkan rekening bank pertama untuk pembayaran</p>
                <a href="{{ route('admin.bank-accounts.create') }}" class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors"><i class="fas fa-plus mr-2"></i>Tambah Rekening Bank Pertama</a>
            </div>
        @endif
    </div>
@endsection

<!-- JavaScript untuk konfirmasi -->
@push('scripts')
    <script>
        function confirmToggle(e) {
            e.preventDefault();
            e.stopPropagation();
            const form = e.target.closest('form');
            Swal.fire({ icon: 'question', title: 'Ubah status?', showCancelButton: true, confirmButtonText: 'Ya' })
                .then(r => { if (r.isConfirmed) form.submit(); });
        }
        function confirmDelete(e) {
            e.preventDefault();
            e.stopPropagation();
            const form = e.target.closest('form');
            Swal.fire({ icon: 'warning', title: 'Hapus rekening?', text: 'Tindakan ini tidak bisa dibatalkan', showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Hapus' })
                .then(r => { if (r.isConfirmed) form.submit(); });
        }
    </script>
@endpush
