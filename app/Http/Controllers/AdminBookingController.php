<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Ticket;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    /**
     * List semua booking
     */
    public function index()
    {
        $bookings = Booking::with(['user', 'event'])->latest()->paginate(15);
        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Detail satu booking
     */
    public function show(Booking $booking)
    {
        $booking->load(['user', 'event', 'tickets', 'payments']);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Setujui pembayaran
     */
    public function approvePayment(Booking $booking)
    {
        $booking->update([
            'payment_status' => 'paid',
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $payment = $booking->payments()->first();
        if ($payment) {
            $payment->update(['status' => 'success', 'paid_at' => now()]);
        }

        // Aktifkan semua tiket
        $booking->tickets()->update(['status' => 'active']);

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Pembayaran disetujui. Semua ticket telah diaktifkan.');
    }

    /**
     * Tolak pembayaran
     */
    public function rejectPayment(Booking $booking)
    {
        $booking->update(['payment_status' => 'failed', 'status' => 'cancelled']);

        $payment = $booking->payments()->first();
        if ($payment) {
            $payment->update(['status' => 'failed']);
        }

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Pembayaran ditolak. Booking dibatalkan.');
    }

    /**
     * Batalkan booking
     */
    public function cancelBooking(Booking $booking)
    {
        $booking->update(['status' => 'cancelled', 'payment_status' => 'failed']);
        $booking->tickets()->update(['status' => 'cancelled']);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Pemesanan berhasil dibatalkan.');
    }

    /**
     * Tandai satu ticket sebagai digunakan
     */
    public function markTicketUsed(Ticket $ticket)
    {
        $ticket->update(['status' => 'used', 'used_at' => now()]);

        return redirect()->back()->with('success', 'Ticket berhasil ditandai sebagai digunakan.');
    }
}
