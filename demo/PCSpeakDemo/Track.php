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
     * @param int $position
     * @return bool
     */
    public function hasEvent($position)
    {
        return isset($this->events[$position]);
    }

    /**
     * @param int $position
     * @return Event
     */
    public function getEvent($position)
    {
        return $this->events[$position];
    }

    /**
     * @return Duration
     */
    public function getDuration()
    {
        return new Duration($this->events);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%-50s %-30s (%s)',
            $this->title,
            $this->artist,
            $this->getDuration()
        );
    }
}
