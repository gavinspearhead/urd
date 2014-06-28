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
 * $LastChangedDate: 2014-05-30 00:49:17 +0200 (vr, 30 mei 2014) $
 * $Rev: 3077 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: urdd_test.php 3077 2014-05-29 22:49:17Z gavinspearhead@gmail.com $
 */
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

class test_result
{
    public $name;
    public $result; // bool
    public $message;
    public function __construct ($name, $result, $message)
    {
        $this->name = $name;
        $this->result = $result;
        $this->message = $message;
    }
}

class test_result_list
{
    private $results;
    public function __construct()
    {
        $this->results = array();
    }
    public function add(test_result $r)
    {
        $this->results[] = $r;
    }
    public function get_all_as_string()
    {
        $res = '';
        foreach ($this->results as $r) {
            $res .= $r->name . ':  ' . ($r->result ? 'Succeeded':'Failed') . ' -- ' . $r->message . "\n";
        }

        return $res;
    }
    public function get_all_as_xml($xml)
    {
        $res = '';
        $xml->addChild('tests');
        foreach ($this->results as $r) {
            $xml->tests->addChild('test');
            $xml->tests->test->addChild('name', $r->name);
            $xml->tests->test->addChild('result', $r->result ? 'Succeeded':'Failed');
            $xml->tests->test->addChild('message', $r->message);
        }

        return $xml;
    }
}
