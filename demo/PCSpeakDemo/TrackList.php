<?php

namespace PCSpeakDemo;

/**
 * Scan directory and assemble list of all tracks
 */
class TrackList
{
    /**
     * @var array<Track>
     */
    protected $tracks = [];

    /**
     * @param string $directory
     */
    public function __construct($directory)
    {
        if (!is_dir($directory)) {
            throw new Exception('Not a valid track directory');
        }

        $this->loadTracks(
            new \RecursiveDirectoryIterator($directory)
        );
    }

    /**
     * @param int $index
     * @return bool
     */
    public function hasTrack($index)
    {
        return isset($this->tracks[$index]);
    }

    /**
     * @param int $index
     * @return Track
     */
    public function getTrack($index)
    {
        return $this->tracks[$index];
    }

    /**
     * @return Track|null
     */
    public function prompt()
    {
        echo "\n";
        echo $this;
        echo "q) quit\n";

        do {
            readline_add_history(
                $choice = readline('Make your choice>')
            );

            if (strtolower($choice) === 'q') {
                return;
            }
        } while (!$this->hasTrack((int)$choice));

        return $this->getTrack((int)$choice);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $list = [];
        foreach ($this->tracks as $index => $track) {
            $list[] = sprintf("%02d) %s\n", $index, $track);
        }

        return implode($list);
    }

    /**
     * @param \RecursiveDirectoryIterator $iterator
     * @return TrackList
     */
    protected function loadTracks(\RecursiveDirectoryIterator $iterator)
    {
        foreach ($iterator as $file) {
            if (!$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            $data = include $file->getPathname();
            $title = $data[0];
            $artist = $data[1];
            $events = (new EventLoader($data[2]))->getEvents();

            $this->tracks[] = new Track($title, $artist, $events);
        }

        usort($this->tracks, function($left, $right){
            return strcasecmp($left, $right);
        });

        return $this;
    }
}
