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
 * $LastChangedDate: 2014-06-14 01:20:27 +0200 (za, 14 jun 2014) $
 * $Rev: 3094 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: pref_functions.php 3094 2014-06-13 23:20:27Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathprf = realpath(dirname(__FILE__));

require_once "$pathprf/periods.php";
require_once "$pathprf/error_codes.php";

class user_levels
{
    const CONFIG_LEVEL_ALWAYS       = 0;
    const CONFIG_LEVEL_BASIC        = 10;
    const CONFIG_LEVEL_ADVANCED     = 50;
    const CONFIG_LEVEL_MASTER       = 100;

    public static function get_user_levels()
    {
        global $LN;

        $level_array = array(
            self::CONFIG_LEVEL_BASIC	  => $LN['level_basic'],
            self::CONFIG_LEVEL_ADVANCED   => $LN['level_advanced'],
            self::CONFIG_LEVEL_MASTER	  => $LN['level_master']
        );

        return $level_array;
    }

}

class pref_list
{
    public $name;
    public $value;
    public $length;
    public function __construct($n, array $v)
    {
        global $LN;
        $this->name = $LN[$n];
        $this->tabname = $n;
        $this->value = $v;
        $this->length = count($v) . ' ';
    }
}

abstract class pref_basic
{
    public $level; // config level
    public $name; // name of input
    public $id;
    public $tr_class; // class of of the tr ??
    public $tr_id;    // ID of the tr to use in hiding
    public $javascript; // javascript code to add to the input
    public $text;  // left hand side of ext
    public $popup; // text to put in a "hover" --> explanation
    public $error_msg;
    public function __construct($lvl, $tx, $nm, $p, $err, $js=NULL, $tr_i=NULL, $tr_c=NULL)
    {
        assert(is_numeric($lvl));
        $this->level = $lvl;
        $this->text = $tx;
        $this->name = $nm;
        $this->popup = $p;
        if ($err == '' || $err == NULL) {
            $err = make_error_msg();
        }
        $this->error_msg = $err;

        $this->javascript = $js;
        $this->tr_id = $tr_i;
        $this->tr_class = $tr_c;
        $this->id = str_replace(array('[',']'), '_', $nm);
    }
    abstract protected function get_type();
};

class pref_plain extends pref_basic
{
    public $value; // value of input
    public function __construct($lvl, $tx, $p, $v, $js=NULL, $tr_i=NULL, $tr_c=NULL)
    {
        parent::__construct($lvl, $tx, '', $p, '',  $js, $tr_i, $tr_c);
        $this->value = $v;

    }
    public function get_type() {return 'plain';}
};

// public $select; // selected value
class pref_text extends pref_basic
{
    public $size; //size of the text field
    public $value; // value of input
    public function __construct($lvl, $tx, $nm, $p, $err, $v, $sz, $js=NULL, $tr_i=NULL, $tr_c=NULL)
    {
        assert(is_numeric($sz));
        parent::__construct($lvl, $tx, $nm, $p, $err, $js, $tr_i, $tr_c);
        $this->size = $sz;
        $this->value = $v;
    }
    public function get_type() {return 'text';}
};

class pref_email extends pref_basic
{
    public $size; //size of the text field
    public $value; // value of input
    public function __construct($lvl, $tx, $nm, $p, $err, $v, $sz, $js=NULL, $tr_i=NULL, $tr_c=NULL)
    {
        assert(is_numeric($sz));
        parent::__construct($lvl, $tx, $nm, $p, $err, $js, $tr_i, $tr_c);
        $this->size = $sz;
        $this->value = $v;
    }
    public function get_type() {return 'email';}
};


class pref_custom_text extends pref_basic
{
    public $size; //size of the text field
    public $value; // value of input
    public function __construct($lvl, $tx, $nm, $p, $err, $v, $sz, $js=NULL, $tr_i=NULL, $tr_c=NULL)
    {
        assert(is_numeric($sz));
        parent::__construct($lvl, $tx, $nm, $p, $err, $js, $tr_i, $tr_c);
        $this->size = $sz;
        $this->value = $v;
    }
    public function get_type() {return 'custom_text';}
};


class pref_numeric_noformat extends pref_basic
{
    public $size; //size of the text field
    public $value; // value of input
    public function __construct($lvl, $tx, $nm, $p, $err, $v, $sz, $js=NULL, $tr_i=NULL, $tr_c=NULL)
    {
        assert(is_numeric($sz));
        parent::__construct($lvl, $tx, $nm, $p, $err, $js, $tr_i, $tr_c);
        $this->size = $sz;
        $this->value = $v;
    }
    public function get_type() {return 'text';}
};

