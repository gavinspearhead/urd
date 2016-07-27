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
 * $LastChangedDate: 2014-06-07 17:12:45 +0200 (za, 07 jun 2014) $
 * $Rev: 3082 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: nntp.class.php 3082 2014-06-07 15:12:45Z gavinspearhead@gmail.com $
 */

/*
 * This file is based on Daniel Eiland's nzb.class.php:
 *
 * 5 feb 2005
 * NZB Generator (c) 2005
 * Written by Daniel Eiland daniel@bokko.nl
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

class URD_NNTP
{
    private $server;           // server name
    private $port;             // server port
    private $username;          // username for authenticating to the server
    private $password;          // and the password
    private $auth;              // we need authentication?
    private $newsgroup;         // the selected newsgroup at the server
    private $maxMssgs;         // fetch this amount of messages at the time
    private $downloadspeedArr; // is used to keep track of avg download speeds
    private $downloadspeedArr_b; // is used to keep track of avg download speeds
    private $nntp;             // the nntp server connection
    private $db;               // the database to use
    private $timeout;	   // the timeout used for socket connections
    private $groupid;          // used to temporarily store the groupID across functions
    //    private $xoverformat;      // format of xover messages
    private $xover_number;     // store location of fields
    private $xover_subject;
    private $xover_from;
    private $xover_date;
    private $xover_messageid;
    private $xover_bytes;
    private	$xover_lines;
    private $xover_xref;
    private $extset_headers;

    const MIN_OLDER_COUNTER = 5000;
    const MAX_OLDER_COUNTER = 10000;
    const BINARYID_CACHE_SIZE = 64; // number of binary IDs we keep in memory to minimise the redundant subjects and posters we store
    const GROUP_FILTER = 'alt.bin*, free.*';

    public function __destruct()
    {
        $this->disconnect(TRUE);
        $this->downloadspeedArr = NULL;
        $this->downloadspeedArr_b = NULL;
        $this->db = NULL;
        $this->extset_headers = NULL;
    }

    public function __construct (DatabaseConnection $db, $server, $connection_type=NULL, $port=0, $timeout=socket::DEFAULT_SOCKET_TIMEOUT)
    {
        assert (is_numeric($port) && is_numeric($timeout));
        echo_debug_function(DEBUG_NNTP, __FUNCTION__);
        //configuration
        //initialize some stuff
        $this->db = $db;
        $this->nntp = NULL;
        $this->downloadspeedArr = array();
        $this->downloadspeedArr_b = array();
        $conn = strtolower($connection_type);
        switch ($conn) {
            default:
                write_log("Unknown encryption type: $connection_type", LOG_NOTICE);
                // fall through
            case NULL :
            case 'tcp':
            case 'off':
                $conn = FALSE;
                break;
            case 'tls':
            case 'ssl':
                if (!extension_loaded('openssl')) {
                    write_log('SSL module not loaded', LOG_ERR);
                    throw new exception('SSL module not loaded', ERR_NNTP_CONNECT_FAILED);
                }
                $timeout = NULL; // needed so SSL connections will not cause random timeouts. Seems to be a buggy PHP /SSL issue with stream_select
                break;
        }
        $this->auth = FALSE;
        $this->username = $this->password = '';
        $this->server = $server;
        $this->timeout = $timeout;
        $this->extset_headers = array();
        $this->connection = $conn;
        if ($port === NULL || $port == 0 || $port > 65535 || !is_numeric($port)) {
            $this->port = ($conn === FALSE) ? Base_NNTP_Client::NNTP_PROTOCOL_CLIENT_DEFAULT_PORT : Base_NNTP_Client::NNTP_SSL_PROTOCOL_CLIENT_DEFAULT_PORT;
        } else {
            $this->port = (int) $port;
        }
        $this->maxMssgs = get_config($db, 'maxheaders'); //fetch this ammount of messages at the time

    }
    public function get_extset_headers()
    {
        return $this->extset_headers;
    }
    public function reset_extset_headers()
    {
        $this->extset_headers = array();
    }

    public function disconnect($silent=FALSE)
    {
        if ($this->nntp !== NULL && ($this->nntp instanceof NNTP_Client)) {
            $msg = $this->nntp->disconnect();
            if (!$silent) {
                write_log('Disconnect message: ' . $msg, LOG_INFO);
            }
        }
        $this->nntp = NULL;
    }
    public function is_connected()
    {
        return ($this->nntp instanceof NNTP_Client) && $this->nntp->is_connected();
    }

    public function reconnect()
    {
        echo_debug_function(DEBUG_NNTP, __FUNCTION__);
        $this->disconnect();
        $this->connect($this->auth, $this->username, $this->password);
        declare(ticks=1);
        if ($this->newsgroup != '') {
            $this->select_group_name($this->newsgroup, $code);
        }
    }

    public function connect($auth, $username='', $password='')
    {// return true if posting is allowed, false if not
        echo_debug_function(DEBUG_NNTP, __FUNCTION__);
        //connect to server
        assert(is_bool($auth));
        $this->auth = $auth;
        $this->username = $username;
        $this->password = $password;
        $password = keystore::decrypt_password($this->db, $password);

        $conn = '';
        if ($this->connection !== FALSE) {
            $conn = $this->connection . ':';
        }
        write_log ("Connecting to NNTP server: $conn{$this->server}:{$this->port}", LOG_INFO);
        try {
            $this->nntp = new NNTP_Client();
            $posting = $this->nntp->connect($this->server, $this->connection, $this->port, $this->timeout);
        } catch (exception $e) {
            $this->nntp->disconnect();
            $this->nntp = NULL;
            write_log("Cannot connect to NNTP server: $conn{$this->server}:{$this->port}", LOG_ERR);
            write_log($e->getmessage() . " ({$e->getCode()})", LOG_ERR);
            throw new exception('Cannot connect to NNTP server', ERR_NNTP_CONNECT_FAILED);
        }
        if ($this->auth === TRUE) {
            try {
                write_log("Using authentication as $username", LOG_INFO);
                $this->nntp->authenticate($username, $password);
            } catch (exception $e) {
                $this->nntp->disconnect();
                $err_msg = "Cannot authenticate to server to {$this->server}:{$this->port} as $username";
                write_log($err_msg, LOG_ERR);
                write_log($e->getMessage(), LOG_ERR);
                if ($e->getCode() == NNTP_PROTOCOL_RESPONSECODE_AUTHENTICATION_REJECTED) {
                    throw new exception($err_msg, ERR_NNTP_TOO_MANY_CONNECTIONS);
                } else {
                    throw new exception($err_msg, ERR_NNTP_AUTH_FAILED);
                }
            }
        }

        return $posting;
    }
    public function get_first_last_group($groupid)
    {
        echo_debug_function(DEBUG_NNTP, __FUNCTION__);
        if (!is_numeric($groupid)) {
            throw new exception('Invalid command', ERR_INTERNAL_ERROR);
        }
        $groupArr = get_group_info($this->db, $groupid);
        $code = 0;
        try {
            $data = $this->select_group_name($groupArr['name'], $code);
        } catch (exception $e) {
            throw new exception("Could not select group: $groupid}", ERR_GROUP_NOT_FOUND);
        }

        $last = $data['last'];
        if ($groupArr['last_record'] == 0) {
            $first = $data['first'];
        } else {
            $first = $groupArr['last_record'] + 1;
        }

        //calculate total number op parts
        $total = gmp_div($last, $first);

        return array($first, $last, gmp_strval($total));
    }
    private function get_content($msg_id, $fn)
    {
        try {
            return $this->nntp->$fn($msg_id, FALSE);
        } catch (exception $e) {
            $code = $e->getCode();
            if (!$this->is_connected()) {
                try {
                    usleep(500000);
                    $this->reconnect();

                    return $this->nntp->$fn($msg_id, FALSE);
                } catch (exception $e) {
                    $code = $e->getCode();
                    $msg = $e->getMessage();
                    throw new exception_nntp_connect("Connection lost: $msg ($code)", ERR_NNTP_CONNECT_FAILED);
                }
            } elseif ($code == '') {
                $err_code = UNKNOWN_ERROR;
            } else {
                $err_code = $code;
            }
            throw new exception("Article not found ($err_code) $msg_id", ERR_ARTICLE_NOT_FOUND);
        }
    }
    public function get_header_multi(array $msg_id)
    {
        return $this->nntp->get_header($msg_id);
    }
    public function get_header($msg_id)
    {
        return $this->get_content($msg_id, 'get_header');
    }
    public function get_article($msg_id)
    {
        return $this->get_content($msg_id, 'get_body');
    }
    protected function estimate_headers($first, $last, $expire)
    {
        echo_debug('Estimated header count 1: ' . gmp_strval(gmp_sub($last, $first)), DEBUG_MAIN);
        try {
            $art = gmp_init($this->nntp->select_next_article());
            echo_debug('next art: ' . gmp_strval($art), DEBUG_MAIN);
            if (gmp_cmp($art, $first) > 0) {
                $first = $art;
                echo_debug('new first 1: ' . gmp_strval($first), DEBUG_MAIN);
            }
        } catch (exception $e) {
            write_log('Next failed: ' . $e->getMessage(), LOG_WARNING);
            $this->reconnect(); // no worries, we just continue by reconnecting
        }
        $cnt1 = gmp_sub($last, $first);
        echo_debug('Estimated header count 2: ' . gmp_strval($cnt1), DEBUG_MAIN);

        $now = time();
        $this->get_overview_format();
        $msg = $this->nntp->get_fast_overview(gmp_strval($first), gmp_strval(gmp_add($first, 10)));
        echo_debug("Expire $expire days", DEBUG_SERVER);
        if (count($msg) > 0) {
            $parsed_msg = explode("\t", $msg[0]);
            $first_date = strtotime($parsed_msg[ $this->xover_date ]);
            $first_art = $parsed_msg[ $this->xover_number ];
            echo_debug("First is $first_art $first_date", DEBUG_MAIN);
            if ($first_date > 0 && $first_date < ($now - ($expire * 24 * 3600))) {
                // we need to correct the counter here
                $cnt1 = gmp_div(gmp_mul($cnt1, ($expire * 24 * 3600)), $now - $first_date);
            }
        } else { // no msgs received, so we don't correct...
            echo_debug('No messages downloaded', DEBUG_MAIN);
        }
        echo_debug('Estimated header count 3: ' . gmp_strval($cnt1), DEBUG_MAIN);

        return $cnt1;
    }

    protected function estimate_header_count($first1, $last1, $first2, $last2, $total_max, $expire)
    {
        echo_debug('Estimating header count: ' . gmp_strval($first1) . ' ' . gmp_strval($last1) . ' ' . gmp_strval($first2) . ' '
                . gmp_strval($last2) . ' ' . gmp_strval($total_max) . ' ' . $expire, DEBUG_MAIN);
        $cnt1 = $cnt2 = $cnt = gmp_init(0);
        $first_guess = (gmp_add(gmp_sub($last1, $first1), gmp_sub($last2, $first2)));
        echo_debug('First guess: ' . gmp_strval($first_guess) , DEBUG_MAIN);
        if (gmp_cmp($last1, 0) > 0 && gmp_cmp($first1, 0) > 0) {
            $cnt1 = $this->estimate_headers($first1, $last1, $expire);
        }
        if (gmp_cmp($last2, 0) > 0 && gmp_cmp($first2, 0) > 0) {
            $cnt2 = $this->estimate_headers($first2, $last2, $expire);
        }

        $cnt = gmp_add($cnt1, $cnt2);
        echo_debug('Estimated header count 5: ' . gmp_strval($cnt), DEBUG_MAIN);

        if (gmp_cmp($cnt, $total_max) > 0 && gmp_cmp($total_max, 0) > 0) {
            return $total_max;
        } elseif (gmp_cmp($cnt, 0) < 0) {
            return $first_guess;
        } else {
            return $cnt;
        }
    }

    private function msg_length(array $msgs)
    {
        return array_sum(array_map('strlen', $msgs));
    }

    private function get_headers(array $groupArr, $orig_start, $orig_stop, $dbid, $mindate, $total, $done, $update_last_updated, $total_max, $total_counter, $compressed_headers)
    {
        //assert(is_resource($orig_start) && is_resource($orig_stop) && is_numeric($mindate) && is_resource($total) && is_resource($done));
        assert(is_numeric($mindate) );
        echo_debug_function(DEBUG_NNTP, __FUNCTION__);
        // Start > stop ?
        if (gmp_cmp($orig_start, $orig_stop) > 0) {
            write_log('Odd values: ' . gmp_strval($orig_start) . ', ', gmp_strval($orig_stop), LOG_NOTICE);
            // need to do 0-stop and start to maxint???
        }
        // Loop as we have a maximum number of articles we download per batch:
        echo_debug('Getting articles ' . gmp_strval($orig_start) . ' - ' . gmp_strval($orig_stop), DEBUG_MAIN);
        if (gmp_cmp($orig_start, 0) == 0 && gmp_cmp($orig_stop, 0) == 0) {
            return 0;
        }

        $GREATEST = $this->db->get_greatest_function();
        // Download headers, to update local info
        $blacklist_counter = gmp_init(0);
        $poster_blacklist = get_config($this->db, 'poster_blacklist');
        $poster_blacklist = unserialize($poster_blacklist);
        if (!is_array($poster_blacklist)) {
            $poster_blacklist = array();
        }
        echo_debug('Using blacklist:', DEBUG_NNTP);
        echo_debug_var($poster_blacklist, DEBUG_NNTP);
        $this->groupID = $groupid = $groupArr['ID'];
        $parse_spots = $groupArr['parse_spots'];
        $parse_spots_comments = $groupArr['parse_spots_comments'];
        $parse_spots_reports = $groupArr['parse_spots_reports'];
        $get_extsetdata = $groupArr['parse_extsetdata'];
        echo_debug('Total max:' . gmp_strval($total_max), DEBUG_MAIN);
        // Store the xover message syntax in the class variable, used by ParseMessages:
        $this->get_overview_format();
        $stop = $orig_stop;
        $start = gmp_max($orig_start, gmp_sub($stop, $this->maxMssgs));

        $older_counter = 0;
        // if 5% is older in one go we quit adding articles + 1000 articles for small ngs
        $older_top = gmp_min(self::MAX_OLDER_COUNTER, (gmp_add(1000, gmp_div($total, 20))));
        do {
            $starttime = microtime(TRUE);

            // Get the batch of messages and store them in $msgs
            try {
                echo_debug('Getting headers ' . gmp_strval($start) . ' - ' . gmp_strval($stop), DEBUG_MAIN);
                try {
                    $msgs = $this->nntp->get_fast_overview(gmp_strval($start), gmp_strval($stop));
                } catch (exception $e) {
                    // we try a quick reconnect if it times out... for timouts happen

                    if ($e->getcode() == NNTP_NOT_CONNECTED_ERROR) {
                        $this->reconnect();
                        $msgs = $this->nntp->get_fast_overview(gmp_strval($start), gmp_strval($stop));
                    } else {
                        throw $e;
                    }
                }
            } catch (exception $e) {
                $this->disconnect();
                write_log('Cannot get messages:', LOG_ERR);
                write_log("{$e->getmessage()} ({$e->getcode()})", LOG_ERR);
                throw $e;
            }
            echo_debug('Total: ' . gmp_strval($total) . "; older: $older_counter; older_top: " . gmp_strval($older_top), DEBUG_MAIN);
            // Parse and process each message
            $bytes = $this->msg_length($msgs);
            if (empty($msgs)) {
                write_log('Skipping:', LOG_INFO);
            } else {
                // check mindate otherwise return
                $rv = $this->parse_messages($msgs, $mindate, $older_counter, $blacklist_counter, $get_extsetdata, $parse_spots, $parse_spots_comments, $parse_spots_reports, $poster_blacklist);
                $total_counter = gmp_add($total_counter, count($msgs));
                if (gmp_cmp($older_counter, $older_top) > 0) {
                    echo_debug('too old', DEBUG_MAIN);
                    break;
                }
            }

            $ostop = gmp_strval($orig_stop);
            $ostart = gmp_strval($orig_start);
            $s = gmp_strval($start);

            if ($update_last_updated === TRUE) {
                $query = "UPDATE groups SET \"last_record\" = $GREATEST('$ostop', \"last_record\"), \"first_record\" = '$s', \"mid_record\"= '$ostart' WHERE \"ID\"=?"; // /lazy last_record need only the first time
                $res = $this->db->execute_query($query, array($groupid));
            } else {
                $res = $this->db->update_query_2('groups', array('first_record' => $s, 'mid_record' => 0), '"ID"=?', array($groupid));
            }

            // Determine download speed & ETA
            $stoptime = microtime(TRUE);
            $timeneeded = $stoptime - $starttime;
            $arts_processed = gmp_add(gmp_sub($stop, $start), 1); // need to add one because the borders are inclusive
            echo_debug('Downloaded ' . gmp_strval($arts_processed) . ' articles in ' . $timeneeded . 's and ' . $bytes . ' bytes', DEBUG_MAIN);

            $d1 = gmp_sub($orig_stop, gmp_add($start, $done));
            $d2 = (gmp_cmp($total_max, 0) > 0) ? gmp_min($total_max, $total) : $total;
            $this->store_ETA_info($timeneeded, $this->maxMssgs, gmp_strval($d1), gmp_strval($d2), $bytes, $dbid); // inaccurate
            echo_debug(gmp_strval($start) . ' ' . gmp_strval($stop), DEBUG_MAIN);
            $stop = gmp_max($orig_start, gmp_sub($start, 1));
            echo_debug(gmp_strval($start) . ' ' . gmp_strval($stop), DEBUG_MAIN);
            $start = gmp_max($orig_start, gmp_sub($start, $this->maxMssgs));
            echo_debug(gmp_strval($start) . ' ' . gmp_strval($stop), DEBUG_MAIN);
            echo_debug('Total max: ' . gmp_strval($total_max) . ', counter: ' . gmp_strval($total_counter) . ' ; blacklisted: ' . gmp_strval($blacklist_counter), DEBUG_MAIN);
        } while (gmp_cmp($start, $stop) < 0 && (gmp_cmp($total_max, 0) == 0 || gmp_cmp($total_counter, $total_max) <= 0));

        // Update group table with update time:
        $o = gmp_strval($orig_stop);
        $now = time();
        $query = "UPDATE groups SET \"last_updated\" = ? , \"last_record\" = $GREATEST('$o', \"last_record\") WHERE \"ID\"=?";
        $res = $this->db->execute_query($query, array($now, $groupid));

        return $total_counter;
    }

    public function update_newsgroup(array $groupArr, action $item)
    {
        echo_debug_function(DEBUG_NNTP, __FUNCTION__);
        $first = $last = gmp_init(0);
        $dbid = $item->get_dbid();
        $this->extset_articles = array();
        $groupid = $groupArr['ID'];
        $continue_old = FALSE;
        $group_name = $groupArr['name'];
        $total_max = gmp_init(get_config($this->db, 'total_max_articles'));
        $compressed_headers = $groupArr['compressed_headers'];
        echo_debug('Total max: ' . gmp_strval($total_max), DEBUG_MAIN);
        try {
            update_queue_status ($this->db, $dbid, NULL, NULL, 0, 'Finding first valid article');

            $extset_group = $spots_comments_group = $spots_reports_group = $spots_group = 0;
            $extset_group_name = get_config($this->db, 'extset_group', '');
            if ($extset_group_name != '') {
                $extset_group = group_by_name($this->db, $extset_group_name);
            }
            $spots_reports_group_name = get_config($this->db, 'spots_reports_group', '');
            if ($spots_reports_group_name != '') {
                $spots_reports_group = group_by_name($this->db, $spots_reports_group_name);
            }
            $spots_comments_group_name = get_config($this->db, 'spots_comments_group', '');
            if ($spots_comments_group_name != '') {
                $spots_comments_group = group_by_name($this->db, $spots_comments_group_name);
            }
            $spots_group_name = get_config($this->db, 'spots_group', '');
            if ($spots_group_name != '') {
                $spots_group = group_by_name($this->db, $spots_group_name);
            }

            $groupArr['parse_spots_comments'] = ($spots_comments_group == $groupid) ? TRUE : FALSE;
            $groupArr['parse_spots_reports'] = ($spots_reports_group == $groupid) ? TRUE : FALSE;
            $groupArr['parse_extsetdata'] = ($extset_group == $groupid) ? TRUE : FALSE;
            $groupArr['parse_spots'] = ($spots_group == $groupid) ? TRUE : FALSE;
            write_log("Selecting group: $group_name", LOG_INFO);
            try {
                $data = $this->select_group_name($group_name, $code);
            } catch (exception $e) {
                throw new exception("Could't select group $group_name", ERR_GROUP_NOT_FOUND);
            }
            $expire = $groupArr['expire'];
            if ($groupArr['parse_spots'] || $groupArr['parse_spots_reports'] || $groupArr['parse_spots_comments']) {
                $expire = get_config($this->db, 'spots_expire_time', DEFAULT_SPOTS_EXPIRE_TIME);
                echo_debug("Using spots expire $expire days", DEBUG_SERVER);
            }
            echo_debug("Expire $expire days", DEBUG_SERVER);
            $mindate = time() - ($expire * 24 * 3600);
            echo_debug("Expire $mindate seconds", DEBUG_SERVER);
            $m1 = gmp_init($groupArr['first_record']);
            $m2 = gmp_init($groupArr['mid_record']);
            $m3 = gmp_init($groupArr['last_record']);

            $first = gmp_init($data['first']);
            $last = gmp_add(gmp_init($data['last']), 1);
            $last1 = $first1 = $last2 = $first2 = gmp_init(0);
            $cases = 0;

            // we have several cases of update we need to do
            // *** done --- to do
            // F -----------------------L m1=0; m2=0 ; m3=0 ---> F - L
            // F -----------------m1****L m1>F; m2 < F ---> F-m1
            // F -----------m1****m3----L m1>F; m3<L ---> F-m1, m3-L
            // F ******m2----m1****m3---L m2<m1,m2>F ---> m2-m1, m3-L
            if (gmp_cmp($m1, $first) > 0 ) { // m1 > F
                if (gmp_cmp ($m2, $first) > 0 && gmp_cmp($m2, $m1) < 0) {  // m2 > F (and thus m2 > 0) and m2 < m1
                    // case m2-m1
                    $first1 = $m2;
                    $last1 = $m1;
                    $cases++;
                } else {
                    //case F-m1 or F-L
                    $first1 = $first;
                    $last1 = gmp_min($m1, $last);
                    $cases++;
                }
            }
            if (gmp_cmp($m3, $last) < 0) {  // m3 < l && m3 > 0
                // case m3-L
                $first2 = gmp_max($first, $m3);
                $last2 = $last;
                $cases++;
            }

            try {
                $headers_count = gmp_init(0);
                $headers_count = $this->estimate_header_count($first1, $last1, $first2, $last2, $total_max, $expire);
                echo_debug('Estimated header count 6: ' . gmp_strval($headers_count), DEBUG_MAIN);
            } catch (exception $e) {
                write_log('Error while guessing header count ' . $groupArr['name'] . ': ' . $e->getMessage() . ' (' . $e->getCode(). ') ', LOG_WARNING);
                // let's just continue
            }

            $this->nntp->set_compressed_headers($compressed_headers); // won't like compressed headers for estimating the counter
            $total_count = gmp_init(0);
            $total = $headers_count;

            if (gmp_cmp($last1, 0) > 0 && gmp_cmp($first1, 0) > 0) {
                $total_count = $this->get_headers($groupArr, $first1, $last1, $dbid, $mindate, $total, gmp_init(0), FALSE, $total_max, gmp_init(0), $compressed_headers);
            }
            if (gmp_cmp($total_max, 0) == 0 || gmp_cmp($total_count, $total_max) <= 0) {
                $this->get_headers($groupArr, $first2, $last2, $dbid, $mindate, $total, gmp_sub($last1, $first1), TRUE, $total_max, $total_count, $compressed_headers);
            }
            $f1 = gmp_strval($first);
            $l1 = gmp_strval($last);
            if ($continue_old === FALSE) {
                $this->db->update_query('groups', array('last_updated', 'mid_record', 'first_record', 'last_record'), array(time(), $f1, $f1, $l1), '"ID"=?', array($groupid));
            }

            return NO_ERROR;
        } catch (exception $e) {
            write_log('Error while updating ' . $groupArr['name'] . ': ' . $e->getMessage() . ' (' . $e->getCode(). ') ', LOG_ERR);
            if ($e->getCode() !== NNTP_NOT_CONNECTED_ERROR) {
                echo_debug_trace($e, DEBUG_NNTP);
                update_queue_status($this->db, $dbid, QUEUE_FAILED, 0, NULL, $e->getMessage());
            }

            return $e->getCode();
        }
    }
    private function get_overview_format()
    {
        /*
XoverFormat: (for newszilla.xs4all.nl)
Array
(
[Subject] =>
[From] =>
[Date] =>
[Message-ID] =>
[References] =>
[:bytes] =>
[:lines] =>
[Xref] => 1
)
         */
        /*
        // getOverviewFormat gives back fixed values for the first 7 items!!! Useless!
        // Should fix getOverviewFormat to parse the LIST OVERVIEW.FMT response!

        $this->xoverformat = $this->nntp->getOverviewFormat(TRUE, TRUE);
        $temp = array_keys($this->xoverformat);
        $this->xover_subject	= array_search('Subject',$temp);
        $this->xover_from	= array_search('From',$temp);
        $this->xover_date	= array_search('Date',$temp);
        $this->xover_messageid	= array_search('Message-ID',$temp);
        $this->xover_bytes	= array_search('Bytes',$temp);
        $this->xover_lines	= array_search('Lines',$temp);
        $this->xover_xref	= array_search('Xref',$temp);

        // Check if we found every required value:
        if ($this->xover_subject == FALSE ||
        $this->xover_from == FALSE ||
        $this->xover_date == FALSE ||
        $this->xover_messageid == FALSE ||
        $this->xover_bytes == FALSE)
        throw new exception("Failed to find a required XOver format value");

        // First value is always number, shift rest:
        $this->xover_number = 0;
        $this->xover_subject++;
        $this->xover_from++;
        $this->xover_date++;
        $this->xover_messageid++;
        $this->xover_bytes++;
        $this->xover_lines++;
        $this->xover_xref++;
         */
        $this->xover_number	    = 0;
        $this->xover_subject	= 1;
        $this->xover_from	    = 2;
        $this->xover_date	    = 3;
        $this->xover_messageid	= 4;
        $this->xover_references = 5;
        $this->xover_bytes	    = 6;
        $this->xover_lines  	= 7;
        $this->xover_xref   	= 8;
    }

    private function parse_messages(array $msgs, $mindate, &$older_counter, &$blacklist_counter, $get_extset_data=FALSE, $parse_spots, $parse_spots_comments, $parse_spots_reports, array $poster_blacklist)
    {
        // Parse messages received from a newsgroup and store it in the database

        // From the parts table, a binaries table will be generated at the end, from which setdata will be generated.
        // This is quicker than updating them on the fly.

        /*
           After explode on \t:
           Array
           (
           [0] => 51368878
           [1] => Superman.Boxset.Multi.Pal.DVDR REPOST---WWW.UNITED-FORUMS.CO.UK---[158/416] -Superman2.Multi.Pal.--WWW.UNITED-FORUMS.CO.UK--.r53 (128/201)
           [2] => web@www.united-forums.co.uk (FiSO)
           [3] => Tue, 26 Feb 2008 13:21:40 GMT
           [4] => <part128of201.EFPVc$UyVk20CXtrwBhA@powerpost2000AA.local>
           [5] => <q9pqp2JCyJ8XpN4TgAAv6@spot.net>
           [6] => 258900
           [7] => 1986
           [8] => Xref: article.news.xs4all.nl alt.binaries.boneless:1245016480 alt.binaries.dvd:419435634 alt.binaries.ftd:35220227 alt.binaries.movies:51368878
           )
         */
        assert(is_numeric($mindate));
        $spot_ids = $spot_reports = $spot_comments = $allParts = array();
        $cnt = 0;
        if ($mindate < 0) {
            $mindate = 0;
        }
        $binary_id = array();
        $_blacklist_counter = 0;
        $total_counter_1 = count($msgs);
        foreach($msgs as $msgtxt) {
            // Split the bunch:
            $msg = explode("\t", $msgtxt);
            if (!isset($msg[$this->xover_subject], $msg[$this->xover_bytes], $msg[$this->xover_messageid], $msg[$this->xover_date], $msg[$this->xover_from])) {
                continue;
            }
            $date = strtotime($msg[$this->xover_date]);
            if (($date < $mindate && $date > 0) || $date <= 0) {
                ++$cnt;
              //  echo_debug("too old: " . $msg[$this->xover_date], DEBUG_SERVER);
                continue;
            }
            $messageID = $msg[$this->xover_messageid];
            $messageID = trim($messageID, "<> \n\t\r");
            $subject   = $msg[$this->xover_subject];

            if ($parse_spots) { 
                $spot_ids[] = array($messageID);
            } elseif ($parse_spots_reports) {
                $spot_reports[] = array($messageID);
            } elseif ($parse_spots_comments) {
                $spot_comments[] = array($messageID);
            } else {
                $bytes = $msg[$this->xover_bytes];
                if ($bytes <= 0) {
                    //var_dump("invalid bytes or invalid date:", $msg);
                    continue;
                }
                $from = $msg[$this->xover_from];

                if ($get_extset_data && strpos($subject, 'urd_extsetdata_post') !== FALSE) {
                    echo_debug("Found subject $subject. We get some extset data here", DEBUG_SERVER);
                    $this->extset_headers[] = $messageID;
                    //var_dump("got extsetheadr:", $msg);
                    continue;
                }
                if (substr_compare($subject, 're:', 0, 3, TRUE) == 0) {
                    //var_dump("contains re:", $msg);
                    // we don't need to store answers to other posts
                    continue;
                }

                // pregmatch to find article numbers and such:
                if (!preg_match('|^(.*)\((\d+)/(\d+)\)|', $subject, $vars)) {
                    //var_dump("no part number:", $msg);
                    continue;
                }
                if (!isset($vars[2])) {
                    //var_dump("no part number 2:", $msg);
                    continue; // it probably isn't a binary file but a regular post... don't add it
                }
                $subject = trim(str_replace('yEnc', '', $vars[1]));
                if ($subject == '') {
                    //var_dump("empty subject:", $msg);
                    continue;
                }
                $part0 = $vars[2];
                if (!is_numeric($part0)) {
                    //var_dump("no part number 3:", $msg);
                    continue;
                }
                foreach ($poster_blacklist as $poster) {
                    if ($poster != '' && preg_match("|$poster|", $from)) {
                        $_blacklist_counter++;
                        //var_dump("blacklisted:", $msg);
                        continue 2;
                    }
                }
                // Create binaryID / add to store of tables:
                $table = new TableParts;
                $table->binaryID	= create_binary_id($subject, $from);
                $table->messageID	= $messageID;
                $table->partnumber	= (int) $part0;
                $table->size		= $bytes;
                // Yes, these are not 3NF, but they save executing 2 extra queries:
                if (in_array($table->binaryID, $binary_id)) {
                    $table->fromname = $table->subject = '';
                } else {
                    $table->fromname = utf8_encode(trim($from));
                    $table->subject	= utf8_encode($subject);
                    if (count($binary_id) >= self::BINARYID_CACHE_SIZE) {
                        array_shift($binary_id);
                    }
                    $binary_id[] = $table->binaryID;
                }
                $table->date = $date;
                $allParts[] = $table;

            }
        }
        unset($msgs, $binary_id);
        if (!$parse_spots && !$parse_spots_comments && !$parse_spots_reports) {
            $total_counter_2 = count($allParts);
            if ($total_counter_2 != $total_counter_1) {
                write_log("We dropped $total_counter_1 - $total_counter_2 == " . ($total_counter_1 - $total_counter_2 ) . ' messages', LOG_INFO); 
            }
        }

        if (count($spot_ids) > 0) {
            $this->db->start_transaction();
            $this->db->insert_query('spot_messages', array('message_id'), $spot_ids);
            $this->db->commit_transaction();
        }
        if (count($spot_reports) > 0) {
            $this->db->start_transaction();
            $this->db->insert_query('spot_reports', array('message_id'), $spot_reports);
            $this->db->commit_transaction();
        }
        if (count($spot_comments) > 0) {
            $this->db->start_transaction();
            $this->db->insert_query('spot_comments', array('message_id'), $spot_comments);
            $this->db->commit_transaction();
        }
        // Add all parts into the database:
        if ($cnt == 0) {
            $older_counter = 0;
        } else {
            $older_counter += $cnt;
        }
        echo_debug("$_blacklist_counter of " . $total_counter_1 . ' articles matched the blacklist', DEBUG_NNTP);
        $blacklist_counter = gmp_add($blacklist_counter, $_blacklist_counter);
        unset($spot_comments, $spot_reports, $spot_ids);
        $ug = new urdd_group($this->db);
        $ug->add_parts($allParts, $this->groupID);

        return TRUE;
    }

    private function store_ETA_info($timeneeded, $nrmessages, $articlesdone, $totalarticles, $bytes, $dbid)
    {
        assert(is_numeric($timeneeded) && is_numeric($nrmessages) && is_numeric($articlesdone) && is_numeric($totalarticles) && is_numeric($bytes) && is_numeric($dbid));
        // Sanity check:
        if ($timeneeded == 0) {
            return;
        }

        // Keep track of update statistics.
        // Remember the download speed of the last $speedmemory batches, use this to calculate
        // the average speed, and use this to estimate the time of arrival.
        $speedmemory = 100;

        // Speed of the last batch:
        $downloadspeed = $nrmessages / $timeneeded;
        $downloadspeed_b = $bytes / $timeneeded;

        if (round($downloadspeed) > 0) {
            // Update $this->downloadspeedArr:
            $this->downloadspeedArr[] = $downloadspeed;
            $this->downloadspeedArr_b[] = $downloadspeed_b;
            if (count($this->downloadspeedArr) > $speedmemory) {
                // Remove oldest value:
                array_shift($this->downloadspeedArr);
                array_shift($this->downloadspeedArr_b);
            }
        }

        // Basic downloadspeed: Average of all values in dowlnoadspeedArr:
        $avgdownloadspeed = array_sum($this->downloadspeedArr) / count($this->downloadspeedArr);
        $avgdownloadspeed_b = array_sum($this->downloadspeedArr_b) / count($this->downloadspeedArr_b);

        // Sanity check:
        $avgdownloadspeed = floor($avgdownloadspeed);
        if ($avgdownloadspeed == 0) {
            $avgdownloadspeed = 0.01;
        }
        $avgdownloadspeed_b = floor($avgdownloadspeed_b);
        if ($avgdownloadspeed_b == 0) {
            $avgdownloadspeed_b = 0.01;
        }

        // Calculate ETA:
        $percentage = ($totalarticles > 0) ? floor(100 * ($articlesdone / $totalarticles)) : 0;
        $percentage = min(100, $percentage); // Just in case ;)
        $articlestodo = $totalarticles - $articlesdone;
        $ETA = round($articlestodo / $avgdownloadspeed);

        // Store:
        store_ETA($this->db, $ETA, $percentage, $avgdownloadspeed . ' articles/s - ' . "total $totalarticles articles; $avgdownloadspeed_b bytes/s" , $dbid);
        echo_debug($avgdownloadspeed . ' articles/s - ' . "total $totalarticles articles; $avgdownloadspeed_b bytes/s" , DEBUG_MAIN);
    }
    private function select_group_name($name, &$code)
    {
        try {
            $this->newsgroup = '';
            $name = strtolower($name);
            $data = $this->nntp->select_group($name);
            $this->newsgroup = $name;
            return $data;
        } catch (exception $e) {
            $code = $e->getcode();
            if ($code == NNTP_PROTOCOL_RESPONSECODE_AUTHENTICATION_REQUIRED) {
                throw new exception("Could not select group: $name. Authentication needed.", ERR_NNTP_AUTH_FAILED);
            } else {
                throw new exception("Could not select group: $name", ERR_GROUP_NOT_FOUND);
            }
        }
    }

    public function select_group($groupid, &$code)
    {
        echo_debug_function(DEBUG_NNTP, __FUNCTION__);
        assert(is_numeric($groupid));
        $name = group_name($this->db, $groupid);
        try {
            return $this->select_group_name($name, $code); // we first try as in the db
        } catch (exception $e) {
            return $this->select_group_name(strtolower($name), $code); // if it fails we try the lowercase version, some servers seem a bit dubious in how they handle case 
        }
    }

    public function db_update_group_list($groups)
    {
        echo_debug_function(DEBUG_NNTP, __FUNCTION__);
        foreach ($groups as $group) {
            if (isset($group['group']) && (fnmatch($this->pattern, $group['group']))) {
                $gr = $group['group'];
                $adult = check_xrated($gr);

                $desc = (isset($group['desc'])) ? $group['desc'] : '';
                $msg_count = $group['last'] - $group['first'];
                if ($msg_count < 0) {
                    $msg_count = 0;
                }

                if (isset($this->grouparray["$gr"])) {
                    $ID = $this->grouparray["$gr"];
                    update_group($this->db, $ID, $desc, $msg_count, $adult);
                    $this->update++;
                } else {
                    $ID = insert_group($this->db, $gr, $desc, $this->expire_time, $msg_count, $adult);
                    if (!is_numeric($ID) || $ID <= 0) {
                        $ID = get_group_by_name($this->db, $gr);
                    }
                    $this->grouparray["$gr"] = $ID;
                    $this->insert++;
                }
            }
        }
    }

    private $update;
    private $insert;
    private $grouparray;
    private $expire_time;
    private $pattern;

    public function update_group_list()
    {
        echo_debug_function(DEBUG_NNTP, __FUNCTION__);
        write_log('Getting grouplist from server.', LOG_NOTICE);
        $pattern = get_config($this->db, 'group_filter');
        $patterns = explode (',', $pattern);

        $this->expire_time = get_config($this->db, 'default_expire_time');

        //  echo_debug('Processing grouplist.', DEBUG_NNTP);
        $this->update = $this->insert = 0;

        // New Function => Cache all known groups in one array
        $res = $this->db->select_query('"ID", "name" FROM groups');
        $this->grouparray = array();
        // Filling the Array
        if (is_array($res)) {
            foreach ($res as $dbGroup) {
                $this->grouparray[ strtolower($dbGroup['name']) ] = $dbGroup['ID'];
            }
        }

        try {
            foreach ($patterns as $pattern) {
                $this->pattern = trim($pattern);
                if ($this->pattern == '') {
                    continue;
                }
                $groups = $this->nntp->get_groups($this, $this->pattern);
            }
            $this->pattern = get_config($this->db, 'spots_group', 'free.pt');
            $groups = $this->nntp->get_groups($this, $this->pattern);
            $this->pattern = get_config($this->db, 'spots_comments_group', 'free.usenet');
            $groups = $this->nntp->get_groups($this, $this->pattern);
            $this->pattern = get_config($this->db, 'spots_reports_group', 'free.willey');
            $groups = $this->nntp->get_groups($this, $this->pattern);
        } catch (exception $e) {
            throw new exception_nntp_connect('Could not update group list');
        }
        write_log('Done with grouplist from server. Inserted ' . $this->insert . ', Updated ' . $this->update, LOG_NOTICE);
        $this->grouparray = array();

        return array($this->update, $this->insert);
    }
    public function test_nntp($group, &$code)
    {
        $code = 0;
        try {
            $data = $this->select_group_name($group, $code);
            $last = $data['last'];
            $msgs = $this->nntp->get_fast_overview($last - 2, $last, FALSE);
            if (count($msgs) == 0) {
                return FALSE;
            }

            return TRUE;
        } catch (exception $e) {
            return FALSE;
        }
    }
    public function test_compressed_headers_nntp($group, &$code)
    {
        $code = 0;
        try {
            $data = $this->select_group_name($group, $code);
            $last = $data['last'];
            $this->nntp->set_compressed_headers(TRUE);

            $msgs = $this->nntp->get_fast_overview($last - 2, $last);
            if (stristr($msgs[0], 'ybegin')) {
                return TRUE;
            }

            return FALSE;

        } catch (exception $e) {
            return FALSE;
        }

    }
    public function server_needs_auth($group)
    {
        try {
            $this->select_group_name($group, $code);

            return FALSE;
        } catch (exception $e) {
            if ($code == NNTP_PROTOCOL_RESPONSECODE_AUTHENTICATION_REQUIRED) {
                return TRUE;
            }

            return FALSE;
        }
    }
    public function post_article(array $article)
    {
        $this->nntp->post($article, $articleid);

        return $articleid;
    }

} //end class
