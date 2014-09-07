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
 * $LastChangedDate: 2011-04-03 23:01:00 +0200 (Sun, 03 Apr 2011) $
 * $Rev: 2111 $
 * $Author: gavinspearhead $
 * $Id: parse_nfo.php 2111 2011-04-03 21:01:00Z gavinspearhead $
 */
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

class sets_marking
{
    const MARKING_NEW_OFF   = 0;   // not yet marked yet, but may still be auto_marked
    const MARKING_ON        = 1;   // marked
    const MARKING_OFF       = 255; // not marked and will not be automarked

    public static function mark_set(DatabaseConnection $db, $userid, $setid, $element, $type, $value, $alt_value=NULL)
    {
        assert(is_numeric($value) && is_numeric($userid));
        assert(in_array($type, array(USERSETTYPE_GROUP, USERSETTYPE_RSS, USERSETTYPE_SPOT)));

        $sql = "\"$element\" FROM usersetinfo WHERE \"userID\"=? AND \"setID\"=? AND \"type\"=?";
        $res = $db->select_query($sql, 1, array($userid, $setid, $type));
        if ($res === FALSE) {
            $db->insert_query('usersetinfo', array('setID', 'userID', $element, 'type'), array($setid, $userid, $value, $type));
        } else {
            if ($alt_value !== NULL) {
                $status = $res[0][$element];
                $sr = ($status == 1) ? $alt_value : $value;
            } else {
                $sr = $value;
            }
            $db->update_query_2('usersetinfo', array($element=>$sr), '"setID"=? AND "userID"=? AND "type"=?', array($setid, $userid, $type));
        }
    }

    private static function mark_sets(DatabaseConnection $db, array $sets, $userid, $type, $status = self::MARKING_ON, $element='statusint', $manual=FALSE)
    {
        global $LN;
        assert(in_array($type, array(USERSETTYPE_GROUP, USERSETTYPE_RSS, USERSETTYPE_SPOT)));
        assert(is_bool($manual) && is_string($element) && is_numeric($userid));
        if ($element != 'statuskill' && $element != 'statusread') {
            $element = 'statusint';
        }
        if ($status != self::MARKING_NEW_OFF && $status != self::MARKING_ON && $status != self::MARKING_OFF) {
            throw new exception ($LN['error_invalidstatus'], ERR_INVALID_STATUS);
        }
        if (!is_numeric($userid)) {
            throw new exception ($LN['error_invaliduserid'], ERR_INVALID_STATUS);
        }
        foreach ($sets as $set) {
            $setid = $set['ID'];
            $res = $db->select_query('* FROM usersetinfo WHERE "userID"=? AND "setID"=? AND "type"=?', 1, array($userid, $setid, $type));
            if ($res === FALSE) {
                $db->insert_query('usersetinfo', array('setID', 'userID', $element, 'type'), array($setid, $userid, $status, $type));
            } else {
                switch ($res[0][$element]) {
                case self::MARKING_ON:
                case self::MARKING_NEW_OFF:
                    $set_status = $status;
                    break;
                case self::MARKING_OFF:
                    if ($manual === TRUE) {
                        $set_status = $status;
                    } else {
                        $set_status = self::MARKING_OFF;
                    }
                    break;
                default:
                    throw new exception ($LN['error_invalidstatus'], ERR_INVALID_STATUS);
                }

                $db->update_query_2('usersetinfo', array($element=>$set_status), '"setID"=? AND "userID"=?', array($setid, $userid));
            }
        }
    }

    public static function unmark_all2(DatabaseConnection $db, $userid, array $sets, $element='statusint', $groupid = NULL, $type=USERSETTYPE_GROUP, $marking=self::MARKING_OFF, $manual = FALSE)
    {
        assert (in_array($type, array(USERSETTYPE_GROUP, USERSETTYPE_RSS, USERSETTYPE_SPOT)) && is_numeric($marking) && is_numeric($userid));
        if ($element != 'statuskill' && $element != 'statusread') {
            $element = 'statusint';
        }

        if (!is_numeric($userid)) {
            throw new exception ($LN['error_invaliduserid'], ERR_INVALID_USERID);
        }

        if ($groupid === NULL) {
            $db->update_query('usersetinfo', array($element), array($marking), '"userID"=? AND "type"=? AND "setID" IN ( ' . str_repeat('?,', count($sets) - 1) . '?)', array_merge(array($userid, $type), $sets));
        } elseif (is_numeric($groupid)) {
            $input_arr = array();
            if ($type == USERSETTYPE_GROUP) {
                $qry = 'setdata."ID" FROM setdata LEFT JOIN usersetinfo AS usi '
                    . ' ON usi."setID" = setdata."ID" WHERE usi."userID" = ? AND setdata."groupID"=? AND "type"=? AND usi."setID" IN ( ' . str_repeat('?,', count($sets) - 1) . '?)';
                $input_arr = array($userid, $groupid, $type, array_merge($input_arr, $sets));
            } elseif ($type == USERSETTYPE_SPOT) {
                $qry = 'spots."spotid" FROM spots LEFT JOIN usersetinfo AS usi '
                    . ' ON usi."setID" = spots."spotid" WHERE usi."userID" = ? AND "type"=? AND usi."setID" IN ( ' . str_repeat('?,', count($sets) - 1) . '?)';
                $input_arr = array($userid, $type, array_merge($input_arr, $sets));
            } elseif ($type == USERSETTYPE_RSS) { // feed
                $qry = 'rss_sets."setid" AS "ID" FROM rss_sets LEFT JOIN usersetinfo AS usi '
                    . ' ON usi."setID" = rss_sets."setid" WHERE usi."userID" = ? AND rss_sets."rss_id"=? AND "type"=? AND usi."setID" IN ( ' . str_repeat('?,', count($sets) - 1) . '?)';
                $input_arr = array($userid, $groupid, $type, array_merge($input_arr, $sets));
            }
            $res = $db->select_query($qry);
            if ($res !== FALSE) {
                self::mark_sets($db, $res, $userid, $type, $marking, $element, $manual);
            }
        } else {
            throw new exception ($LN['error_invalidgroupid'], ERR_INVALID_USERID);
        }
    }

