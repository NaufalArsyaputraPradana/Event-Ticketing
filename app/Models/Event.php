<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'venue',
        'event_date',
        'capacity',
        'price',
        'status'
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'price' => 'decimal:2'
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function getAvailableSeatsAttribute()
    {
        $bookedSeats = $this->bookings()->where('status', 'paid')->sum('quantity');
        return $this->capacity - $bookedSeats;
    }

    public function getIsAvailableAttribute()
    {
        return $this->status === 'published' && $this->available_seats > 0;
    }
}
