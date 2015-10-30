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
    private $db;

    public function __construct(DatabaseConnection $db)
    {
        $this->db = $db;
    }

    private static function make_spot_id($spotid, $message_id, $poster)
    {
        return md5($spotid . $message_id . $poster);
    }

    public function add_spot(array $spot_data)
    {
        //echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        $spotid = self::make_spot_id($spot_data['spotid'], $spot_data['messageid'], $spot_data['poster']);
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
            'reference',
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
            $spot_data['reference'],
            0,
            0,
            0,
            0,
        );
        $this->db->insert_query('spots', $cols, $vals);
        if (is_string($spot_data['image'])) {
            $this->db->insert_query('spot_images', array('spotid', 'image', 'fetched', 'stamp'), 
                    array($spotid, $spot_data['image'], 
                        ((substr($spot_data['image'], 0, 9) == 'articles:') ? 0 : 1), $spot_data['date']));
        }

        return $spotid;
    }

    private static function parse_spot_report(array $lines)
    {
        $header = $report = array();
        foreach ($lines as $line) {
            $hdr = explode(':', $line, 2);
            if (count($hdr) < 2) {
                continue;
            }
            $header[$hdr[0]] = trim($hdr[1]);
        }

        $reportId = trim($header['Message-ID'], '<>');

        $tmp = explode(' ', $header['Subject'], 3);
        if (count($tmp) > 2) {
            $report['date'] = strtotime($header['Date']);
            $report['message_id'] = $reportId;
            $report['keyword'] = $tmp[0];
            $report['reference'] = substr($tmp[1], 1, -1); // remove the < and >
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
        foreach ($header as $line) {
            $parts = explode(':', $line, 2);
            if (!isset($parts[1])) {
                echo_debug('Something wrong with the header', DEBUG_SERVER);
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
                case 'x-user-signature':
                    $spot_data['user-signature'] = spotparser::unspecial_string(substr($line, 18));
                    break;
                case 'x-xml':
                    $spot_data['xml'] .= substr($line, 7);
                    break;
                case 'x-user-avatar':
                    $spot_data['user-avatar'] .= substr($line, 15);
                    break;
            }
        }
        if ($spot_data['xml-signature'] == '' || $spot_data['xml'] == '' || $spot_data['user-key'] == '' || $spot_data['user-signature'] == '') {
            echo_debug('No valid signature', DEBUG_SERVER);
            return FALSE;
        }
        
        // Parse nu de XML file, alles wat al gedefinieerd is eerder wordt niet overschreven
        $spot_data = array_merge(spotparser::parse_full($spot_data['xml']), $spot_data);
        if ($spot_data['title'] == '') {
            write_log('Something wrong with the title', LOG_INFO);
            return FALSE;
        }

        return $spot_data;
    }

    private static function verify_spot(array &$spot_data)
    {
        $spotSigning = new spotsigning(extension_loaded('openssl'));
        $spot_data['verified'] = $spotSigning->verifyFullSpot($spot_data);
        // als de spot verified is, toon dan de userid van deze user
        if ($spot_data['verified']) {
            $spot_data['userid'] = self::calculate_spotter_id($spot_data['user-key']['modulo']);
            echo_debug('verified spot for ' . $spot_data['userid'], DEBUG_SERVER);
        } else {
            echo_debug('Unverified spot', DEBUG_SERVER);
        }

        return $spot_data;
    }

    private static function parse_spot_data(array &$spot_data)
    {
        $spotParser = new spotparser();
        $spot_data = array_merge($spotParser->parse_full($spot_data['xml']), $spot_data);
    }

    public function expire_spots($dbid)
    {
        assert(is_numeric($dbid));
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        try {
            $group_name = get_config($this->db, 'spots_group');
            $group_id = group_by_name($this->db, $group_name);
            $expire_time = get_config($this->db, 'spots_expire_time', DEFAULT_SPOTS_EXPIRE_TIME);
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
        $spam_count = $keep_int = '';
        $inputarr = array(':stamp' => $expire);
        $spot_expire_spam_count = get_config($this->db, 'spot_expire_spam_count', 0);
        if ($spot_expire_spam_count > 0) {
            $spam_count = ' OR spots."reports" > :reports';
            $inputarr[':reports'] = $spot_expire_spam_count;
        }
        if (get_config($this->db, 'keep_interesting', FALSE)) {
            $keep_int = ' AND "spotid" NOT IN (SELECT "setID" FROM usersetinfo WHERE "type" = :type AND "statusint" = :marking) ';
            $inputarr[':type'] = USERSETTYPE_SPOT;
            $inputarr[':marking'] = sets_marking::MARKING_ON;
        }

        echo_debug('Deleting expired spots', DEBUG_DATABASE);

        $sql = "count(*) AS cnt FROM spots WHERE (\"stamp\" < :stamp $spam_count ) $keep_int";
        $res = $this->db->select_query($sql, $inputarr);
        $cnt = (isset($res[0]['cnt'])) ? $res[0]['cnt'] : 0;
        write_log('Deleting ' . $cnt . ' spots', LOG_INFO);
        update_queue_status ($this->db, $dbid, NULL, 0, 1);

        // expiring
        $res = $this->db->delete_query('spots', " (\"stamp\" < :stamp $spam_count ) $keep_int", $inputarr);
        echo_debug("Deleted {$cnt} spots", DEBUG_DATABASE);
        $sql = 'count(*) AS "cnt" FROM spot_images WHERE "spotid" NOT IN (SELECT "spotid" FROM spots)';
        $res = $this->db->select_query($sql);
        $cnt = (isset($res[0]['cnt'])) ? $res[0]['cnt'] : 0;
        update_queue_status ($this->db, $dbid, NULL, 0, 10);

        $this->delete_image_cache(FALSE, $safety_expire);

        update_queue_status ($this->db, $dbid, NULL, 0, 20);
        // delete files from cache too

        if ($cnt > 0) {
            write_log('Deleting '. $cnt . ' spot images', LOG_INFO);
            $res = $this->db->delete_query('spot_images', '"spotid" NOT IN (SELECT "spotid" FROM spots) AND "stamp" < :stamp', array(':stamp'=>$safety_expire));
            echo_debug("Deleted {$cnt} spot images", DEBUG_DATABASE);
        }

        update_queue_status ($this->db, $dbid, NULL, 0, 40);
        $sql = 'count(*) AS cnt FROM spot_comments WHERE "spotid" NOT IN (SELECT "spotid" FROM spots) AND "stamp" < :stamp';
        $res = $this->db->select_query($sql, array(':stamp'=>$safety_expire));
        $cnt = (isset($res[0]['cnt'])) ? $res[0]['cnt'] : 0;

        update_queue_status ($this->db, $dbid, NULL, 0, 60);
        if ($cnt > 0) {
            write_log('Deleting '. $cnt . ' spot comments', LOG_INFO);
            $res = $this->db->delete_query('spot_comments', '"spotid" NOT IN (SELECT "spotid" FROM spots) AND "stamp" < :stamp', array(':stamp'=>$safety_expire));
            echo_debug("Deleted {$cnt} spot comments", DEBUG_DATABASE);
        }

        update_queue_status ($this->db, $dbid, NULL, 0, 70);
        // expiring reports
        $sql = 'count(*) AS cnt FROM spot_reports WHERE "spotid" NOT IN (SELECT "spotid" FROM spots) AND "stamp" < :stamp';
        $res = $this->db->select_query($sql, array(':stamp'=>$safety_expire));
        $cnt = (isset($res[0]['cnt'])) ? $res[0]['cnt'] : 0;
        update_queue_status ($this->db, $dbid, NULL, 0, 80);

        $res = $this->db->delete_query('extsetdata', '"setID" NOT IN (SELECT "spotid" FROM spots) AND "type" = :type', array(':type'=>USERSETTYPE_SPOT));
        update_queue_status ($this->db, $dbid, NULL, 0, 90);

        if ($cnt > 0) {
            write_log('Deleting ' . $cnt . ' spot reports', LOG_INFO);
            $res = $this->db->delete_query('spot_reports', '"spotid" NOT IN (SELECT "spotid" FROM spots) AND "stamp" < :stamp', array(':stamp'=>$safety_expire));
            echo_debug("Deleted {$cnt} spot reports", DEBUG_DATABASE);
        }
        $this->update_spots_report_count();
        $this->update_spots_comment_count();
        update_queue_status ($this->db, $dbid, NULL, 0, 100);

        return $cnt;
    }

    public function purge_spots($dbid)
    {
        assert(is_numeric($dbid));
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        $group = get_config($this->db, 'spots_group');
        $groupid = group_by_name($this->db, $group);
        $group_comments = get_config($this->db, 'spots_comments_group');
        $comments_groupid = group_by_name($this->db, $group_comments);
        $group_reports = get_config($this->db, 'spots_reports_group');
        $reports_groupid = group_by_name($this->db, $group_reports);
        $spots_cnt = $report_cnt = $comment_cnt = $image_cnt = 0;
        $sql = 'count("spotid") AS cnt FROM spots';
        $res = $this->db->select_query($sql);
        if (isset($res[0]['cnt'])) {
            $spots_cnt = $res[0]['cnt'];
        }
        $sql = 'count("id") AS cnt FROM spot_comments';
        $res = $this->db->select_query($sql);
        if (isset($res[0]['cnt'])) {
            $comment_cnt = $res[0]['cnt'];
        }
        $sql = 'count("id") AS cnt FROM spot_reports';
        $res = $this->db->select_query($sql);
        if (isset($res[0]['cnt'])) {
            $report_cnt = $res[0]['cnt'];
        }
        $sql = 'count("spotid") AS cnt FROM spot_images';
        $res = $this->db->select_query($sql);
        if (isset($res[0]['cnt'])) {
            $report_cnt = $res[0]['cnt'];
        }
        write_log("Deleted $spots_cnt spots, $comment_cnt comments, $report_cnt reports and $image_cnt images", LOG_NOTICE);
        update_queue_status ($this->db, $dbid, NULL, 0, 1);

        $res = $this->db->truncate_table('spots');
        $res = $this->db->truncate_table('spot_comments');
        $res = $this->db->truncate_table('spot_reports');
        $res = $this->db->truncate_table('spot_images');

        $this->delete_image_cache(TRUE);

        echo_debug("Deleted $spots_cnt spots, $comment_cnt comments, $report_cnt reports and $image_cnt images", DEBUG_DATABASE);
        update_queue_status ($this->db, $dbid, NULL, 0, 100);
        $ug = new urdd_group($this->db);
        $ug->purge_binaries($groupid);
        $ug->purge_binaries($comments_groupid);
        $ug->purge_binaries($reports_groupid);

        return $spots_cnt;
    }

    public function delete_image_cache($all=TRUE, $safety_expire=0)
    {
        $image_dir = get_dlpath($this->db) . IMAGE_CACHE_PATH;
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
                $sql = '"spotid" FROM spots WHERE "spotid" = :id';
                $res = $this->db->select_query($sql, 1, array(':id' => $id));
                if (!isset($res[0]['spotid'])) {
                    $cnt++;
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
        $res = array(
            'rating' => 0, // default rating is 0
            'user-avatar' => ''
        );
        foreach ($header as $line) {
            $line = explode(':', $line, 2);
            switch (strtolower($line[0])) {
                case 'message-id':
                    $msgid = trim($line[1], "<>\t ");
                    $res['messageid'] = $msgid;
                    $msgid_parts = explode('.', $msgid, 6);
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
                    $pos = strpos($from, '<');
                    $spotter_id = self::parse_spotterid(substr($from, $pos));
                    if (isset($spot_blacklist[$spotter_id])) {
                        echo_debug("User $spotter_id on blacklist - spot comment not added", DEBUG_SERVER);
                        throw new exception('Poster blacklisted');
                    }
                    $res['fullfrom'] = $from;
                    $res['from'] = trim(substr($from, 0, $pos - 1));
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
                    $res['user-signature'] = spotparser::unspecial_string($sig);
                    break;
                case 'date':
                    $sig = trim($line[1]);
                    $res['date'] = strtotime($sig);
                    break;
                case 'lines':
                    $res['lines'] = trim($line[1]);
                    break;
                case 'x-user-avatar':
                    $res['user-avatar'] .= trim($line[1]);
                    break;
            }
        }
        return $res;
    }

    private function update_spot_comment_ratings(array $ratings)
    {
        $sql = 'UPDATE spots SET "rating_count" = "rating_count" + :cnt1, "rating" = ("rating" + :sum) / ("rating_count" + :cnt2) WHERE "spotid" = :spotid';
        foreach($ratings as $spotid => $rating) {
            $cnt = count($rating);
            $sum = array_sum($rating);
            $this->db->execute_query($sql, array(':cnt1' => $cnt, ':cnt2' => $cnt, ':sum' => $sum, ':spotid' => $spotid));
        }
    }

    private static function parse_links($data, $spot_url)
    {
        $imdb_link = $moviemeter_link = $default_link = '';
        $links = array();
        $rv = preg_match_all('|(https?:\/\/[-a-z0-9_:./&%!@,#$?^()+=\\;]+)|i', $data, $matches);
        if ($rv > 0) {
            foreach ($matches[1] as $link) {
                if ((stristr($link, 'imdb.') !== FALSE) && ($imdb_link == '')) {
                    $imdb_link = $link;
                } elseif ((stristr($link, 'moviemeter.') !== FALSE) && ($moviemeter_link == '')) {
                    $moviemeter_link = $link;
                } elseif ($default_link == '') {
                    $default_link = $link;
                }
            }
        } else {
            return FALSE;
        }
        $link = '';
        if ($imdb_link != '' && $imdb_link != $spot_url) {
            $link = $imdb_link;
        } elseif ($moviemeter_link != '' && $moviemeter_link != $spot_url) {
            $link = $moviemeter_link;
        } elseif ($default_link != '' && $default_link != $spot_url) {
            $link = $default_link;
        } else {
            return FALSE;
        }

        return $link;
    }

    private function update_spot_reference(DatabaseConnection $db, $spotid, $reference) 
    {
        $sql = '"reference" FROM spots WHERE "spotid" = :spotid';
        $res = $db->select_query($sql, 1, array(':spotid' => $spotid));
        if (!isset($res[0]['reference'])) {
            echo_debug("Setting reference: $reference", DEBUG_SERVER);
            $db->update_query_2('spots', array('reference'=> $reference), '"spotid"=?', array($setid));
        }
    }


    private function parse_spots_for_extset_data(DatabaseConnection $db, array $spot_data, $spotid)
    {
        $link_data = self::parse_links($spot_data['body'], $spot_data['url']);
        //echo_debug("Found links: " . count( $link_data), DEBUG_SERVER);
        $extset_data = array();
        if (($link_data !== FALSE) && ($link_data != $spot_data['url'])) {
            $extset_data['link'] = $link_data;
        }
        if (count($extset_data) > 0) {
            //echo_debug("Found link: $link_data", DEBUG_SERVER);
            $reference = find_reference($link_data);
            //      echo_debug("Found ref: $reference", DEBUG_SERVER);
            urd_extsetinfo::add_ext_setdata($db, $spotid, $extset_data, USERSETTYPE_SPOT, ESI_NOT_COMMITTED, FALSE);
            self::update_spot_reference($db, $spotid, $reference);
        }
    }

    private static function parse_spotterid($from)
    {
        $from = ltrim(trim($from), '<');
        $addr = explode('@', $from, 2);
        if (!isset($addr[1])) {
            return '';
        }
        $sig = explode('.', $addr[0]);
        $pubkey = spotparser::unspecial_string($sig[0]);
        $spotterid = self::calculate_spotter_id($pubkey);

        return $spotterid;
    }

    public function get_spot_by_messageid($message_id)
    {
        $sql = '"spotid" FROM spots WHERE "messageid"=:msg_id';
        $res = $this->db->select_query($sql, 1, array(':msg_id' => $message_id));
        if (!isset($res[0]['spotid'])) {
            throw new exception ('Spot not found ' . $message_id, ERR_SPOT_NOT_FOUND);
        }

        return $res[0]['spotid'];
    }

    public function load_spot_comments(URD_NNTP $nzb, action $item, $expire)
    {
        assert(is_numeric($expire));
        $sql = 'COUNT(*) AS "cnt" FROM spot_comments WHERE "spotid"=:spotid1 OR "spotid"=:spotid2';
        $res = $this->db->select_query($sql, array(':spotid1'=> '', ':spotid2'=>'0'));
        if (!isset($res[0]['cnt'])) {
            update_queue_status($this->db, $item->get_dbid(), QUEUE_FINISHED, 0, 100, 'No spot comments');

            return NO_ERROR;
        }
        $load_avatars = get_config($this->db, 'download_comment_avatar', 0);
        $totalcount = $res[0]['cnt'];
        write_log("Getting $totalcount spot comments", LOG_NOTICE);
        $cnt = 0;
        $limit = 100;
        $spotSigning = new spotsigning(extension_loaded('openssl'));
        $nzb->reconnect();
        $blacklist_url = get_config($this->db, 'spots_blacklist', '');
        $spots_blacklist = array();
        if ($blacklist_url != '') {
            $spots_blacklist = load_blacklist($this->db, NULL, blacklist::ACTIVE, TRUE);
        }
        echo_debug("Expire $expire days", DEBUG_SERVER);
        $expire_timestamp = time() - ($expire * 24 * 3600);
        echo_debug("Expire $expire_timestamp seconds", DEBUG_SERVER);
        static $cols = array('spotid', 'from', 'comment', 'userid', 'stamp', 'user_avatar');
        $arr1 = array(':spotid1' => '', ':spotid2' => '0');
        $sql = '"id", "message_id", "spotid" FROM spot_comments WHERE "spotid"=:spotid1 OR "spotid"=:spotid2';

        $time_a = microtime(TRUE);
        while (TRUE) {
            $res = $this->db->select_query($sql, $limit, $arr1);
            if (!is_array($res)) {
                break;
            }
            $delete_ids = $ids = $msg_ids = array();
            foreach ($res as $row) {
                $msg_ids[] = $row['message_id'];
                $ids[ $row['message_id'] ] = array('id' => $row['id'], 'spotid' => $row['spotid']);
            }
           unset($res);
            try {
                $headers = $nzb->get_header_multi($msg_ids);
            } catch (exception $e) {
                write_log($e->getMessage(), LOG_WARNING);
            }
            $ratings = array();
            foreach ($headers as $msg_id => $header) {
                if (!isset($ids[ $msg_id ]) || !is_array($header)) {
                    echo_debug('Message not found' . $msg_id, DEBUG_SERVER);
                    continue;
                }
                $this_id = $ids[ $msg_id ]['id'];
                try {
                    $cnt++;
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
                        $spotid = $this->get_spot_by_messageid($ref_msg_id);
                    } catch (exception $e) {
                        if (trim($ids[ $msg_id ]['spotid']) == '0') { // a quirk in postgresql / pdo seems to extend the char(32)  to 32 chars with spaces appended
                            throw $e;
                        }
                        echo_debug(DEBUG_SERVER, 'Spot not found');
                        $spotid = '1'; // we set it to 1 and after we finish set it to 0 so that comments we may have missed are updated the next run
                        $from = $body = $userid = ''; 
                        $date = 0;
                    }
                    if ($spotid != '1') { // don't need to get the comment yet, as we haven't the spot 
                        $body = $nzb->get_article($msg_id);
                        $body = $comment['body'] = utf8_encode(implode("\n", $body));
                        if (strlen($body) > self::SPOT_COMMENT_SIZE_LIMIT) { 
                            $body = substr($body, 0, self::SPOT_COMMENT_SIZE_LIMIT); // body can only be 10kB long
                        }
                        $body = db_compress($body);
                        $from = utf8_encode($comment['from']);

                        $comment['verified'] = $spotSigning->verifyComment($comment);
                        if (!$comment['verified']) {
                            throw new exception('Comment signature invalid for spot ' . $spotid);
                        }
                        $userid = $comment['userid'] = self::calculate_spotter_id($comment['user-key']['modulo']);
                        if ($comment['rating'] > 0 && $comment['rating'] <= 10) {
                            $ratings[$spotid][] = $comment['rating'];
                        }
                        $user_avatar = '';
                        if ($load_avatars) {
                            $user_avatar = $comment['user-avatar'];
                        }
                    }
                    $vals = array($spotid, $from, $body, $userid, $date, $user_avatar);
                    $this->db->update_query('spot_comments', $cols, $vals, '"id"=?', array($this_id));
                } catch (exception $e) {
                    $delete_ids[] = $this_id;
                    if ($e->getCode() != ERR_SPOT_NOT_FOUND) {
                        write_log($e->getMessage(), LOG_NOTICE);
                    }
                }
            }
            $del_count = count($delete_ids);
            if ($del_count > 0) {
                $this->db->delete_query('spot_comments', '"id" IN (' . (str_repeat('?,', $del_count - 1) . '?') . ')', $delete_ids);
            }

            $perc = ceil(((float) $cnt * 100) / $totalcount);
            $time_left = 0;
            if ($perc != 0 && $cnt > 0) {
                $time_b = microtime(TRUE);
                $time_left = ($time_b - $time_a) * (($totalcount - $cnt) / $cnt);
            }
            $this->update_spot_comment_ratings($ratings);
            update_queue_status($this->db, $item->get_dbid(), NULL, $time_left, $perc, 'Getting spot comments');
        }
        $this->db->update_query_2('spot_comments', array('spotid' => 0), '"spotid"=?', array('1'));
        self::update_spots_comment_count($this->db);

        return $cnt;
    }

    public function load_spot_reports(URD_NNTP $nzb, action $item, $expire)
    {
        assert(is_numeric($expire));
        $sql = 'COUNT(*) AS "cnt" FROM spot_reports WHERE "reference"=:ref OR "spotid"=:spotid';
        $res = $this->db->select_query($sql, array(':ref'=>'', ':spotid'=>'0'));
        if (!isset($res[0]['cnt'])) {
            $status = QUEUE_FINISHED;
            update_queue_status($this->db, $item->get_dbid(), $status, 0, 100, 'No spot reports');

            return NO_ERROR;
        }
        $totalcount = $res[0]['cnt'];
        write_log("Getting $totalcount spot reports", LOG_NOTICE);
        $cnt = 0;
        $limit = 100;
        $sql = '"id", "message_id", "spotid" FROM spot_reports WHERE "reference"=:ref OR "spotid"=:spotid';
        $expire_timestamp = time() - ($expire * 24 * 3600);
        $time_a = microtime(TRUE);
        while (TRUE) {
            $res = $this->db->select_query($sql, $limit, array(':ref' => '', ':spotid' => '0'));
            if (!is_array($res)) {
                break;
            }
            $delete_ids = $ids = $msg_ids = array();
            foreach ($res as $row) {
                $msg_ids[] = $row['message_id'];
                $ids[ $row['message_id'] ] = array('id'=>$row['id'], 'spotid'=>$row['spotid']);
            }
            unset($res);
            try {
                $headers = $nzb->get_header_multi($msg_ids);
            } catch (exception $e) {
                write_log($e->getMessage(), LOG_WARNING);
            }
            $ratings = array();
            foreach ($headers as $msg_id => $header) {
                $id = $ids[ $msg_id ]['id'];
                try {
                    $report = self::parse_spot_report($header);
                    if (!isset($report['reference'], $report['date'])) {
                        throw new exception('Invalid spot report');
                    }
                    $ref_msg_id = $report['reference'];
                    try {
                        $spotid = $this->get_spot_by_messageid($ref_msg_id);
                    } catch (exception $e) {
                        if ($ids[ $msg_id ]['spotid'] == '0') {
                            throw $e;
                        }
                        echo_debug(DEBUG_SERVER, 'Spot not found');
                        $spotid = 1; // we set it to 1 and after we finish set it to 0 so that reports we may have missed are updated the next run
                    }

                    $date = $report['date'];
                    if ($date < $expire_timestamp) {
                        throw new exception('Report too old for spot ' . $id);
                    }
                    $this->db->update_query_2('spot_reports', array('reference'=>$ref_msg_id, 'spotid'=> $spotid, 'stamp'=>$date), '"id"=?', array($id));
                    $cnt++;
                } catch (exception $e) {
                    $delete_ids[] = $id;
                    write_log($e->getMessage(), LOG_INFO);
                }
            }
            $del_count = count($delete_ids);
            if ($del_count > 0) {
                $this->db->delete_query('spot_reports', '"id" IN (' . (str_repeat('?,',  $del_count - 1)) . '?)', $delete_ids);
            }

            $status = QUEUE_RUNNING;
            $perc = ceil(((float) $cnt * 100) / $totalcount);
            $time_left = 0;
            if ($perc != 0 && $cnt > 0) {
                $time_b = microtime(TRUE);
                $time_left = ($time_b - $time_a) * (($totalcount - $cnt) / $cnt);
            }
            update_queue_status($this->db, $item->get_dbid(), NULL, $time_left, $perc, 'Getting spot reports');
        }

        $this->db->update_query_2('spot_reports', array('reference'=>'0'), '"spotid"=?', array('1'));
        $this->update_spots_report_count();
        return $cnt;
    }

    private function update_spots_comment_count()
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        // updating count in spots table
        $subsql = 'SELECT COUNT(spot_comments."spotid") FROM spot_comments WHERE spots."spotid" = spot_comments."spotid" GROUP BY spot_comments."spotid"';
        $sql = "UPDATE spots SET \"comments\" = ( CASE WHEN ( $subsql ) IS NULL THEN 0 ELSE ( $subsql ) END )";
        $this->db->execute_query($sql);
    }

     private function update_spots_report_count()
    {
        echo_debug_function(DEBUG_SERVER, __FUNCTION__);
        // updating count in spots table
        $subsql = 'SELECT COUNT(spot_reports."spotid") FROM spot_reports WHERE spots."spotid" = spot_reports."spotid" GROUP BY spot_reports."spotid"';
        $sql = "UPDATE spots SET \"reports\" = ( CASE WHEN ( $subsql ) IS NULL THEN 0 ELSE ( $subsql ) END )";
        $this->db->execute_query($sql);
    }

    public function load_spots(URD_NNTP $nzb, action $item)
    {
        $sql = 'count(*) AS "cnt" FROM spot_messages';
        $res = $this->db->select_query($sql);
        if (!isset($res[0]['cnt'])) {
            $status = QUEUE_FINISHED;
            update_queue_status($this->db, $item->get_dbid(), $status, 0, 100, 'No spots');
            return NO_ERROR;
        }
        $totalcount = $res[0]['cnt'];
        write_log("Getting $totalcount spots", LOG_NOTICE);
        $limit = 100;
        $blacklist_url = get_config($this->db, 'spots_blacklist', '');
        $spots_blacklist = array();
        if ($blacklist_url != '') {
            $spots_blacklist = load_blacklist($this->db, NULL, blacklist::ACTIVE, TRUE);
        }
        $cnt = 0;
        $max_cat_count = get_config($this->db, 'spots_max_categories', 0);
        $time_a = microtime(TRUE);
        $expire = get_config($this->db, 'spots_expire_time', DEFAULT_SPOTS_EXPIRE_TIME);
        $expire_time = time() - ($expire * 24 * 3600);
           
        update_queue_status($this->db, $item->get_dbid(), NULL, 0, 0, 'Getting spots');
        $sql = '"id", "message_id" FROM spot_messages';
        while (TRUE) {
            $res = $this->db->select_query($sql, $limit);
            if (!is_array($res)) {
                break;
            }
            $ids = array();
            $msg_ids = array();
            foreach ($res as $row) {
                $msg_ids[] = $row['message_id'];
                $ids[] = $row['id'];
            }
            $cnt += count($res);
            unset($res);
            try {
                $headers = $nzb->get_header($msg_ids);
            } catch (exception $e) {
                write_log($e->getMessage(), LOG_WARNING);
            }
            foreach ($headers as $msg_id => $header) {
                try {
                    if ($header == '') { 
                        continue; 
                    }
                    $spot_data = self::parse_spot_header($header, $msg_id, $spots_blacklist);
                    if (($spot_data != FALSE) && ($spot_data['date'] > $expire_time)) {
                        $spot_data['body'] = $nzb->get_article($msg_id);
                        self::parse_spot_data($spot_data);
                        if ($max_cat_count > 0 && $spot_data['subcat_count'] > $max_cat_count) {
                            echo_debug(DEBUG_SERVER, 'Rejected - too many subcats ' . $spot_data['subcat_count'] . " > $max_cat_count");
                            continue;
                        }
                        if (!isset(SpotCategories::$_head_categories[$spot_data['category']])) {
                            echo_debug(DEBUG_SERVER, 'Rejected - Invalid category');
                            continue;
                        }
                        $spot_data['body'] = utf8_encode(implode("\n", $spot_data['body']));
                        if (strlen($spot_data['body']) > (self::SPOT_BODY_SIZE_LIMIT)) { // we skip extremely large spots
                            echo_debug(DEBUG_SERVER, 'Rejected - spot too large');
                            continue;
                        }
                        self::verify_spot($spot_data);
                        if (!$spot_data['verified']) {
                            write_log('Signature on spot incorrect', LOG_NOTICE);
                        }
                        $spot_data['reference'] = find_reference($spot_data['url']);
                        $spotid = $this->add_spot($spot_data);
                        $this->parse_spots_for_extset_data($this->db, $spot_data, $spotid);
                    }
                } catch (exception $e) {
                    write_log($e->getMessage(), LOG_WARNING);
                }
            }
            if (count($ids) > 0) {
                $this->db->delete_query('spot_messages', '"id" IN (' . (str_repeat('?,', count($ids) - 1) . '?') . ')', $ids);
            }
            $status = QUEUE_RUNNING;
            $perc = ceil(((float) $cnt * 100) / $totalcount);
            $time_left = 0;
            if ($perc != 0 && $cnt > 0) {
                $time_b = microtime(TRUE);
                $time_left = ($time_b - $time_a) * (($totalcount - $cnt) / $cnt);
            }
            update_queue_status($this->db, $item->get_dbid(), NULL, $time_left, $perc, 'Getting spots');
        }

        return $cnt;
    }

    static private function calculate_spotter_id($userkey)
    {
        $user_sign_crc = crc32(base64_decode($userkey));
        $user_id_tmp =
            chr($user_sign_crc & 0xFF) .
            chr(($user_sign_crc >> 8) & 0xFF) .
            chr(($user_sign_crc >> 16) & 0xFF) .
            chr(($user_sign_crc >> 24) & 0xFF);

        return str_replace(array('/', '+', '='), '', base64_encode($user_id_tmp));
    }

}