    public static function mark_all2(DatabaseConnection $db, $userid, array $sets, $element='statusint', $groupid, $type, $marking=self::MARKING_ON, $manual = FALSE, $skip_interesting=FALSE)
    { // mark all sets in a given group
        global $LN;
        assert (in_array($type, array(USERSETTYPE_GROUP, USERSETTYPE_RSS, USERSETTYPE_SPOT)) && is_numeric($marking) && is_numeric($userid));
        if ($element != 'statuskill' && $element != 'statusread') {
            $element = 'statusint';
        }
        $input_arr = array();
        if (!is_numeric($userid)) {
            throw new exception ($LN['error_invaliduserid'], ERR_INVALID_USERID);
        }
        $sets_str = $grp = $qint = '';
        foreach ($sets as $idx => $setid) {
            $sets_str .= ", :setid_$idx";
            $input_arr[":setid_$idx"] = $setid;
        }
        $sets_str = ltrim($sets_str, ', ');

        if ($skip_interesting) {
            $qint = ' OR "statusint" = :markon ';
            $input_arr[':markon'] = self::MARKING_ON;
        }
        if ($type == USERSETTYPE_GROUP) {
            if (is_numeric($groupid)) {
                $grp = ' AND "groupID" = :groupid ';
                $input_arr[':groupid'] = $groupid;
            }

            $qry = '* FROM setdata WHERE "ID" NOT IN ( SELECT "setID" FROM usersetinfo ' .
                " WHERE (\"$element\" = :marking $qint) AND \"userID\" = :userid AND \"type\" = :type ) AND \"ID\" IN ($sets_str) $grp";
            $input_arr[':userid'] = $userid;
            $input_arr[':marking'] = $marking;
            $ipnut_arr[':type'] = $type;

        } elseif ($type == USERSETTYPE_RSS) { // feed
            if (is_numeric($groupid)) {
                $grp = ' AND "feedid" = :feedid ';
                $input_arr[':feedid'] = $groupid;
            }

            $qry = ' "setid" AS "ID" FROM rss_sets WHERE "setid" NOT IN (SELECT "setID" FROM usersetinfo ' .
                " WHERE (\"$element\" = :marking $qint) AND \"userID\" = :userid AND \"type\" = :type ) AND \"setid\" IN ($sets_str) $grp";
            $input_arr[':userid'] = $userid;
            $input_arr[':marking'] = $marking;
            $ipnut_arr[':type'] = $type;
        } elseif ($type == USERSETTYPE_SPOT) {
            $qry = ' "spotid" AS "ID" FROM spots WHERE "spotid" NOT IN (SELECT "setID" FROM usersetinfo ' .
                " WHERE (\"$element\" = :marking $qint) AND \"userID\" = :userid AND \"type\" = :type) AND \"spotid\" IN ($sets_str) ";
            $input_arr[':userid'] = $userid;
            $input_arr[':marking'] = $marking;
            $ipnut_arr[':type'] = $type;
        }
        $res = $db->select_query($qry, $input_arr);
        if ($res !== FALSE) {
            self::mark_sets($db, $res, $userid, $type, $marking, $element, $manual);
        }
    }

