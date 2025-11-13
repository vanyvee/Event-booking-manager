<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'price',
        'available_from',
        'available_until',
        'max_per_user',
        'total_quantity',
        'sold_quantity',
        'is_seated'
    ];

    protected $casts = [
        'is_seated' => 'boolean',
        'available_from' => 'datetime',
        'available_until' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function seatZones()
    {
        return $this->hasMany(SeatZone::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}