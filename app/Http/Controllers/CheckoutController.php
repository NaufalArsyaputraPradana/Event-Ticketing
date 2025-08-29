<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Payment;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Halaman checkout dengan ringkasan biaya dan rekening bank aktif
     */
    public function show(Event $event, Request $request)
    {
        $quantity = (int) $request->query('quantity', 1);
        $totalAmount = $event->price * $quantity;
        $bankAccounts = BankAccount::where('is_active', true)->get();

        return view('checkout.show', compact('event', 'quantity', 'totalAmount', 'bankAccounts'));
    }

    /**
     * Proses pembuatan booking + payment + tickets
     */
    public function process(Request $request, Event $event)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $event->available_seats,
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'payment_method' => 'required|in:bank_transfer,ewallet',
            'bank_account_id' => 'required_if:payment_method,bank_transfer|exists:bank_accounts,id',
            'payment_details' => 'nullable|string|max:1000',
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
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
        ]);

        // Create payment record
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'payment_code' => 'PAY' . strtoupper(Str::random(8)),
            'amount' => $totalAmount,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'bank_account_id' => $request->bank_account_id,
            'payment_details' => $request->payment_details,
        ]);

        // Create tickets
        for ($i = 0; $i < $quantity; $i++) {
            Ticket::create([
                'booking_id' => $booking->id,
                'event_id' => $event->id,
                'ticket_code' => 'TK' . strtoupper(Str::random(8)),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'ticket_number' => $i + 1,
                'status' => 'pending', // aktif setelah verifikasi pembayaran
            ]);
        }

        return redirect()->route('checkout.success', $booking)
            ->with('success', 'Pemesanan berhasil dibuat! Silakan lakukan pembayaran.');
    }

    /**
     * Halaman sukses setelah checkout
     */
    public function success(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('checkout.success', compact('booking'));
    }

    /**
     * Upload bukti pembayaran dari halaman checkout
     */
    public function uploadProof(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate(['payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048']);

        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

            $payment = $booking->payments()->first();
            if ($payment) {
                $payment->update(['payment_proof' => $proofPath, 'status' => 'pending']);
            }

            $booking->update(['payment_status' => 'pending']);
        }

        return redirect()->route('checkout.success', $booking)
            ->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu verifikasi.');
    }
}