    public static function unmark_all(DatabaseConnection $db, $userid, $element='statusint', $groupid = NULL, $type=USERSETTYPE_GROUP, $marking=self::MARKING_OFF, $manual = FALSE)
    { // unmark all sets for a user that are already marked in a group
        global $LN;
        assert (in_array($type, array(USERSETTYPE_GROUP, USERSETTYPE_RSS, USERSETTYPE_SPOT)) && is_numeric($marking));
        if ($element != 'statuskill' && $element != 'statusread') {
            $element = 'statusint';
        }
        if (!is_numeric($userid)) {
            throw new exception ($LN['error_invaliduserid'], ERR_INVALID_USERID);
        }

        if ($groupid === NULL) {
            $db->update_query_2('usersetinfo', array($element=>$marking), '"userID"=? AND "type"=?', array($userid, $type));
        } elseif (is_numeric($groupid)) {
            if ($type == USERSETTYPE_GROUP) {
                $qry = 'setdata."ID" FROM setdata LEFT JOIN usersetinfo AS usi '
                    . ' ON usi."setID" = setdata."ID" WHERE usi."userID" = ? AND setdata."groupID"=? AND "type"=?';
                $input_arr = array($userid, $groupid, $type);
            } elseif ($type == USERSETTYPE_RSS) {
                $qry = 'rss_sets."setid" AS "ID" FROM rss_sets LEFT JOIN usersetinfo AS usi '
                    . ' ON usi."setID" = rss_sets."setid" WHERE usi."userID" = ? AND rss_sets."rss_id"=? AND "type"=?';
                $input_arr = array($userid, $groupid, $type);
            } elseif ($type == USERSETTYPE_SPOT) {
                $qry = 'spots."spotid" AS "ID" FROM spots LEFT JOIN usersetinfo AS usi '
                    . ' ON usi."setID" = spots."spotid" WHERE usi."userID" = ? AND "type"=?';
                $input_arr = array($userid, $type);
            }
            $res = $db->select_query($qry, $input_arr);
            if ($res !== FALSE) {
                self::mark_sets($db, $res, $userid, $type, $marking, $element, $manual);
            }
        } else {
            throw new exception ($LN['error_invalidgroupid'], ERR_INVALID_USERID);
        }
    }

    public static function mark_all(DatabaseConnection $db, $userid, $element='statusint', $groupid, $type, $marking=self::MARKING_ON, $manual = FALSE)
    { // mark all sets in a given group
        global $LN;
        assert (in_array($type, array(USERSETTYPE_GROUP, USERSETTYPE_RSS, USERSETTYPE_SPOT)));
        if ($element != 'statuskill' && $element != 'statusread') {
            $element = 'statusint';
        }
        if (!is_numeric($userid)) {
            throw new exception ($LN['error_invaliduserid'], ERR_INVALID_USERID);
        }
        $input_arr = array();
        if ($type == USERSETTYPE_GROUP) {
            $grp = '';
            if (is_numeric($groupid)) {
                $grp = ' AND "groupID" = :groupid ';
                $input_arr[':groupid'] = $groupid;
            }

            $qry = ' * FROM setdata WHERE "ID" NOT IN ( SELECT "setID" FROM usersetinfo ' . 
                   "WHERE \"$element\" = :marking AND \"userID\" = :userid AND \"type\" = :type) $grp";
            $input_arr[':userid'] = $userid;
            $input_arr[':marking'] = $marking;
            $ipnut_arr[':type'] = $type;
        } else {
            $grp = '';
            if (is_numeric($groupid)) {
                $grp = ' AND "feed_id" = :feedid ';
                $input_arr[':feedid'] = $groupid;
            }

            $qry = ' "setid" AS "ID" FROM rss_sets WHERE "setid" NOT IN (SELECT "setID" FROM usersetinfo ' .
                   "WHERE \"$element\" = :marking' AND \"userID\" = :userid AND \"type\"= :type) $grp";
            $input_arr[':userid'] = $userid;
            $input_arr[':marking'] = $marking;
            $ipnut_arr[':type'] = $type;
        }
        $res = $db->select_query($qry, $input_arr);
        if ($res !== FALSE) {
            self::mark_sets($db, $res, $userid, $type, $marking, $element, $manual);
        }
    }

    private static function get_match_terms(DatabaseConnection $db, $terms, $userid)
    {
        assert(is_numeric($userid));
        $search_terms = get_pref($db, $terms, $userid, '');
        if ($search_terms == '') {
            return FALSE;
        }
        $terms = unserialize($search_terms);

        return $terms;
    }

    private static function expand_search_terms_as_query(DatabaseConnection $db, array $terms, $search_type, array $fields)
    {
        assert(count($fields) >= 2);

        $like_a = $like_b = $like1 = $like2 = '';
        reset($fields);
        $table1 = key($fields);
        $column1 = current($fields);
        next($fields);
        $table2 = key($fields);
        $column2 = current($fields);
        foreach ($terms as $idx1 => $term) {
            $term = trim($term);
            if ($term == '') {
                continue;
            }
            $words = explode(' ', $term);
            $like1 = $like2 = '1=1';
            foreach ($words as $idx2 => $word) {
                $word = trim($word);
                if ($word == '') {
                    continue;
                }
                $not = '';
                if ($word[0] == '-') {
                    $not = 'NOT';
                    $word = ltrim($word, '-');
                }
                if ($search_type == 'REGEXP') {
                    $word = ".*$word.*";
                } else {
                    $word = "%$word%";
                }
                $db->escape($word, TRUE);

                $like1 .= " AND ($not $table1.\"$column1\" $search_type $word)";
                $like2 .= " AND ($not $table2.\"$column2\" $search_type $word)";
            }
            $like_a .= " OR ($like1)";
            $like_b .= " OR ($like2)";
        }

        return array($like_a, $like_b);
    }

