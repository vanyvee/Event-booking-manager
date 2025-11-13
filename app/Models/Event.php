<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $table = 'events';

    protected $fillable = [
        'title',
        'description',
        'location',
        'start_date',
        'end_date',
        'organizer_id',
        'total_seats',
        'available_seats',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
    ];

    protected $hidden = [
        'deleted_at'
    ];

    // Relationships
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Ticket::class);
    }
    
public function categories()
{
    return $this->belongsToMany(Category::class);
}
    // Query scopes
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function scopeActive($query)
    {
        return $query->where('start_date', '<=', now())
                     ->where('end_date', '>=', now());
    }
public function isDraft()
{
    return $this->status === 'draft';
}

public function isPublished()
{
    return $this->status === 'published';
}

public function isCancelled()
{
    return $this->status === 'cancelled';
}

public function isEnded()
{
    return $this->status === 'ended';
}
    // Accessors
    public function getIsSoldOutAttribute()
    {
        return $this->available_seats <= 0;
    }

    // Mutators
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucfirst($value);
    }

    // Events
    protected static function booted()
    {
        static::creating(function ($event) {
            // sync available seats on creation
            $event->available_seats = $event->total_seats;
        });
    }
}