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
 * $LastChangedDate: 2014-02-16 01:03:46 +0100 (zo, 16 feb 2014) $
 * $Rev: 3009 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: periods.php 3009 2014-02-16 00:03:46Z gavinspearhead@gmail.com $
 */

if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

$pathper = realpath(dirname(__FILE__));

class period_c
{
    private $text;
    private $interval;
    private $next;
    private $id;
    private $index;

    public function __construct($LN_id, $i, $n, $index)
    {
        assert (is_numeric($LN_id) && ($i === NULL || is_numeric($i)) && is_numeric($index));
        global $LN;
        $this->id = $LN_id;
        $this->text = $LN['periods'][$LN_id];
        $this->interval = $i; // in hours
        $this->next = $n;
        $this->index = $index;
    }
    public function get_text()
    {
        return $this->text;
    }
    public function get_interval()
    {
        return $this->interval;
    }
    public function get_next()
    {
        return $this->next;
    }
    public function get_id()
    {
        return $this->id;
    }
    public function get_index()
    {
        return $this->index;
    }
}

class periods_c
{
    private $periods;
    public function add(period_c $p)
    {
        $this->periods[$p->get_id()] = $p;
    }
    public function get($id)
    {
        assert(is_numeric($id));
        foreach ($this->periods as $p) {
            if ($p->get_id() == $id) {
                return $p;
            }
        }

        return NULL;
    }
    public function get_texts()
    {
        $text = array();
        foreach ($this->periods as $k=>$p) {
            $text[$k] = $p->text;
        }

        return $text;
    }
    private static function pcomp(period_c $a, period_c $b)
    {
        if ($a->get_index() == $b->get_index()) {
            return 0;
        } else {
            return $a->get_index() < $b->get_index() ? -1 : 1;
        }
    }
    public function psort()
    {
        usort($this->periods, array('periods_c', 'pcomp'));
    }
    public function get_periods()
    {
        $keys = $texts = array();
        foreach ($this->periods as $p) {
            $keys[$p->get_id()] = $p->get_id();
            $texts[$p->get_id()] = $p->get_text();
        }

        return array($keys, $texts);
    }
    public function get_period_keys()
    {
        return array_keys($this->periods);
    }
}

global $periods;

$periods = new periods_c;

$periods->add(new period_c(0, NULL, NULL,0));
$periods->add(new period_c(1, 6, '+6 hours',3));
$periods->add(new period_c(2, 24, 'today',5));
$periods->add(new period_c(3, 24*7, 'next monday',6));
$periods->add(new period_c(4, 24*7,'next tuesday',7));
$periods->add(new period_c(5, 24*7,'next wednesday',8));
$periods->add(new period_c(6, 24*7,'next thursday',9));
$periods->add(new period_c(7, 24*7,'next friday',10));
$periods->add(new period_c(8, 24*7,'next saturday',11));
$periods->add(new period_c(9, 24*7, 'next sunday',12));
$periods->add(new period_c(10, 24*28, '4 weeks',13));
$periods->add(new period_c(11, 1, '+1 hour',1));
$periods->add(new period_c(12, 3, '+3 hour',2));
$periods->add(new period_c(13, 12, '+12 hour',4));

$periods->psort();