    private static function mark_search_rss(DatabaseConnection $db, $userid, $element = 'statusint', $feed_id=NULL)
        // used to automatically mark sets interesting or kill from the search terms / blocked terms
    {
        global $LN;
        assert(is_numeric($userid));
        $mail_interesting_sets = get_pref($db, 'mail_user_sets', $userid);
        if ($element == 'statuskill') {
            $terms = 'blocked_terms';
    /*	elseif $element == 'statusread')
    $terms = '' */ // todo
        } else {
            $element = 'statusint';
            $terms = 'search_terms';
        }
        $search_type = strtoupper(get_pref($db, 'search_type', $userid, 'LIKE'));
        if ($search_type != 'REGEXP' && $search_type != 'LIKE') {
            $search_type = 'LIKE';
        }
        $search_type = $db->get_pattern_search_command($search_type); // postgres doesn't like regexp, but uses similar .... I just love standards
        $terms = self::get_match_terms($db, $terms, $userid);
        if ($terms === FALSE) {
            return NULL;
        }
        list ($like_setdata, $like_extsetdata) = self::expand_search_terms_as_query($db, $terms, $search_type, array('rss_sets'=> 'setname', 'extsetdata' => 'value'));
        if ($feed_id === NULL) {
            $feed = '';
        } elseif (is_numeric($feed_id)) {
            if (!feed_subscribed($db, $feed_id)) {
                throw new exception( $LN['error_feednotfound'], ERR_RSS_NOT_FOUND);
            }
            $feed = " \"rss_id\"=$feed_id AND";
        } else {
            throw new exception ($LN['error_invalidfeedid'], ERR_RSS_NOT_FOUND);
        }
        if ($like_setdata == '') {
            $like_setdata = ' AND 1=0 ';
        }
        if ($like_extsetdata == '') {
            $like_extsetdata = ' AND 1=0 ';
        }

        $type = USERSETTYPE_RSS;

        $sql = "SELECT rss_sets.\"setid\" AS \"ID\" FROM rss_sets WHERE $feed (1=0 $like_setdata) AND rss_sets.\"setid\" NOT IN (SELECT \"setID\" FROM usersetinfo WHERE \"userID\" = $userid AND \"type\"='$type') ";
        $sql .= "UNION SELECT rss_sets.\"setid\" AS \"ID\" FROM rss_sets, extsetdata WHERE $feed \"type\"='$type' AND rss_sets.\"setid\" = extsetdata.\"setID\" AND extsetdata.\"name\" = 'setname' AND (1=0 $like_extsetdata) AND rss_sets.\"setid\" NOT IN (SELECT \"setID\" FROM usersetinfo WHERE \"userID\" = $userid AND \"type\"='$type')";
        $res = $db->execute_query($sql); // query is still incorrect??
        if ($res !== FALSE) {
            self::mark_sets($db, $res, $userid, USERSETTYPE_RSS, self::MARKING_ON, $element, FALSE);
            if ($mail_interesting_sets && $element == 'statusint') {
                urd_mail::mail_sets($db, $res, $userid, $feed_id, USERSETTYPE_RSS);
            }
        }
    }


    private static function mark_search_group(DatabaseConnection $db, $userid, $element = 'statusint', $groupid=NULL)
        // used to automatically mark sets interesting or kill from the search terms / blocked terms
    {
        global $LN;
        assert(is_numeric($userid));
        $mail_interesting_sets = get_pref($db, 'mail_user_sets', $userid);
        if ($element == 'statuskill') {
            $terms = 'blocked_terms';
        }
    /*	elseif $element == 'statusread')
    $terms = '' */ // todo
        else {
            $element = 'statusint';
            $terms = 'search_terms';
        }
        $search_type = strtoupper(get_pref($db, 'search_type', $userid, 'LIKE'));
        if ($search_type != 'REGEXP' && $search_type != 'LIKE') {
            $search_type = 'LIKE';
        }
        $search_type = $db->get_pattern_search_command($search_type); // postgres doesn't like regexp, but uses similar .... I just love standards
        $terms = self::get_match_terms($db, $terms, $userid);
        if ($terms === FALSE) {
            return NULL;
        }
        list ($like_setdata, $like_extsetdata) = self::expand_search_terms_as_query($db, $terms, $search_type, array('setdata'=> 'subject', 'extsetdata' => 'value'));
        if ($groupid === NULL) {
            $group = '';
        } elseif (is_numeric($groupid)) {
            if (!group_subscribed($db, $groupid)) {
                throw new exception($LN['error_groupnotfound'] . ": $groupid", ERR_GROUP_NOT_FOUND);
            }
            $group = " \"groupID\"=$groupid AND";
        } else {
            throw new exception ($LN['error_invalidgroupid'] . ": $groupid", ERR_GROUP_NOT_FOUND);
        }
        if ($like_setdata == '') {
            $like_setdata = ' AND 1=0 ';
        }
        if ($like_extsetdata == '') {
            $like_extsetdata = ' AND 1=0 ';
        }

        $type = USERSETTYPE_GROUP;
        $sql = "SELECT setdata.\"ID\" FROM setdata WHERE $group (1=0 $like_setdata) AND setdata.\"ID\" NOT IN (SELECT \"setID\" FROM usersetinfo WHERE \"userID\" = $userid AND \"type\"='$type') ";
        $sql .= "UNION SELECT setdata.\"ID\" FROM setdata, extsetdata WHERE $group \"type\"='$type' AND setdata.\"ID\" = extsetdata.\"setID\" AND extsetdata.\"name\" = 'setname' AND (1=0 $like_extsetdata) AND setdata.\"ID\" NOT IN (SELECT \"setID\" FROM usersetinfo WHERE \"userID\" = $userid AND \"type\"='$type')";

        $res = $db->execute_query($sql);
        if ($res !== FALSE) {
            self::mark_sets($db, $res, $userid, USERSETTYPE_GROUP, self::MARKING_ON, $element, FALSE);
            if ($mail_interesting_sets && $element == 'statusint') {
                urd_mail::mail_sets($db, $res, $userid, $groupid, USERSETTYPE_GROUP);
            }
        }
    }

