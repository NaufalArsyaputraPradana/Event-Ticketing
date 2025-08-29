@extends('layouts.app')

@section('title', 'Admin - Scan QR Code Tiket')

@section('content')
    <!-- Scan Tiket -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Scan QR Code Tiket (Admin)</h1>

                <!-- Scan QR Code -->
                <div class="mb-8">
                    <div class="text-center mb-4">
                        <p class="text-gray-600 mb-4">Scan QR code tiket untuk melihat detail dan validasi</p>
                        <button id="startScan"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200">
                            <i class="fas fa-qrcode mr-2"></i> Mulai Scan
                        </button>
                    </div>

                    <div id="cameraContainer" class="hidden">
                        <div class="relative">
                            <div id="camera" class="w-full rounded-lg border-2 border-gray-300"></div>
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <div class="border-2 border-blue-500 w-64 h-64 rounded-lg"></div>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button id="stopScan"
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">Stop
                                Scan</button>
                        </div>
                    </div>

                    <!-- Manual Input -->
                    <div class="mt-6">
                        <div class="border-t pt-6">
                            <p class="text-center text-gray-600 mb-4">Atau masukkan kode tiket secara manual:</p>
                            <div class="flex gap-2">
                                <input type="text" id="ticketCode" placeholder="Masukkan kode tiket"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <button id="searchTicket"
                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">Cari</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hasil Scan -->
                <div id="resultContainer" class="hidden">
                    <div class="border-t pt-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Hasil Scan:</h2>
                        <div id="ticketResult" class="bg-gray-50 rounded-lg p-4"></div>
                    </div>
                </div>

                <!-- Loading -->
                <div id="loading" class="hidden text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <p class="mt-2 text-gray-600">Memproses...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- JavaScript untuk scan QR code -->
@push('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startScanBtn = document.getElementById('startScan');
            const stopScanBtn = document.getElementById('stopScan');
            const cameraContainer = document.getElementById('cameraContainer');
            const resultContainer = document.getElementById('resultContainer');
            const ticketResult = document.getElementById('ticketResult');
            const loading = document.getElementById('loading');
            const ticketCodeInput = document.getElementById('ticketCode');
            const searchTicketBtn = document.getElementById('searchTicket');

            let html5Qrcode = null;

            startScanBtn.addEventListener('click', async () => {
                await startScanning();
            });
            stopScanBtn.addEventListener('click', () => {
                stopScanning();
            });
            searchTicketBtn.addEventListener('click', () => {
                const code = ticketCodeInput.value.trim();
                if (code) searchTicket(code);
            });
            ticketCodeInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    const code = ticketCodeInput.value.trim();
                    if (code) searchTicket(code);
                }
            });

            // Start scanning
            async function startScanning() {
                if (!html5Qrcode) {
                    html5Qrcode = new Html5Qrcode('camera');
                }
                cameraContainer.classList.remove('hidden');
                startScanBtn.classList.add('hidden');
                try {
                    const devices = await Html5Qrcode.getCameras();
                    const cameraId = devices[0]?.id;
                    await html5Qrcode.start(cameraId, {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    }, (decodedText) => onScanSuccess(decodedText), () => {});
                } catch (err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal membuka kamera',
                        text: String(err)
                    });
                }
            }

            // Stop scanning
            function stopScanning() {
                if (html5Qrcode) {
                    html5Qrcode.stop().then(() => {
                        html5Qrcode.clear();
                    });
                }
                cameraContainer.classList.add('hidden');
                startScanBtn.classList.remove('hidden');
                resultContainer.classList.add('hidden');
            }

            // Success callback
            function onScanSuccess(decodedText) {
                try {
                    const data = JSON.parse(decodedText);
                    if (data.ticket_code) return searchTicket(data.ticket_code);
                    if (data.scan_url) {
                        const code = data.scan_url.split('/').pop();
                        return searchTicket(code);
                    }
                } catch {
                    return searchTicket(decodedText);
                }
            }

            // Search ticket
            function searchTicket(ticketCode) {
                showLoading();
                fetch(`/tickets/scan/${ticketCode}/api`)
                    .then(res => res.json())
                    .then(data => {
                        hideLoading();
                        if (data.success) displayTicketData(data.data);
                        else Swal.fire({
                            icon: 'error',
                            title: 'Tidak ditemukan',
                            text: data.message
                        });
                    })
                    .catch(() => {
                        hideLoading();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat memproses tiket'
                        });
                    });
            }

            // Display ticket data
            function displayTicketData(ticket) {
                const statusClass = ticket.is_valid ? 'text-green-600' : 'text-red-600';
                const statusText = ticket.is_valid ? 'Aktif' : 'Tidak Aktif';
                ticketResult.innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="font-semibold text-gray-700">Informasi Event</h3>
                            <p class="text-gray-900">${ticket.event_title}</p>
                            <p class="text-gray-600">${ticket.event_date}</p>
                            <p class="text-gray-600">${ticket.venue}</p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-700">Status Tiket</h3>
                            <p class="${statusClass} font-semibold">${statusText}</p>
                            <p class="text-gray-600">Kode: ${ticket.ticket_code}</p>
                            <p class="text-gray-600">No: ${ticket.ticket_number}</p>
                        </div>
                    </div>
                    <div class="border-t pt-4">
                        <h3 class="font-semibold text-gray-700 mb-2">Data Customer</h3>
                        <p class="text-gray-900">${ticket.customer_name}</p>
                        <p class="text-gray-600">${ticket.customer_email}</p>
                        <p class="text-gray-600">${ticket.customer_phone || '-'}</p>
                    </div>
                    <div class="border-t pt-4">
                        <h3 class="font-semibold text-gray-700 mb-2">Informasi Pemesanan</h3>
                        <p class="text-gray-600">Booking Code: ${ticket.booking_code}</p>
                        <p class="text-gray-600">Jumlah: ${ticket.quantity}</p>
                        <p class="text-gray-600">Total: Rp ${ticket.total_amount}</p>
                        <p class="text-gray-600">Payment: ${ticket.payment_status}</p>
                        <p class="text-gray-600">Method: ${ticket.payment_method || '-'}</p>
                        ${ticket.paid_at ? `<p class="text-gray-600">Paid: ${ticket.paid_at}</p>` : ''}
                        ${ticket.used_at ? `<p class="text-gray-600">Used: ${ticket.used_at}</p>` : ''}
                    </div>
                    ${ticket.is_valid ? `
                        <div class="border-t pt-4">
                            <button id="validateBtn" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">Validasi Tiket (Entry Event)</button>
                        </div>` : ''}
                </div>`;

                resultContainer.classList.remove('hidden');

                const validateBtn = document.getElementById('validateBtn');
                if (validateBtn) {
                    validateBtn.addEventListener('click', function() {
                        Swal.fire({
                            icon: 'question',
                            title: 'Validasi Tiket?',
                            text: 'Tiket akan ditandai sebagai sudah digunakan.',
                            showCancelButton: true,
                            confirmButtonText: 'Validasi',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#16a34a',
                        }).then((r) => {
                            if (r.isConfirmed) doValidate(ticket.ticket_code);
                        });
                    });
                }
            }

            // Validate ticket
            function doValidate(ticketCode) {
                fetch(`/tickets/validate/${ticketCode}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: data.message
                            });
                            searchTicket(ticketCode);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: data.message
                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat memvalidasi tiket'
                        });
                    });
            }

            function showLoading() {
                loading.classList.remove('hidden');
                resultContainer.classList.add('hidden');
            }

            function hideLoading() {
                loading.classList.add('hidden');
            }
        });
    </script>
@endpush
