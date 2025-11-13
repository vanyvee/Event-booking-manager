<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotifyOrganizer implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(BookingCreated $event)
    {
        $booking = $event->booking;

        //  (prevent null access)
        if (!$booking || !$booking->ticket || !$booking->ticket->event) {
            Log::warning('NotifyOrganizer: Missing related models for booking ID ' . ($booking->id ?? 'unknown'));
            return;
        }

        $ticket = $booking->ticket;
        $eventModel = $ticket->event;
        $organizer = $eventModel->organizer ?? null;

        if (!$organizer || !$organizer->email) {
            Log::warning("NotifyOrganizer: No organizer email found for event '{$eventModel->title}'");
            return;
        }

        // Compose mail content
        $subject = ' New Booking Alert';
        $body = <<<MAIL
Hello {$organizer->name},

A new booking was made for your event: "{$eventModel->title}".

Booking details:
- Ticket: {$ticket->name}
- Quantity: {$booking->quantity}
- Total: ₦{$booking->total_price}

Login to your dashboard for full details.

Thanks,  
Edukittas Event System
MAIL;

        try {
            Mail::raw($body, function ($message) use ($organizer, $subject) {
                $message->to($organizer->email)
                        ->subject($subject);
            });

            Log::info("NotifyOrganizer: Mail sent to {$organizer->email} for event '{$eventModel->title}'");

        } catch (\Throwable $e) {
            Log::error("NotifyOrganizer: Failed to send mail for booking ID {$booking->id}. Error: {$e->getMessage()}");
        }
    }
}