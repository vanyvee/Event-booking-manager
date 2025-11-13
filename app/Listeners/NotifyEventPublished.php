<?php

namespace App\Listeners;

use App\Events\EventPublished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NotifyEventPublished implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(EventPublished $event)
    {
        // Example: log or send notifications
        Log::info("Event published: {$event->event->title}");

        // e.g. notify subscribers
        // Notification::send(User::subscribers(), new EventPublishedNotification($event->event));
    }
}