    private static function mark_search_spots(DatabaseConnection $db, $userid, $element = 'statusint')
        // used to automatically mark sets interesting or kill from the search terms / blocked terms
    {
        assert(is_numeric($userid));
        $mail_interesting_sets = get_pref($db, 'mail_user_sets', $userid);
        if ($element == 'statuskill') {
            $terms = 'blocked_terms';
        }
    /*	elseif $element == 'statusread')
    $terms = '' */ // todo
        else {
            $element = 'statusint';
            $terms = 'search_terms';
        }
        $search_type = strtoupper(get_pref($db, 'search_type', $userid, 'LIKE'));
        if ($search_type != 'REGEXP' && $search_type != 'LIKE') {
            $search_type = 'LIKE';
        }
        $search_type = $db->get_pattern_search_command($search_type); // postgres doesn't like regexp, but uses similar .... I just love standards
        $terms = self::get_match_terms($db, $terms, $userid);
        if ($terms === FALSE) {
            return NULL;
        }
        list ($like_setdata, $like_extsetdata) = self::expand_search_terms_as_query($db, $terms, $search_type, array('spots'=> 'title', 'extsetdata' => 'value')); // generalise this function TODO
        if ($like_setdata == '') {
            $like_setdata = ' AND 1=0 ';
        }

        $type = USERSETTYPE_SPOT;
        $sql = "spots.\"spotid\" AS \"ID\" FROM spots WHERE (1=0 $like_setdata) AND spots.\"spotid\" NOT IN (SELECT \"setID\" FROM usersetinfo WHERE \"userID\" = :userid AND \"type\"=:type) ";
        $res = $db->select_query($sql, array(':userid'=>$userid, ':type'=> $type));
        if ($res !== FALSE) {
            self::mark_sets($db, $res, $userid, $type, self::MARKING_ON, $element, FALSE);
            if ($mail_interesting_sets && $element == 'statusint') {
                urd_mail::mail_sets($db, $res, $userid, NULL, USERSETTYPE_SPOT);
            }
        }
    }

    private static function auto_download_spots(DatabaseConnection $db, $userid)
    {
        echo_debug_function(DEBUG_WORKER, __FUNCTION__);

        assert(is_numeric($userid));

        $type = USERSETTYPE_SPOT;
        $search_type = strtoupper(get_pref($db, 'search_type', $userid, 'LIKE'));
        if ($search_type != 'REGEXP' && $search_type != 'LIKE') {
            $search_type = 'LIKE';
        }

        $search_type = $db->get_pattern_search_command($search_type); // postgres doesn't like regexp, but uses similar .... I just love standards
        $blocked_terms = 'blocked_terms';
        $search_terms = 'search_terms';
        $search_terms = self::get_match_terms($db, $search_terms , $userid, '');
        $like_setdata_true = $like_extsetdata_true = $like_setdata_false = $like_extsetdata_false = '';
        if ($search_terms === FALSE) {
            return NULL;
        }
        list ($like_setdata_true, $like_extsetdata_true) = self::expand_search_terms_as_query($db, $search_terms, $search_type, array('spots'=> 'title', 'extsetdata' => 'value'));
        $blocked_terms = self::get_match_terms($db, $blocked_terms, $userid, '');
        if ($blocked_terms !== FALSE) {
            list ($like_setdata_false, $like_extsetdata_false) = self::expand_search_terms_as_query($db, $blocked_terms, $search_type, array('spots'=> 'title', 'extsetdata' => 'value'));
        }
        if ($like_setdata_true != '' && $like_setdata_false != '') {
            $like_setdata = " (1=0 $like_setdata_true) AND NOT (1=0 $like_setdata_false)";
        } elseif ($like_setdata_true != '') {
            $like_setdata = " (1=0 $like_setdata_true)";
        } else {// we don't do anything
            $like_setdata = ' 1=0';
        }
        if ($like_extsetdata_true != '' && $like_extsetdata_false != '') {
            $like_extsetdata = " (1=0 $like_extsetdata_true) AND NOT (1=0 $like_extsetdata_false)";
        } elseif ($like_extsetdata_true != '') {
            $like_extsetdata = " (1=0 $like_extsetdata_true)";
        } else { // we don't do anything
            $like_extsetdata = ' 1=0';
        }
        $maxsetsize = get_pref($db, 'maxsetsize', $userid, 0);
        $minsetsize = get_pref($db, 'minsetsize', $userid, 0);

        $Qsize = " AND ( spots.\"size\" >= $minsetsize )";
        if ($maxsetsize != 0) {
            $Qsize .= " AND ( spots.\"size\" <=  $maxsetsize ) ";
        }

        $sql  = 'spots."spotid" AS "ID", spots."size", extsetdata."value", spots."title" AS "subject", spots."spotid" AS "spotid", "category" FROM spots ';
        $sql .= "LEFT JOIN usersetinfo ON spots.\"spotid\" = usersetinfo.\"setID\" AND usersetinfo.\"type\"='$type' and usersetinfo.\"userID\" = '$userid'";
        $sql .= "LEFT JOIN extsetdata ON spots.\"spotid\" = extsetdata.\"setID\" AND extsetdata.\"name\" = 'setname' AND extsetdata.\"type\"='$type' ";
        $sql .= 'WHERE (usersetinfo."statusread" != 1 OR usersetinfo."statusread" IS NULL) AND ';
        $sql .= '(usersetinfo."statusnzb" != 1 OR usersetinfo."statusnzb" IS NULL) AND ';
        $sql .= '(usersetinfo."statuskill" != 1 OR usersetinfo."statuskill" IS NULL) AND ';
        $sql .= "(($like_extsetdata) OR ($like_setdata)) $Qsize";

        return $sql;
    }

