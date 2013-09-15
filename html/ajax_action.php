<?php
/*
 *  This file is part of Urd.
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
 * $LastChangedDate: 2013-09-06 00:48:29 +0200 (vr, 06 sep 2013) $
 * $Rev: 2922 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_action.php 2922 2013-09-05 22:48:29Z gavinspearhead@gmail.com $
 */
define('ORIGINAL_PAGE', $_SERVER['PHP_SELF']);

$pathac = realpath(dirname(__FILE__));
require_once "$pathac/../functions/ajax_includes.php";
require_once "$pathac/../functions/buttons.php";

$prefs = load_config($db);
$uc = new urdd_client($db, $prefs['urdd_host'], $prefs['urdd_port'], $userid);

function check_connected(urdd_client $uc)
{
    global $LN;
    if ($uc->is_connected() === FALSE) {
        throw new exception($LN['error_urddconnect']);
    }
}

if (isset($_GET['cmd']) && $_GET['cmd'] == 'export_all') {
    // we will accept export also from a GET request
    $command = 'export_all';
} elseif (!isset($_POST['cmd'])) {
    throw new exception($LN['error_novalidaction']);
} else {
    // everything else is a post
    $command = strtolower(get_post('cmd'));
    challenge::verify_challenge_text($_POST['challenge']);
}

function update_spots_images(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, NULL, TRUE, 'M', $userid, TRUE);
    check_connected($uc);
    $uc->update_spotsimages();
    $uc->disconnect();
    die_html('OK' . $LN['taskgetspot_images']);
}

function update_spots_comments(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, NULL, TRUE, 'M', $userid, TRUE);
    check_connected($uc);
    $uc->update_spotscomments();
    $uc->disconnect();
    die_html('OK' . $LN['taskgetspot_comments']);
}

function update_spots(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, NULL, TRUE, 'M', $userid, TRUE);
    check_connected($uc);
    $uc->update('', USERSETTYPE_SPOT);
    $uc->disconnect();
    die_html('OK' . $LN['taskgetspots']);
}

function expire_spots(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, NULL, TRUE, 'M', $userid, TRUE);
    check_connected($uc);
    $uc->expire('', USERSETTYPE_SPOT);
    $uc->disconnect();
    die_html('OK' . $LN['taskexpirespots']);
}

function purge_spots(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, NULL, TRUE, '', $userid, TRUE);
    check_connected($uc);
    $uc->purge('', USERSETTYPE_SPOT);
    $uc->disconnect();
    die_html('OK' . $LN['taskpurgespots'] );
}

function find_servers(DatabaseConnection $db, urdd_client $uc, $userid, $extended=FALSE)
{
    global $LN;
    verify_access($db, NULL, TRUE, '', $userid, TRUE);
    check_connected($uc);
    $ext_str = '';
    if ($extended) {
        $ext_str = 'extended';
    }
    $uc->findservers($ext_str);
    $uc->disconnect();
    die_html('OK' . $LN['taskfindservers'] );
}

function clean_db(DatabaseConnection $db, urdd_client $uc, $userid, $all=FALSE)
{
    global $LN;
    verify_access($db, NULL, FALSE, '', $userid, TRUE);
    check_connected($uc);
    $all_str = 'now';
    if ($all) {
        $all_str = 'all';
    };
    $uc->cleandb();
    $uc->disconnect();
    die_html('OK'. $LN['taskcleandb']);
}

function update_blacklist(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, urd_modules::URD_CLASS_SPOTS, TRUE, '', $userid, TRUE);
    check_connected($uc);
    $uc->update_blacklist();
    $uc->disconnect();
    die_html('OK' . $LN['taskgetblacklist'] );
}

function update_whitelist(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, urd_modules::URD_CLASS_SPOTS, TRUE, '', $userid, TRUE);
    check_connected($uc);
    $uc->update_whitelist();
    $uc->disconnect();
    die_html('OK' . $LN['taskgetwhitelist']);
}

function update_articles(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, urd_modules::URD_CLASS_GROUPS, TRUE, 'M', $userid, TRUE);
    check_connected($uc);
    $uc->update('all', USERSETTYPE_GROUP);
    $uc->disconnect();
    add_stat_data($db, stat_actions::UPDATE, 'all', $userid);
    die_html('OK' . $LN['taskupdate']);
}

