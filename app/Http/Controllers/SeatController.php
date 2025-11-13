<?php

namespace App\Http\Controllers;

use App\Models\SeatZone;
use App\Models\Seat;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function index(SeatZone $zone)
    {
        return response()->json($zone->seats()->orderBy('seat_number')->get());
    }

    // ⚙️ Auto-generate seat numbers like A1-A10, B1-B10, etc.
    public function autoGenerate(Request $request, SeatZone $zone)
    {
        $validated = $request->validate([
            'rows' => 'required|integer|min:1|max:26',
            'seats_per_row' => 'required|integer|min:1|max:100',
        ]);

        $zone->seats()->delete(); // clear old seats first

        $rows = range('A', chr(64 + $validated['rows']));
        $bulk = [];

        foreach ($rows as $row) {
            for ($i = 1; $i <= $validated['seats_per_row']; $i++) {
                $bulk[] = [
                    'seat_zone_id' => $zone->id,
                    'seat_number' => $row . $i,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Seat::insert($bulk);

        $zone->update(['total_seats' => count($bulk)]);

        return response()->json([
            'message' => 'Seats generated successfully',
            'total' => count($bulk)
        ]);
    }
}