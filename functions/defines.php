<?php
/*
 *  This file is part of Urd.
 *  vim:ts=4:expandtab:cindent
 *
 *  Urd is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  Urd is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program. See the file "COPYING". If it does not
 *  exist, see <http://www.gnu.org/licenses/>.
 *
 * $LastChangedDate: 2014-06-08 00:30:19 +0200 (zo, 08 jun 2014) $
 * $Rev: 3087 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: defines.php 3087 2014-06-07 22:30:19Z gavinspearhead@gmail.com $
 */

// username for the superuser

define ('DB_VERSION',               72);

define ('URDD_DOWNLOAD_LOCKFILE',   '.urdd_lock');
define ('URDD_PORT',                11666);

define ('MIN_PHP_VERSION',          '5.3.0');
define ('RECOMMENDED_PHP_VERSION',  '5.6.16');

define ('SERVER_CONNECTION_TIMEOUT', 300);

// Error codes
define('NO_ERROR',                  0);
define('GROUP_NOT_FOUND',           1);
define('DB_FAILURE',                2);
define('UNKNOWN_ACTION',            3);
define('FEED_NOT_FOUND',            4);
define('INTERNAL_FAILURE',          5);
define('CONFIG_ERROR',              6);
define('SOCKET_FAILURE',            7);
define('COMMANDLINE_ERROR',         8);
define('QUEUE_ERROR',               9);
define('NNTP_NOT_CONNECTED_ERROR',  10);
define('COULD_NOT_CHECK_VERSION',   11);
define('URDLAND_CONNECT_ERROR',     12);
define('FILE_NOT_FOUND',            13);
define('DB_LOCKED',                 14);
define('NOT_ALLOWED',               15);
define('UNKNOWN_ERROR',             16);
define('RESTART_URDD',              17);
define('POST_FAILURE',              18);
define('FILE_NOT_CREATED',          19);
define('ENCRYPTED_RAR',             20);
define('NNTP_AUTH_ERROR',           21);
define('RESTART_DOWNLOAD',          22); //used when there are still par2 files available and we should try to download those
define('HTTP_CONNECT_ERROR',        23); //
define('GETARTICLE_ERROR',          24); //
define('SERVER_INACTIVE',           25);
define('PIPE_ERROR',                26);
define('SIGNAL_TERM',               27);

// debug values
define('DEBUG_ALL',         255); // all debug messages
define('DEBUG_MAIN',        1);  // everything in the main loop or before it
define('DEBUG_WORKER',      2); // all messages that are in forked processes
define('DEBUG_SERVER',      4); // all messages that are in the main process... stuff called from the main loop
define('DEBUG_SIGNAL',      8); // all messages that are in the signal handlers
define('DEBUG_DATABASE',    16); // all messages that are in the database classes
define('DEBUG_NNTP',        32); // all messages that are in the nntp classes
define('DEBUG_CLIENT',      64); // all messages that are in the (web) client
define('DEBUG_HTTP',        128);

// thread error codes
// values determine order in transfer page:
define('DOWNLOAD_READY',        0); // ready to put on queue
define('DOWNLOAD_ACTIVE',       1); // downloading
define('DOWNLOAD_QUEUED',       2); // put on queue
define('DOWNLOAD_PAUSED',       3); // download paused
define('DOWNLOAD_FINISHED',     4); // download succeeded and rar/par successful
define('DOWNLOAD_CANCELLED',    5); // download cancelled
define('DOWNLOAD_STOPPED',      6); // download is terminated, but requeued
define('DOWNLOAD_SHUTDOWN',     7); // used when process is sent kill term command
define('DOWNLOAD_COMPLETE',     8); // download succeeded but need to run par/unrar now
define('DOWNLOAD_RAR_FAILED',   9); // download but rar failed
define('DOWNLOAD_CKSFV_FAILED', 10); // download but rar failed
define('DOWNLOAD_PAR_FAILED',   11); // download but par2 failed
define('DOWNLOAD_FAILED',       12); // Article could not be dled or complete dl failed (esp preview).
define('DOWNLOAD_CANCELLED_PW', 254); // download cancelled
define('DOWNLOAD_ERROR',        255); // an error occurred
define('DOWNLOAD_IS_PAR_FILE',  13); // the article is a par2 file, skipped for now

