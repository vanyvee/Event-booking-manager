<?php

namespace App\Http\Controllers;

use App\Services\OrganizerService;
use Illuminate\Http\Request;
use Exception;

class OrganizerReportController extends Controller
{
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

    //  report by date range
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