class pref_numeric extends pref_basic
{
    public $size; //size of the text field
    public $value; // value of input
    public function __construct($lvl, $tx, $nm, $p, $err, $v, $sz, $js=NULL, $tr_i=NULL, $tr_c=NULL)
    {
        assert(is_numeric($sz));
        parent::__construct($lvl, $tx, $nm, $p, $err, $js, $tr_i, $tr_c);
        $this->size = $sz;
        list ($v, $s) = format_size($v, 'h', '', 1024, 0);
        $this->value = "$v$s";
    }
    public function get_type() {return 'text';}
};

class pref_button extends pref_basic
{
    public $value; // value of input
    public function __construct($lvl, $tx, $nm, $p, $err, $v,  $js=NULL, $tr_i=NULL, $tr_c=NULL)
    {
        parent::__construct($lvl, $tx, $nm, $p, $err, $js, $tr_i, $tr_c);
        $this->value = $v;
    }
    public function get_type() {return 'button';}
};

class pref_password_submit extends pref_basic
{
    public function __construct($opw_id, $npw_id1, $npw_id2, $id, $value, $tr_id, $username)
    {
        $this->opw_id = $opw_id;
        $this->npw_id1 = $npw_id1;
        $this->npw_id2 = $npw_id2;
        $this->id = $id;
        $this->tr_id = $tr_id;
        $this->value = $value;
        $this->text = '';
        $this->username = $username;
    }
    public function get_type() { return 'password_submit'; }
}

class pref_password extends pref_basic
{
    public $size; //size of the text field
    public $value; // value of input
    public function __construct($lvl, $tx, $nm, $p, $err, $v, $sz, $js=NULL, $tr_i=NULL, $tr_c=NULL)
    {
        assert(is_numeric($sz));
        parent::__construct($lvl, $tx, $nm, $p, $err, $js, $tr_i, $tr_c);
        $this->size = $sz;
        $this->value = $v;
    }
    public function get_type() {return 'password';}
};

class pref_textarea extends pref_basic
{
    public $rows; //size of the text field: rows
    public $cols; //size of the text field: rows
    public $value; // value of input

    public function __construct($lvl, $tx, $nm, $p, $err, $v, $rw, $cl, $js=NULL, $tr_i=NULL, $tr_c=NULL)
    {
        assert(is_numeric($rw) && is_numeric($cl));
        parent::__construct($lvl, $tx, $nm, $p, $err, $js, $tr_i, $tr_c);
        $this->rows = $rw;
        $this->cols = $cl;
	$this->value = '';
	if ($v != '') { 
		$this->value = utf8_decode(@implode("\n", $v));
	} 
		
    }
    public function get_type() {return 'textarea';}
};

class pref_checkbox extends pref_basic
{
    public $toggle;
    public function __construct($lvl, $tx, $nm, $p, $err, $toggle, $js=NULL, $tr_i=NULL, $tr_c=NULL)
    {
        parent::__construct($lvl, $tx, $nm, $p, $err, $js, $tr_i, $tr_c);
        $this->toggle = $toggle;
    }
    public function get_type() {return 'checkbox';}
};

class pref_select extends pref_basic
{
    public $options; // the select array
    public $selected; // the selected value
    public function __construct($lvl, $tx, $nm, $p, $err, array $options, $selected, $js=NULL, $tr_i=NULL, $tr_c=NULL)
    {
        parent::__construct($lvl, $tx, $nm, $p, $err, $js, $tr_i, $tr_c);
        $this->options = $options;
        $this->selected = $selected;
    }
    public function get_type() {return 'select';}
};

class pref_multiselect extends pref_basic
{
    public $size; //size of the text field
    public $options_triple; // the select array  of array('name'=>X, 'on'=>Y, 'id'=>Z);
    public function __construct($lvl, $tx, $nm, $p, $err, array $options_triple, $sz, $js=NULL, $tr_i=NULL, $tr_c=NULL)
    {
        parent::__construct($lvl, $tx, $nm, $p, $err, $js, $tr_i, $tr_c);
        $this->size = $sz;
        $this->options_triple = $options_triple;
    }
    public function get_type() {return 'multiselect';}
};

class pref_period extends pref_basic
{
    public $period_name;
    public $period_options;
    public $period_selected;
    public $period_keys;
    public $period_texts;
    public $time1_name;
    public $time1_value;
    public $time2_name;
    public $time2_value;

