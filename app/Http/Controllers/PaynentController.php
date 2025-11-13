<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Exception;

class PaymentController extends Controller
{
    public function initialize($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->status !== 'pending') {
            return response()->json(['message' => 'Booking already processed'], 400);
        }

        try {
            $response = PaymentService::initializePayment($booking);
            return response()->json($response);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function verify($reference)
    {
        try {
            $data = PaymentService::verifyPayment($reference);
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function webhook(Request $request)
    {
        $reference = $request->input('reference');
        if ($reference) {
            PaymentService::verifyPayment($reference);
        }
        return response()->json(['message' => 'Webhook received']);
    }
}