    private static function auto_download_rss(DatabaseConnection $db, $userid, $feed_id=NULL)
    {
        echo_debug_function(DEBUG_WORKER, __FUNCTION__);
        global $LN;
        assert(is_numeric($userid));
        $type = USERSETTYPE_RSS;
        $search_type = strtoupper(get_pref($db, 'search_type', $userid, 'LIKE'));
        if ($search_type != 'REGEXP' && $search_type != 'LIKE') {
            $search_type = 'LIKE';
        }

        $search_type = $db->get_pattern_search_command($search_type); // postgres doesn't like regexp, but uses similar .... I just love standards
        $blocked_terms = 'blocked_terms';
        $search_terms = 'search_terms';
        $search_terms = self::get_match_terms($db, $search_terms , $userid, '');
        $like_setdata_true = $like_extsetdata_true = $like_setdata_false = $like_extsetdata_false = '';
        if ($search_terms === FALSE) {
            return NULL;
        }
        list ($like_setdata_true, $like_extsetdata_true) = self::expand_search_terms_as_query($db, $search_terms, $search_type, array('rss_sets'=> 'setname', 'extsetdata' => 'value'));
        $blocked_terms = self::get_match_terms($db, $blocked_terms , $userid, '');
        if ($blocked_terms !== FALSE) {
            list ($like_setdata_false, $like_extsetdata_false) = self::expand_search_terms_as_query($db, $blocked_terms, $search_type, array('rss_sets'=> 'setname', 'extsetdata' => 'value'));
        }

        if ($feed_id === NULL) {
            $feed = '';
        } elseif (is_numeric($feed_id)) {
            get_feed_by_id($db, $feed_id);
            $db->escape($feed_id, TRUE);
            $feed = " AND rss_sets.\"rss_id\"=$feed_id ";
        } else {
            throw new exception ($LN['error_invalidfeedid'], ERR_RSS_NOT_FOUND);
        }

        if ($like_setdata_true != '' && $like_setdata_false != '') {
            $like_setdata = " (1=0 $like_setdata_true) AND NOT (1=0 $like_setdata_false)";
        } elseif ($like_setdata_true != '') {
            $like_setdata = " (1=0 $like_setdata_true)";
        } else {// we don't do anything
            $like_setdata = ' 1=0';
        }
        if ($like_extsetdata_true != '' && $like_extsetdata_false != '') {
            $like_extsetdata = " (1=0 $like_extsetdata_true) AND NOT (1=0 $like_extsetdata_false)";
        } elseif ($like_extsetdata_true != '') {
            $like_extsetdata = " (1=0 $like_extsetdata_true)";
        } else { // we don't do anything
            $like_extsetdata = ' 1=0';
        }

        $maxsetsize = get_pref($db, 'maxsetsize', $userid, 0);
        $minsetsize = get_pref($db, 'minsetsize', $userid, 0);

        $Qsize = "( rss_sets.\"size\" >= ((SELECT CASE WHEN \"minsetsize\" IS NULL THEN $minsetsize ELSE minsetsize END) ))";

        if ($maxsetsize == 0) {
            $Qsize .= ' AND (userfeedinfo."maxsetsize" = 0 OR "maxsetsize" IS NULL OR rss_sets."size" <= ( "maxsetsize" )) ';
        } else {
            $Qsize .= " AND (userfeedinfo.\"maxsetsize\" = 0 OR (rss_sets.\"size\" <= ((SELECT CASE WHEN \"maxsetsize\" IS NULL THEN $maxsetsize ELSE \"maxsetsize\" END) ))) ";
        }

        $Qsize = " AND (rss_sets.\"size\" = 0 OR ($Qsize) )"; // size == 0 is special in rss feeds --> size unknown
        $Qvisible = ' AND (userfeedinfo.visible IS NULL OR userfeedinfo.visible = 1)';

        $sql  = 'rss_sets."setid" AS "ID", rss_sets."size", extsetdata."value", rss_sets."setname" AS "subject", rss_sets."rss_id" AS "rss_id" FROM rss_sets ';
        $sql .= "LEFT JOIN usersetinfo ON rss_sets.\"setid\" = usersetinfo.\"setID\" AND usersetinfo.\"type\"='$type' and usersetinfo.\"userID\" = '$userid'";
        $sql .= "LEFT JOIN userfeedinfo ON rss_sets.\"rss_id\" = userfeedinfo.\"feedid\" AND userfeedinfo.\"userid\" = '$userid' ";
        $sql .= "LEFT JOIN extsetdata ON rss_sets.\"setid\" = extsetdata.\"setID\" AND extsetdata.\"name\" = 'setname' AND extsetdata.\"type\"='$type' ";
        $sql .= 'WHERE (usersetinfo."statusread" != 1 OR usersetinfo."statusread" IS NULL) AND ';
        $sql .= '(usersetinfo."statusnzb" != 1 OR usersetinfo."statusnzb" IS NULL) AND ';
        $sql .= '(usersetinfo."statuskill" != 1 OR usersetinfo."statuskill" IS NULL) ';
        $sql .= "$Qvisible AND (($like_extsetdata) OR ($like_setdata)) $feed $Qsize";

        return $sql;
    }