    public function __construct($lvl, $tx, $p, $err, $pn, $ps, $tn1, $tv1, $tn2, $tv2, $en=NULL, $eo=NULL, $es=NULL, $js=NULL, $tr_i=NULL, $tr_c=NULL)
    {
        parent::__construct($lvl, $tx, NULL, $p, $err, $js, $tr_i, $tr_c);
        global $periods;
        $this->period_name = $pn;
        //$this->period_options = $po;
        list($this->period_keys, $this->period_texts) = $periods->get_periods();
        $this->period_selected = $ps;
        $this->time1_name = $tn1;
        $this->time1_value = $tv1;
        $this->time2_name = $tn2;
        $this->time2_value = $tv2;
        $this->extra_name = $en;
        $this->extra_options = $eo;
        $this->extra_selected = $es;
    }
    public function get_type() {return 'period';}
};

function make_error_msg($msg=NULL, $hdr=NULL)
{
    return array('msg'=>$msg, 'hdr'=>$hdr);
}

function verify_email_address($email)
{
    global $LN;
    if (verify_email($email)) {
        return '';
    } else {
        return make_error_msg($LN['error_invalidemail'], $LN['error_error'] . ':');
    }
}

function verify_prog($prog, $optional=FALSE)
{
    global $LN;
    if (($optional === TRUE && $prog == '') || is_executable($prog)) {
        return '';
    } else {
        $prog = htmlspecialchars($prog);

        return make_error_msg($LN['error_filenotexec'] . ": <i>'$prog'</i>",  $LN['error_error'] . ':');
    }
}

function verify_numeric_opt($val, $optional=FALSE, $min = NULL, $max = NULL, $base=1024, $default_mul =NULL)
{
    assert(is_bool($optional) && is_numeric($base));
    if ($optional === TRUE && $val == '') {
        return '';
    } else {
        return verify_numeric($val, $min, $max, $base=1024, $default_mul = '');
    }
}

function verify_group($group, $optional= FALSE)
{
    global $LN;
    if (($optional === TRUE && $group == '') || posix_getgrnam($group) !== FALSE) {
        return '';
    } else {
        $group = htmlspecialchars($group);

        return make_error_msg($LN['error_invalidgroup'] . ": <i>'$group'</i>",  $LN['error_error'] . ':');
    }
}

function match_all($haystack, $needles)
{
    $pattern = "/^($needles)+$/";

    $rv = preg_match($pattern, $haystack);

    return $rv != 0;
}

function verify_url($text)
{
    global $LN;
    if (validate_url($text)) {
        return '';
    } else {
        if ($text == '') {
            $msg = $LN['error_notleftblank'];
        } else {
            $msg = $LN['error_invalidvalue'] . "'<i>$text</i>'<br/> " . $LN['error_urlstart'];
        }
        $text = htmlspecialchars($text);

        return make_error_msg($msg,  $LN['error_error'] . ':');
    }
}

function verify_text_area($text)
{
    return '';
}

function verify_text($text, $match=NULL)
{
    global $LN;
    if (($match === NULL) && ($text != '')) {
        return '';
    } elseif ($match !== NULL && match_all($text, $match)) {
        return '';
    } else {
        if ($text == '') {
            $msg = $LN['error_notleftblank'];
        } else {
            $msg = $LN['error_invalidvalue'] . " '<i>$text</i>'";
        }
        $text = htmlspecialchars($text);

        return make_error_msg($msg, $LN['error_error'] . ':');
    }
}

function verify_text_opt($text, $required=TRUE, $match=NULL)
{
    if ($required === FALSE && $text == '') {
        return '';
    } else {
        return verify_text($text, $match);
    }
}


function verify_read_only_path(DatabaseConnection $db, &$path)
{
    global $LN;

    $orig_path = $path;
    if (strpos($path, '..') !== FALSE) {
        return make_error_msg($LN['error_invaliddir'] . ": '<i>$orig_path</i>'", $LN['error_error'] . ':');
    }
    if ($path == '') {
        return make_error_msg($LN['error_invaliddir'] . ": '<i>$orig_path</i>'", $LN['error_error'] . ':');
    }
    add_dir_separator($path);
    clearstatcache(); // we want to be sure, so cache values are flushed.
    if ((!is_dir($path) || !is_readable($path))) {
        return make_error_msg($LN['error_dirnotwritable'] . " '<i>$orig_path</i>'", $LN['error_error'] . ':');
    }

    return '';
}


