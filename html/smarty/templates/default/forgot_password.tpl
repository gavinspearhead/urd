{* Smarty *}{*
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
 * $LastChangedDate: 2014-06-14 01:20:27 +0200 (za, 14 jun 2014) $
 * $Rev: 3094 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: forgot_password.tpl 3094 2014-06-13 23:20:27Z gavinspearhead@gmail.com $
 *}

{extends file="barehead.tpl"}
{block name=contents}
<div id="logindiv" class="light">
<div class="urdlogo2 floatleft noborder buttonlike down3" id="urd_logo"></div>
<div><input type="hidden" name="challenge" value="{$challenge}"/>
<table class="logintable" id="form_table">
<tbody id="error">
<tr><td colspan="2" id="error_msg" class="head2"></td></tr>
</tbody>
<tbody id="form">
<tr><td colspan="2"><h3 class="fronttitle">{$title}</h3></td></tr>
<tr><td class="nowrap bold">{$LN_username}:</td><td><input type="text" name="username" id="username" class="textbox18m" placeholder="Username" required/></td></tr>
<tr><td class="nowrap bold">{$LN_email}:</td><td><input type="email" name="email" id="email" class="textbox18m" placeholder="Email address" required/></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td colspan="2" class="centered"><input type="button" value="{$LN_forgot_mail}" class="submitsmall" id="forgot_button"/></td></tr>
</tbody>
</table>
</div>

<table class="logintable" id="sent_table">
<tr><td><h3 class="title">{$title}</h3></td></tr>
<tr><td>{$LN_forgot_sent}</td></tr>
<tr><td><a href="login.php">{$LN_login_title}</a></td></tr>
</table>

</div>
<script>
$(document).ready(function() {
    $("#sent_table").hide();
    $("#error_msg").hide();
    $('#urd_logo').click( function() { jump('http://www.urdland.com'); });
    $('#forgot_button').click( function() { submit_forgot_password(); });
});
</script>
{/block}
