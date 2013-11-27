<?php

namespace PCSpeakDemo;

class Duration
{
    /**
     * @var array<Event>
     */
    protected $events;

    /**
     * @param array<Event> $events
     */
    public function __construct(array $events)
    {
        $this->events = $events;
    }

    /**
     * Calculate total duration of all events in seconds
     *
     * @param int $start Start counting from event
     * @return int $end Stop counting on event
     * @return int
     */
    public function seconds($start = 0, $end = null)
    {
        if ($end === null) {
            $end = count($this->events);
        }

        $duration = 0;
        foreach (array_slice($this->events, $start, $end) as $event) {
            $duration += $event->getDelay();
        }
        return round($duration / 1000);
    }

    /**
     * Format calculated duration "mm:ss"
     *
     * @param int $start Start counting from event
     * @return int $end Stop counting on event
     * @return int */
    public function format($start = 0, $end = null)
    {
        $duration = $this->seconds($start, $end);
        $minutes = floor($duration / 60);
        $seconds = $duration % 60;

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->format();
    }
}
