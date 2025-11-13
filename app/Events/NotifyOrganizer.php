<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NotifyOrganizer implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(BookingCreated $event)
    {
        $booking = $event->booking;
        $ticket = $booking->ticket;
        $eventModel = $ticket->event;
        $organizer = $eventModel->organizer; // Assuming relationship event → organizer

        $details = [
            'subject' => 'New Booking Alert',
            'body' => "Hello {$organizer->name}, a new booking was made for your event '{$eventModel->title}'.\n" .
                      "Quantity: {$booking->quantity}\nTotal: ₦{$booking->total_price}"
        ];

        Mail::raw($details['body'], function ($message) use ($organizer, $details) {
            $message->to($organizer->email)
                    ->subject($details['subject']);
        });
    }
}