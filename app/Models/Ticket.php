<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    protected $table = 'tickets';

    protected $fillable = [
        'event_id',
        'type',
        'price',
        'quantity',
        'available_quantity',
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    protected $hidden = ['deleted_at'];

    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Query Scopes
    public function scopeAvailable($query)
    {
        return $query->where('available_quantity', '>', 0);
    }

    // Accessors
    public function getIsAvailableAttribute()
    {
        return $this->available_quantity > 0;
    }

    // Mutators
    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = ucwords(strtolower($value));
    }

    // Events
    protected static function booted()
    {
        static::creating(function ($ticket) {
            $ticket->available_quantity = $ticket->quantity;
        });
    }
}