#!/usr/bin/env php
<?php
declare(ticks=1);

use PCSpeakDemo\Player;
use PCSpeakDemo\TrackList;

if (!extension_loaded('pcspeak')) {
    echo "pcspeak module is not enabled\n";
    exit(1);
}

spl_autoload_register(function($class){
    $parts = explode('\\', $class);
    require implode(DIRECTORY_SEPARATOR, $parts) . '.php';
});

$options = getopt('', array('device::', 'help::'));

if (isset($options['help'])) {
    print <<<HELP
Example usage:
 $ ./demo.php --device=/dev/console
HELP;
    exit;
}

$device = isset($options['device'])
    ? $options['device']
    : trim(`tty`);

$trackList = new TrackList(__DIR__ . '/tracks');

pcspeak_open($device);

while ($track = $trackList->prompt()) {
    $player = new Player($track);

    register_tick_function([$player, 'play']);

    while ($player->isPlaying()) {
        // arbitrary sleep, track is playing in background
        usleep(1e4);

        // update player status
        echo $player;
    }

    unregister_tick_function([$player, 'play']);
}

pcspeak_close();