define('POST_READY',            0); // ready to put on queue
define('POST_RARRED',           1); // rar complete
define('POST_PARRED',           2); // par2 complete
define('POST_YYENCODED',        3); // yyencode complete
define('POST_ACTIVE',           11); // posting
define('POST_QUEUED',           12); // put on queue
define('POST_PAUSED',           13); // posting paused
define('POST_FINISHED',         14  ); // posting succeeded
define('POST_CANCELLED',        25); // post cancelled
define('POST_STOPPED',          26); // post is terminated, but requeued
define('POST_SHUTDOWN',         27); // used when process is sent kill term command
define('POST_RAR_FAILED',       29); // POST could not succeed, rar failed
define('POST_PAR_FAILED',       30); // POST could not succeed, par2 failed
define('POST_YYENCODE_FAILED',  31); // POST could not succeed, yyencode faild
define('POST_FAILED',           32); // posting to the server failed
define('POST_ERROR',            255); // an error occurred

// Queue info status
define('QUEUE_QUEUED',      'Queued');
define('QUEUE_FINISHED',    'Finished');
define('QUEUE_FAILED',      'Failed');
define('QUEUE_RUNNING',     'Running');
define('QUEUE_PAUSED',      'Paused');
define('QUEUE_CANCELLED',   'Cancelled');
define('QUEUE_CRASH',       'Crashed');
define('QUEUE_REMOVED',     'Removed');

class download_types
{
    const NORMAL    = 1;
    const PREVIEW   = 2;
    const NZB       = 3;
}

class file_extensions
{
    const RAR_EXT = 'rar';
    const ACE_EXT = 'ace';
    const ZIP_EXT = 'zip';
    const ARJ_EXT = 'arj';
    const ZR7_EXT = '7z'; // 7zr.. but can't start with a 7
    const PAR_EXT = 'par2';
    const SFV_EXT = 'svf';
    const CAT_EXT = 'cat';
    const UUE_EXT = 'urd_uuencoded_part';

    public static $archives = array (self::RAR_EXT, self::ACE_EXT, self::ZIP_EXT, self::ARJ_EXT, self::ZR7_EXT);
}

define ('DEFAULT_PER_PAGE', 50);

define ('MIN_PASSWORD_LENGTH', 8);
define ('MAX_DL_NAME', 42);

class batch_size
{
    const DL_BATCH_SIZE   = 8;
    const POST_BATCH_SIZE = 5;
    const PV_BATCH_SIZE   = 4;
}

define ('MAX_PREVIEW_SIZE', 2 * 1024 * 1024); // in MB
define ('MAX_NNTP_THREADS', 8);
define ('MAX_THREADS', 10);
define ('MAX_DB_INTENSIVE_THREADS', 1);

define ('TEXT_BOX_SIZE', 40);
define ('NUMBER_BOX_SIZE', 5);

class user_status
{
    // users status
    const USER_INACTIVE = 0;
    const USER_ACTIVE   = 1;
    const USER_PENDING  = 2;

    const USER_USER     = 0;
    const USER_ADMIN    = 1;

    const SUPER_USER    = 'root';
    const SUPER_USERID  = 0;
}


class newsgroup_status {
    const NG_SUBSCRIBED   = 1;
    const NG_UNSUBSCRIBED = 0;
}


class rssfeed_status {
    const RSS_SUBSCRIBED   = 1; 
    const RSS_UNSUBSCRIBED = 0;
}

define ('VERSION_CHECK_URL',    'http://www.urdland.com/checkversion.php');
define ('DOWNLOAD_URL',         'http://www.urdland.com/');

class update_types
{
    const NO_UPDATE         = 0;
    const NEW_VERSION       = 1;
    const SECURITY_FIX      = 2;
    const BUG_FIX           = 4;
    const NEW_FEATURE       = 8;
    const OTHER             = 128;
}

define ('DEFAULT_PRIORITY', 50);

define ('URD_NOERROR',      0);
define ('URD_FILENOTFOUND', 1);
define ('URD_UNKNOWNERROR', 2);

