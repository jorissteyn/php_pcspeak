<?php

namespace PCSpeakDemo;

class Track
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $artist;

    /**
     * @var array<Event>
     */
    protected $events;

    /**
     * @param string $title
     * @param string $artist
     * @param array $events
     */
    public function __construct($title, $artist, array $events)
    {
        $this->title  = $title;
        $this->artist = $artist;
        $this->events = $events;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * @return array<Event>
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Calculate total duration of all events in seconds
     *
     * @return int
     */
    public function getDuration()
    {
        $duration = 0;
        foreach ($this->getEvents() as $event) {
            $duration += $event->getDelay();
        }
        return round($duration / 1000);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $duration = $this->getDuration();
        $minutes = floor($duration / 60);
        $seconds = $duration % 60;

        return sprintf(
            '%-50s %-30s (%02d:%02d)',
            $this->title,
            $this->artist,
            $minutes, $seconds
        );
    }
}
