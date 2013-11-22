/*
  +----------------------------------------------------------------------+
  | PHP Version 5                                                        |
  +----------------------------------------------------------------------+
  | Copyright (c) 1997-2013 The PHP Group                                |
  +----------------------------------------------------------------------+
  | This source file is subject to version 3.01 of the PHP license,      |
  | that is bundled with this package in the file LICENSE, and is        |
  | available through the world-wide-web at the following url:           |
  | http://www.php.net/license/3_01.txt                                  |
  | If you did not receive a copy of the PHP license and are unable to   |
  | obtain it through the world-wide-web, please send a note to          |
  | license@php.net so we can mail you a copy immediately.               |
  +----------------------------------------------------------------------+
  | Author:                                                              |
  +----------------------------------------------------------------------+
*/

/* $Id$ */

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include <linux/kd.h>
#include <sys/ioctl.h>
#include <fcntl.h>

#include "php.h"
#include "php_ini.h"
#include "ext/standard/info.h"
#include "php_pcspeak.h"

/* Document SYS_CLOCK_RATE */
#define SYS_CLOCK_RATE     1193180

ZEND_DECLARE_MODULE_GLOBALS(pcspeak)

/* True global resources - no need for thread safety here */
static int le_pcspeak;

/* {{{ PHP_MINIT_FUNCTION
 */
PHP_MINIT_FUNCTION(pcspeak)
{
	return SUCCESS;
}
/* }}} */

/* {{{ PHP_MSHUTDOWN_FUNCTION
 */
PHP_MSHUTDOWN_FUNCTION(pcspeak)
{
	/* uncomment this line if you have INI entries
	UNREGISTER_INI_ENTRIES();
	*/
	return SUCCESS;
}
/* }}} */

/* {{{ PHP_MINFO_FUNCTION
 */
PHP_MINFO_FUNCTION(pcspeak)
{
	php_info_print_table_start();
	php_info_print_table_header(2, "pcspeak support", "enabled");
	php_info_print_table_end();

	/* Remove comments if you have entries in php.ini
	DISPLAY_INI_ENTRIES();
	*/
}
/* }}} */

/* {{{ proto void pcspeak_open(char* ttyname)
   { */
PHP_FUNCTION(pcspeak_open)
{
	int argc = ZEND_NUM_ARGS();
	char* ttyname = NULL;
	int ttyname_length = 0;

	if (zend_parse_parameters(argc TSRMLS_CC, "s", &ttyname, &ttyname_length) != SUCCESS)
		return;

	if ((PCSPEAK_G(device) = open(ttyname, O_WRONLY)) == -1) {
	 	zend_error(E_ERROR, "Unable to open device for writing");
		RETURN_FALSE;
	}

	RETURN_TRUE;
}
/* }}} */

/* {{{ proto void pcspeak_close()
   { */
PHP_FUNCTION(pcspeak_close)
{
	if (PCSPEAK_G(device) != -1) {
		close(PCSPEAK_G(device));
	}
}
/* }}} */

/* {{{ proto void pcspeak_sustain()
   { */
PHP_FUNCTION(pcspeak_sustain)
{
	float freq = PCSPEAK_G(frequency);
	int dev = PCSPEAK_G(device);

	int rounded_freq = (freq >= 0) ? (int)(freq + 0.5) : (int)(freq - 0.5);

	ioctl(dev, KIOCSOUND, SYS_CLOCK_RATE/rounded_freq);
}
/* }}} */

/* {{{ proto void pcspeak_release()
   { */
PHP_FUNCTION(pcspeak_release)
{
	int dev = PCSPEAK_G(device);

	ioctl(dev, KIOCSOUND, 0);
}
/* }}} */

/* {{{ proto void pcspeak_tune(int octave, int note)
   { */
PHP_FUNCTION(pcspeak_tune)
{
	int argc = ZEND_NUM_ARGS();
	int octave;
	int note;

	if (zend_parse_parameters(argc TSRMLS_CC, "ll", &octave, &note) != SUCCESS)
		return;

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
	PCSPEAK_G(frequency) = 440 * powf(etr, steps);
}
/* }}} */

/* {{{ pcspeak_functions[]
 *
 * Every user visible function must have an entry in pcspeak_functions[].
 */
const zend_function_entry pcspeak_functions[] = {
	PHP_FE(pcspeak_open, NULL)
	PHP_FE(pcspeak_close, NULL)
	PHP_FE(pcspeak_sustain, NULL)
	PHP_FE(pcspeak_release, NULL)
	PHP_FE(pcspeak_tune, NULL)
	PHP_FE_END
};
/* }}} */

/* {{{ pcspeak_module_entry
 */
zend_module_entry pcspeak_module_entry = {
	STANDARD_MODULE_HEADER,
	"pcspeak",
	pcspeak_functions,
	PHP_MINIT(pcspeak),
	PHP_MSHUTDOWN(pcspeak),
	NULL,
	NULL,
	PHP_MINFO(pcspeak),
	PHP_PCSPEAK_VERSION,
	STANDARD_MODULE_PROPERTIES
};
/* }}} */

#ifdef COMPILE_DL_PCSPEAK
ZEND_GET_MODULE(pcspeak)
#endif

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 * vim600: noet sw=4 ts=4 fdm=marker
 * vim<600: noet sw=4 ts=4
 */