function update_newsgroups(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, urd_modules::URD_CLASS_GROUPS, TRUE, 'M',  $userid, TRUE);
    check_connected($uc);
    $uc->update_newsgroups();
    $uc->disconnect();
    die_html('OK'.$LN['taskgrouplist']);
}

function gensets_articles(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, urd_modules::URD_CLASS_GROUPS, TRUE, 'M', $userid, TRUE);
    check_connected($uc);
    $uc->gensets('all');
    $uc->disconnect();
    die_html('OK'. $LN['taskgensets'] . ' ' . $LN['all']);
}

function purge_articles(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, urd_modules::URD_CLASS_GROUPS, TRUE, '', $userid, TRUE);
    check_connected($uc);
    $uc->purge('all', USERSETTYPE_GROUP);
    $uc->disconnect();
    add_stat_data($db, stat_actions::PURGE, 'all', $userid);
    die_html('OK'. $LN['taskpurge'] );

}

function expire_articles(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, urd_modules::URD_CLASS_GROUPS, TRUE, 'M', $userid, TRUE);
    check_connected($uc);
    $uc->expire('all', USERSETTYPE_GROUP);
    $uc->disconnect();
    add_stat_data($db, stat_actions::EXPIRE, 'all', $userid);
    die_html('OK'. $LN['taskexpire'] );
}

function gensets_group(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, urd_modules::URD_CLASS_GROUPS, TRUE, 'M', $userid, TRUE);
    check_connected($uc);
    $id = get_request('group');
    $name = '';
    if (substr($id, 0, 9) == 'category_') {
        $id = substr($id, 9);
        $ids = get_groups_by_category($db, $userid, $id);
        foreach ($ids as $i) {
            if (is_numeric($i)) {
                $uc->gensets($i);
            }
        }
        $name = get_category($db, $id, $userid);
    } else {
        if (substr($id, 0, 6) == 'group_') {
            $id = substr($id, 6);
        }
        if (is_numeric($id)) {
            $uc->gensets($id);
            $name = get_group_by_id($db, $id);

        } else {
            throw new exception($LN['error_invalidvalue'] . ' ' . $id);
        }
    }

    $uc->disconnect();
    die_html('OK'. $LN['taskgensets'] . ' ' . $name);
}

function update_newsgroup(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, urd_modules::URD_CLASS_GROUPS, TRUE, 'M', $userid, TRUE);
    check_connected($uc);
    $id = get_request('group');
    if (substr($id, 0, 9) == 'category_') {
        $id = substr($id, 9);
        $ids = get_groups_by_category($db, $userid, $id);
        foreach ($ids as $i) {
            if (is_numeric($i)) {
                $uc->update($i, USERSETTYPE_GROUP);
                add_stat_data($db, stat_actions::UPDATE, $i, $userid);
            }
        }
        $name = get_category($db, $id, $userid);
    } else {
        if (substr($id, 0, 6) == 'group_') {
            $id = substr($id, 6);
        }
        if (is_numeric($id)) {
            $uc->update($id, USERSETTYPE_GROUP);
            add_stat_data($db, stat_actions::UPDATE, $id, $userid);
            $name = get_group_by_id($db, $id);
        } else {
            throw new exception($LN['error_invalidvalue'] . ' ' . $id);
        }
    }
    $uc->disconnect();
    die_html('OK'. $LN['taskupdate'] . ' ' . $name);
}

function optimise(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, NULL, TRUE, '', $userid, TRUE);
    check_connected($uc);
    $uc->optimise();
    $uc->disconnect();
    die_html('OK' . $LN['taskoptimise']);
}

function check_version(urdd_client $uc)
{
    global $LN;
    check_connected($uc);
    $uc->check_version();
    $uc->disconnect();
    die_html('OK'. $LN['taskcheckversion']);
}

