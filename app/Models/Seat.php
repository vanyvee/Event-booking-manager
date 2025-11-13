<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'seat_zone_id',
        'seat_number',
        'is_booked',
        'booked_by',
        'booking_id'
    ];

    protected $casts = [
        'is_booked' => 'boolean',
    ];

    public function zone()
    {
        return $this->belongsTo(SeatZone::class, 'seat_zone_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'booked_by');
    }
}