function verify_path(DatabaseConnection $db, &$path)
{
    global $LN;

    $orig_path = $path;
    if (strpos($path, '..') !== FALSE) {
        return make_error_msg($LN['error_invaliddir'] . ": '<i>$orig_path</i>'", $LN['error_error'] . ':');
    }
    if ($path == '') {
        return make_error_msg($LN['error_invaliddir'] . ": '<i>$orig_path</i>'", $LN['error_error'] . ':');
    }
    add_dir_separator($path);
    clearstatcache(); // we want to be sure, so cache values are flushed.
    if ((!is_dir($path) || !is_writable($path))) {
        return make_error_msg($LN['error_dirnotwritable'] . " '<i>$orig_path</i>'", $LN['error_error'] . ':');
    }

    return '';
}

function verify_dlpath(DatabaseConnection $db, &$path)
{
    $orig_path = $path;
    global $LN;
    if (strpos($path, '..') !== FALSE) {
        return make_error_msg($LN['error_invaliddir'] . ": '<i>$orig_path</i>'", $LN['error_error'] . ':');
    }
    if ($path == '') {
        return make_error_msg($LN['error_invaliddir'] . ": '<i>$orig_path</i>'", $LN['error_error'] . ':');
    }
    $path = my_realpath($path);
    add_dir_separator($path);

    clearstatcache(); // we want to be sure, so cache values are flushed.

    if (!file_exists($path)) {
        $rv = @mkdir($path, 0775, TRUE);
        if ($rv === FALSE) {
            return make_error_msg($LN['error_notmakedir']. " '<i>$orig_path</i>'", $LN['error_error'] . ':');
        }
        set_group($db, $path);
    } 
    clearstatcache(); // we want to be sure, so cache values are flushed.
    if ((!is_dir($path) || !is_writable($path))) {
        return make_error_msg($LN['error_dirnotwritable'] . " '<i>$orig_path</i>'", $LN['error_error'] . ':');
    }

    $rand = 'urd_test_file' . mt_rand(10000,99999);
    $paths = get_all_paths($path, '', TRUE);
    foreach ($paths as $p) {
        if ((!is_dir($p) || !is_writable($p))) {
            $rv = create_dir($p, 0775, TRUE);
            if ($rv === FALSE) {
                return make_error_msg($LN['error_notmakedir'] . " '<i>$p</i>'", $LN['error_error'] . ':');
            }
            set_group($db, $p);
            clearstatcache(); // we want to be sure, so cache values are flushed.
            if ((!is_dir($p) || !is_writable($p))) {
                return make_error_msg($LN['error_dirnotwritable'] . " '<i>$p</i>'", $LN['error_error'] . ':');
            }
            // try creating a file just to be sure
        }
        $rv = touch ($p . $rand);
        if ($rv === FALSE) {
            return make_error_msg($LN['error_dirnotwritable'] . " '<i>$p</i>'<br/>" . $LN['error_notestfile'], $LN['error_error'] . ':');
        }
        unlink($p . $rand);
    }

    return '';
}

function verify_array($val, array $arr)
{
    global $LN;
    if (in_array($val, $arr)) {
        return '';
    } else {
        return make_error_msg($LN['error_invalidvalue'], $LN['error_error'] . ':');
    }
}

function verify_sort($text, array $valids)
{
    global $LN;

    if (in_array(strtolower($text), array_map('strtolower', $valids))) {
        return '';
    } else {
        $text = htmlspecialchars($text);

        return make_error_msg($LN['error_invalidvalue'] . " '<i>$text</i>'", $LN['error_error'] . ':');
    }
}

function verify_numeric($val, $min=NULL, $max=NULL, $base=1024, $default_mul=NULL)
{
    global $LN;
    assert(is_numeric($base));
    try {
        $val_new = unformat_size($val, $base, $default_mul);
        $val = $val_new;
    } catch (exception $e) {
        $val = htmlspecialchars($val);
        $rv =  $LN['error_invalidvalue'] . " ($val)<br/>";

        return make_error_msg($rv, $LN['error_error'] . ':');
    }
    if (is_numeric($val) &&
            (($min === NULL) || ($val >= $min)) &&
            (($max === NULL) || ($val <= $max))) {
        return '';
    } else {
        $val = htmlspecialchars($val);
        $rv =  $LN['error_invalidvalue'] . " ($val)<br/>";
        if ($min !== NULL) {
            $rv .= $LN['error_mustbemore'] . " $min ";
        }
        if ($max !== NULL) {
            $rv .= $LN['error_mustbeless'] . " $max ";
        }

        return make_error_msg($rv, $LN['error_error'] . ':');
    }
}

