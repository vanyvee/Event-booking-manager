<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    protected $table = 'bookings';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'quantity',
        'total_price',
        'status'
    ];

    protected $casts = [
        'total_price' => 'integer',
    ];

    protected $hidden = ['deleted_at'];

    // Relationships
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'booked');
    }

    // Accessors
    public function getFormattedStatusAttribute()
    {
        return strtoupper($this->status);
    }

    // Events
    protected static function booted()
    {
        static::creating(function ($booking) {
            // calculate total price automatically
            if (!$booking->total_price) {
                $price = $booking->ticket->price;
                $booking->total_price = $booking->quantity * $price;
            }
        });
    }
}