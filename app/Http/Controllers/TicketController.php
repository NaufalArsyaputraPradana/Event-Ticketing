<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    /**
     * Download tiket dalam format PDF.
     * Memastikan user pemilik tiket, generate QR jika belum ada,
     * lalu render view PDF dan unduh file.
     */
    public function download(Ticket $ticket)
    {
        // Authorization: pastikan tiket milik user yang sedang login
        if ($ticket->booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Ensure QR code exists
        if (!$ticket->qr_code) {
            $this->generateQrCode($ticket);
        }

        // Render PDF dan kirim sebagai download
        $pdf = $this->generateTicketPDF($ticket);
        return $pdf->download("ticket-{$ticket->ticket_code}.pdf");
    }

    /**
     * Tampilkan halaman detail tiket untuk user pemilik.
     */
    public function show(Ticket $ticket)
    {
        // Authorization: pastikan tiket milik user yang sedang login
        if ($ticket->booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Ensure QR code exists
        if (!$ticket->qr_code) {
            $this->generateQrCode($ticket);
        }

        return view('tickets.show', compact('ticket'));
    }

    /**
     * Generate QR code menggunakan external API (qrserver)
     * Menyimpan URL QR pada kolom qr_code tiket.
     */
    private function generateQrCode(Ticket $ticket)
    {
        try {
            $data = json_encode([
                'ticket_code' => $ticket->ticket_code,
                'event_id' => $ticket->event_id,
                'booking_id' => $ticket->booking_id,
                'customer_name' => $ticket->customer_name,
                'customer_email' => $ticket->customer_email,
                'ticket_number' => $ticket->ticket_number,
            ]);

            // External QR API (PNG 200x200)
            $externalQrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($data);
            $ticket->update(['qr_code' => $externalQrUrl]);
        } catch (\Exception $e) {
            // Fallback: encode hanya ticket_code
            $simpleQrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($ticket->ticket_code);
            $ticket->update(['qr_code' => $simpleQrUrl]);
        }
    }

    /**
     * Render view PDF tiket dengan DomPDF.
     */
    private function generateTicketPDF(Ticket $ticket)
    {
        // Render view
        $pdf = Pdf::loadView('tickets.pdf', compact('ticket'));
        // Ukuran custom: 200mm x 80mm (landscape kecil memanjang)
        $pdf->setPaper([0, 0, 566.93, 226.77], 'landscape'); // 200mm x 80mm dalam pt
        return $pdf;
    }
}
