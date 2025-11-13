<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSuccessful extends Notification implements ShouldQueue
{
    use Queueable;

    public $booking;
    public $invoiceUrl;

    public function __construct($booking, $invoiceUrl)
    {
        $this->booking = $booking;
        $this->invoiceUrl = $invoiceUrl;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Payment Confirmation — ' . $this->booking->ticketType->event->name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your payment for the event has been successfully confirmed!')
            ->line('Event: ' . $this->booking->ticketType->event->name)
            ->line('Ticket Type: ' . $this->booking->ticketType->name)
            ->line('Amount Paid: ₦' . number_format($this->booking->total_price, 2))
            ->action('Download Invoice', $this->invoiceUrl)
            ->line('We look forward to seeing you at the event!');
    }
}