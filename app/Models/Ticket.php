<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'event_id',
        'ticket_code',
        'customer_name',
        'customer_email',
        'customer_phone',
        'ticket_number',
        'qr_code',
        'status',
        'used_at'
    ];

    protected $casts = [
        'used_at' => 'datetime'
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function getQrCodeUrlAttribute()
    {
        if (!$this->qr_code) {
            return null;
        }

        // If QR code is a data URI (e.g., data:image/png;base64,...), return as-is
        if (str_starts_with($this->qr_code, 'data:image/')) {
            return $this->qr_code;
        }

        // If it's an absolute URL (http/https), return as-is
        if (preg_match('#^https?://#i', $this->qr_code)) {
            return $this->qr_code;
        }

        // Otherwise, treat it as a path stored in public storage
        return asset('storage/' . ltrim($this->qr_code, '/'));
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    public function getIsUsedAttribute()
    {
        return $this->status === 'used';
    }

    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    public function getFormattedTicketNumberAttribute()
    {
        return sprintf('TKT-%06d', $this->ticket_number);
    }
}
