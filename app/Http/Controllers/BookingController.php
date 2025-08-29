<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Buat booking sederhana (legacy flow)
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $event->available_seats,
        ]);

        $quantity = (int) $request->quantity;
        $totalAmount = $event->price * $quantity;

        // Create booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'booking_code' => 'BK' . strtoupper(Str::random(8)),
            'quantity' => $quantity,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        // Create tickets
        for ($i = 0; $i < $quantity; $i++) {
            Ticket::create([
                'booking_id' => $booking->id,
                'event_id' => $event->id,
                'ticket_code' => 'TK' . strtoupper(Str::random(8)),
                'status' => 'active',
            ]);
        }

        // Create payment record
        Payment::create([
            'booking_id' => $booking->id,
            'payment_code' => 'PAY' . strtoupper(Str::random(8)),
            'amount' => $totalAmount,
            'status' => 'pending',
            'payment_method' => 'transfer',
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Pemesanan berhasil dibuat! Silakan lakukan pembayaran.');
    }

    /**
     * Detail satu booking milik user
     */
    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('bookings.show', compact('booking'));
    }

    /**
     * List booking milik user
     */
    public function index()
    {
        $bookings = Auth::user()->bookings()->with('event')->latest()->paginate(10);
        return view('bookings.index', compact('bookings'));
    }

    /**
     * Upload bukti pembayaran
     */
    public function confirmPayment(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

            $booking->update(['payment_status' => 'pending', 'payment_method' => 'transfer']);

            $payment = $booking->payments()->first();
            if ($payment) {
                $payment->update(['payment_proof' => $proofPath, 'status' => 'pending']);
            }
        }

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Bukti pembayaran berhasil diunggah! Tim kami akan memverifikasi.');
    }
}
