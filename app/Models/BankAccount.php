<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'account_number',
        'account_holder',
        'account_type',
        'is_active',
        'description'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getFullAccountInfoAttribute()
    {
        return "{$this->bank_name} - {$this->account_number} ({$this->account_holder})";
    }
}
