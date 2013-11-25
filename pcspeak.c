/**
 * Licensed under the MIT or GPL Version 2 licenses.
 *
 * Copyright (C) 2012 - 2013 by Joris Steyn
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/* $Id$ */

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "php.h"
#include "php_ini.h"
#include "ext/standard/info.h"
#include "php_pcspeak.h"

/* not a shared library */
#include "libpcspeak.c"

ZEND_DECLARE_MODULE_GLOBALS(pcspeak)

/* True global resources - no need for thread safety here */
static int le_pcspeak;

/* {{{ PHP_MINIT_FUNCTION
 */
PHP_MINIT_FUNCTION(pcspeak)
{
	REGISTER_LONG_CONSTANT("TEMP_X", 1, CONST_CS | CONST_PERSISTENT);

	return SUCCESS;
}
/* }}} */

/* {{{ PHP_MSHUTDOWN_FUNCTION
 */
PHP_MSHUTDOWN_FUNCTION(pcspeak)
{
	libpcspeak_close(PCSPEAK_G(device));
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

	if ((PCSPEAK_G(device) = libpcspeak_open(ttyname)) == -1) {
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
		libpcspeak_close(PCSPEAK_G(device));
	}
}
/* }}} */

/* {{{ proto void pcspeak_sustain()
   { */
PHP_FUNCTION(pcspeak_sustain)
{
	int argc = ZEND_NUM_ARGS();
	int note;
	int octave;

	if (zend_parse_parameters(argc TSRMLS_CC, "ll", &note, &octave) != SUCCESS)
		return;

	libpcspeak_sustain(PCSPEAK_G(device), note, octave);
}
/* }}} */

/* {{{ proto void pcspeak_release()
   { */
PHP_FUNCTION(pcspeak_release)
{
	libpcspeak_release(PCSPEAK_G(device));
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
