#include "libpcspeak.h"

#include <linux/kd.h>
#include <sys/ioctl.h>
#include <fcntl.h>
#include <stdint.h>
#include <unistd.h>

/* Document SYS_CLOCK_RATE */
#define SYS_CLOCK_RATE     1193180

int libpcspeak_open(char* ttyname)
{
	return open(ttyname, O_WRONLY);
}

void libpcspeak_close(int device)
{
	libpcspeak_release(device);

	if (device != -1) {
		close(device);
	}
}

void libpcspeak_sustain(int device, int note, int octave)
{
	// calculate steps from A4 in 4th ocatave
	int steps = note - 9;

	// calculate steps for octaves < 4 >
	steps = ((octave - 4) * 12) + steps;

	// Define harmonic tuning ratio on equal tempered scale
	// See http://www.phy.mtu.edu/~suits/scales.html
	// float etr = 1;       // unison
	float etr = 1.05946;   // minor
	// float etr = 1.12246; // major
	// float etr = 2;       // octave

	// bias A4 to 440Hz
	float fq = 440 * powf(etr, steps);

	// round to int
	int rounded_freq = (fq >= 0) ? (int)(fq + 0.5) : (int)(fq - 0.5);

	ioctl(device, KIOCSOUND, SYS_CLOCK_RATE/rounded_freq);
}

void libpcspeak_release(int device)
{
	ioctl(device, KIOCSOUND, 0);
}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: noet sw=4 ts=4 fdm=marker
 * vim<600: noet sw=4 ts=4
 */
