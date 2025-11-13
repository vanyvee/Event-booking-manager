<?php

namespace App\Listeners;

use App\Events\EventEnded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NotifyEventEnded implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(EventEnded $event)
    {
        Log::info("Event ended: {$event->event->title}");

        }
}