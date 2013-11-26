--TEST--
Tune, sustain, release
--SKIPIF--
<?php if (!extension_loaded("pcspeak")) print "skip"; ?>
--FILE--
<?php
var_dump(pcspeak_open("/dev/console"));
for ($o=2; $o<7; $o++) {
    for ($i=1; $i<=7; $i++) {
        pcspeak_sustain($i, $o);
        usleep(1000*300);
        pcspeak_release();
    }
}
pcspeak_close();
?>
--EXPECT--
bool(true)
