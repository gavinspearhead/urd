<?php
/*
 * Project:     MagpieRSS: a simple RSS integration tool
 * File:        rss_cache.inc, a simple, rolling(no GC), cache
 *              for RSS objects, keyed on URL.
 * Author:      Kellan Elliott-McCrea <kellan@protest.net>
 * Updated by 	Gavin Spearhead for URD
 * Version:     0.51
 * License:     GPL
 *
 * The lastest version of MagpieRSS can be obtained from:
 * http://magpierss.sourceforge.net
 *
 * For questions, help, comments, discussion, etc., please join the
 * Magpie mailing list:
 * http://lists.sourceforge.net/lists/listinfo/magpierss-general
 *
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

class RSSCache
{
    private $BASE_CACHE = '';    // where the cache files are stored
    private $MAX_AGE    = 3600;         // when are files stale, default one hour

    public function __construct ($base='', $age='')
    {
        if ($base)
            $this->BASE_CACHE = $base;
        if ($age)
            $this->MAX_AGE = $age;

        // attempt to make the cache directory
        if (!file_exists($this->BASE_CACHE)) {
            $status = @mkdir( $this->BASE_CACHE, 0755 );
            // if make failed
            if (!$status) {
                throw new exception ("Could not make cache magpie directory: '" . $this->BASE_CACHE . "'.", ERR_MAGPIE_FAILED);
            }
        }
    }

/*=======================================================================*\
    Function:   set
    Purpose:    add an item to the cache, keyed on url
    Input:      url from wich the rss file was fetched
    Output:     true on sucess
\*=======================================================================*/
    public function set ($url, $rss)
    {
        assert(is_string($url));
        $cache_file = $this->file_name($url);
        if (file_exists($cache_file) && !is_writeable($cache_file)) {
            throw new exception ("Cache unable to open file for writing 1: $cache_file", ERR_MAGPIE_FAILED);
        }
        $fp = @fopen($cache_file, 'w');
        if (!$fp) {
            throw new exception ("Cache unable to open file for writing 2: $cache_file", ERR_MAGPIE_FAILED);
        }

        $data = $this->serialize($rss);
        fwrite($fp, $data);
        fclose($fp);

        return $cache_file;
    }

    // remove the cached url from the cache
    public function remove($url)
    {
        $cache_file = $this->file_name( $url );
        if ( file_exists( $cache_file ) ) {
            unlink($cache_file);
        }
    }

/*=======================================================================*\
    Function:   get
    Purpose:    fetch an item from the cache
    Input:      url from wich the rss file was fetched
    Output:     cached object on HIT, false on MISS
\*=======================================================================*/
    public function get($url)
    {
        assert(is_string($url));
        $cache_file = $this->file_name($url);

        if (!file_exists( $cache_file)) {
            throw new exception("Cache doesn't contain: $url (cache file: $cache_file)", ERR_MAGPIE_FAILED);
        }
        $fp = @fopen($cache_file, 'r');
        if (! $fp) {
            throw new exception("Failed to open cache file for reading: $cache_file", ERR_MAGPIE_FAILED);
        }
        if ($filesize = filesize($cache_file) ) {
            $data = fread( $fp, filesize($cache_file) );
            $rss = $this->unserialize( $data );

            return $rss;
        }

        return 0;
    }

/*=======================================================================*\
    Function:   check_cache
    Purpose:    check a url for membership in the cache
        and whether the object is older then MAX_AGE (ie. STALE)
    Input:      url from wich the rss file was fetched
    Output:     cached object on HIT, false on MISS
\*=======================================================================*/
    public function check_cache( $url )
    {
        assert(is_string($url));
        $filename = $this->file_name( $url );

        if (file_exists($filename)) {
            // find how long ago the file was added to the cache
            // and whether that is longer then MAX_AGE
            $mtime = filemtime( $filename );
            $age = time() - $mtime;
            if ($this->MAX_AGE > $age) { // object exists and is current

                return 'HIT';
            } else {  // object exists but is old

                return 'STALE';
            }
        } else { // object does not exist

            return 'MISS';
        }

    }

    public function cache_age($cache_key)
    {
        $filename = $this->file_name($cache_key);
        if (file_exists($filename)) {
            $mtime = filemtime($filename);
            $age = time() - $mtime;

            return $age;
        } else {
            return -1;
        }
    }

/*=======================================================================*\
    Function:   serialize
\*=======================================================================*/
    public function serialize($rss)
    {
        return serialize($rss);
    }

/*=======================================================================*\
    Function:   unserialize
\*=======================================================================*/
    public function unserialize($data)
    {
        return unserialize($data);
    }

/*=======================================================================*\
    Function:   file_name
    Purpose:    map url to location in cache
    Input:      url from wich the rss file was fetched
    Output:     a file name
\*=======================================================================*/
    public function file_name($url)
    {
        $filename = md5($url);

        return join(DIRECTORY_SEPARATOR, array($this->BASE_CACHE, $filename));
    }
}