function update_rss(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, urd_modules::URD_CLASS_RSS, TRUE, 'M', $userid, TRUE);
    check_connected($uc);
    $id = get_request('group');
    $name = '';
    if (substr($id, 0, 9) == 'category_') {
        $id = substr($id, 9);
        $ids = get_groups_by_category($db, $userid, $id);
        foreach ($ids as $i) {
            if (is_numeric($i)) {
                $uc->update($i, USERSETTYPE_RSS);
                add_stat_data($db, stat_actions::UPDATE, $i, $userid);
            }
        }
        $name = get_category($db, $id, $userid);
    } else {
        if (substr($id, 0, 5) == 'feed_') {
            $id = substr($id, 5);
        }
        if (is_numeric($id)) {
            $uc->update($id, USERSETTYPE_RSS);
            add_stat_data($db, stat_actions::UPDATE, $id, $userid);
            $name = get_feed_by_id($db, $id);
        }
    }
    $uc->disconnect();
    die_html('OK' . $LN['taskupdate']. ' ' . $name);
}

function update_rss_all(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, urd_modules::URD_CLASS_RSS, TRUE, 'M', $userid, TRUE);
    check_connected($uc);
    $uc->update('all', USERSETTYPE_RSS);
    $uc->disconnect();
    add_stat_data($db, stat_actions::UPDATE, 'all', $userid);
    die_html('OK'. $LN['taskupdate']  . ' ' . $LN['all']);
}

function expire_newsgroups(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, urd_modules::URD_CLASS_GROUPS, TRUE, 'M', $userid, TRUE);
    check_connected($uc);
    $id = get_request('group');
    $db->escape($id);
    $name = '';
    if (substr($id, 0, 9) == 'category_') {
        $id = substr($id, 9);
        $ids = get_groups_by_category($db, $userid, $id);
        foreach ($ids as $i) {
            if (is_numeric($i)) {
                $uc->expire($i, USERSETTYPE_GROUP);
            }
        }
        $name = get_category($db, $id, $userid);
    } else {
        if (substr($id, 0, 6) == 'group_') {
            $id = substr($id, 6);
        }
        if (is_numeric($id)) {
            $uc->expire($id, USERSETTYPE_GROUP);
            $name = get_group_by_id($db, $id);
        }
    }

    $uc->disconnect();
    add_stat_data($db, stat_actions::EXPIRE, $id, $userid);
    die_html('OK'. $LN['taskexpire'] . ' ' . $name);
}

function purge_rss_all(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, urd_modules::URD_CLASS_RSS, TRUE, '', $userid, TRUE);
    check_connected($uc);
    $uc->purge('all', USERSETTYPE_RSS);
    $uc->disconnect();
    add_stat_data($db, stat_actions::PURGE, 'all', $userid);
    die_html('OK' . $LN['taskpurge'] );
}

function expire_rss_all(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, urd_modules::URD_CLASS_RSS, TRUE, 'M', $userid, TRUE);
    check_connected($uc);
    $uc->expire('all', USERSETTYPE_RSS);
    $uc->disconnect();
    add_stat_data($db, stat_actions::EXPIRE, 'all', $userid);
    die_html('OK'. $LN['taskexpire'] );
}

function expire_rss(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, urd_modules::URD_CLASS_RSS, TRUE, '', $userid);
    check_connected($uc);
    $name = '';
    $id = get_request('group');
    if (substr($id, 0, 9) == 'category_') {
        $id = substr($id, 9);

        $ids = get_feeds_by_category($db, $userid, $id);
        foreach ($ids as $i) {
            if (is_numeric($i)) {
                $uc->expire($i, USERSETTYPE_RSS);
                add_stat_data($db, stat_actions::EXPIRE, $i, $userid);
            }
        }
        $name = get_category($db, $id, $userid);
    } else {
        if (substr($id, 0, 5) == 'feed_') {
            $id = substr($id, 5);
        }
        if (is_numeric($id)) {
            $uc->expire($id, USERSETTYPE_RSS);
            add_stat_data($db, stat_actions::EXPIRE, $id, $userid);
            $name = get_feed_by_id($db, $id);
        }
    }

    $uc->disconnect();
    die_html('OK'. $LN['taskexpire'] . ' ' . $name);
}

