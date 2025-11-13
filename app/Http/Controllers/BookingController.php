<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckEvent;
use App\Facades\BookingService;
use Illuminate\Http\Request;
use Exception;

class BookingController extends Controller
{
    public function store(CheckEvent $request)
    {
        try {
            $booking = BookingService::bookTicket(
                auth()->id(),
                $request->ticket_id,
                $request->quantity
            );
  //Fire the event
        event(new BookingCreated($booking));

            return response()->json([
                'message' => 'Booking successful',
                'data' => $booking
            ], 201);

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function cancel($id)
    {
        try {
            $booking = BookingService::cancelBooking($id);
            return response()->json(['message' => 'Booking cancelled', 'data' => $booking]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function myBookings()
    {
        return response()->json(
            BookingService::getUserBookings(auth()->user())
        );
    }
}