function clean_area($text)
{
    $text = str_replace(array('\r', '\n'), array('', "\n"), $text);
    $terms = explode("\n", $text);
    $terms_new = array();
    foreach ($terms as $line) {
        if ($line != '') {
            $terms_new[] = $line;
        }
    }
    
    $text = serialize($terms_new);

    return $text;
}

function set_and_test_pref_text_area($name, $userid, $clean_area=FALSE)
{
    global $db;
    assert(is_numeric($userid));
    if (isset($_POST[$name])) {
        $val = $_POST[$name];
        $rv = verify_text_area($val);
        if ($clean_area === TRUE) {
            $val = clean_area($val);
        }
        if ($rv == '') {
            set_pref($db, $name, $val, $userid);

            return '';
        } else {
            return $rv;
        }
    } else {
        return '';
    }
}

function set_and_test_pref_bool($name, $userid, &$val)
{
    global $db;

    assert(is_numeric($userid));
    if (isset($_POST[$name]) && $_POST[$name] != 0) {
        $val = $_POST[$name];
        set_pref($db, $name, 1, $userid);
        $val = TRUE;
    } else {
        set_pref($db, $name, 0, $userid);
        $val = FALSE;
    }

    return '';
}

function set_and_test_pref_array($name, $userid, $valids)
{
    global $db;

    assert(is_numeric($userid));
    if (isset($_POST[$name])) {
        $val = $_POST[$name];
        $rv = verify_array($val, $valids);
        if ($rv == '') {
            set_pref($db, $name, $val, $userid);

            return '';
        } else {
            return $rv;
        }
    } else {
        return '';
    }
}

function set_and_test_pref_sort($name, $userid, $valids)
{
    global $db;

    assert(is_numeric($userid));
    if (isset($_POST[$name])) {
        $val = $_POST[$name];
        $rv = verify_sort($val, $valids);
        if ($rv == '') {
            set_pref($db, $name, $val, $userid);

            return '';
        } else {
            return $rv;
        }
    } else {
        return '';
    }
}

function set_and_test_pref_url($name, $userid)
{
    assert(is_numeric($userid));
    global $db;
    if (isset($_POST[$name])) {
        $val = $_POST[$name];
        $rv = verify_url($val);
        if ($rv == '') {
            set_pref($db, $name, $val, $userid);

            return '';
        } else {
            return $rv;
        }
    } else {
        return '';
    }
}

function set_and_test_pref_text($name, $userid, $match=NULL, $optional=FALSE, $notrim=FALSE)
{
    assert(is_numeric($userid));
    global $db;
    if (isset($_POST[$name])) {
        $val = $notrim?($_POST[$name]):(trim($_POST[$name]));
        if ($optional === FALSE) {
            $rv = verify_text($val, $match);
        } else {
            $rv = verify_text_opt($val, !$optional, $match);
        }
        if ($rv == '') {
            set_pref($db, $name, $val, $userid);

            return '';
        } else {
            return $rv;
        }
    } else {
        return '';
    }
}

function set_and_test_pref_prog($name, $userid, $optional=NULL)
{
    global $db;

    assert(is_numeric($userid));
    if (isset($_POST[$name])) {
        $val = trim($_POST[$name]);
        $rv = verify_prog($val, $optional);
        if ($rv == '') {
            set_pref($db, $name, $val, $userid);

            return '';
        } else {
            return $rv;
        }
    } else {
        return '';
    }
}

function set_and_test_pref_group($name, $userid, $optional=NULL)
{
    global $db;
    assert(is_numeric($userid));

    if (isset($_POST[$name])) {
        $val = trim($_POST[$name]);
        $rv = verify_group($val, $optional);
        if ($rv == '') {
            set_pref($db, $name, $val, $userid);

            return '';
        } else {
            return $rv;
        }
    } else {
        return '';
    }
}

function set_and_test_pref_email($name, $userid)
{
    global $db;

    assert(is_numeric($userid));
    if (isset($_POST[$name])) {
        $val = trim($_POST[$name]);
        $rv = verify_email_address($val);
        if ($rv == '') {
            set_pref($db, $name, $val, $userid);

            return '';
        } else {
            return $rv;
        }
    } else {
        return '';
    }
}

