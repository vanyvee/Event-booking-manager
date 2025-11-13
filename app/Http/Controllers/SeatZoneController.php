<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\SeatZone;
use Illuminate\Http\Request;

class SeatZoneController extends Controller
{
    public function index(Event $event)
    {
        return response()->json($event->seatZones()->with('ticketType')->get());
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'ticket_type_id' => 'nullable|exists:ticket_types,id',
            'name' => 'required|string|max:100',
            'total_seats' => 'required|integer|min:1'
        ]);

        $zone = $event->seatZones()->create($validated);

        return response()->json(['message' => 'Seat Zone Created', 'data' => $zone], 201);
    }

    public function update(Request $request, SeatZone $zone)
    {
        $zone->update($request->all());
        return response()->json(['message' => 'Seat Zone Updated', 'data' => $zone]);
    }

    public function destroy(SeatZone $zone)
    {
        $zone->delete();
        return response()->json(['message' => 'Seat Zone Deleted']);
    }
}