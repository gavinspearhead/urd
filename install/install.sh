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
# $LastChangedDate: 2012-05-13 19:29:51 +0200 (Sun, 13 May 2012) $
# $Rev: 2519 $
# $Author: gavinspearhead $
# $Id: install.sh 2519 2012-05-13 17:29:51Z gavinspearhead $
#

#this is a generic install script
#should be called from the specific install script - can be run by itself too

urd_user="urd"
urd_group="urd"
if [ -x /sbin/addgroup ] ; then
	addgroup="/sbin/addgroup"
	ag_options="--system --quiet"
elif [ -x /usr/sbin/addgroup ] ; then
	addgroup="/usr/sbin/addgroup"
	ag_options="--system --quiet"
elif [ -x /sbin/groupadd ] ; then
	addgroup="/sbin/groupadd"
	ag_options="-r"
elif [ -x /usr/sbin/groupadd ] ; then
	addgroup="/usr/sbin/groupadd"
	ag_options="-r"
else 
	addgroup='none'
fi


if [ -x /sbin/adduser ] ; then
	adduser="/sbin/adduser"
	au_options="--disabled-password --disabled-login --quiet --system --group --no-create-home"
elif [ -x /usr/sbin/adduser ] ; then
	au_options="--disabled-password --disabled-login --quiet --system --group --no-create-home"
	adduser="/usr/sbin/adduser"
elif [ -x /sbin/useradd ] ; then
	adduser="/sbin/useradd"
	au_options="-s /bin/false -d /tmp -g $urd_group"
elif [ -x /usr/sbin/useradd ] ; then
	adduser="/usr/sbin/useradd"
	au_options="-s /bin/false -d /tmp -g $urd_group"
else 
	adduser='none'
fi

echo $adduser 
echo $addgroup

if [ "$addgroup" != "none" ] ; then
	$addgroup $ag_options $urd_group 2> /dev/null
	rv="$?"
	if [ "$rv" -eq "0" ] ; then
		echo "Group created";
	else 
		echo "Cannot create group ($rv)"
		exit 1
	fi
else 
	echo "Cannot find addgroup / groupadd utility"
	exit 1
fi

if [ "$adduser" != "none" ] ; then
	$adduser $au_options $urd_group
	if [ "$?" -eq "0" ] ; then
		echo "User created";
	else 
		echo "Cannot create user"
		exit 1
	fi
else 
	echo "Cannot find adduser / useradd utility"
fi

