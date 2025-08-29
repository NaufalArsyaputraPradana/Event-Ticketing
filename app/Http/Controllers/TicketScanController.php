<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TicketScanController extends Controller
{
    /**
     * Tampilkan halaman scan QR code.
     */
    public function showScanPage(): View
    {
        return view('tickets.scan');
    }

    /**
     * Scan tiket dan tampilkan detail.
     */
    public function scanTicket(string $ticketCode): View
    {
        $ticket = Ticket::where('ticket_code', $ticketCode)
            ->with(['event', 'booking'])
            ->first();

        if (!$ticket) {
            abort(404, 'Tiket tidak ditemukan');
        }

        return view('tickets.scan-result', compact('ticket'));
    }

    /**
     * API untuk scan tiket.
     */
    public function scanTicketApi(string $ticketCode): JsonResponse
    {
        $ticket = Ticket::where('ticket_code', $ticketCode)
            ->with(['event', 'booking'])
            ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan',
            ], 404);
        }

        $ticketData = [
            'ticket_id' => $ticket->id,
            'ticket_code' => $ticket->ticket_code,
            'ticket_number' => $ticket->formatted_ticket_number,
            'event_title' => $ticket->event->title,
            'event_date' => $ticket->event->event_date->format('Y-m-d H:i'),
            'venue' => $ticket->event->venue,
            'customer_name' => $ticket->customer_name,
            'customer_email' => $ticket->customer_email,
            'customer_phone' => $ticket->customer_phone,
            'status' => $ticket->status,
            'booking_code' => $ticket->booking->booking_code,
            'quantity' => $ticket->booking->quantity,
            'total_amount' => $ticket->booking->total_amount,
            'payment_status' => $ticket->booking->payment_status,
            'payment_method' => $ticket->booking->payment_method,
            'paid_at' => $ticket->booking->paid_at?->format('Y-m-d H:i'),
            'used_at' => $ticket->used_at?->format('Y-m-d H:i'),
            'is_valid' => $ticket->status === 'active',
            'is_used' => $ticket->status === 'used',
        ];

        return response()->json([
            'success' => true,
            'data' => $ticketData,
        ]);
    }

    /**
     * Validasi tiket.
     */
    public function validateTicket(string $ticketCode): JsonResponse
    {
        $ticket = Ticket::where('ticket_code', $ticketCode)->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan',
            ], 404);
        }

        if ($ticket->status === 'used') {
            return response()->json([
                'success' => false,
                'message' => 'Tiket sudah digunakan',
            ], 400);
        }

        if ($ticket->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak aktif',
            ], 400);
        }

        $ticket->update([
            'status' => 'used',
            'used_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil divalidasi',
            'data' => [
                'ticket_code' => $ticket->ticket_code,
                'customer_name' => $ticket->customer_name,
                'event_title' => $ticket->event->title,
                'validated_at' => now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
