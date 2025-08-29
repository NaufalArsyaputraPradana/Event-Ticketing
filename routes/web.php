<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminBankAccountController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketScanController;

// =====================
// Public routes
// =====================
Route::get('/', [PublicEventController::class, 'index'])->name('home');
Route::get('/events', [PublicEventController::class, 'index'])->name('events.index');
Route::get('/events/search', [PublicEventController::class, 'search'])->name('events.search');
Route::get('/events/{event}', [PublicEventController::class, 'show'])->name('events.show');

// =====================
// Authenticated user routes
// =====================
Route::middleware(['auth'])->group(function () {
    // Checkout
    Route::get('/checkout/{event}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/{event}/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{booking}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/checkout/{booking}/upload-proof', [CheckoutController::class, 'uploadProof'])->name('checkout.upload-proof');

    // Bookings (user)
    Route::post('/events/{event}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/confirm-payment', [BookingController::class, 'confirmPayment'])->name('bookings.confirm-payment');

    // Tickets (user)
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{ticket}/download', [TicketController::class, 'download'])->name('tickets.download');

    // Ticket scanning APIs (digunakan oleh halaman scan admin)
    Route::get('/tickets/scan/{ticketCode}', [TicketScanController::class, 'scanTicket'])->name('tickets.scan-result');
    Route::get('/tickets/scan/{ticketCode}/api', [TicketScanController::class, 'scanTicketApi'])->name('tickets.scan-api');
    Route::post('/tickets/validate/{ticketCode}', [TicketScanController::class, 'validateTicket'])->name('tickets.validate');
});

// =====================
// Admin routes
// =====================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Event management
    Route::resource('events', EventController::class);
    Route::patch('/events/{event}/toggle-status', [EventController::class, 'toggleStatus'])->name('events.toggle-status');

    // Booking & ticket management
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/approve-payment', [AdminBookingController::class, 'approvePayment'])->name('bookings.approve-payment');
    Route::patch('/bookings/{booking}/reject-payment', [AdminBookingController::class, 'rejectPayment'])->name('bookings.reject-payment');
    Route::patch('/bookings/{booking}/cancel', [AdminBookingController::class, 'cancelBooking'])->name('bookings.cancel');
    Route::patch('/tickets/{ticket}/mark-used', [AdminBookingController::class, 'markTicketUsed'])->name('tickets.mark-used');

    // Admin ticket scan page
    Route::get('/tickets/scan', function () {
        return view('admin.tickets.scan');
    })->name('tickets.scan');

    // Bank account management
    Route::resource('bank-accounts', AdminBankAccountController::class);
    Route::patch('/bank-accounts/{bankAccount}/toggle-status', [AdminBankAccountController::class, 'toggleStatus'])->name('bank-accounts.toggle-status');
});

require __DIR__.'/auth.php';
