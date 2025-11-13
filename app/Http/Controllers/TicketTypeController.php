<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
    public function index(Event $event)
    {
        return response()->json($event->ticketTypes()->latest()->get());
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'total_quantity' => 'required|integer|min:1',
            'max_per_user' => 'nullable|integer|min:1',
            'is_seated' => 'boolean'
        ]);

        $ticketType = $event->ticketTypes()->create($validated);

        return response()->json(['message' => 'Ticket Type Created', 'data' => $ticketType], 201);
    }

    public function update(Request $request, TicketType $ticketType)
    {
        $ticketType->update($request->all());
        return response()->json(['message' => 'Updated successfully', 'data' => $ticketType]);
    }

    public function destroy(TicketType $ticketType)
    {
        $ticketType->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}