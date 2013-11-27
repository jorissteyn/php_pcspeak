# PHP PCSpeak
This PHP extension exposes an API to control your PC speaker.

# API Overview
A console terminal device is used to access the hardware. Before you can use
the speaker, open a device:

```php
pcspeak_open("/dev/tty");
```

Note that you must have privileges to access the device. Opening the TTY
from your current session should work without special privileges.

To start and sustain a tone on the speaker, use:

```php
pcspeak_sustain($note, $octave);
```

The pitch of the speaker is determined based on the two arguments passed to
pcspeak_sustain. These arguments are integers mapped on the chromatic scale to
the corresponding frequency. To sustain a 'central-C', pass $note=0 and
$octave=4

To release the tone, use:

```php
pcspeak_release();
```

By providing a "low level" sustain/release mechanism, it's possible to produce
audible signals without blocking normal execution of the application.

When finished, close the device:

```php
pcspeak_close();
```

# Demo
The demo folder contains a music player, check it out!

```
demo $ ./demo.php --device=/dev/console
```

# Random thoughts and further reading
* this will definately only work on Linux
* make sure you have the pcspkr kernel module (or alternative) loaded
* man console_ioctl
* Physics of Music: http://www.phy.mtu.edu/~suits/scales.html
* miditones.c was used to generate a byte stream of simplified midi events for the demo
* https://code.google.com/p/miditones/
* use gcc -lbsd to compile miditones