    private static function add_download_sets(DatabaseConnection $db, $userid, $sql, $type)
    {
        assert(is_numeric($userid) && is_string($sql));
        $start = 0;
        $stepsize = 10;

        do {
            $res = $db->select_query($sql, $stepsize, $start);
            try {
                if ($res !== FALSE && count($res) > 0) {
                    download_sets($db, $res, $userid, $type);
                } else {
                    break;
                }
            } catch (exception $e) {
                write_log('Something went wrong while auto-downloading: ' . $e->getMessage(), LOG_NOTICE);
                break;
            }
            $start += $stepsize;
        } while (TRUE);
    }

    private static function auto_download_group(DatabaseConnection $db, $userid, $groupid=NULL)
    { // works only for index sets yet
        global $LN;
        echo_debug_function(DEBUG_WORKER, __FUNCTION__);
        assert(is_numeric($userid));
        $type = USERSETTYPE_GROUP;
        $like_setdata_true = $like_extsetdata_true = $like_setdata_false = $like_extsetdata_false = '';
        $search_type = strtoupper(get_pref($db, 'search_type', $userid, 'LIKE'));
        if ($search_type != 'REGEXP' && $search_type != 'LIKE') {
            $search_type = 'LIKE';
        }

        $search_type = $db->get_pattern_search_command($search_type); // postgres doesn't like regexp, but uses similar .... I just love standards
        $blocked_terms = 'blocked_terms';
        $search_terms = 'search_terms';
        $search_terms = self::get_match_terms($db, $search_terms, $userid, '');
        if ($search_terms === FALSE) {
            return NULL;
        }
        list ($like_setdata_true, $like_extsetdata_true) = self::expand_search_terms_as_query($db, $search_terms, $search_type, array('setdata' => 'subject', 'extsetdata' => 'value'));
        $blocked_terms = self::get_match_terms($db, $blocked_terms , $userid, '');
        if ($blocked_terms !== FALSE) {
            list ($like_setdata_false, $like_extsetdata_false) = self::expand_search_terms_as_query($db, $blocked_terms, $search_type, array('setdata' => 'subject', 'extsetdata' => 'value'));
        }

        if ($groupid === NULL) {
            $group = '';
        } elseif (is_numeric($groupid)) {
            get_group_by_id($db, $groupid);
            $db->escape($groupid, TRUE);
            $group = " AND setdata.\"groupID\"=$groupid ";
        } else {
            throw new exception ($LN['error_invalidgroupid'], ERR_GROUP_NOT_FOUND);
        }
        if ($like_setdata_true != '' && $like_setdata_false != '') {
            $like_setdata = " (1=0 $like_setdata_true) AND NOT (1=0 $like_setdata_false)";
        } elseif ($like_setdata_true != '') {
            $like_setdata = " (1=0 $like_setdata_true)";
        } else { // we don't do anything
            $like_setdata = ' 1=0';
        }
        if ($like_extsetdata_true != '' && $like_extsetdata_false != '') {
            $like_extsetdata = " (1=0 $like_extsetdata_true) AND NOT (1=0 $like_extsetdata_false)";
        } elseif ($like_extsetdata_true != '') {
            $like_extsetdata = " (1=0 $like_extsetdata_true)";
        } else {// we don't do anything
            $like_extsetdata = ' 1=0';
        }

        $maxsetsize = get_pref($db, 'maxsetsize', $userid, 0);
        $minsetsize = get_pref($db, 'minsetsize', $userid, 0);
        $setcompleteness = get_pref($db, 'setcompleteness', $userid, 0);

        $GREATEST = $db->get_greatest_function();
        $Qcomplete = " AND (\"articlesmax\"=0 OR floor(\"binaries\" * 100 / $GREATEST(1, \"articlesmax\")) >= {$setcompleteness})";/// euah ... the horror... but it is ansi sql compliant... no refers to as fields in where clauses ...
        $Qsize = " AND ( setdata.\"size\" >= ((SELECT CASE WHEN \"minsetsize\" IS NULL THEN $minsetsize ELSE minsetsize END) ))";

        if ($maxsetsize == 0) {
            $Qsize .= ' AND (usergroupinfo."maxsetsize" = 0 OR maxsetsize IS NULL OR setdata.size <= ( maxsetsize)) ';
        } else {
            $Qsize .= " AND (usergroupinfo.\"maxsetsize\" = 0 OR (setdata.size <= ((SELECT CASE WHEN maxsetsize IS NULL THEN $maxsetsize ELSE maxsetsize END)))) ";
        }
        $Qvisible = ' AND (usergroupinfo.visible IS NULL OR usergroupinfo.visible = 1)';

        $sql  = "setdata.\"ID\", setdata.\"size\", extsetdata.\"value\", setdata.\"subject\", setdata.\"groupID\" AS \"groupID\" FROM setdata ";
        $sql .= "LEFT JOIN usersetinfo ON setdata.\"ID\" = usersetinfo.\"setID\" AND usersetinfo.\"type\"='$type' and usersetinfo.\"userID\" = '$userid'";
        $sql .= "LEFT JOIN usergroupinfo ON setdata.\"groupID\" = usergroupinfo.\"groupid\" AND usergroupinfo.\"userid\" = '$userid' ";
        $sql .= "LEFT JOIN extsetdata ON setdata.\"ID\" = extsetdata.\"setID\" AND extsetdata.\"name\" = 'setname' AND extsetdata.\"type\"='$type' ";
        $sql .= 'WHERE (usersetinfo."statusread" != 1 OR usersetinfo."statusread" IS NULL) AND ';
        $sql .= '(usersetinfo."statusnzb" != 1 OR usersetinfo."statusnzb" IS NULL) AND ';
        $sql .= '(usersetinfo."statuskill" != 1 OR usersetinfo."statuskill" IS NULL) ';
        $sql .= " $Qvisible AND (($like_extsetdata) OR ($like_setdata)) $group $Qsize $Qcomplete";

        return $sql;
    }

