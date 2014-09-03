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
 * $LastChangedDate: 2014-06-07 14:53:28 +0200 (za, 07 jun 2014) $
 * $Rev: 3081 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: urdd_rss.php 3081 2014-06-07 12:53:28Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathrss = realpath(dirname(__FILE__));

require_once "$pathrss/../functions/libs/magpierss/rss_fetch.php";

class urdd_rss
{
    private $cache_dir;

    public function __construct(DatabaseConnection $db)
    {
        $this->cache_dir = get_magpie_cache_dir($db);
    }

    private static function check_link(DatabaseConnection $db, $id, $link)
    {
        // check if a link already exists in the db for that rss feed
        $qry = '"setid" FROM rss_sets WHERE "nzb_link"=? AND "rss_id"=?';
        $res = $db->select_query($qry, 1, array($link, $id));
        if (isset($res[0]['setid'])) {
            return $res[0]['setid'];
        }

        return FALSE;
    }

    private static function get_size_from_description($description)
    {
        $rv = preg_match('/size\h*:?\h*([\d.,]+)\h*([kmgt]b?)?/i', $description, $matches);

        $size = 0;
        if ($rv >= 0) { // we found a size
            $size = isset($matches[1]) ? trim($matches[1]) : 0;
            $size = str_replace(',', '.', $size);
            if (is_numeric($size) && $size > 0) {
                $order = isset($matches[2]) ? trim( $matches[2]) : ' ';
                $size = unformat_size($size . $order[0], 1024);
            } else {
                $size = 0;
            }
        }

        return $size;
    }

    public function update_rss_set(DatabaseConnection $db, $setid, $title, $timestamp, $description, $summary)
    {
        assert(is_numeric($timestamp) && $title != '');
        $title = html_entity_decode($title, ENT_COMPAT, 'UTF-8');
        $description = html_entity_decode($description, ENT_COMPAT, 'UTF-8');
        $summary = html_entity_decode($summary, ENT_COMPAT, 'UTF-8');
        $size = self::get_size_from_description($description);
        if ($size == 0) {
            $size = self::get_size_from_description($summary);
        }
        $db->update_query_2('rss_sets',
            array('setname'=>$title, 'timestamp'=>$timestamp, 'description'=>$description, 'summary'=>$summary, 'size'=>$size),
            '"setid"=?', array($setid));
    }

    public function insert_rss_set(DatabaseConnection $db, $rss_id, $link, $title, $timestamp, $description, $summary)
    {
        assert(is_numeric($rss_id) && is_numeric($timestamp) && $link != '' && $title != '');
        $title = html_entity_decode($title, ENT_COMPAT, 'UTF-8');
        $description = html_entity_decode($description, ENT_COMPAT, 'UTF-8');
        $summary = html_entity_decode($summary, ENT_COMPAT, 'UTF-8');
        $size = self::get_size_from_description($description);
        if ($size == 0) {
            $size = self::get_size_from_description($summary);
        }
        $db->insert_query('rss_sets',
            array('setid', 'rss_id', 'setname', 'nzb_link', 'timestamp', 'description', 'summary', 'size'),
            array(md5($link), $rss_id, $title, $link, $timestamp, $description, $summary, $size));
    }

    public function update_feedcount(DatabaseConnection $db, $rss_id)
    {
        assert(is_numeric($rss_id));
        $sql = 'UPDATE rss_urls SET "feedcount"= (SELECT COUNT(*) FROM rss_sets WHERE "rss_id"= :rss_id1 ) WHERE "id"=:rss_id2';
        $db->execute_query($sql, array(':rss_id1'=>$rss_id, ':rss_id2'=>$rss_id));
    }

    public function rss_update(DatabaseConnection $db, array $rss_info)
    {
        echo_debug_function(DEBUG_MAIN, __FUNCTION__);
        $newcnt = $updcnt = 0;
        $rss_id = $rss_info['id'];
        $url = $rss_info['url'];
        $username = $rss_info['username'];
        $password = $rss_info['password'];
        $expire = $rss_info['expire']; // in days
        $expire *= 24 * 3600; // in seconds now
        $now = time();
        $expire_time = $now - $expire;
        $rss = fetch_rss::do_fetch_rss($url, $this->cache_dir, $username, $password);
        foreach ($rss->items as $item) {
            if (!isset($item['link'], $item['title'])) {
                continue;
            }

            $link = $item['link'];
            $title = utf8_encode($item['title']);
            $timestamp = isset($item['date_timestamp']) ? $item['date_timestamp'] : time();
            $description = utf8_encode(isset ($item['description']) ? $item['description'] : $title);
            $description = str_replace('&nbsp;', ' ', $description);
            $summary = utf8_encode(isset($item['summary'])? $item['summary'] : $title);
            $summary = str_replace('&nbsp;', ' ', $summary);
            if ($timestamp >= $expire_time) {
                $id = self::check_link($db, $rss_id, $link);
                if ($id === FALSE) {// if it doesn't exist, add it
                    $this->insert_rss_set($db, $rss_id, $link, $title, $timestamp, $description, $summary);
                    $newcnt++;
                } else {
                    $this->update_rss_set($db, $id, $title, $timestamp, $description, $summary);
                    $updcnt++;
                }
            }
        }
        echo_debug("Updated $updcnt sets; added $newcnt sets", DEBUG_SERVER);
        $this->update_feedcount($db, $rss_id);

        return $newcnt;
    }


