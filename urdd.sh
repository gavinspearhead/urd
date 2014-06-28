#!/bin/sh


#  This file is part of Urd.
#
#  Urd is free software; you can redistribute it and/or modify
#  it under the terms of the GNU General Public License as published by
#  the Free Software Foundation; either version 3 of the License, or
#  (at your option) any later version.
#  Urd is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU General Public License for more details.
#
#  You should have received a copy of the GNU General Public License
#  along with this program. See the file "COPYING". If it does not
#  exist, see <http://www.gnu.org/licenses/>.
#
# $LastChangedDate: 2014-06-19 00:03:31 +0200 (do, 19 jun 2014) $
# $Rev: 3098 $
# $Author: gavinspearhead@gmail.com $
# $Id
#


if [ -x "/usr/bin/php" ] ; then
	PHP="/usr/bin/php"
elif [ -x "/usr/local/bin/php" ] ; then
	PHP="/usr/local/bin/php"
else
	echo "cannot find PHP CLI; is it installed?"
	exit 1
fi

urdd_dir=`dirname $0`
rv="17" # 17 is the return code that means please restart me
while [ "$rv" -eq "17" ] ; do 

    $PHP $urdd_dir/urdd/urdd.php $@ 3>&- 4>&- 5>&- 6>&- 7>&- 8>&- 9>&- 
    rv=$?
    sleep 1
done


exit 0
