<?php

namespace PCSpeakDemo;

/**
 * Contains state of currently playing track
 */
class Player
{
    /**
     * @var Track
     */
    protected $track;

    /**
     * Position of current event
     *
     * @var int
     */
    protected $position = 0;

    /**
     * Time of next event in microseconds
     *
     * @var int
     */
    protected $delayUntil = 0;

    /**
     * @param TrackList $list
     * @param string $tty
     */
    public function __construct(Track $track)
    {
        $this->track = $track;
    }

    /**
     * Update the speaker state / play next event
     */
    public function play()
    {
        if ($this->isPlaying() && $this->isDelaying()) {
            $event = $this->getCurrentEvent();
            if ($event->isNoteOn()) {
                pcspeak_sustain($event->getNote(), $event->getOctave());
            } elseif ($event->isNoteOff()) {
                pcspeak_release();
            } elseif ($event->isDelay()) {
                $microDelay = $event->getDelay() / 1000;
                $this->delayUntil = microtime(true) + $microDelay;
            }

            if ($event->isEnd()) {
                $this->position = null;
            } else {
                $this->position++;
            }
        }
    }

    /**
     * @return bool
     */
    public function isPlaying()
    {
        return ($this->position !== null) &&
               $this->track->hasEvent($this->position + 1);
    }

    /**
     * @return bool
     */
    public function isDelaying()
    {
        return microtime(true) > $this->delayUntil;
    }

    /**
     * @return Event
     */
    public function getCurrentEvent()
    {
        return $this->track->getEvent($this->position);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $duration = $this->track->getDuration();

        return sprintf(
            "\rPlaying '%s', elapsed %s, remaining %s",
            $this->track->getTitle(),
            $this->track->getDuration()->format(0, $this->position),
            $this->track->getDuration()->format($this->position)
        );
    }
}
