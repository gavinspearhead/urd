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
 * $LastChangedDate: 2013-07-17 12:45:45 +0200 (wo, 17 jul 2013) $
 * $Rev: 2876 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: install.5.inc.php 2876 2013-07-17 10:45:45Z gavinspearhead@gmail.com $
 */

// This is an include-only file:
if (!defined('ORIGINAL_PAGE')) {
    die('This file cannot be accessed directly.');
}

// Create user account and user preferences
$OUT .= '<tr><td colspan="2" class="install1">Creating user account</td></tr>' . "\n";

// Read session variables, if set:
$urdusername = isset($_SESSION['urdusername']) ? $_SESSION['urdusername'] : '';
$urdusermail = isset($_SESSION['urdusermail']) ? $_SESSION['urdusermail'] : '';
$urduserfull = isset($_SESSION['urduserfull']) ? $_SESSION['urduserfull'] : '';
$urduserpass1 = isset($_SESSION['urduserpass1']) ? $_SESSION['urduserpass1'] : '';
$urduserpass2 = isset($_SESSION['urduserpass2']) ? $_SESSION['urduserpass2'] : '';
$urdupdatecheck = isset($_SESSION['urdupdatecheck']) ? $_SESSION['urdupdatecheck'] : TRUE;
$urddownloaddir = isset($_SESSION['urddownloaddir']) ? $_SESSION['urddownloaddir'] : '';

$urdusername = htmlspecialchars($urdusername);
$urdusermail = htmlspecialchars($urdusermail);
$urduserfull = htmlspecialchars($urduserfull);
$urduserpass1 = htmlspecialchars($urduserpass1);
$urduserpass2 = htmlspecialchars($urduserpass2);
$urddownloaddir = htmlspecialchars($urddownloaddir);

$urdupdatechecked = $urdupdatecheck ? 'CHECKED' : '';

$OUT .= <<<USERAC


<tr><td class="install2">URD admin username:</td><td class="install3">
<input type="text" name="urduser" value="$urdusername"></td></tr>
<tr><td class="install2">URD user email address:</td><td class="install3">
<input type="text" name="urdemail" value="$urdusermail"></td></tr>
<tr><td class="install2">URD full name:</td><td class="install3">
<input type="text" name="urdfullname" value="$urduserfull"></td></tr>
<tr><td class="install2">URD password (1):<br/>
<span id="urdd_pass_weak"></span>
</td><td class="install3">
<input type="password" name="urdpass1" id="urdpass1" value="$urduserpass1"></td></tr>
<tr><td class="install2">URD password (2):<br/>
<span id="password_incorrect"></span>
</td><td class="install3">
<input type="password" name="urdpass2" id="urdpass2" value="$urduserpass2"><i><span onclick="javascript:toggle_show_password('urdpass1');toggle_show_password('urdpass2');">$showpasspic</span></i></td>
</tr>
<tr><td colspan="2"><br/></td></tr>
<tr><td colspan="2" class="install1">Other settings</td></tr>
<tr><td class="install2">URD download directory:</td><td class="install3">
<input type="text" name="urddownloaddir" value="$urddownloaddir"></td></tr>
<tr><td class="install2">Do you want URD to periodically check for new versions (recommended) ?:</td><td class="install3">
<input type="checkbox" name="urdupdatecheck" $urdupdatechecked" checked="checked"></td></tr>
USERAC;
$OUT .= '<tr colspan="2"><td><a onclick="LoadPage(6);">'.$continuepic.'</a></td></tr>';
$OUT .= <<<PWSCR
<script>
 check_password_strength('urdpass1', 'urdpass2');
</script>
PWSCR;

