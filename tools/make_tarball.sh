#!/bin/bash


cd /tmp

add_ver=''

if [ "$1" = "devel" ] ; then
    git clone 'https://github.com/gavinspearhead/urd.git' 'urd-tmp'
    add_ver="-"`date +"%Y%m%d%H%M%S"`
else 
    git clone 'https://github.com/gavinspearhead/urd.git' 'urd-tmp'
fi

version=`php -r 'define("ORIGINAL_PAGE", 1); include "urd-tmp/functions/urdversion.php"; echo urd_version::get_version();'`
version=$version$add_ver

echo $version

rm -rf urd-tmp/deb urd-tmp/urd_lang urd-tmp/tools

mv urd-tmp/ urd-$version/

tar -c urd-$version/  --exclude=.gitignore --exclude=.git --exclude=tags --exclude=v*.swp| gzip -9 > urd-$version.tar.gz 

cd -

mv "/tmp/urd-$version.tar.gz" .

rm -rf /tmp/urd-$version

