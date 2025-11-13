<?php

namespace App\Http\Controllers;
  use Illuminate\Support\Facades\DB;
use App\Facades\EventService;
use App\Http\Requests\CheckEvent;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
{
    $events = Event::published()->get();
    return response()->json($events);
}

    public function store(CheckEvent $request)
{
  
DB::transaction(function () use ($request) {
    $event = Event::create([
        'title' => $request->title,
        'description' => $request->description,
        'date' => $request->date,
     'start_time' => $data['start_time'],
        'end_time' => $data['end_time'],
        'location' => $request->location,
    ]);

    $event->categories()->sync($request->category_ids);
});
    
   return response()->json([
        'message' => 'Event created successfully',
        'data' => $event->load('categories')
    ], 201);
}

    
      public function changeStatus(Request $request, Event $event)
    {
        $request->validate([
            'status' => 'required|in:draft,published,cancelled,ended',
        ]);

        try {
            $updated = EventService::updateStatus($event, $request->status);
            return response()->json(['message' => 'Status updated', 'event' => $updated]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function publish(Event $event)
    {
        try {
            $updated = EventService::publish($event);
            return response()->json(['message' => 'Event published', 'event' => $updated]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function cancel(Event $event)
    {
        try {
            $updated = EventService::cancel($event);
            return response()->json(['message' => 'Event cancelled', 'event' => $updated]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function end(Event $event)
    {
        try {
            $updated = EventService::end($event);
            return response()->json(['message' => 'Event ended', 'event' => $updated]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    
    public function show(Event $event)
    {
        return response()->json($event->load('tickets'));
    }
}