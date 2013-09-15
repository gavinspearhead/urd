#!/bin/sh

if [ -x "/usr/bin/php" ] ; then
	PHP="/usr/bin/php"
elif [ -x "/usr/local/bin/php" ] ; then
	PHP="/usr/local/bin/php"
else
	echo "Cannot find PHP CLI; is it installed?"
	exit 1
fi


urdd_dir=`dirname $0`
$PHP $urdd_dir/yydecode.php $@

