<?php

namespace App\Http\Controllers;

use App\Facades\BookingFacade;
use App\Models\Refund;
use Illuminate\Http\Request;
use Exception;

class RefundController extends Controller
{
    // Request refund manually
    public function refund(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'reason' => 'nullable|string|max:255',
        ]);

        try {
            $refund = BookingFacade::refundBooking(
                $request->booking_id,
                $request->reason
            );

            return response()->json([
                'message' => 'Refund initiated successfully',
                'data' => $refund,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    // Get all refunds (Admin or Organizer)
    public function index()
    {
        $refunds = Refund::with('booking.user', 'booking.ticket.event')
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => $refunds,
        ]);
    }

    // View a single refund record
    public function show($id)
    {
        $refund = Refund::with('booking.ticket.event')->findOrFail($id);
        return response()->json(['data' => $refund]);
    }
    
    
    // Organizer revenue summary
    public function revenueSummary(Request $request)
    {
        try {
            $organizerId = auth()->id();

            $data = OrganizerService::revenueReport($organizerId);

            return response()->json([
                'status' => true,
                'data' => $data,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    // report by date range
    public function revenueByDate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        try {
            $organizerId = auth()->id();
            $data = OrganizerService::revenueReportByDate(
                $organizerId,
                $request->start_date,
                $request->end_date
            );

            return response()->json(['status' => true, 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        }
    }
}