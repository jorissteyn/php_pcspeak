#define SYS_CLOCK_RATE     1193180

int libpcspeak_open(char* ttyname);
void libpcspeak_close(int device);
void libpcspeak_sustain(int device, int note, int octave);
void libpcspeak_release(int device);