    public static function auto_download(DatabaseConnection $db, $type, $userid, $id=NULL)
    {
        echo_debug_function(DEBUG_WORKER, __FUNCTION__);
        assert(is_numeric($userid));
        switch ($type) {
        case USERSETTYPE_GROUP:
            $sql = self::auto_download_group($db, $userid, $id);
            break;
        case USERSETTYPE_RSS:
            $sql = self::auto_download_rss($db, $userid, $id);
            break;
        case USERSETTYPE_SPOT:
            $sql = self::auto_download_spots($db, $userid);
            break;
        default:
            return;
        }
        if ($sql === NULL) {
            return;
        }

        self::add_download_sets($db, $userid, $sql, $type);
    }

    public static function mark_search(DatabaseConnection $db, $type, $userid, $element = 'statusint', $id=NULL)
    {
        assert(is_numeric($userid));
        echo_debug_function(DEBUG_WORKER, __FUNCTION__);
        switch ($type) {
        case USERSETTYPE_GROUP:
            return self::mark_search_group($db, $userid, $element, $id);
            break;
        case USERSETTYPE_RSS:
            return self::mark_search_rss($db, $userid, $element, $id);
            break;
        case USERSETTYPE_SPOT:
            return self::mark_search_spots($db, $userid, $element);
            break;
        default:
            throw new exception('Unknown type');

            return;
        }
    }

    public static function mark_interesting(DatabaseConnection $db, $type, $id=NULL)
    {
        echo_debug_function(DEBUG_WORKER, __FUNCTION__);
        $auto_download = get_config($db, 'auto_download', 0);

        $res = get_active_users($db);
        if ($res === FALSE) {
            global $LN;
            write_log($LN['error_nousersfound'], LOG_NOTICE);
        }

        foreach ($res as $row) {
            $userid = $row['ID'];
            sets_marking::mark_search($db, $type, $userid, 'statuskill', $id);
            if (($auto_download > 0) && (get_pref($db, 'use_auto_download', $userid, 0) > 0) && urd_user_rights::is_autodownloader($db, $userid)) {
                self::auto_download($db, $type, $userid, $id);
            } else {
                self::mark_search($db, $type, $userid, 'statusint', $id);
            }
        }
    }
}
