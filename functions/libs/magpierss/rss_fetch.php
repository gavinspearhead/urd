<?php
/*
 * Project:     MagpieRSS: a simple RSS integration tool
 * File:        rss_fetch.inc, a simple functional interface
 to fetching and parsing RSS files, via the
 function fetch_rss()
 * Author:      Kellan Elliott-McCrea <kellan@protest.net>
 * Updated by 	Gavin Spearhead for URD
 * License:     GPL
 *
 * The lastest version of MagpieRSS can be obtained from:
 * http://magpierss.sourceforge.net
 *
 * For questions, help, comments, discussion, etc., please join the
 * Magpie mailing list:
 * magpierss-general@lists.sourceforge.net
 *
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

// Setup MAGPIE_DIR for use on hosts that don't include
// the current path in include_path.
// with thanks to rajiv and smarty
if (!defined('DIR_SEP')) {
    define('DIR_SEP', DIRECTORY_SEPARATOR);
}

$pathrf = realpath(dirname(__FILE__));
require_once("$pathrf/rss_parse.php");
require_once("$pathrf/rss_cache.php");

/*
 * CONSTANTS - redefine these in your script to change the
 * behaviour of fetch_rss() currently, most options effect the cache
 *
 * MAGPIE_CACHE_AGE - How long to store cached RSS objects? In seconds.
 *
 */

/*=======================================================================*\
    Function: fetch_rss:
    Purpose:  return RSS object for the give url
          maintain the cache
    Input:    url of RSS file
    Output:   parsed RSS object (see rss_parse.inc)

    NOTES ON CACHEING:
    If caching is on (MAGPIE_CACHE_ON) fetch_rss will first check the cache.

    NOTES ON RETRIEVING REMOTE FILES:
    If conditional gets are on (MAGPIE_CONDITIONAL_GET_ON) fetch_rss will

    return a cached object, and touch the cache object upon recieving a
    304.

    NOTES ON FAILED REQUESTS:
    If there is an HTTP error while fetching an RSS object, the cached
    version will be return, if it exists (and if MAGPIE_CACHE_FRESH_ONLY is off)
\*=======================================================================*/

class fetch_rss
{
    const MAGPIE_FETCH_TIME_OUT = 60;
    const MAGPIE_FETCHCONNECT_TIME_OUT = 30;
    const MAGPIE_OUTPUT_ENCODING = 'ISO-8859-1';
    const MAGPIE_CACHE_AGE = 3600; // one hour

    const MAGPIE_USER_AGENT = 'URD-MAGPIE/0.73 (Cached)';

    public static function delete_cache_entry($url, $cache_dir)
    {
        assert(is_string($url) && is_string($cache_dir));
        $cache = new RSSCache($cache_dir, self::MAGPIE_CACHE_AGE);
        $cache_key = $url . self::MAGPIE_OUTPUT_ENCODING;
        $cache->remove($cache_key);
    }

    public static function do_fetch_rss($url, $cache_dir, $username='', $password='', $cache_age=self::MAGPIE_CACHE_AGE)
    {
        assert(is_string($url) && is_string($cache_dir));

        if ( !isset($url) ) {
            throw new exception ('Fetch_rss called without a url', ERR_MAGPIE_FAILED);
        }
        // Flow
        // 1. check cache
        // 2. if there is a hit, make sure its fresh
        // 3. if cached obj fails freshness check, fetch remote
        // 4. if remote fails, return stale object, or error

        $cache_status    = 0;       // response of check_cache
        $request_headers = array(); // HTTP headers to send with fetch
        $rss             = 0;       // parsed RSS object
        $errormsg        = 0;       // errors, if any
        try {
            $cache = new RSSCache($cache_dir, $cache_age);
            $cache_key = $url . self::MAGPIE_OUTPUT_ENCODING;
            $cache_status = $cache->check_cache($cache_key);
        } catch (exception $e) {
            write_log('Retrieving cached RSS failed', LOG_NOTICE);
        }
        // store parsed XML by desired output encoding
        // as character munging happens at parse time

        // if object cached, and cache is fresh, return cached obj
        if ($cache_status == 'HIT') {
            $rss = $cache->get($cache_key);
            if (isset($rss) && $rss) {
                // should be cache age
                $rss->from_cache = 1;

                return $rss;
            }
        }

        // else attempt a conditional get

        // setup headers
        if ($cache_status == 'STALE') {
            $rss = $cache->get($cache_key);
            if (isset($rss) && $rss && isset($rss->eta) && isset($rss->last_modified) && $rss->etag && $rss->last_modified) {
                $request_headers['If-None-Match'] = $rss->etag;
                $request_headers['If-Last-Modified'] = $rss->last_modified;
            }
        }

        $resp = new http_doc();

        $resp->fetch_remote_file($url, $request_headers, $username, $password);

        if ($resp->get_status() == '304') {
            // we have the most current copy
            // reset cache on 304 (at minutillo insistent prodding)
            try {
                $cache->set($cache_key, $rss);
            } catch (exception $e) {
                write_log('Setting cached item failed: '. $e->getmessage());
            }

            return $rss;
        } elseif (is_success($resp->get_status())) {
            $rss = $resp->response_to_rss();
            if ($rss) {
                // add object to cache
                try {
                    $cache->set( $cache_key, $rss);
                } catch (exception $e) {
                    write_log('Setting cached item failed: '. $e->getmessage());
                }

                return $rss;
            }
        } else {
            $errormsg = "Failed to fetch $url ";
            if ( $resp->get_status() == '-100' )
                $errormsg .= '(Request timed out after '. self::MAGPIE_FETCH_TIME_OUT . ' seconds)';
            elseif ($resp->get_error())
                $errormsg .= "(HTTP Error: $http_error)";
            else
                $errormsg .= '(HTTP Response: '. $resp->get_response_code(). ')';
        }

        // attempt to return cached object
        if ($rss) {
            write_log('Returning STALE object for '. $url, LOG_NOTICE);

            return $rss;
        }

        // else we totally failed
        throw new exception($errormsg, ERR_MAGPIE_FAILED);

        return FALSE;// not needed really

        } // end fetch_rss()
}

