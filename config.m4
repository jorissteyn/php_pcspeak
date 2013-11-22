dnl $Id$
dnl config.m4 for extension pcspeak

dnl Comments in this file start with the string 'dnl'.
dnl Remove where necessary. This file will not work
dnl without editing.

dnl If your extension references something external, use with:

dnl PHP_ARG_WITH(pcspeak, for pcspeak support,
dnl Make sure that the comment is aligned:
dnl [  --with-pcspeak             Include pcspeak support])

dnl Otherwise use enable:

dnl PHP_ARG_ENABLE(pcspeak, whether to enable pcspeak support,
dnl Make sure that the comment is aligned:
dnl [  --enable-pcspeak           Enable pcspeak support])

if test "$PHP_PCSPEAK" != "no"; then
  dnl Write more examples of tests here...

  dnl # --with-pcspeak -> check with-path
  dnl SEARCH_PATH="/usr/local /usr"     # you might want to change this
  dnl SEARCH_FOR="/include/pcspeak.h"  # you most likely want to change this
  dnl if test -r $PHP_PCSPEAK/$SEARCH_FOR; then # path given as parameter
  dnl   PCSPEAK_DIR=$PHP_PCSPEAK
  dnl else # search default path list
  dnl   AC_MSG_CHECKING([for pcspeak files in default path])
  dnl   for i in $SEARCH_PATH ; do
  dnl     if test -r $i/$SEARCH_FOR; then
  dnl       PCSPEAK_DIR=$i
  dnl       AC_MSG_RESULT(found in $i)
  dnl     fi
  dnl   done
  dnl fi
  dnl
  dnl if test -z "$PCSPEAK_DIR"; then
  dnl   AC_MSG_RESULT([not found])
  dnl   AC_MSG_ERROR([Please reinstall the pcspeak distribution])
  dnl fi

  dnl # --with-pcspeak -> add include path
  dnl PHP_ADD_INCLUDE($PCSPEAK_DIR/include)

  dnl # --with-pcspeak -> check for lib and symbol presence
  dnl LIBNAME=pcspeak # you may want to change this
  dnl LIBSYMBOL=pcspeak # you most likely want to change this 

  dnl PHP_CHECK_LIBRARY($LIBNAME,$LIBSYMBOL,
  dnl [
  dnl   PHP_ADD_LIBRARY_WITH_PATH($LIBNAME, $PCSPEAK_DIR/lib, PCSPEAK_SHARED_LIBADD)
  dnl   AC_DEFINE(HAVE_PCSPEAKLIB,1,[ ])
  dnl ],[
  dnl   AC_MSG_ERROR([wrong pcspeak lib version or lib not found])
  dnl ],[
  dnl   -L$PCSPEAK_DIR/lib -lm
  dnl ])
  dnl
  dnl PHP_SUBST(PCSPEAK_SHARED_LIBADD)

  PHP_NEW_EXTENSION(pcspeak, pcspeak.c, $ext_shared)
fi