function purge_rss(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, urd_modules::URD_CLASS_RSS, TRUE, '', $userid, TRUE);
    check_connected($uc);
    $id = get_request('group');
    $db->escape($id);
    $name = '';
    if (substr($id, 0, 9) == 'category_') {
        $id = substr($id, 9);
        $ids = get_feeds_by_category($db, $userid, $id);
        foreach ($ids as $i) {
            if (is_numeric($i)) {
                $uc->purge($i, USERSETTYPE_RSS);
                add_stat_data($db, stat_actions::PURGE, $i, $userid);
            }
        }
        $name = get_category($db, $id, $userid);
    } else {
        if (substr($id, 0, 5) == 'feed_') {
            $id = substr($id, 5);
        }
        if (is_numeric($id)) {
            $uc->purge($id, USERSETTYPE_RSS);
            add_stat_data($db, stat_actions::PURGE, $id, $userid);
            $name = get_feed_by_id($db, $id);
        }
    }

    $uc->disconnect();
    die_html('OK'. $LN['taskpurge'] . ' ' . $name);
}

function clean_all(urdd_client $uc)
{
    // Clear completed downloads:
    global $LN;
    if ($uc->is_connected()) {
        $uc->cleandb('now');
        die_html('OK' . $LN['taskcleandb']);
    } else {
        throw new exception($LN['error_urddconnect']);
    }
}

function purge_newsgroups(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, urd_modules::URD_CLASS_GROUPS, TRUE, '', $userid, TRUE);
    check_connected($uc);
    $id = get_request('group');
    $db->escape($id);
    $name = '';
    if (substr($id, 0, 9) == 'category_') {
        $id = substr($id, 9);
        $ids = get_groups_by_category($db, $userid, $id);
        foreach ($ids as $i) {
            if (is_numeric($i)) {
                $uc->purge($i, USERSETTYPE_GROUP);
            }
        }
        $name = get_category($db, $id, $userid);
    } else {
        if (substr($id, 0, 6) == 'group_') {
            $id = substr($id, 6);
        }
        if (is_numeric($id)) {
            $uc->purge($id, USERSETTYPE_GROUP);
            $name = get_group_by_id($db, $id);
        }
    }
    $uc->disconnect();
    add_stat_data($db, stat_actions::PURGE, $id, $userid);
    die_html('OK'. $LN['taskpurge'] . ' ' . $name);
}

function get_setinfo(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, urd_modules::URD_CLASS_SYNC, TRUE, '', $userid, TRUE);
    check_connected($uc);
    $uc->getsetinfo();
    $uc->disconnect();
    die_html('OK'. $LN['taskgetsetinfo'] );
}

function send_setinfo(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, urd_modules::URD_CLASS_SYNC, TRUE, '', $userid, TRUE);
    check_connected($uc);
    $uc->sendsetinfo();
    $uc->disconnect();
    die_html('OK'. $LN['tasksendsetinfo'] );
}

function clean_dir(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    verify_access($db, NULL, TRUE, '', $userid, TRUE);
    check_connected($uc);
    $uc->cleandir('all');
    $uc->disconnect();
    die_html('OK' . $LN['taskcleandir']);
}

function unschedule_job(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    $job = get_post('job');
    $uc->unschedule($job, '');
    $uc->disconnect();
    die_html('OK');
}

function delete_task(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    $task = get_post('task');
    if (!is_numeric($task)) {
        throw new exception($LN['error_notanumber']);
    }
    $db->escape($task, TRUE);
    $db->delete_query('queueinfo', "\"ID\" = $task");
    die_html('OK');
}

function cancel_all_tasks(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    check_connected($uc);
    $uc->cancel('all');
    die_html('OK');
}

function cancel_task(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    $task = get_post('task');
    $uc->cancel($task);
    $uc->disconnect();
    die_html('OK'. $LN['transfers_status_cancelled']);
}

function pause_task(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    $task = get_post('task');
    $uc->pause($task);
    $uc->disconnect();
    die_html('OK'. $LN['transfers_status_paused']);
}


function pause_all_tasks(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    $uc->pause('all');
    $uc->disconnect();
    die_html('OK'. $LN['transfers_status_paused'] . ' ' . $LN['all']);
}


function continue_all_tasks(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    $uc->continue_cmd('all');
    $uc->disconnect();
    die_html('OK'. $LN['success']);
}


function continue_task(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    $task = get_post('task');
    $uc->continue_cmd($task);
    $uc->disconnect();
    die_html('OK'. $LN['success2']);
}

