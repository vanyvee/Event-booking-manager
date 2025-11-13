<?php

namespace App\Services;

use App\Models\Event;
use App\Events\EventPublished;
use App\Events\EventCancelled;
use App\Events\EventEnded;
use Exception;

class EventService
{
    /**
     * Update event status with all rules.
     */
    public static function updateStatus(Event $event, string $newStatus): Event
    {
        $allowed = ['draft', 'published', 'cancelled', 'ended'];

        if (!in_array($newStatus, $allowed)) {
            throw new Exception("Invalid status: $newStatus");
        }

        // cannot publish a past event
        if ($newStatus === 'published' && now()->greaterThan($event->end_date)) {
            throw new Exception('Cannot publish an event that has already ended.');
        }

        $event->status = $newStatus;
        $event->save();

        // Fire  when status changes - not designed yet
        match ($newStatus) {
            'published' => event(new EventPublished($event)),
            'cancelled' => event(new EventCancelled($event)),
            'ended'     => event(new EventEnded($event)),
            default     => null,
        };

        return $event;
    }

    /**
     * Publish an event.
     */
    public static function publish(Event $event): Event
    {
        if ($event->status === 'published') {
            throw new Exception('Event already published.');
        }

        if (now()->greaterThan($event->end_date)) {
            throw new Exception('Cannot publish an expired event.');
        }

        return self::updateStatus($event, 'published');
    }

    /**
     * Cancel an event.
     */
    public static function cancel(Event $event): Event
    {
        if ($event->status === 'cancelled') {
            throw new Exception('Event already cancelled.');
        }

        if ($event->status === 'ended') {
            throw new Exception('Cannot cancel an ended event.');
        }

        return self::updateStatus($event, 'cancelled');
    }

    /**
     * End an event (can be called automatically or manually).
     */
    public static function end(Event $event): Event
    {
        if ($event->status === 'ended') {
            throw new Exception('Event already ended.');
        }

        return self::updateStatus($event, 'ended');
    }

    /**
     * Move event back to draft mode.
     */
    public static function revertToDraft(Event $event): Event
    {
        if (in_array($event->status, ['cancelled', 'ended'])) {
            throw new Exception('Cannot revert a cancelled or ended event to draft.');
        }

        return self::updateStatus($event, 'draft');
    }
}