<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\DB;

class OrganizerService
{
    public static function revenueReport($organizerId)
    {
        $events = Event::where('organizer_id', $organizerId)->pluck('id');

        $gross = DB::table('bookings')
            ->whereIn('event_id', $events)
            ->where('payment_status', 'paid')
            ->sum('amount');

        $refunds = DB::table('refunds')
            ->whereIn('booking_id', function ($q) use ($events) {
                $q->select('id')->from('bookings')->whereIn('event_id', $events);
            })
            ->where('status', 'success')
            ->sum('amount');

        $tickets = DB::table('bookings')
            ->whereIn('event_id', $events)
            ->where('payment_status', 'paid')
            ->count();

        return [
            'total_events' => count($events),
            'tickets_sold' => $tickets,
            'gross_revenue' => $gross,
            'refunds' => $refunds,
            'net_revenue' => $gross - $refunds,
        ];
    }

    public static function revenueReportByDate($organizerId, $start, $end)
    {
        $events = Event::where('organizer_id', $organizerId)->pluck('id');

        $gross = DB::table('bookings')
            ->whereIn('event_id', $events)
            ->whereBetween('created_at', [$start, $end])
            ->where('payment_status', 'paid')
            ->sum('amount');

        $refunds = DB::table('refunds')
            ->whereBetween('created_at', [$start, $end])
            ->where('status', 'success')
            ->sum('amount');

        return [
            'period' => [$start, $end],
            'gross_revenue' => $gross,
            'refunds' => $refunds,
            'net_revenue' => $gross - $refunds,
        ];
    }
}