function import_all(DatabaseConnection $db, urdd_client $uc,  $userid)
{
    verify_access($db, NULL, TRUE, '', $userid, TRUE);
    check_connected($uc);
    if (isset($_FILES['filename']['tmp_name'])) {
        $xml = new urd_xml_reader($_FILES['filename']['tmp_name']);
        $settings = $xml->read_all_settings($db);
        clear_all_feeds($db, $userid);
        clear_all_groups($db, $userid);
        clear_all_usenet_servers($db);
        clear_all_buttons($db);
        stop_urdd($userid);
        clear_all_users($db);
        clean_config($db);

        reset_config($db);
        set_configs($db, $settings['config']);
        set_all_users($db, $settings['users'], $settings['users_settings']);
        set_all_buttons($db, $settings['buttons']);
        set_all_usenet_servers($db, $settings['usenet_servers']);
        $userid = get_admin_userid($db);
        // we need to get the ID of the first admin we find since we reloaded users
        start_urdd();
        $s_time = time();
        $uprefs = load_config($db);
        while (1) {
            try {
                $uc = new urdd_client($db, $uprefs['urdd_host'], $uprefs['urdd_port'], $userid);
            } catch (execption$e) {
            }
            if ($uc->is_connected()) {
                break;
            } else {
                sleep(1);
                // sleep one second before we try again
                if (time() - $s_time >= 60) {
                    // until 1 minute has passed... probably an incompatible config loaded
                    throw new exception('urdd has not started yet... stopping');
                }
            }
        }
        set_all_groups($db, $settings['newsgroups'], $userid);
        set_all_feeds($db, $settings['rssfeeds'], $userid);
        redirect('index.php');
    }
    die;
}

function add_search(DatabaseConnection $db, $userid)
{
    global $LN;
    $type = trim(get_post('type', ''));
    $value = trim(get_post('value', ''));
    if ($type == 'search') {
        if ($value  != '') {
            add_line_to_text_area($db, 'search_terms', $value, $userid);
        }
    } elseif ($type == 'block') {
        if ($value  != '') {
            add_line_to_text_area($db, 'blocked_terms', $value, $userid);
        }
    } else {
        throw new exception($LN['error_unknowntype']);
    }
    die_html('OK');
}

function add_blacklist(DatabaseConnection $db)
{
    global $LN;
    challenge::verify_challenge_text($_POST['challenge']);
    $spotid = trim(get_post('spotid', ''));
    $spotterid = get_spotterid_from_spot($db, $spotid);
    if ($spotterid !== FALSE) {
        add_to_blacklist($db, $spotterid);
        die_html('OK');
    } else {
        throw new exception ($LN['error_spotnotfound']);
    }
}

function delete_preview(DatabaseConnection $db, urdd_client $uc, $userid)
{
    global $LN;
    $dlid = get_post('dlid');
    $qadmin = '';
    $is_admin = urd_user_rights::is_admin($db, $userid);
    if (!$is_admin) {
        //$username = get_username($db, $userid);
        $db->escape($userid, TRUE);
        $qadmin = " AND \"userid\" = $userid ";
    }
    if (is_numeric($dlid)) {
        $uc->cancel(get_command(urdd_protocol::COMMAND_DOWNLOAD_ACTION) . ' ' . $dlid);  // Cancel it, just in case; see pauso
        $uc->cancel(get_command(urdd_protocol::COMMAND_DOWNLOAD) . ' ' . $dlid);  // Cancel it, just in case
        $db->escape($dlid, TRUE);
        $sql = "UPDATE downloadinfo SET \"hidden\" = 1 WHERE \"ID\" = $dlid AND \"preview\"=2 " . $qadmin;
        $db->execute_query($sql);
    } elseif ($dlid == 'all') {
        $sql = "UPDATE downloadinfo SET \"hidden\" = 1 WHERE \"preview\"=2 AND status >= " . DOWNLOAD_FINISHED . ' ' . $qadmin;
        $db->execute_query($sql);
    } else {
        throw new exception('Need download ID');
    }
    die_html('OK'. $LN['deleted'] );
}

