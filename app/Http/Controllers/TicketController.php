<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'type'              => 'required|string|max:100',
            'price'             => 'required|integer|min:0',
            'quantity'          => 'required|integer|min:1',
        ]);

        $ticket = $event->tickets()->create([
            'type'      => $validated['type'],
            'price'     => $validated['price'],
            'quantity'  => $validated['quantity'],
            'available_quantity' => $validated['quantity'],
        ]);

        return response()->json($ticket, 201);
    }
}