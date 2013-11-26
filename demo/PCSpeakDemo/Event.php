<?php

namespace PCSpeakDemo;

/**
 * Parse a MIDI event
 */
class Event
{
    /**
     * @var int
     */
    protected $command;

    /**
     * @var int
     */
    protected $data;

    /**
     * @param int $command
     * @param int $data
     */
    public function __construct($command, $data = null)
    {
        $this->command = $command;
        $this->data    = $data;
    }

    /**
     * @return bool
     */
    public function isEnd()
    {
        // like MIDI
        return ($this->command === 0xe0) ||
               ($this->command === 0xf0);
    }

    /**
     * @return bool
     */
    public function isNoteOn()
    {
        return ($this->command & 0xf0) === 0x90;
    }

    /**
     * @return bool
     */
    public function isNoteOff()
    {
        return ($this->command & 0xf0) === 0x80;
    }

    /**
     * @return bool
     */
    public function isDelay()
    {
        // MSB = 0
        return !($this->command & 0x80);
    }

    /**
     * @return int
     */
    public function getNote()
    {
        // standard chromatic scale
        return $this->data % 12;
    }

    /**
     * @return int
     */
    public function getOctave()
    {
        // standard chromatic scale
        return ($this->data - $this->getNote()) / 12;
    }

    /**
     * Return intended tone generator
     *
     * @return int
     */
    public function getToneGenerator()
    {
        // 2nd trough 8th bit
        return $this->command & 0xf;
    }

    /**
     * Return delay in milliseconds
     *
     * @return int
     */
    public function getDelay()
    {
        if (!$this->isDelay()) {
            return 0;
        }

        // parse remaining 15 bits
        return $this->data + ($this->command << 8);
    }
}
