<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Exception;

class PaymentService
{
    public static function initializePayment(Booking $booking)
    {
        $reference = 'OPAY_' . uniqid();

        $payload = [
            'reference' => $reference,
            'amount' => $booking->total_price * 100, // convert to kobo
            'currency' => 'NGN',
            'returnUrl' => url('/api/payments/opay/verify/' . $reference),
            'callbackUrl' => config('app.url') . '/api/payments/opay/webhook',
            'userPhone' => $booking->user->phone ?? '',
            'userEmail' => $booking->user->email ?? '',
            'userName' => $booking->user->name ?? '',
            'description' => 'Ticket purchase for ' . $booking->ticketType->name,
        ];

        // store locally first
        Payment::create([
            'booking_id' => $booking->id,
            'reference' => $reference,
            'amount' => $booking->total_price,
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPAY_SECRET_KEY'),
            'Content-Type' => 'application/json',
        ])->post(env('OPAY_BASE_URL') . 'payments/initialize', $payload);

        if (!$response->successful()) {
            throw new Exception('Opay initialization failed');
        }

        return $response->json();
    }

  public static function verifyPayment($reference)
{
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('OPAY_SECRET_KEY'),
    ])->get(env('OPAY_BASE_URL') . 'payments/' . $reference);

    if (!$response->successful()) {
        throw new Exception('Verification failed');
    }

    $data = $response->json();
    $payment = Payment::where('reference', $reference)->firstOrFail();

    $status = $data['data']['status'] ?? 'FAILED';

    if ($status === 'SUCCESS') {
        $payment->update([
            'status' => 'success',
            'gateway_response' => $data,
        ]);

        $booking = $payment->booking;
        $booking->update(['status' => 'paid']);

        // 🔹 Generate invoice
        $invoiceUrl = InvoiceService::generateInvoice($booking);

        // 🔹 Notify user
        $booking->user->notify(new PaymentSuccessful($booking, $invoiceUrl));

    } else {
        $payment->update([
            'status' => 'failed',
            'gateway_response' => $data,
        ]);
    }

    return $data;
}
}