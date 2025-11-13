@component('mail::message')
# Booking Confirmation

Hello {{ $booking->user->name }},

Your booking for **{{ $booking->ticket->event->title }}** is confirmed.

### Booking Details:
- Ticket Type: {{ $booking->ticket->type }}
- Quantity: {{ $booking->quantity }}
- Total Price: ₦{{ number_format($booking->total_price) }}
- Status: {{ ucfirst($booking->status) }}

@component('mail::button', ['url' => '#'])
View Event
@endcomponent

Thank you for booking with us!

@endcomponent