define ('DEFAULT_TEMPLATE',     'default');
define ('DEFAULT_STYLESHEET',   'light');
define ('DEFAULT_LANGUAGE',     'english.php');
define ('DEFAULT_INDEX_PAGE',   'manual');

define ('GROUPS_FILE', '/etc/group');

define ('PREVIEW_PATH',      'preview/');
define ('TMP_PATH',          'tmp/');
define ('DONE_PATH',         'done/');
define ('NZB_PATH',          'nzb/');
define ('SPOOL_PATH',        'spool/');
define ('POST_PATH',         'post/');
define ('SCRIPTS_PATH',      'scripts/');
define ('CACHE_PATH',        '.cache/');
define ('MAGPIE_CACHE_PATH', '.cache/magpie/');
define ('IMAGE_CACHE_PATH',  '.cache/image/');
define ('FILELIST_CACHE_PATH',  '.cache/filelists/');

$yes = array ('1', 'TRUE', 'ON', 'YES');
$no  = array ('0', 'FALSE', 'OFF', 'NO');

$log_str = array (
    LOG_DEBUG =>    'DEBUG',
    LOG_INFO =>     'INFO',
    LOG_NOTICE =>   'NOTICE',
    LOG_WARNING =>  'WARNING',
    LOG_ERR =>      'ERROR',
    LOG_CRIT =>     'CRITICAL',
    LOG_ALERT =>    'ALERT',
    LOG_EMERG =>    'EMERGENCY'
);

define ('DEFAULT_USENET_SERVER_PRIORITY', 10);
define ('DISABLED_USENET_SERVER_PRIORITY', 0);

define ('URDD_NOERROR',     0);
define ('URDD_CLOSE_CONN',  1);
define ('URDD_NOCOMMAND',   2);
define ('URDD_SHUTDOWN',    3);
define ('URDD_ERROR',       4);
define ('URDD_RESTART',     5);

class stat_actions
{
    const UNUSED        = 0;
    const DOWNLOAD      = 1;
    const PREVIEW       = 2;
    const UPDATE        = 3;
    const EXPIRE        = 4;
    const PURGE         = 5;
    const WEBVIEW       = 6;
    const IMPORTNZB     = 7;
    const GETNZB        = 8;
    const POST          = 9;
    const SENDEXTSET    = 10;
    const GETEXTSET     = 10;
    const SPOT_COUNT    = 11;
    const SET_COUNT     = 12;
    const RSS_COUNT     = 13;
    const POST_MSG_COUNT= 14;
    const POST_SPOT_COUNT= 15;
}

define('USERSETTYPE_GROUP', 0);
define('USERSETTYPE_RSS',   1);
define('USERSETTYPE_SPOT',  2);

define('ESI_NOT_COMMITTED', 0);
define('ESI_COMMITTED',     1);

define('URDD_SCRIPT_EXT', 'urdd_sh');

define ('MAX_EXPIRE_TIME',              365);
define ('DEFAULT_EXPIRE_TIME',          5);
define ('DEFAULT_SPOTS_EXPIRE_TIME',    365);
define ('MAX_HEADERS',                  20000);
define ('QUEUE_SIZE',                   20000);

define ('MAX_RECONNECTION_ATTEMPTS_PER_THREAD', 15);

define ('PASSWORD_PLACE_HOLDER', '__URDD_PASSWORD__');

define ('ADULT_ON',      1);
define ('ADULT_OFF',     255);
define ('ADULT_DEFAULT', 0);

class blacklist
{
    const BLACKLIST_EXTERNAL = 1;
    const BLACKLIST_INTERNAL = 2;

    const NONACTIVE = 0;
    const ACTIVE = 1;
    const DISABLED = 255;
}

class whitelist
{
    const WHITELIST_EXTERNAL = 1;
    const WHITELIST_INTERNAL = 2;
    const NONACTIVE = 0;
    const ACTIVE = 1;
    const DISABLED = 255;
}

class encrar
{
    const ENCRAR_CONTINUE = 0;
    const ENCRAR_CANCEL   = 1;
    const ENCRAR_PAUSE    = 2;
}

class basket_type
{
    const LARGE = 1;
    const SMALL = 2;
}

class spot_view
{
    const CLASSIC = 1;
    const MODERN  = 2;
}
