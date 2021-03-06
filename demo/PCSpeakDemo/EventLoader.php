<?php

namespace PCSpeakDemo;

/**
 * Load byte sequence generated by miditones
 */
class EventLoader
{
    /**
     * @var array
     */
    protected $sequence;

    /**
     * Load Event objects for given sequence of "miditones" bytes
     *
     * @param array
     */
    public function __construct(array $sequence)
    {
        $this->sequence = $sequence;
    }

    /**
     * @return array<Event>
     */
    public function getEvents()
    {
        $events = [];
        for ($i=0; $i<count($this->sequence); $i++) {
            $command = $this->sequence[$i];
            $data    = null;

            // include a data byte if the 4th MSB is set (tone start)
            if (($command & 0xf0) === 0x90) {
                $data = $this->sequence[++$i];
            }

            // include a data byte if the MSB on the command byte is 0 (delay)
            if (!($command & 0x80)) {
                $data = $this->sequence[++$i];
            }

            $event = new Event($command, $data);

            // we only support 1 PC speaker
            if ($event->isNoteOn() && ($event->getToneGenerator() !== 0)) {
                continue;
            }

            $events[] = $event;
        }
        return $events;
    }
}