class http_doc
{
    private $headers = array();
    private $status = 0;
    private $error = '';
    private $results = '';
    private $response_code = 0;

    public function get_response_code()
    {
        return $this->response_code;
    }
    public function get_error ()
    {
        return $this->error;
    }
    public function get_status()
    {
        return $this->status;
    }

/*=======================================================================*\
    Function:   _fetch_remote_file
    Purpose:    retrieve an arbitrary remote file
    Input:      url of the remote file
        headers to send along with the request (optional)
    Output:     an HTTP response object
\*=======================================================================*/
    public function fetch_remote_file ($url, array $headers=array(), $username='', $password='')
    {
        assert (is_string($url));
        $hdr = array();
        foreach ($headers as $key => $val) {
            $hdr[] = "$key: $val\n\r";
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, fetch_rss::MAGPIE_USER_AGENT);
        curl_setopt($ch, CURLOPT_TIMEOUT, fetch_rss::MAGPIE_FETCH_TIME_OUT);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, fetch_rss::MAGPIE_FETCHCONNECT_TIME_OUT);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $hdr);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if ($username != '' && $password != '') {
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        }
        $output = curl_exec($ch);

        if ($output === FALSE) {
            $c_err = curl_error($ch);
            curl_close ($ch);
            throw new exception('RSS feed could not be retrieved: '. $c_err, ERR_MAGPIE_FAILED);
        }

        $this->status = curl_getinfo ($ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $this->headers = explode("\n", substr($output, 0, $header_size));
        $this->results = substr( $output, $header_size );
        foreach ($this->headers as &$h) {
            $h = trim($h, "\n\r \t");
        }
        $this->response_code = $this->headers[0];
        curl_close ($ch);

    }

/*=======================================================================*\
    Function:   _response_to_rss
    Purpose:    parse an HTTP response object into an RSS object
    Input:      an HTTP response object (see Snoopy)
    Output:     parsed RSS object (see rss_parse)
\*=======================================================================*/
    public function response_to_rss ()
    {
        $rss = new MagpieRSS( $this->results, fetch_rss::MAGPIE_OUTPUT_ENCODING, NULL, TRUE );

        // if RSS parsed successfully
        if ($rss) {
            // find Etag, and Last-Modified
            foreach ($this->headers as $h) {
                // 2003-03-02 - Nicola Asuni (www.tecnick.com) - fixed bug "Undefined offset: 1"
                if (strpos($h, ': '))
                    list($field, $val) = explode(': ', $h, 2);
                else {
                    $field = $h;
                    $val = '';
                }
                if ( $field == 'ETag' )
                    $rss->etag = $val;
                if ( $field == 'Last-Modified' )
                    $rss->last_modified = $val;
            }

            return $rss;
        } else {// else construct error message
            $errormsg = 'Failed to parse RSS file.';
            if ($rss)
                $errormsg .= ' ('. $rss->ERROR. ')';

            throw new exception($errormsg, ERR_MAGPIE_FAILED);

        } // end if ($rss and !$rss->error)
    }

};

// NOTE: the following code should really be in Snoopy, or at least
// somewhere other then rss_fetch!

/*=======================================================================*\
    HTTP STATUS CODE PREDICATES
    These functions attempt to classify an HTTP status code
    based on RFC 2616 and RFC 2518.

    All of them take an HTTP status code as input, and return true or false

    All this code is adapted from LWP's HTTP::Status.
\*=======================================================================*/

/*=======================================================================*\
    Function:   is_info
    Purpose:    return true if Informational status code
\*=======================================================================*/
function is_info ($sc)
{
    assert (is_numeric($sc));

    return $sc >= 100 && $sc < 200;
}

/*=======================================================================*\
    Function:   is_success
    Purpose:    return true if Successful status code
\*=======================================================================*/
function is_success ($sc)
{
    assert (is_numeric($sc));

    return $sc >= 200 && $sc < 300;
}

/*=======================================================================*\
    Function:   is_redirect
    Purpose:    return true if Redirection status code
\*=======================================================================*/
function is_redirect ($sc)
{
    assert (is_numeric($sc));

    return $sc >= 300 && $sc < 400;
}

/*=======================================================================*\
    Function:   is_error
    Purpose:    return true if Error status code
\*=======================================================================*/
function is_error ($sc)
{
    assert (is_numeric($sc));

    return $sc >= 400 && $sc < 600;
}

/*=======================================================================*\
    Function:   is_client_error
    Purpose:    return true if Error status code, and its a client error
\*=======================================================================*/
function is_client_error ($sc)
{
    assert (is_numeric($sc));

    return $sc >= 400 && $sc < 500;
}

/*=======================================================================*\
    Function:   is_client_error
    Purpose:    return true if Error status code, and its a server error
\*=======================================================================*/
function is_server_error ($sc)
{
    assert (is_numeric($sc));

    return $sc >= 500 && $sc < 600;
}
