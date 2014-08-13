<?php
/*  vim:ts=4:expandtab:cindent
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
 * $LastChangedDate$
 * $Rev$
 * $Author$
 * $Id$
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathsf = realpath(dirname(__FILE__));

class urd_spots
{
    const SPOT_BODY_SIZE_LIMIT      = 51200;
    const SPOT_COMMENT_SIZE_LIMIT   = 10240;

    private static function make_spot_id($spotid, $message_id, $poster)
    {
        return md5($spotid . $message_id . $poster);
    }

    public static function add_spot(DatabaseConnection $db, array $spot_data)
    {
        //echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        $spotid = self::make_spot_id($spot_data['spotid'], $spot_data['messageid'], $spot_data['poster']);
      //  $res = $db->select_query('"spotid" FROM spots WHERE "spotid"=?', 1, array($spotid));
        static $cols = array (
            'messageid',
            'spotid',
            'category',
            'subcat',
            'poster',
            'spotter_id',
            'subcata',
            'subcatb',
            'subcatc',
            'subcatd',
            'subcatz',
            'title',
            'tag',
            'stamp',
            'size',
            'url',
            'nzbs',
            'description',
            'reports',
            'comments',
            'rating',
            'rating_count',
        );
        $vals = array (
            $spot_data['messageid'],
            $spotid,
            (is_numeric($spot_data['category']) ? $spot_data['category'] : 0),
            (is_numeric($spot_data['sub']) ? $spot_data['sub'] : 0),
            $spot_data['poster'],
            $spot_data['spotter_id'],
            $spot_data['subcata'],
            $spot_data['subcatb'],
            $spot_data['subcatc'],
            $spot_data['subcatd'],
            $spot_data['subcatz'],
            $spot_data['title'],
            $spot_data['tag'],
            $spot_data['date'],
            $spot_data['size'],
            $spot_data['url'],
            (($spot_data['nzb'] != '') ? db_compress(serialize($spot_data['nzb'])) : ''),
            db_compress($spot_data['body']),
            0,
            0,
            0,
            0,
        );
    //    if (!isset($res[0]['spotid'])) {
            $db->insert_query('spots', $cols, $vals);
            if (is_string($spot_data['image'])) {
                $db->insert_query('spot_images', array('spotid', 'image', 'fetched', 'stamp'), 
                        array($spotid, $spot_data['image'], 
                            ((substr($spot_data['image'], 0, 9) == 'articles:') ? 0 : 1), $spot_data['date']));
            }
    /*    } else {
            $db->update_query('spots', $cols, $vals, '"spotid"=?', array($spotid));
            write_log('We are updating a spot', LOG_INFO);
            if (is_string($spot_data['image'])) {
                $db->update_query('spot_images', array('image', 'fetched'), 
                        array($spot_data['image'], (substr($spot_data['image'], 0, 9) == 'articles:') ? 0 : 1), '"spotid"=?', array($spotid));
            }
        }*/

        return $spotid;
    }

    private static function parse_spot_report(array $lines)
    {
        $header = array();
        foreach ($lines as $line) {
            $hdr = explode(':', $line, 2);
            if (count($hdr) < 2) {
                continue;
            }
            $header[$hdr[0]] = trim($hdr[1]);
        }

        $reportId = trim($header['Message-ID'], '<>');
        $report = array();

        $tmp = explode(' ', $header['Subject']);
        if (count($tmp) > 2) {
            $report['date'] = strtotime($header['Date']);
            $report['message_id'] = $reportId;
            $report['keyword'] = $tmp[0];
            $report['reference'] = substr($tmp[1], 1, -1);
        }
        return $report;
    }

    private static function parse_spot_header(array $header, $message_id, array $spot_blacklist)
    {
        $spot_data = array(
                'from' => '',
                'subject' => '',
                'date' => '',
                'xml-signature' => '',
                'user-signature' => '',
                'user-key'=> '',
                'xml' =>'',
                'messageid' => $message_id,
                'user-avatar' => '',
                'verified' => FALSE
                );
        $spotParser = new SpotParser();
        foreach ($header as $line) {
            $parts = explode(':', $line, 2);
            if (!isset($parts[1])) {
                echo "something wrong with the header\n";
                continue;
            }

            $parts[1] = trim($parts[1]);
            switch (strtolower(trim($parts[0]))) {
                case 'from':
                    $spot_data['from'] .= $parts[1];
                    $from = $parts[1];
                    $spotter_id = self::parse_spotterid(substr($from, strpos($from, '<')));
                    if (isset($spot_blacklist[$spotter_id])) {
                        echo_debug("User $spotter_id on blacklist - spot not added", DEBUG_SERVER);

                        return FALSE;
                    }
                    $spot_data['spotter_id'] = $spotter_id;
                    break;
                case 'date':
                    $spot_data['date'] .= strtotime($parts[1]);
                    $now = time();
                    if ($spot_data['date'] > ($now + 3600)) {
                        //correct dates that are in the future to avoid those pesky spots that stick at the top
                        $spot_data['date'] = $now;
                    }
                    break;
                case 'x-xml-signature':
                    $spot_data['xml-signature'] .= substr($line, 17);
                    break;
                case 'x-user-key':
                    $xml = simplexml_load_string(substr($line, 12));
                    if ($xml !== FALSE) {
                        $spot_data['user-key']['exponent'] = (string) $xml->Exponent;
                        $spot_data['user-key']['modulo'] = (string) $xml->Modulus;
                    }
                    break;
                case 'x-user-signature' :
                    $spot_data['user-signature'] = SpotParser::unspecial_string(substr($line, 18));
                    break;
                case 'x-xml' :
                    $spot_data['xml'] .= substr($line, 7);
                    break;
                case 'x-user-avatar':
                    $spot_data['user-avatar'] .= substr($line, 15);
                    break;
            }
        }
        if ($spot_data['xml-signature'] == '' ||
                $spot_data['xml'] == '' ||
                $spot_data['user-key'] == '' ||
                $spot_data['user-signature'] == '') {
            return FALSE;
        }
        
        // Parse nu de XML file, alles wat al gedefinieerd is eerder wordt niet overschreven
        $spot_data = array_merge($spotParser->parse_full($spot_data['xml']), $spot_data);
        if ($spot_data['title'] == '') {
            echo "something wrong with the title\n";
            return FALSE;
        }

        return $spot_data;
    }

    private static function verify_spot(array &$spot_data)
    {
        $spotSigning = new SpotSigning(extension_loaded('openssl'));
        $spot_data['verified'] = $spotSigning->verifyFullSpot($spot_data);
        // als de spot verified is, toon dan de userid van deze user
        if ($spot_data['verified']) {
            $spot_data['userid'] = $spotSigning->calculate_userid($spot_data['user-key']['modulo']);
            echo_debug("verified spot for " . $spot_data['userid'] , DEBUG_SERVER);

        } else {
            echo_debug("Unverified spot", DEBUG_SERVER);
        }

        return $spot_data;
    }

    private static function parse_spot_data(array &$spot_data)
    {
        $spotParser = new SpotParser();
        $spot_data = array_merge($spotParser->parse_full($spot_data['xml']), $spot_data);
    }

    public static function expire_spots(DatabaseConnection $db, $dbid)
    {
        assert(is_numeric($dbid));
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        try {
            $group_name = get_config($db, 'spots_group');
            $group_id = group_by_name($db, $group_name);
            $expire_time = get_config($db, 'spots_expire_time', DEFAULT_SPOTS_EXPIRE_TIME);
        } catch (exception $e) {
            write_log('cannot find spots group', LOG_WARNING);

            return 0;
        }

        echo_debug("Expire $expire_time days", DEBUG_SERVER);
        // Expire : from days to seconds
        $expire_time *= 24 * 3600;
        // convert to epochtime:
        $time = time();
        $expire = $time - $expire_time;
        echo_debug("Expire $expire seconds", DEBUG_SERVER);
        $safety_expire = $time - (24 * 3600); // we always take a day in advance so images or reports etc may not yet have been retrieved
        $type = USERSETTYPE_SPOT;
        $marking_on = sets_marking::MARKING_ON;
        $spam_count = $keep_int = '';
        $inputarr = array($expire);
        $spot_expire_spam_count = get_config($db, 'spot_expire_spam_count', 0);
        if ($spot_expire_spam_count > 0) {
            $spam_count = ' OR spots."reports" > ?';
            $inputarr[] = $spot_expire_spam_count;
        }
        if (get_config($db, 'keep_interesting', FALSE)) {
            $keep_int = ' AND "spotid" NOT IN (SELECT "setID" FROM usersetinfo WHERE "type"=? AND "statusint"=?) ';
            $inputarr[] = $type;
            $inputarr[] = $marking_on;
        }

        echo_debug('Deleting expired spots', DEBUG_DATABASE);

        $sql = "count(\"spotid\") AS cnt FROM spots WHERE (\"stamp\" < ? $spam_count ) $keep_int";
        $res = $db->select_query($sql, -1, -1, $inputarr);
        $cnt = 0;
        if (isset($res[0]['cnt'])) {
            $cnt = $res[0]['cnt'];
        }
        write_log('Deleting ' . $cnt . ' spots');
        update_queue_status ($db, $dbid, NULL, 0, 1);

        // expiring
        $res = $db->delete_query('spots', " (\"stamp\" < ? $spam_count ) $keep_int", $inputarr);
        echo_debug("Deleted {$cnt} spots", DEBUG_DATABASE);
        $sql = 'count(*) AS "cnt" FROM spot_images WHERE "spotid" NOT IN (SELECT "spotid" FROM spots)';
        $res = $db->select_query($sql);
        $cnt = 0;
        if (isset($res[0]['cnt'])) {
            $cnt = $res[0]['cnt'];
        }
        update_queue_status ($db, $dbid, NULL, 0, 10);

        self::delete_image_cache($db, FALSE, $safety_expire);

        update_queue_status ($db, $dbid, NULL, 0, 20);
        // delete files from cache too

        write_log('Deleting '. $cnt . ' spot images');
        $res = $db->delete_query('spot_images', '"spotid" NOT IN (SELECT "spotid" FROM spots) AND "stamp" < ?', array($safety_expire));
        echo_debug("Deleted {$cnt} spot images", DEBUG_DATABASE);

        update_queue_status ($db, $dbid, NULL, 0, 40);
        $sql = 'count(*) AS cnt FROM spot_comments WHERE "spotid" NOT IN (SELECT "spotid" FROM spots) AND "stamp" < ?';
        $res = $db->select_query($sql, array($safety_expire));
        $cnt = 0;
        if (isset($res[0]['cnt'])) {
            $cnt = $res[0]['cnt'];
        }

        update_queue_status ($db, $dbid, NULL, 0, 60);
        write_log('Deleting '. $cnt . ' spot comments');
        $res = $db->delete_query('spot_comments', '"spotid" NOT IN (SELECT "spotid" FROM spots) AND "stamp" < ?', array($safety_expire));
        echo_debug("Deleted {$cnt} spot comments", DEBUG_DATABASE);

        update_queue_status ($db, $dbid, NULL, 0, 80);
        // expiring reports
        $sql = 'count(*) AS cnt FROM spot_reports WHERE "spotid" NOT IN (SELECT "spotid" FROM spots) AND "stamp" < ?';
        $res = $db->select_query($sql, array($safety_expire));
        $cnt = 0;
        if (isset($res[0]['cnt'])) {
            $cnt = $res[0]['cnt'];
        }

        $res = $db->delete_query('extsetdata', '"setID" NOT IN (SELECT "spotid" FROM spots) AND "type"=?', array($type));
        update_queue_status ($db, $dbid, NULL, 0, 90);

        update_queue_status ($db, $dbid, NULL, 0, 90);
        write_log('Deleting '. $cnt . ' spot reports');
        $res = $db->delete_query('spot_reports', '"spotid" NOT IN (SELECT "spotid" FROM spots) AND "stamp" < ?', array($safety_expire));
        echo_debug("Deleted {$cnt} spot reports", DEBUG_DATABASE);
        self::update_spots_report_count($db);
        self::update_spots_comment_count($db);
        update_queue_status ($db, $dbid, NULL, 0, 100);

        return $cnt;
    }

    public static function purge_spots(DatabaseConnection $db, $dbid)
    {
        assert(is_numeric($dbid));
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        $group = get_config($db, 'spots_group');
        $groupid = group_by_name($db, $group);
        $group_comments = get_config($db, 'spots_comments_group');
        $comments_groupid = group_by_name($db, $group_comments);
        $group_reports = get_config($db, 'spots_reports_group');
        $reports_groupid = group_by_name($db, $group_reports);
        $spots_cnt = $report_cnt = $comment_cnt = $image_cnt = 0;
        $sql = 'count("spotid") AS cnt FROM spots';
        $res = $db->select_query($sql);
        if (isset($res[0]['cnt'])) {
            $spots_cnt = $res[0]['cnt'];
        }
        $sql = 'count("id") AS cnt FROM spot_comments';
        $res = $db->select_query($sql);
        if (isset($res[0]['cnt'])) {
            $comment_cnt = $res[0]['cnt'];
        }
        $sql = 'count("id") AS cnt FROM spot_reports';
        $res = $db->select_query($sql);
        if (isset($res[0]['cnt'])) {
            $report_cnt = $res[0]['cnt'];
        }
        $sql = 'count("spotid") AS cnt FROM spot_images';
        $res = $db->select_query($sql);
        if (isset($res[0]['cnt'])) {
            $report_cnt = $res[0]['cnt'];
        }
        write_log("Deleted $spots_cnt spots, $comment_cnt comments, $report_cnt reports and $image_cnt images", LOG_NOTICE);
        update_queue_status ($db, $dbid, NULL, 0, 1);

        $res = $db->truncate_table('spots');
        $res = $db->truncate_table('spot_comments');
        $res = $db->truncate_table('spot_reports');
        $res = $db->truncate_table('spot_images');

        self::delete_image_cache($db, TRUE);

        echo_debug("Deleted $spots_cnt spots, $comment_cnt comments, $report_cnt reports and $image_cnt images", DEBUG_DATABASE);
        update_queue_status ($db, $dbid, NULL, 0, 100);
        purge_binaries($db, $groupid);
        purge_binaries($db, $comments_groupid);
        purge_binaries($db, $reports_groupid);

        return $spots_cnt;
    }

    public static function delete_image_cache(DatabaseConnection $db, $all=TRUE, $safety_expire=0)
    {
        $image_dir = get_dlpath($db) . IMAGE_CACHE_PATH;
        add_dir_separator($image_dir);
        if ($all === TRUE) {
            array_map('unlink', glob($image_dir . '*'));
        } else {
            $ids = array();
            $a = glob ($image_dir . '*');
            $cnt = count($a);
            if ($cnt > 0) {
                $ids = array_map('pathinfo', $a, array_fill(0, $cnt, PATHINFO_FILENAME));
            }
            $cnt = 0;
            foreach ($ids as $id) {
                $filename = $image_dir . $id . '.jpg';
                $sql = '"spotid" FROM spots WHERE "spotid"=?';
                $res = $db->select_query($sql, 1, array($id));
                if (!isset($res[0]['spotid'])) {
                    $cnt ++;
                    if (file_exists($filename)) {
                        @unlink($filename);
                    }
                }
            }
            echo_debug("Deleted $cnt spot images files from disc", DEBUG_DATABASE);
        }
    }

    public static function parse_spot_comment(array $header, array $spot_blacklist)
    {
        $res = array();
        $res['rating'] = 0; // default rating is 0
        foreach ($header as $line) {
            $line = explode(':', $line, 2);
            switch (strtolower($line[0])) {
                case 'message-id':
                    $msgid = trim($line[1], "<>\t ");
                    $res['messageid'] = $msgid;
                    $msgid_parts = explode('.', $msgid);
                    if (count($msgid_parts) == 5) {
                        $res['rating'] = (int)$msgid_parts[1];
                    }
                    break;
                case 'references' :
                    $ref = trim($line[1], "<>\t ");
                    $res['references'] = $ref;
                    break;
                case 'from':
                    $from = trim($line[1]);
                    $res['fullfrom'] = $from;
                    $pos = strpos($from, '<');
                    $res['from'] = trim(substr($from, 0, $pos - 1));
                    $spotter_id = self::parse_spotterid(substr($from, $pos));

                    if (isset($spot_blacklist[$spotter_id])) {
                        echo_debug("User $spotter_id on blacklist - spot comment not added", DEBUG_SERVER);

                        throw new exception('Poster blacklisted');
                    }
                    $res['spotter_id'] = $spotter_id;
                    break;
                case 'x-user-key':
                    $key = trim($line[1]);
                    $xml = simplexml_load_string($key);
                    if ($xml !== FALSE) {
                        $res['user-key']['exponent'] = (string) $xml->Exponent;
                        $res['user-key']['modulo'] = (string) $xml->Modulus;
                    }
                    break;
                case 'x-user-signature':
                    $sig = trim($line[1]);
                    $res['user-signature'] = SpotParser::unspecial_string($sig);
                    break;
                case 'date':
                    $sig = trim($line[1]);
                    $res['date'] = strtotime($sig);
                    break;
                case 'lines':
                    $res['lines'] = trim($line[1]);
                    break;
            }
        }

        return $res;
    }

    private static function update_spot_comment_ratings(DatabaseConnection$db, array $ratings)
    {
        foreach($ratings as $spotid => $rating) {
            $cnt = count($rating);
            $sum = array_sum($rating);
            $db->escape($cnt, FALSE);
            $db->escape($sum, FALSE);
            $sql = "UPDATE spots SET \"rating_count\" = \"rating_count\" + $cnt, \"rating\" = (\"rating\" + $sum) / (rating_count + $cnt) WHERE \"spotid\" = ?";
            $db->execute_query($sql, array($spotid));
        }
    }

    private static function parse_links($data)
    {
        $imdb_link = $moviemeter_link = $default_link = '';
        $links = array();
        $rv = preg_match_all('|(https?:\/\/[-a-z0-9_:./&%!@#$?^()+=\\;]+)|i', $data, $matches);
        if ($rv > 0) {
            foreach ($matches[1] as $match) {
                $links [] = $match;
            }
        }
        foreach ($links as $link) {
            if (stristr($link, 'imdb.') !== FALSE && $imdb_link == '') {
                $imdb_link = $link;
            } elseif (stristr($link, 'moviemeter.') !== FALSE && $moviemeter_link == '') {
                $moviemeter_link = $link;
            } elseif ($default_link == '') {
                $default_link = $link;
            }
        }
        $link = '';
        if ($imdb_link != '') {
            $link = $imdb_link;
        } elseif ($moviemeter_link != '') {
            $link = $moviemeter_link;
        } elseif ($default_link != '') {
            $link = $default_link;
        } else {
            return FALSE;
        }

        return $link;
    }

    private static function parse_spots_for_extset_data(DatabaseConnection $db, array $spot_data, $spotid)
    {
        $extset_data = array();
        $link_data = self::parse_links($spot_data['body']);
        if (($link_data !== FALSE) && ($link_data != $spot_data['url'])) {
            $extset_data['link'] = $link_data;
        }
        if (count($extset_data) > 0) {
            urd_extsetinfo::add_ext_setdata($db, $spotid, $extset_data, USERSETTYPE_SPOT, ESI_NOT_COMMITTED);
        }
    }

    private static function parse_spotterid($from)
    {
        $from = ltrim(trim($from), '<');
        $addr = explode('@', $from);
        if (count($addr) < 2) {
            return '';
        }
        $sig = explode('.', $addr[0]);
        $pubkey = SpotParser::unspecial_string($sig[0]);

        $spotterid = self::calculate_spotter_id($pubkey);

        return $spotterid;
    }

    public static function get_spot_by_messageid(DatabaseConnection $db, $message_id)
    {
        $sql = '"spotid" FROM spots WHERE "messageid"=?';
        $res = $db->select_query($sql, 1, array($message_id));
        if (!isset($res[0]['spotid'])) {
            throw new exception ('Spot not found ' . $message_id);
        }

        return $res[0]['spotid'];
    }

    public static function load_spot_comments(DatabaseConnection $db, URD_NNTP $nzb, action $item, $expire)
    {
        assert(is_numeric($expire));
        $sql = 'COUNT(*) AS "cnt" FROM spot_comments WHERE "spotid"=? OR "spotid"=?';
        $res = $db->select_query($sql, array('', '0'));
        if (!isset($res[0]['cnt'])) {
            $status = QUEUE_FINISHED;
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'No spot comments');

            return NO_ERROR;
        }
        $time_a = microtime(TRUE);
        $totalcount = $res[0]['cnt'];
        write_log("Getting $totalcount spot comments", LOG_NOTICE);
        $cnt = 0;
        $limit = 100;
        $spotSigning = new SpotSigning(extension_loaded('openssl'));
        $nzb->reconnect();
        $blacklist_url = get_config($db, 'spots_blacklist', '');
        $spots_blacklist = array();
        if ($blacklist_url != '') {
            $spots_blacklist = load_blacklist($db, NULL, blacklist::ACTIVE, TRUE);
        }
        echo_debug("Expire $expire days", DEBUG_SERVER);
        $expire_timestamp = time() - ($expire * 24 * 3600);
        echo_debug("Expire $expire_timestamp seconds", DEBUG_SERVER);
        static $cols = array('spotid', 'from', 'comment', 'userid', 'stamp');

        while (TRUE) {
            $sql = '"id", "message_id", "spotid" FROM spot_comments WHERE "spotid"=? OR "spotid"=?';
            $res = $db->select_query($sql, $limit, array('', '0'));
            if (!is_array($res)) {
                break;
            }
            $ids = array();

            $ratings = array();
            foreach ($res as $row) {
                $this_id = $row['id'];
                $msg_id = $row['message_id'];
                try {
                    $cnt++;
                    $header = $nzb->get_header($msg_id);
                    $comment = self::parse_spot_comment($header, $spots_blacklist);
                    if (!isset($comment['references'], $comment['from'], $comment['date'], $comment['user-key'])) {
                        throw new exception('Invalid spot comment ' . $msg_id);
                    }
                    $date = $comment['date'];
                    if ($date < $expire_timestamp) {
                        throw new exception('Comment too old: ' . $msg_id);
                    }
                    if ($comment['lines'] > 100) {
                        throw new exception('Comment too long: ' . $msg_id);
                    }

                    $ref_msg_id = $comment['references'];
                    try { 
                        $spotid = self::get_spot_by_messageid($db, $ref_msg_id);
                    } catch (exception $e) {
                        if (trim($row['spotid']) == '0') { // a quirk in postgresql / pdo seems to extend the char(32)  to 32 chars with spaces appended
                            throw $e;
                        }
                        echo_debug(DEBUG_SERVER, 'Spot not found');
                        $spotid = '1'; // we set it to 1 and after we finish set it to 0 so that comments we may have missed are updated the next run
                        $from = $body = $userid = ''; 
                        $date = 0;
                    }
                    if ($spotid != '1') { // don't need to get the comment yet, as we haven't the spot 
                        $body = $nzb->get_article($msg_id);
                        $body = $comment['body'] = array_map('utf8_encode', $body);
                        $body = implode("\n", $body);
                        if (strlen($body) > self::SPOT_COMMENT_SIZE_LIMIT) { 
                            $body = substr($body, 0, self::SPOT_COMMENT_SIZE_LIMIT); // body can only be 10kB long
                        }
                        $body = db_compress($body);
                        $from = utf8_encode($comment['from']);

                        $comment['verified'] = $spotSigning->verifyComment($comment);
                        $userid = $comment['userid'] = $spotSigning->calculate_userid($comment['user-key']['modulo']);
                        if (!$comment['verified']) {
                            throw new exception('Comment signature invalid for spot ' . $spotid);
                        }
                        if ($comment['rating'] > 0 && $comment['rating'] <= 10) {
                            $ratings[$spotid][] = $comment['rating'];
                        }
                    }
                    $vals = array($spotid, $from, $body, $userid, $date);
                    $db->update_query('spot_comments', $cols, $vals, '"id"=?', array($this_id));
                } catch (exception $e) {
                    $ids[] = "$this_id";
                    if ($e->getCode() != ERR_SPOT_NOT_FOUND) {
                        write_log($e->getMessage(), LOG_NOTICE);
                    }
                }
            }
            if (count($ids) > 0) {
                $db->delete_query('spot_comments', '"id" IN (' . (str_repeat('?,', count($ids) - 1) . '?') . ')', $ids);
            }

            $time_b = microtime(TRUE);
            $status = QUEUE_RUNNING;
            $perc = ceil(((float) $cnt * 100) / $totalcount);
            $time_left = 0;
            if ($perc != 0 && $cnt > 0) {
                $time_left = ($time_b - $time_a) * (($totalcount - $cnt) / $cnt);
            }
            self::update_spot_comment_ratings($db, $ratings);
            update_queue_status($db, $item->get_dbid(), NULL, $time_left, $perc, 'Getting spot comments');
        }
        $db->update_query_2('spot_comments', array('spotid' => 0), '"spotid"=?', array('1'));
        self::update_spots_comment_count($db);

        return $cnt;
    }

    static function load_spot_reports(DatabaseConnection $db, URD_NNTP $nzb, action $item, $expire)
    {
        assert(is_numeric($expire));
        $sql = 'COUNT(*) AS "cnt" FROM spot_reports WHERE "reference"=? OR "spotid"=?';
        $res = $db->select_query($sql, array('', '0'));
        if (!isset($res[0]['cnt'])) {
            $status = QUEUE_FINISHED;
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'No spot reports');

            return NO_ERROR;
        }
        $time_a = microtime(TRUE);
        $expire_timestamp = time() - ($expire * 24 * 3600);
        $totalcount = $res[0]['cnt'];
        write_log("Getting $totalcount spot reports", LOG_NOTICE);
        $cnt = 0;
        $limit = 100;
        while (TRUE) {
            $sql = '"id", "message_id", "spotid", "reference" FROM spot_reports WHERE "reference"=? OR "spotid"=?';
            $res = $db->select_query($sql, $limit, array('', '0'));
            if (!is_array($res)) {
                break;
            }
            $ids = array();
            foreach ($res as $row) {
                try {
                    $msg_id = $row['message_id'];
                    $id = $row['id'];
                    $header = $nzb->get_header($msg_id);
                    $report = self::parse_spot_report($header);
                    if (!isset($report['reference'], $report['date'])) {
                        throw new exception('Invalid spot report');
                    }
                    $ref_msg_id = $report['reference'];
                    try {
                        $spotid = self::get_spot_by_messageid($db, $ref_msg_id);
                    } catch (exception $e) {
                        if ($row['spotid'] == '0') {
                            throw $e;
                        }
                        echo_debug(DEBUG_SERVER, 'Spot not found');
                        $spotid = 1; // we set it to 1 and after we finish set it to 0 so that reports we may have missed are updated the next run
                    }

                    $date = $report['date'];
                    if ($date < $expire_timestamp) {
                        throw new exception('Report too old for spot ' . $id);
                    }
                    $db->update_query_2('spot_reports', array('reference'=>$ref_msg_id, 'spotid'=> $spotid, 'stamp'=>$date), '"id"=?', array($id));
                    $cnt++;
                } catch (exception $e) {
                    $ids[] = $row['id'];
                    write_log($e->getMessage(), LOG_INFO);
                }
            }
            if (count($ids) > 0) {
                $db->delete_query('spot_reports', '"id" IN (' . (str_repeat('?,', count($ids) -1)) . '?)', $ids);
            }

            $time_b = microtime(TRUE);
            $status = QUEUE_RUNNING;
            $perc = ceil(((float) $cnt * 100) / $totalcount);
            $time_left = 0;
            if ($perc != 0 && $cnt > 0) {
                $time_left = ($time_b - $time_a) * (($totalcount - $cnt) / $cnt);
            }
            update_queue_status($db, $item->get_dbid(), NULL, $time_left, $perc, 'Getting spot reports');
        }

        $db->update_query_2('spot_reports', array('reference'=>'0'), '"spotid"=?', array('1'));
        self::update_spots_report_count($db);
        return $cnt;
    }

    static private function update_spots_comment_count(DatabaseConnection $db)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        // updating count in spots table
        $subsql = 'SELECT COUNT(spot_comments."spotid") FROM spot_comments WHERE spots."spotid" = spot_comments."spotid" GROUP BY spot_comments."spotid"';
        $sql = "UPDATE spots SET \"comments\" = ( CASE WHEN ( $subsql ) IS NULL THEN 0 ELSE ( $subsql ) END )";
        $db->execute_query($sql);
    }

    static private function update_spots_report_count(DatabaseConnection $db)
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        // updating count in spots table
        $subsql = 'SELECT COUNT(spot_reports."spotid") FROM spot_reports WHERE spots."spotid" = spot_reports."spotid" GROUP BY spot_reports."spotid"';
        $sql = "UPDATE spots SET \"reports\" = ( CASE WHEN ( $subsql ) IS NULL THEN 0 ELSE ( $subsql ) END )";
        $db->execute_query($sql);
    }

    static function load_spots(DatabaseConnection $db, URD_NNTP $nzb, action $item)
    {
        $sql = 'count(*) AS "cnt" FROM spot_messages';
        $res = $db->select_query($sql);
        if (!isset($res[0]['cnt'])) {
            $status = QUEUE_FINISHED;
            update_queue_status($db, $item->get_dbid(), $status, 0, 100, 'No spots');

            return NO_ERROR;
        }
        $totalcount = $res[0]['cnt'];
        write_log("Getting $totalcount spots", LOG_NOTICE);
        $limit = 100;
        $blacklist_url = get_config($db, 'spots_blacklist', '');
        $spots_blacklist = array();
        if ($blacklist_url != '') {
            $spots_blacklist = load_blacklist($db, NULL, blacklist::ACTIVE, TRUE);
        }
        $cnt = 0;
        $max_cat_count = get_config($db, 'spots_max_categories', 0);
        $time_a = microtime(TRUE);
        $expire = get_config($db, 'spots_expire_time', DEFAULT_SPOTS_EXPIRE_TIME);
        $expire_time = time() - ($expire * 24 * 3600);
           
$time1 = $time2 = $time3 = $time4 = $time5= $time6= $time7 = $time8 = $time9 = $timea = 0;
        update_queue_status($db, $item->get_dbid(), NULL, 0, 0, 'Getting spots');
        while (TRUE) {
            $sql = '"id", "message_id" FROM spot_messages';
            $res = $db->select_query($sql, $limit);
            if (!is_array($res)) {
                break;
            }
            $ids = array();
            foreach ($res as $row) {
$time1 = microtime(true);
                $msg_id = $row['message_id'];
                $ids[] = $row['id'];
                try {
$time2 += microtime(true) - $time1;
                    $header = $nzb->get_header($msg_id);
$time3 += microtime(true) - $time1;
                    $spot_data = self::parse_spot_header($header, $msg_id, $spots_blacklist);
$time4 += microtime(true) - $time1;
                    if (($spot_data != FALSE) && ($spot_data['date'] > $expire_time)) {
                        $spot_data['body'] = $nzb->get_article($msg_id);
$time5 += microtime(true) - $time1;
                        self::parse_spot_data($spot_data);
$time6 += microtime(true) - $time1;
                        if ($max_cat_count > 0 && $spot_data['subcat_count'] > $max_cat_count) {
                            echo_debug(DEBUG_SERVER, 'Rejected - too many subcats ' . $spot_data['subcat_count'] . " > $max_cat_count");
                            continue;
                        }
                        if (!isset(SpotCategories::$_head_categories[$spot_data['category']])) {
                            echo_debug(DEBUG_SERVER, 'Rejected - Invalid category');
                            continue;
                        }
                        $spot_data['body'] = implode("\n", $spot_data['body']);
                        if (strlen($spot_data['body']) > (self::SPOT_BODY_SIZE_LIMIT)) { // we skip extremely large spots
                            echo_debug(DEBUG_SERVER, 'Rejected - spot too large');
                            continue;
                        }
$time7 += microtime(true) - $time1;
                        self::verify_spot($spot_data);
                        if (!$spot_data['verified']) {
                            write_log('Signature on spot incorrect', LOG_NOTICE);
                        }
$time8 += microtime(true) - $time1;
                        $spotid = self::add_spot($db, $spot_data);
$time9 += microtime(true) - $time1;
                        self::parse_spots_for_extset_data($db, $spot_data, $spotid);
$timea += microtime(true) - $time1;
                    }
                } catch (exception $e) {
                    write_log($e->getMessage(), LOG_WARNING);
                }
            }
var_dump($time1, $time2, $time3, $time4, $time5, $time6, $time7, $time8, $time9, $timea);
            if (count($ids) > 0) {
                $db->delete_query('spot_messages', '"id" IN (' . (str_repeat('?,', count($ids) - 1) . '?') . ')', $ids);
            }
            $cnt += count($res);
            $time_b = microtime(TRUE);
            $status = QUEUE_RUNNING;
            $perc = ceil(((float) $cnt * 100) / $totalcount);
            $time_left = 0;
            if ($perc != 0 && $cnt > 0) {
                $time_left = ($time_b - $time_a) * (($totalcount - $cnt) / $cnt);
            }
            update_queue_status($db, $item->get_dbid(), NULL, $time_left, $perc, 'Getting spots');
        }

        return $cnt;
    }

    static private function calculate_spotter_id($userkey)
    {
        $userSignCrc = crc32(base64_decode($userkey));

        $userIdTmp =
            chr($userSignCrc & 0xFF) .
            chr(($userSignCrc >> 8) & 0xFF) .
            chr(($userSignCrc >> 16) & 0xFF) .
            chr(($userSignCrc >> 24) & 0xFF);

        return str_replace(array('/', '+', '='), '', base64_encode($userIdTmp));
    }
}
