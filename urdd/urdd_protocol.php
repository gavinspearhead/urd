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
 *  along with this program. See the file 'COPYING'. If it does not
 *  exist, see <http://www.gnu.org/licenses/>.
 *
 * $LastChangedDate: 2014-02-15 00:27:46 +0100 (za, 15 feb 2014) $
 * $Rev: 3008 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: urdd_protocol.php 3008 2014-02-14 23:27:46Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

class urdd_protocol
{
    const COMMAND_REPEAT_LAST_COMMAND = 1;
    const COMMAND_ADDDATA = 46;
    const COMMAND_ADDSPOTDATA = 67;
    const COMMAND_CANCEL = 2;
    const COMMAND_CHECK_VERSION = 37;
    const COMMAND_CLEANDB = 32;
    const COMMAND_CLEANDIR = 34;
    const COMMAND_CONTINUE = 3;
    const COMMAND_DISKFREE = 5;
    const COMMAND_DELETE_SET = 56;
    const COMMAND_DELETE_SET_RSS = 57;
    const COMMAND_DELETE_SPOT = 64;
    const COMMAND_DOWNLOAD = 7;
    const COMMAND_DOWNLOAD_ACTION = 43;
    const COMMAND_ECHO = 8;
    const COMMAND_EXIT = 10;
    const COMMAND_EXPIRE = 11;
    const COMMAND_EXPIRE_RSS = 51;
    const COMMAND_EXPIRE_SPOTS = 65;
    const COMMAND_FINDSERVERS = 54;
    const COMMAND_GENSETS = 45;
    const COMMAND_GETBLACKLIST = 68;
    const COMMAND_GETWHITELIST = 71;
    const COMMAND_GETNFO = 62;
    const COMMAND_GETSETINFO = 42;
    const COMMAND_GETSPOTS = 63;
    const COMMAND_GETSPOT_REPORTS = 69;
    const COMMAND_GETSPOT_COMMENTS = 70;
    const COMMAND_GETSPOT_IMAGES = 72;
    const COMMAND_GROUP = 12;
    const COMMAND_GROUPS = 13;
    const COMMAND_HELP = 14;
    const COMMAND_MAKE_NZB = 47;
    const COMMAND_MERGE_SETS = 53;
    const COMMAND_MOVE = 44;
    const COMMAND_NOOP = 15;
    const COMMAND_OPTIMISE = 16;
    const COMMAND_PARSE_NZB = 48;
    const COMMAND_PASS = 17;
    const COMMAND_PAUSE = 18;
    const COMMAND_POST = 58;
    const COMMAND_POST_ACTION = 59;
    const COMMAND_POST_MESSAGE = 61;
    const COMMAND_PREEMPT = 29;
    const COMMAND_PRIORITY = 39;
    const COMMAND_PURGE = 19;
    const COMMAND_PURGE_RSS = 52;
    const COMMAND_PURGE_SPOTS = 66;
    const COMMAND_QUIT = 20;
    const COMMAND_RESTART = 55;
    const COMMAND_SCHEDULE = 21;
    const COMMAND_SENDSETINFO = 41;
    const COMMAND_SET = 40;
    const COMMAND_SHOW = 22;
    const COMMAND_SHUTDOWN = 23;
    const COMMAND_START_POST = 60;
    const COMMAND_STOP = 30;
    const COMMAND_SUBSCRIBE = 26;
    const COMMAND_SUBSCRIBE_RSS = 50;
    const COMMAND_UNPAR_UNRAR = 31;
    const COMMAND_UNSCHEDULE = 27;
    const COMMAND_UPDATE = 28;
    const COMMAND_UPDATE_RSS = 49;
    const COMMAND_USER = 36;
    const COMMAND_WHOAMI = 73; // always keep this the highest number !!

    private static $responses = array (
        // single line responses
        200 => '200 Command ok.',
        201 => '201 [%s] Command ok.',
        202 => '202 %s Command ok.',
        210 => '210 (%d) %s Download created.',
        221 => '221 Goodbye.',
        222 => '222 Shutting down.',
        223 => '223 Restarting.',
        231 => '231 Ok.',
        240 => '240 User logged in. Proceed.',
        299 => '299 Urdd version %s.',

        331 => '331 User name okay, need password.',
        332 => '332 Need account for login.',

        401 => '401 NNTP server not available.',
        402 => '402 Queue full.',
        403 => '403 Already queued.',
        404 => '404 %s Queue full; some actions scheduled.',
        405 => '405 Creating download failed.',
        406 => '406 Already running.',
        410 => '410 NNTP Connections disabled.',

        500 => '500 Syntax error: command not recognised: %s.',
        501 => '501 Syntax error: argument not recognised.',
        502 => '502 Command not implemented.',
        503 => '503 An error occured %s.',
        504 => '504 Required module not loaded: %s.',
        510 => '510 Task not found.',
        511 => '511 Job not found.',
        512 => '512 Download not found.',
        513 => '513 Set not found.',
        520 => '520 No groups found.',
        521 => '521 Invalid timestamp.',
        522 => '522 Invalid recurrence.',
        530 => '530 Not logged in.',
        531 => '531 Invalid username or password.',
        532 => '532 Requires admin privileges.',
        533 => '533 Function disabled by administrator.',
        534 => '534 Posting not allowed.',
        599 => '599 Internal error.',

        // multi line responses
        251 => '251 Urdd server status.',
        252 => '252 The following commands are recognised.',
        253 => '253 Showing',
        254 => '254 Echo:',
        255 => '255 Debug info:',
        256 => '256 Diskspace available:',
        257 => '257 Help for command:',
        258 => '258 Group information:',
        259 => '259 Uptime:',
        260 => '260 Server time:',
        261 => '261 User name:',
        262 => '262 Version:',
        263 => '263 Diskspace percentage:'
    );

    public static function get_response($code)
    {
        return isset(self::$responses[$code]) ? self::$responses[$code] . "\n" : NULL;
    }
}
