<?php

namespace App\Listeners;

use App\Events\EventCancelled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NotifyEventCancelled implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(EventCancelled $event)
    {
        Log::warning("Event cancelled: {$event->event->title}");

        //  notify ticket holders
        
    }
}