    public function purge_rss(DatabaseConnection $db, $rss_id, $dbid=NULL)
    {
        echo_debug_function(DEBUG_MAIN, __FUNCTION__);
        assert (is_numeric($rss_id));
        $rss_info = get_rss_info($db, $rss_id);
        $type = USERSETTYPE_RSS;

        $qry = 'count(*) AS cnt FROM rss_sets WHERE "rss_id"=?';
        $res = $db->select_query($qry, array($rss_id));
        if (!isset($res[0]['cnt'])) {
            throw new exception_db ('DB Error');
        }
        $cnt = $res[0]['cnt'];

        $sql = '"setID" in (SELECT "setid" FROM rss_sets WHERE "rss_id" = ?) AND "type" = ?';
        $db->delete_query('usersetinfo', $sql, array($rss_id, $type));

        if ($dbid !== NULL) {
            update_queue_status ($db, $dbid, NULL, 0, 30);
        }

        $sql = '"setID" in (SELECT "setid" FROM rss_sets WHERE "rss_id"=?) AND "type"=?';
        $db->delete_query('extsetdata', $sql, array($rss_id, $type));

        if ($dbid !== NULL) {
            update_queue_status ($db, $dbid, NULL, 0, 60);
        }

        $qry = '"rss_id"=?';
        $db->delete_query('rss_sets', $qry, array($rss_id));

        if ($dbid !== NULL) {
            update_queue_status ($db, $dbid, NULL, 0, 90);
        }
        $this->update_feedcount($db, $rss_id);
        $db->update_query_2('rss_urls', array('extset_update'=>0), '"id"=?', array($rss_id));
        fetch_rss::delete_cache_entry($rss_info['url'], get_magpie_cache_dir($db));
        if ($dbid !== NULL) {
            update_queue_status ($db, $dbid, NULL, 0, 100);
        }

        return $cnt;
    }


    public function expire_rss(DatabaseConnection $db, $rss_id, $dbid)
    {
        echo_debug_function(DEBUG_MAIN, __FUNCTION__);
        assert(is_numeric($rss_id));
        $orss_id = $rss_id;
        $rss_info = get_rss_info($db, $rss_id);
        $expire = $rss_info['expire'];
        $expire *= 24 * 3600; // in seconds now
        $expire = time() - $expire;
        $type = USERSETTYPE_RSS;
        $marking_on = sets_marking::MARKING_ON;
        $prefs = load_config($db);
        $keep_int = '';
        $input_arr = array();
        if ($prefs['keep_interesting']) {
            $keep_int = ' AND "setid" NOT IN ( SELECT "setID" FROM usersetinfo WHERE "type" = :type AND "statusint" = :marking) ';
            $input_arr[':type'] = $type;
            $input_arr[':marking'] = $marking_on;
        }

        $cnt = 0;
        $qry = "count(*) AS cnt FROM rss_sets WHERE \"rss_id\" = :rss_id AND \"timestamp\" < :expire $keep_int";
        $input_arr[':rss_id'] = $rss_id;
        $input_arr[':expire'] = $expire;
        $res = $db->select_query($qry, $input_arr);
        if (isset($res[0]['cnt'])) {
            $cnt = $res[0]['cnt'];
        }
        update_queue_status ($db, $dbid, NULL, 0, 1);
        // first delete everything we want to remove
        $qry = "\"rss_id\" = :rss_id AND \"timestamp\" < :expire $keep_int";
        $res = $db->delete_query('rss_sets', $qry, $input_arr);
        update_queue_status ($db, $dbid, NULL, 0, 30);

        // then rm all usersetinfo that has a set id of a set we already removed
        $sql = '"setID" NOT IN (SELECT "setid" FROM rss_sets ) AND "type"=?';
        $res = $db->delete_query('usersetinfo', $sql, array($type));

        update_queue_status ($db, $dbid, NULL, 0, 60);
        // ditto extsetinfo
        $sql = '"setID" NOT IN (SELECT "setid" FROM rss_sets) AND "type"=?';
        $res = $db->delete_query('extsetdata', $sql, array($type));

        update_queue_status ($db, $dbid, NULL, 0, 90);

        echo_debug("Deleted {$cnt} sets", DEBUG_DATABASE);
        $this->update_feedcount($db, $orss_id);
        update_queue_status ($db, $dbid, NULL, 0, 100);

        return $cnt;
    }

}