function set_and_test_pref_path($name, $userid, &$path)
{
    global $db;

    assert(is_numeric($userid));
    if (isset($_POST[$name])) {
        $val = trim($_POST[$name]);
        $rv = verify_path($db, $val);
        if ($rv == '') {
            set_pref($db, $name, $val, $userid);
            $path = $val;

            return '';
        } else {
            return $rv;
        }
    } else {
        return '';
    }
}

function set_and_test_pref_dlpath($name, $userid, &$path)
{
    global $db;

    assert(is_numeric($userid));
    if (isset($_POST[$name])) {
        $val = trim($_POST[$name]);
        $rv = verify_dlpath($db, $val);
        if ($rv == '') {
            set_pref($db, $name, $val, $userid);
            $path = $val;

            return '';
        } else {
            return $rv;
        }
    } else {
        return '';
    }
}

function set_and_test_pref_numeric($name, $userid, $min=NULL, $max=NULL, $base=1024, $default_mul = NULL)
{
    global $db , $LN;

    assert(is_numeric($userid));
    if (isset($_POST[$name])) {
        try {
            $val = trim($_POST[$name]);
            $val_new = unformat_size($val, $base, $default_mul);
            $val = $val_new;
        } catch (exception $e) {
            $val = htmlspecialchars($val);
            $rv =  $LN['error_invalidvalue'] . " ($val)<br/>";

            return make_error_msg($rv, $LN['error_error'] . ':');
        }
        $rv = verify_numeric($val, $min, $max, $base, '');
        if ($rv == '') {
            set_pref($db, $name, $val, $userid);

            return '';
        } else {
            return $rv;
        }
    } else {
        return '';
    }
}

function verify_searchbutton($button, array $searchbuttons)
{
    global $LN;
    if ($button == '' || in_array($button, array_keys($searchbuttons))) {
        return '';
    } else {
        return make_error_msg($LN['error_invalidbutton']);
    }
}

function set_and_test_pref_buttons($name, $userid, array $valids)
{
    global $LN, $db;
    assert(is_numeric($userid));
    $prefArray = load_prefs($db, $userid);
    $max_buttons = get_config($db, 'maxbuttons', 0);

    if (isset($_POST[$name])) {
        $val = $_POST[$name];
        $i = 1;
        foreach ($val as $v) { // first check all for validity
            $rv = verify_searchbutton($v, $valids);
            $i++;
            if ($rv != '') {
                return $rv;
            }
        }
        if ($i > $max_buttons + 1) {
            return make_error_msg($LN['error_toomanybuttons']);
        }
        foreach ($prefArray as $k=> $p) { // unset them all first
            if (preg_match('/^button[0-9]+$/', $k)) {
                set_pref($db, $k, 'none', $userid);
            }
        }
        $i = 1;
        foreach ($val as $v) {  // set all new ones
            set_pref($db, "button$i", $v, $userid);
            $i++;
        }
        for (; $i<= $max_buttons; $i++) {
            set_pref($db, "button$i", 'none', $userid);
        }

        return '';

    } else {
        for ($i = 1; $i<= $max_buttons; $i++) {
            set_pref($db, "button$i", 'none', $userid);
        }

        return '';
    }
}

function verify_script(DatabaseConnection $db, $path, $name)
{
    global $LN;
    $fn = $path . $name;
    if (preg_match('/^[A-Za-z0-9_\-.+]*$/', $name) != 1) {
        return make_error_msg($LN['error_invalidfilename'] . ': ' . htmlentities($name), $LN['error_error'] . ':');
    }
    if (!file_exists($fn) || !is_executable($fn)) {
        return make_error_msg($LN['error_filenotexec'] . ': ' . htmlentities($name), $LN['error_error'] . ':');
    } else {
        return '';
    }
}

function merge_error_msg($error_msg, $new_msg)
{
    if (isset($error_msg['msg'])) {
        if (isset($new_msg['msg'])) {
            $error_msg['msg'] .= '<br/>' . $new_msg['msg'];
        } else {
            ;
        }
    } else {
        if (isset($new_msg['msg'])) {
            $error_msg = $new_msg;
        } else {
            $error_msg = '';
        }
    }

    return $error_msg;
}

function set_and_test_pref_scripts($path, $name, $userid)
{
    global $db;
    assert(is_numeric($userid));
    if (isset($_POST[$name])) {
        $rv = '';
        $scripts = '';
        $val = $_POST[$name];
        foreach ($val as $v) {
            $rv = merge_error_msg($rv, verify_script($db, $path, $v));
            $scripts .= $v . "\n";
        }
        set_pref($db, $name, $scripts, $userid);

        return $rv;
    } else {
        return '';
    }
}
