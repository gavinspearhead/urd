#!/bin/bash


cd /tmp

if [ "$1" = "devel" ] ; then
    svn export http://urd.googlecode.com/svn/trunk/branches/devel 'urd-tmp'
else 
    svn export http://urd.googlecode.com/svn/trunk/trunk 'urd-tmp'
fi

version=`php -r 'define("ORIGINAL_PAGE", 1); include "urd-tmp/functions/urdversion.php"; echo urd_version::get_version();'`
echo $version

rm -rf urd-tmp/deb urd-tmp/urd_lang urd-tmp/tools

mv urd-tmp/ urd-$version/



tar -czf urd-$version.tar.gz urd-$version/

cd -

mv /tmp/urd-$version.tar.gz .

rm -rf /tmp/urd-$version

