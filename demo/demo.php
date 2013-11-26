#!/usr/bin/env php
<?php

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

$player = (new Player(
    new TrackList(__DIR__ . '/tracks'),
    (isset($options['device']) ? $options['device'] : null)
))->start();
