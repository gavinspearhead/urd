<?php

/**
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
 * $LastChangedDate: 2013-09-03 23:50:58 +0200 (di, 03 sep 2013) $
 * $Rev: 2911 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: queue_functions.php 2911 2013-09-03 21:50:58Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathqf = realpath(dirname(__FILE__));

require_once "$pathqf/../functions/autoincludes.php";
require_once "$pathqf/../functions/defines.php";
require_once "$pathqf/../config.php";
require_once "$pathqf/../functions/functions.php";
require_once "$pathqf/urdd_command.php";
require_once "$pathqf/urdd_protocol.php";
require_once "$pathqf/urdd_error.php";
require_once "$pathqf/../functions/urd_log.php";

function queue_generic(DatabaseConnection $db, server_data &$servers, $userid, $command, $arg, $priority=DEFAULT_PRIORITY, $restart = TRUE, $paused=FALSE)
{
    assert(is_numeric($priority) && is_numeric($userid) && is_bool($restart));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    try {
        $username = get_username($db, $userid);
        $item = new action($command, $arg, $username, $userid, $paused);
    } catch (exception $e) {
        return urdd_protocol::get_response(500); // should not happen....
    }
    if ($servers->has_equal($item)) {
        $response = urdd_protocol::get_response(403);
    } else {
        $res = $servers->queue_push($db, $item, TRUE, server_data::QUEUE_BOTTOM, $priority);
        if ($restart === FALSE) {
            update_queue_norestart($db, $item->get_dbid());
        }
        if ($res !== FALSE) {
            $response = sprintf(urdd_protocol::get_response(201), $item->get_id());
        } else {
            $response = urdd_protocol::get_response(402);
        }
    }

    return $response;
}

function queue_addspotdata(DatabaseConnection $db, server_data &$servers, array $arg_list, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority)  && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    // TODO set database/servers to lock download
    if (isset($arg_list[0]) && is_numeric($arg_list[0]) && isset($arg_list[1])) {
        $username = get_username($db, $userid);
        $item = new action(urdd_protocol::COMMAND_ADDSPOTDATA, implode (' ', $arg_list), $username, $userid);
        if ($servers->has_equal($item)) {
            $response = urdd_protocol::get_response(403);
        } else {
            $item->set_priority($priority, user_status::SUPER_USERID);
            $res = $servers->queue_push($db, $item, TRUE, server_data::QUEUE_BOTTOM);
            if ($res !== FALSE) {
                inc_dl_lock($db, $arg_list[0]); // lock the dowload so it won't start until it is unlocked and all setdata is added to the article tabels
                $response = sprintf(urdd_protocol::get_response(201), $item->get_id());
            } else {
                $response = urdd_protocol::get_response(402);
            }
        }
    } else {
        $response = urdd_protocol::get_response(501);
    }

    return $response;
}


function queue_adddata(DatabaseConnection $db, server_data &$servers, array $arg_list, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $preview = FALSE;
    // TODO set database/servers to lock download
    if (isset($arg_list[0]) && is_numeric($arg_list[0]) && isset($arg_list[1]) && isset($arg_list[2])) {
        $username = get_username($db, $userid);
        $item = new action(urdd_protocol::COMMAND_ADDDATA, implode (' ', $arg_list), $username, $userid);
        if ((isset($arg_list[3]) && strtolower($arg_list[3]) == 'preview') || (isset($arg_list[4]) && strtolower($arg_list[4]) == 'preview')) {
            $preview = TRUE;
            $item->set_preview(TRUE);
        }
        if ($servers->has_equal($item)) {
            $response = urdd_protocol::get_response(403);
        } else {
            $priority = $preview ? 1 : $priority;
            $item->set_priority($priority, user_status::SUPER_USERID);
            $res = $servers->queue_push($db, $item, TRUE, server_data::QUEUE_BOTTOM);
            if ($res !== FALSE) {
                inc_dl_lock($db, $arg_list[0]); // lock the dowload so it won't start until it is unlocked and all setdata is added to the article tabels
                $response = sprintf(urdd_protocol::get_response(201), $item->get_id());
            } else {
                $response = urdd_protocol::get_response(402);
            }
        }
    } else {
        $response = urdd_protocol::get_response(501);
    }

    return $response;
}

function queue_post(DatabaseConnection $db, server_data &$servers, $arg, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);

        return queue_generic($db, $servers, $userid, urdd_protocol::COMMAND_START_POST, $arg, $priority);
}

function queue_prepare_post(DatabaseConnection $db, server_data &$servers, $arg, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid) );
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);

    return queue_generic($db, $servers, $userid, urdd_protocol::COMMAND_POST, $arg, $priority, TRUE, TRUE);
}

function queue_post_message(DatabaseConnection $db, server_data &$servers, $arg, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);

    return queue_generic($db, $servers, $userid, urdd_protocol::COMMAND_POST_MESSAGE, $arg, $priority, TRUE, FALSE);
}

function queue_delete_spot(DatabaseConnection $db, server_data &$servers, $arg, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);

    return queue_generic($db, $servers, $userid, urdd_protocol::COMMAND_DELETE_SPOT, $arg, $priority);
}

function queue_delete_set(DatabaseConnection $db, server_data &$servers, $arg, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);

    return queue_generic($db, $servers, $userid, urdd_protocol::COMMAND_DELETE_SET, $arg, $priority);
}

function queue_delete_set_rss(DatabaseConnection $db, server_data &$servers, $arg, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);

    return queue_generic($db, $servers, $userid, urdd_protocol::COMMAND_DELETE_SET_RSS, $arg, $priority);
}

function queue_merge_sets(DatabaseConnection $db, server_data &$servers, $arg, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);

    return queue_generic($db, $servers, $userid, urdd_protocol::COMMAND_MERGE_SETS, $arg, $priority);
}

function queue_cleandir(DatabaseConnection $db, server_data &$servers, $arg, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority)  && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);

    return queue_generic($db, $servers, $userid, urdd_protocol::COMMAND_CLEANDIR, $arg, $priority);
}

function queue_cleandb(DatabaseConnection $db, server_data &$servers, $arg, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);

    return queue_generic($db, $servers, $userid, urdd_protocol::COMMAND_CLEANDB, $arg, $priority, FALSE);
}

function queue_optimise(DatabaseConnection $db, server_data &$servers, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);

    return queue_generic($db, $servers, $userid, urdd_protocol::COMMAND_OPTIMISE, '', $priority);
}

function queue_getspot_comments(DatabaseConnection $db, server_data &$servers, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $username = get_username($db, $userid);
    $item1 = new action(urdd_protocol::COMMAND_PURGE_SPOTS, '', $username, $userid);
    $item2 = new action(urdd_protocol::COMMAND_GETSPOT_COMMENTS, '', $username, $userid);
    if ($servers->has_equal($item1) || $servers->has_equal($item2)) {
        return urdd_protocol::get_response(403);
    }

    return queue_generic($db, $servers, $userid, urdd_protocol::COMMAND_GETSPOT_COMMENTS, '', $priority);
}

function queue_getspot_reports(DatabaseConnection $db, server_data &$servers, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $username = get_username($db, $userid);
    $item1 = new action(urdd_protocol::COMMAND_PURGE_SPOTS, '', $username, $userid);
    $item2 = new action(urdd_protocol::COMMAND_GETSPOT_REPORTS, '', $username, $userid);
    if ($servers->has_equal($item1) || $servers->has_equal($item2) ) {
        return urdd_protocol::get_response(403);
    }

    return queue_generic($db, $servers, $userid, urdd_protocol::COMMAND_GETSPOT_REPORTS, '', $priority);
}

function queue_getspot_images(DatabaseConnection $db, server_data &$servers, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority)  && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $username = get_username($db, $userid);
    $item1 = new action(urdd_protocol::COMMAND_PURGE_SPOTS, '', $username, $userid);
    $item3 = new action(urdd_protocol::COMMAND_GETSPOT_IMAGES, '', $username, $userid);
    if ($servers->has_equal($item1) || $servers->has_equal($item3)) {
        return urdd_protocol::get_response(403);
    }

    return queue_generic($db, $servers, $userid, urdd_protocol::COMMAND_GETSPOT_IMAGES, '', $priority);
}

function queue_getspots(DatabaseConnection $db, server_data &$servers, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $username = get_username($db, $userid);
    $item1 = new action(urdd_protocol::COMMAND_EXPIRE_SPOTS, '', $username, $userid);
    $item2 = new action(urdd_protocol::COMMAND_PURGE_SPOTS, '', $username, $userid);
    $item3 = new action(urdd_protocol::COMMAND_GETSPOTS, '', $username, $userid);
    $item4 = new action(urdd_protocol::COMMAND_GETSPOT_COMMENTS, '', $username, $userid);
    $item5 = new action(urdd_protocol::COMMAND_GETSPOT_REPORTS, '', $username, $userid);
    if ($servers->has_equal($item1) || $servers->has_equal($item2) || $servers->has_equal($item3)|| $servers->has_equal($item5) || $servers->has_equal($item4)) {
        return urdd_protocol::get_response(403);
    }

    return queue_generic($db, $servers, $userid, urdd_protocol::COMMAND_GETSPOTS, '', $priority);
}

function queue_expire_spots(DatabaseConnection $db, server_data &$servers, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $username = get_username($db, $userid);

    $item1 = new action(urdd_protocol::COMMAND_EXPIRE_SPOTS, '', $username, $userid);
    $item2 = new action(urdd_protocol::COMMAND_PURGE_SPOTS, '', $username, $userid);
    $item3 = new action(urdd_protocol::COMMAND_GETSPOTS, '', $username, $userid);
    if ($servers->has_equal($item1) || $servers->has_equal($item2) || $servers->has_equal($item3)) {
        return urdd_protocol::get_response(403);
    }

    return queue_generic($db, $servers, $userid, urdd_protocol::COMMAND_EXPIRE_SPOTS, '', $priority);
}

function queue_purge_spots(DatabaseConnection $db, server_data &$servers, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $username = get_username($db, $userid);

    $item1 = new action(urdd_protocol::COMMAND_EXPIRE_SPOTS, '', $username, $userid);
    $item2 = new action(urdd_protocol::COMMAND_PURGE_SPOTS, '', $username, $userid);
    $item3 = new action(urdd_protocol::COMMAND_GETSPOTS, '', $username, $userid);
    if ($servers->has_equal($item1) || $servers->has_equal($item2) || $servers->has_equal($item3)) {
        return urdd_protocol::get_response(403);
    }

    return queue_generic($db, $servers, $userid, urdd_protocol::COMMAND_PURGE_SPOTS, '', $priority);
}

function queue_getwhitelist(DatabaseConnection $db, server_data &$servers, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $username = get_username($db, $userid);
    $item = new action(urdd_protocol::COMMAND_GETWHITELIST, '', $username, $userid);
    if ($servers->has_equal($item)) {
        return urdd_protocol::get_response(403);
    }

    return queue_generic($db, $servers, $userid, urdd_protocol::COMMAND_GETWHITELIST, '', $priority);
}

function queue_getblacklist(DatabaseConnection $db, server_data &$servers, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $username = get_username($db, $userid);
    $item = new action(urdd_protocol::COMMAND_GETBLACKLIST, '', $username, $userid);
    if ($servers->has_equal($item)) {
        return urdd_protocol::get_response(403);
    }

    return queue_generic($db, $servers, $userid, urdd_protocol::COMMAND_GETBLACKLIST, '', $priority);
}

function queue_getnfo(DatabaseConnection $db, server_data &$servers, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $username = get_username($db, $userid);
    $item = new action(urdd_protocol::COMMAND_GETNFO, '', $username, $userid);
    if ($servers->has_equal($item)) {
        return urdd_protocol::get_response(403);
    }

    return queue_generic($db, $servers, $userid, urdd_protocol::COMMAND_GETNFO, '', $priority);
}

function queue_groups(DatabaseConnection $db, server_data &$servers, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);

    return queue_generic($db, $servers, $userid, urdd_protocol::COMMAND_GROUPS, '', $priority);
}

function queue_getsetinfo(DatabaseConnection $db, server_data &$servers, $userid, $priority=DEFAULT_PRIORITY)
{
    return queue_getsendsetinfo($db, urdd_protocol::COMMAND_GETSETINFO, $servers, $userid, $priority);
}

function queue_sendsetinfo(DatabaseConnection $db, server_data &$servers, $userid, $priority=DEFAULT_PRIORITY)
{
    return queue_getsendsetinfo($db, urdd_protocol::COMMAND_SENDSETINFO, $servers, $userid, $priority);
}

function queue_getsendsetinfo(DatabaseConnection $db, $cmd, server_data &$servers, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $username = get_username($db, $userid);
    $item = new action(urdd_protocol::COMMAND_SENDSETINFO, '', $username, $userid);
    if ($servers->has_equal($item)) {
        return urdd_protocol::get_response(403);
    }
    $curl_mod = extension_loaded('curl');
    if ($curl_mod === FALSE) {
        $err_str = '';
        if ($curl_mod === FALSE) {
            $err_str = 'curl ';
        }
        trim($err_str);

        return sprintf(urdd_protocol::get_response(504), $err_str);
    }

    return queue_generic($db, $servers, $userid, $cmd, '', $priority);
}

function queue_gensets(DatabaseConnection $db, server_data &$servers, array $arg_list, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    if (!isset($arg_list[0])) {
        $response = urdd_protocol::get_response(501);
    } elseif (is_numeric($arg_list[0])) {
        $username = get_username($db, $userid);
        $item = new action(urdd_protocol::COMMAND_GENSETS, $arg_list[0], $username, $userid);
        $item2 = new action(urdd_protocol::COMMAND_UPDATE, $arg_list[0], $username, $userid);
        $item3 = new action(urdd_protocol::COMMAND_PURGE, $arg_list[0], $username, $userid);
        $item4 = new action(urdd_protocol::COMMAND_EXPIRE, $arg_list[0], $username, $userid);
        if ($servers->has_equal($item) || $servers->has_equal($item2) || $servers->has_equal($item3) || $servers->has_equal($item4)) {
            $response = urdd_protocol::get_response(403);
        } else {
            $res = $servers->queue_push($db, $item, TRUE, server_data::QUEUE_BOTTOM, $priority);
            if ($res !== FALSE) {
                $response = sprintf(urdd_protocol::get_response(201), $item->get_id());
            } else {
                $response = urdd_protocol::get_response(402);
            }
        }
    } elseif (strtolower($arg_list[0]) == 'all') {
        $response = queue_gensets_all($db, $servers, $userid, $priority);
    } else {
        $response = urdd_protocol::get_response(501);
    }

    return $response;
}

function queue_parse_nzb(DatabaseConnection $db, server_data &$servers, array $arg_list, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    $url = $dlid = NULL;
    $start_time = 0;
    $last_idx = 0;
    if (isset($arg_list[0])) {
        if (is_numeric($arg_list[0])) {
            $dlid = $arg_list[0];
            if (isset($arg_list[1])) {
                $url = $arg_list[1];
                $last_idx = 1;
            }
        } else {
            $url = $arg_list[0];
        }
    }
    if (isset($arg_list[$last_idx + 1]) && is_numeric($arg_list[$last_idx + 1])) {
        $start_time = $arg_list[$last_idx + 1];
        if ($start_time < time()) {
            $start_time = 0;
        }
    }
    if ($url !== NULL) {
        $username = get_username($db, $userid);
        if ($dlid === NULL) {
            list($code, $dlid) = do_create_download($db, $servers, $userid, NULL, FALSE);
            if ($start_time == 0) {
                set_start_time($db, $dlid, time());
            } else {
                set_start_time($db, $dlid, $start_time);
                $item_unpause = new action (urdd_protocol::COMMAND_CONTINUE, get_command(urdd_protocol::COMMAND_DOWNLOAD) . " $dlid", $username, $userid, TRUE);
                $job = new job($item_unpause, $start_time, NULL); //try again in XX secs
                $servers->add_schedule($db, $job);
            }
            if ($code != 210) {
                $response = urdd_protocol::get_response(402);

                return $response;
            }
        }
        $item = new action(urdd_protocol::COMMAND_PARSE_NZB, "$dlid '{$url}'", $username, $userid);
        $res = $servers->queue_push($db, $item, TRUE, server_data::QUEUE_BOTTOM, $priority);
        if ($res !== FALSE) {
            inc_dl_lock($db, $dlid); // lock the dowload so it won't start until it is unlocked and all setdata is added to the article tabels
            $response = sprintf(urdd_protocol::get_response(201), $item->get_id());
        } else {
            $response = urdd_protocol::get_response(402);
        }
    } else {
        $response = urdd_protocol::get_response(501);
    }

    return $response;
}


function queue_check_version(DatabaseConnection $db, server_data &$servers, $userid, $priority)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);

    return queue_generic($db, $servers, $userid, urdd_protocol::COMMAND_CHECK_VERSION, '', $priority);
}


function queue_purge_expire_all(DatabaseConnection $db, $cmd, server_data &$servers, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);

    if ($cmd == get_command_code(urdd_protocol::COMMAND_UPDATE_RSS) ||
        $cmd == get_command_code(urdd_protocol::COMMAND_PURGE_RSS) ||
        $cmd == get_command_code(urdd_protocol::COMMAND_EXPIRE_RSS)) {
        $ids = get_active_feeds($db);
    } elseif ($cmd == get_command_code(urdd_protocol::COMMAND_UPDATE) ||
        $cmd == get_command_code(urdd_protocol::COMMAND_PURGE) ||
        $cmd == get_command_code(urdd_protocol::COMMAND_EXPIRE)) {
        $ids = get_active_groups($db);
    }
    $id_str = '';
    // queue each one
    if ($ids !== FALSE) {
        $rv = TRUE;
        foreach ($ids as $arr) {
            $username = get_username($db, $userid);
            $item = new action($cmd, $arr, $username, $userid);
            if ($servers->has_equal($item)) {
                ; // what to do     $response = $responses[403];
            } else {
                $rv = $servers->queue_push($db, $item, TRUE, server_data::QUEUE_BOTTOM, $priority);
                if ($rv === FALSE) {
                    break;
                }
                $id_str .= "[{$item->get_id()}] ";
            }
        }
        if ($rv === FALSE) {
            $response = sprintf(urdd_protocol::get_response(404), $id_str);
        } else {
            $response = sprintf(urdd_protocol::get_response(202), $id_str);
        }
    } else {
        $response = urdd_protocol::get_response(520);
    }

    return $response;
}


function queue_gensets_all(DatabaseConnection $db, server_data &$servers, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    // find all subsribed newsgroups
    $groups = get_active_groups($db);
    // queue each one
    $id_str = '';
    if ($groups !== FALSE) {
        $rv = TRUE;
        foreach ($groups as $arr) {
            $username = get_username($db, $userid);
            $item = new action(urdd_protocol::COMMAND_GENSETS, $arr, $username, $userid);
            $item2 = new action(urdd_protocol::COMMAND_UPDATE, $arr, $username, $userid);
            $item3 = new action(urdd_protocol::COMMAND_EXPIRE, $arr, $username, $userid);
            $item4 = new action(urdd_protocol::COMMAND_PURGE, $arr, $username, $userid);
            if (!$servers->has_equal($item) && !$servers->has_equal($item2) && !$servers->has_equal($item3) && !$servers->has_equal($item4)) {
                $rv = $servers->queue_push($db, $item, TRUE, server_data::QUEUE_BOTTOM, $priority);
                if ($rv === FALSE) {
                    break;
                }
                $id_str .= "[{$item->get_id()}] ";
            } else {
                ; /// we do what now?
            }
        }
        if ($rv === FALSE) {
            $response = sprintf(urdd_protocol::get_response(404), $id_str);
        } else {
            $response = sprintf(urdd_protocol::get_response(202), $id_str);
        }
    } else {
        $response = urdd_protocol::get_response(520);
    }

    return $response;
}


function queue_update_all(DatabaseConnection $db, server_data &$servers, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    // find all subsribed newsgroups

    $groups = get_active_groups($db);
    // queue each one
    $id_str = '';
    if ($groups !== FALSE) {
        $rv = TRUE;
        foreach ($groups as $arr) {
            $username = get_username($db, $userid);
            $item = new action(urdd_protocol::COMMAND_UPDATE, $arr, $username, $userid);
            $item3 = new action(urdd_protocol::COMMAND_PURGE, $arr, $username, $userid);
            $item4 = new action(urdd_protocol::COMMAND_EXPIRE, $arr, $username, $userid);
            $item2 = new action(urdd_protocol::COMMAND_GENSETS, $arr, $username, $userid);
            if (!$servers->has_equal($item) && ! $servers->has_equal($item2) && !$servers->has_equal($item3) && !$servers->has_equal($item4)) {
                $rv = $servers->queue_push($db, $item, TRUE, server_data::QUEUE_BOTTOM, $priority);
                if ($rv === FALSE) {
                    break;
                }
                $id_str .= "[{$item->get_id()}] ";
            } else {
                ; /// we do what now?
            }
        }
        if ($rv === FALSE) {
            $response = sprintf(urdd_protocol::get_response(404), $id_str);
        } else {
            $response = sprintf(urdd_protocol::get_response(202), $id_str);
        }
    } else {
        $response = urdd_protocol::get_response(520);
    }

    return $response;
}


function queue_update_rss_all(DatabaseConnection $db, server_data &$servers, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    // find all subsribed newsgroups
    $feeds = get_active_feeds($db);
    // queue each one
    $id_str = '';

    if ($feeds !== FALSE) {
        $rv = TRUE;
        foreach ($feeds as $arr) {
            $username = get_username($db, $userid);
            $item = new action(urdd_protocol::COMMAND_UPDATE_RSS, $arr, $username, $userid);
            if (!$servers->has_equal($item)) {
                $rv = $servers->queue_push($db, $item, TRUE, server_data::QUEUE_BOTTOM, $priority);
                if ($rv === FALSE) {
                    break;
                }
                $id_str .= "[{$item->get_id()}] ";
            } else {
                ; /// we do what now?
            }
        }
        if ($rv === FALSE) {
            $response = sprintf(urdd_protocol::get_response(404), $id_str);
        } else {
            $response = sprintf(urdd_protocol::get_response(202), $id_str);
        }
    } else {
        $response = urdd_protocol::get_response(520);
    }

    return $response;
}


function queue_purge_expire(DatabaseConnection $db, $cmd, array $arg_list, $userid, server_data &$servers,$priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid) );
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        if (!isset($arg_list[0])) {
        $response = urdd_protocol::get_response(501);
    } elseif (is_numeric($arg_list[0])) {
        $username = get_username($db, $userid);
        $item = new action($cmd, $arg_list[0], $username, $userid);
        if ($cmd == urdd_protocol::COMMAND_PURGE || $cmd == urdd_protocol::COMMAND_UPDATE || $cmd == urdd_protocol::COMMAND_GENSETS || $cmd == urdd_protocol::COMMAND_EXPIRE) {
            $item1 = new action(urdd_protocol::COMMAND_UPDATE, $arg_list[0], $username, $userid);
            $item2 = new action(urdd_protocol::COMMAND_GENSETS, $arg_list[0], $username, $userid);
            $item3 = new action(urdd_protocol::COMMAND_PURGE, $arg_list[0], $username, $userid);
            $item4 = new action(urdd_protocol::COMMAND_EXPIRE, $arg_list[0], $username, $userid);
        } elseif ($cmd == urdd_protocol::COMMAND_PURGE_RSS || $cmd == urdd_protocol::COMMAND_UPDATE_RSS || $cmd == urdd_protocol::COMMAND_EXPIRE_RSS) {
            $item1 = new action(urdd_protocol::COMMAND_UPDATE_RSS, $arg_list[0], $username, $userid);
            $item3 = $item2 = new action(urdd_protocol::COMMAND_PURGE_RSS, $arg_list[0], $username, $userid);
            $item4 = new action(urdd_protocol::COMMAND_EXPIRE_RSS, $arg_list[0], $username, $userid);
        } else {
            $response = urdd_protocol::get_response(599);
        }
        if ($servers->has_equal($item1) || $servers->has_equal($item2) || $servers->has_equal($item3)|| $servers->has_equal($item4)) {
            $response = urdd_protocol::get_response(403);
        } else {
            $res = $servers->queue_push($db, $item, TRUE);
            if ($res !== FALSE) {
                $response = sprintf(urdd_protocol::get_response(201), $item->get_id());
            } else {
                $response = urdd_protocol::get_response(402);
            }
        }
    } elseif (strtolower($arg_list[0]) == 'all') {
        // find all subsribed newsgroups
        $response = queue_purge_expire_all($db, $cmd, $servers, $userid, $priority);

    } else {
        $response = urdd_protocol::get_response(501);
    }

    return $response;
}


function queue_update_rss(DatabaseConnection $db, $cmd, array $arg_list, $userid, server_data &$servers,$priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);

    if (!isset($arg_list[0])) {
        $response = urdd_protocol::get_response(501);
    } elseif (is_numeric($arg_list[0])) {
        $username = get_username($db, $userid);
        $item = new action($cmd, $arg_list[0], $username, $userid);
        $item2 = new action(urdd_protocol::COMMAND_PURGE_RSS, $arg_list[0], $username, $userid);
        $item3 = new action(urdd_protocol::COMMAND_EXPIRE_RSS, $arg_list[0], $username, $userid);
        if ($servers->has_equal($item)||$servers->has_equal($item2)||$servers->has_equal($item3)) {
            $response = urdd_protocol::get_response(403);
        } else {
            $res = $servers->queue_push($db, $item, TRUE, server_data::QUEUE_BOTTOM, $priority);
            if ($res !== FALSE) {
                $response = sprintf(urdd_protocol::get_response(201), $item->get_id());
            } else {
                $response = urdd_protocol::get_response(402);
            }
        }
    } elseif (strtolower($arg_list[0]) == 'all') {
        $response = queue_update_rss_all($db, $servers, $userid, $priority);
    } else {
        $response = urdd_protocol::get_response(501);
    }

    return $response;
}


function queue_update(DatabaseConnection $db, $cmd, array $arg_list, $userid, server_data &$servers,$priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    if (!isset($arg_list[0])) {
        $response = urdd_protocol::get_response(501);
    } elseif (is_numeric($arg_list[0])) {
        $username = get_username($db, $userid);
        $item = new action($cmd, $arg_list[0], $username, $userid);
        $item2 = new action(urdd_protocol::COMMAND_GENSETS, $arg_list[0], $username, $userid);
        $item3 = new action(urdd_protocol::COMMAND_PURGE, $arg_list[0], $username, $userid);
        $item4 = new action(urdd_protocol::COMMAND_EXPIRE, $arg_list[0], $username, $userid);
        if ($servers->has_equal($item) || $servers->has_equal($item2) || $servers->has_equal($item3) || $servers->has_equal($item4)) {
            $response = urdd_protocol::get_response(403);
        } else {
            $res = $servers->queue_push($db, $item, TRUE, server_data::QUEUE_BOTTOM, $priority);
            if ($res !== FALSE) {
                $response = sprintf(urdd_protocol::get_response(201), $item->get_id());
            } else {
                $response = urdd_protocol::get_responses(402);
            }
        }
    } elseif (strtolower($arg_list[0]) == 'all') {
        $response = queue_update_all($db, $servers, $userid, $priority);
    } else {
        $response = urdd_protocol::get_response(501);
    }

    return $response;
}


function queue_unpar_unrar(DatabaseConnection $db, $dir, $id, server_data &$servers, $userid, $preview=FALSE, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority) && is_bool($preview) && is_numeric($userid));
    echo_debug_function(DEBUG_SERVER, __FUNCTION__);
    if (!is_numeric($id)) {
        return urdd_protocol::get_response(501);
    }
    $query = "\"destination\" FROM downloadinfo WHERE \"ID\"=$id";
    $res = $db->select_query($query, 1);
    if ($res === FALSE) {
        return urdd_protocol::get_response(512);
    }

    $username = get_username($db, $userid);
    $item = new action (urdd_protocol::COMMAND_UNPAR_UNRAR, $id, $username, $userid);
    if ($dir == '' || $dir === NULL) {
        $dir = $res[0]['destination'];
    }
    $item->set_preview($preview);
    $item->set_dlpath($dir);
    $res = $servers->queue_push($db, $item, TRUE, server_data::QUEUE_BOTTOM, $priority);
    if ($res === FALSE) {
        return urdd_protocol::get_response(402);
    }

    return urdd_protocol::get_response(200);
}


function queue_find_servers(DatabaseConnection $db, server_data &$servers, array $arg_list, $userid, $priority=DEFAULT_PRIORITY)
{
    assert(is_numeric($priority)  && is_numeric($userid));
    if (!$servers->has_nntp_task()) {
        return urdd_protocol::get_response(401);
    }
    if ($servers->get_nntp_enabled() !== TRUE) {
        return urdd_protocol::get_response(410);
    }

    try {
        $arg = isset($arg_list[0]) ? $arg_list[0] : '';
        $username = get_username($db, $userid);
        $item = new action(urdd_protocol::COMMAND_FINDSERVERS, $arg, $username, $userid);
    } catch (exception $e) {
        return urdd_protocol::get_response(500); // should not happen....
    }
    if ($servers->has_equal($item)) {
        return urdd_protocol::get_response(403);
    } else {
        $res = $servers->queue_push($db, $item, TRUE, server_data::QUEUE_BOTTOM, $priority);
        if ($res === FALSE) {
            return urdd_protocol::get_response(402);
        }
    }

    return urdd_protocol::get_response(200);
}
