<?php

namespace App\Services;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public static function generateInvoice(Booking $booking)
    {
        $payment = $booking->payment;

        $data = [
            'booking' => $booking,
            'user' => $booking->user,
            'ticketType' => $booking->ticketType,
            'event' => $booking->ticketType->event,
            'payment' => $payment,
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data);

        $fileName = 'invoice_' . $booking->id . '.pdf';
        $filePath = 'invoices/' . $fileName;

        Storage::disk('public')->put($filePath, $pdf->output());

        return Storage::disk('public')->url($filePath);
    }
}