<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'ticket_type_id',
        'name',
        'total_seats',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}