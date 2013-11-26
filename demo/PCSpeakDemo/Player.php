<?php

namespace PCSpeakDemo;

/**
 * Prompt user for track choice and play the track's events
 * using the pcspeak extension
 */
class Player
{
    /**
     * @var TrackList
     */
    protected $list;

    /**
     * @param TrackList $list
     * @param string $tty
     */
    public function __construct(TrackList $list, $tty = null)
    {
        $this->list = $list;

        // If no tty device is specified, use the one from the current session.
        if (empty($tty)) {
            $tty = trim(`tty`);
        }

        pcspeak_open($tty);
    }

    /**
     * Close the device
     */
    public function __destruct()
    {
        pcspeak_close();
    }

    /**
     * Prompt for track choice and play it
     */
    public function start()
    {
        do {
            pcspeak_release();

            echo $this->list;
            echo "q) quit\n";

            readline_add_history(
                $choice = (int)readline('Make your choice>')
            );

            if (strtolower($choice) === 'q') {
                break;
            }

            if (!$this->list->hasTrack($choice)) {
                continue;
            }

            $track = $this->list->getTrack($choice);

            foreach ($track->getEvents() as $event) {
                if ($event->isEnd()) {
                    break;
                }

                $this->play($event);
            }
        } while (true);
    }

    /**
     * @param Event $event
     */
    public function play(Event $event)
    {
        if ($event->isNoteOn()) {
            pcspeak_sustain($event->getNote(), $event->getOctave());
        }

        if ($event->isNoteOff()) {
            pcspeak_release();
        }

        if ($event->isDelay()) {
            usleep($event->getDelay() * 1000);
        }
    }
}