switch ($command) {
    case 'delete_preview':
        delete_preview($db, $uc, $userid);
        break;
    case 'unschedule':
        unschedule_job($db, $uc, $userid);
        break;
    case 'delete_task':
        delete_task($db, $uc, $userid);
        break;
    case 'cancel':
        cancel_task($db, $uc, $userid);
        break;
    case 'pause':
        pause_task($db, $uc, $userid);
        break;
    case 'pause_all':
        pause_all_tasks($db, $uc, $userid);
        break;
    case 'continue':
        continue_task($db, $uc, $userid);
        break;
    case 'continue_all':
        continue_all_tasks($db, $uc, $userid);
        break;
    case 'export_all':
        verify_access($db, NULL, TRUE, '', $userid, TRUE);
        export_settings($db, 'all', 'urd_all_settings.xml');
        break;
    case 'import_all':
        import_all($db, $uc, $userid);
    case 'add_search':
        add_search($db, $userid);
        break;
    case 'add_blacklist':
        add_blacklist($db);
        break;
    case 'updatespots':
        update_spots($db, $uc, $userid);
        break;
    case 'updatespotscomments':
        update_spots_comments($db, $uc, $userid);
        break;
    case 'updatespotsimages':
        update_spots_images($db, $uc, $userid);
        break;
    case 'expirespots':
        expire_spots($db, $uc, $userid);
        break;
    case 'purgespots':
        purge_spots($db, $uc, $userid);
        break;
    case 'findservers':
        find_servers($db, $uc, $userid);
        break;
    case 'findservers_ext':
        find_servers($db, $uc, $userid, TRUE);
        break;
    case 'cleandb_all':
        clean_db($db, $uc, $userid, TRUE);
        break;
    case 'cleandb':
        clean_db($db, $uc, $userid, FALSE);
        break;
    case 'updatearticles':
        update_articles( $db, $uc, $userid);
        break;
    case 'updatewhitelist':
        update_whitelist( $db, $uc, $userid);
        break;
    case 'updateblacklist':
        update_blacklist( $db, $uc, $userid);
    case 'updategroups':
        update_newsgroups( $db, $uc, $userid);
        break;
    case 'gensetsarticles':
        gensets_articles($db, $uc, $userid);
        break;
    case 'purgearticles':
        purge_articles($db, $uc, $userid);
        break;
    case 'expirearticles':
        expire_articles($db, $uc, $userid);
        break;
    case 'gensetsgroup':
        gensets_group($db, $uc, $userid);
        break;
    case 'updategroup':
        update_newsgroup($db, $uc, $userid);
        break;
    case 'optimise':
        optimise($db, $uc, $userid);
        break;
    case 'checkversion':
        check_version($uc);
        break;
    case 'updaterssall':
        update_rss_all($db, $uc, $userid);
        break;
    case 'updaterss':
        update_rss($db, $uc, $userid);
        break;
    case 'expiregroup':
        expire_newsgroups( $db, $uc, $userid);
        break;
    case 'expirerssall':
        expire_rss_all($db, $uc, $userid);
        break;
    case 'purgerssall':
        purge_rss_all($db, $uc, $userid);
        break;
    case 'expirerss':
        expire_rss($db, $uc, $userid);
        break;
    case 'purgerss':
        purge_rss($db, $uc, $userid);
        break;
    case 'cancelall' :
        cancel_all_tasks($db, $uc, $userid);
        break;
    case 'cleanall' :
        clean_all($uc);
        break;
    case 'purgegroup':
        purge_newsgroups($db, $uc, $userid);
        break;
    case 'getsetinfo':
        get_setinfo($db, $uc, $userid);
        break;
    case 'sendsetinfo':
        send_setinfo($db, $uc, $userid);
        break;
    case 'cleandir':
        clean_dir($db, $uc, $userid);
        break;
    case 'poweron':
        // Turn URDD on
        verify_access($db, NULL, TRUE, '', $userid, TRUE);
        start_urdd();
        break;
    case 'poweroff' :
        // Turn URDD off
        verify_access($db, NULL, TRUE, '', $userid, TRUE);
        stop_urdd($userid);
        break;
    case 'restart':
        verify_access($db, NULL, TRUE, '', $userid, TRUE);
        $uc = new urdd_client($db, $prefs['urdd_host'], $prefs['urdd_port'], $userid);
        if ($uc->is_connected()) {
            $uc->restart_urdd();
        }
        usleep(500000);
        break;

    default:
        throw new exception($LN['error_novalidaction']);
        break;
}
