--TEST--
Open and close device
--SKIPIF--
<?php if (!extension_loaded("pcspeak")) print "skip"; ?>
--FILE--
<?php
var_dump(pcspeak_open("/dev/console"));
pcspeak_close();
?>
--EXPECT--
bool(true)
