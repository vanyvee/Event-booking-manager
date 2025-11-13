<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Ticket;
use App\Events\TicketBooked;
use App\Events\TicketCancelled;
use Exception;

class BookingService
{
    public function bookTicket($userId, $ticketId, $quantity)
    {
        $ticket = Ticket::findOrFail($ticketId);

        if ($ticket->quantity < $quantity) {
            throw new Exception('Not enough tickets available.');
        }

        $total = $ticket->price * $quantity;

        $booking = Booking::create([
            'user_id' => $userId,
            'ticket_id' => $ticket->id,
            'quantity' => $quantity,
            'total_price' => $total,
            'status' => 'booked',
        ]);

        event(new TicketBooked($booking));

        return $booking->load('ticket.event');
    }

    public function cancelBooking($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->status === 'cancelled') {
            throw new Exception('Booking already cancelled.');
        }

        $booking->update(['status' => 'cancelled']);

        event(new TicketCancelled($booking));

        return $booking;
    }

    public function getUserBookings($user)
    {
        return $user->bookings()->with('ticket.event')->latest()->get();
    }
}