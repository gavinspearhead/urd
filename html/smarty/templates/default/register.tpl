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
 * $LastChangedDate: 2013-07-26 00:54:03 +0200 (vr, 26 jul 2013) $
 * $Rev: 2882 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: register.tpl 2882 2013-07-25 22:54:03Z gavinspearhead@gmail.com $
 *}
{include file="barehead.tpl" title=$title}

<div id="logindiv" class="light">
<div class="urdlogo2 floatleft noborder buttonlike down3" onclick="javascript:jump('http://www.urdland.com');"></div>
<!-- javascript enabled check -->
<noscript><div id="nojs">{$LN_login_jserror}</div></noscript>

<table class="logintable">
<script>
$(document).ready(function() {
        {if $subpage == 'activated' }
        $('#pending').hide();
        $('#sent').hide();
        $('#form').hide();
        {else if $subpage == 'pending' }
        $('#sent').hide();
        $('#form').hide();
        $('#activated').hide();
        {else} 
        $('#sent').hide();
        $('#activated').hide();
        $('#pending').hide();
        handle_passwords_register('pass1', 'pass2', 'username');
        {/if}
 });
</script>

<tbody id="form">
<tr><td colspan="2"><h3 class="title">{$LN_reg_form}</h3></td></tr>
<tr><td>{$LN_username}</td><td><input name="username" type="text" size="40" id="username"/></td></tr>
<tr><td>{$LN_fullname}</td><td><input name="fullname" type="text" size="40" id="fullname"/></td></tr>
<tr><td>{$LN_email}</td><td><input name="email" type="text" size="40" id="email"/></td></tr>
<tr><td valign="top">{$LN_password}</td><td><input name="password1" type="password" size="40" id="pass1"/>
 &nbsp;&nbsp; 
    <div class="floatright iconsizeplus sadicon buttonlike" onclick="javascript:toggle_show_password('pass1');toggle_show_password('pass2');"></div> 
    <span id="pwweak"><br>{$LN_password_weak}</span>
    <span id="pwmedium"><br>{$LN_password_medium}</span>
    <span id="pwstrong"><br>{$LN_password_strong}</span>
</td></tr>
<tr><td valign="top">{$LN_password} {$LN_reg_again}</td><td><input name="password2" type="password" size="40" id="pass2"/>
    <span id="pwcorrect"><br>{$LN_password_correct}</span>
    <span id="pwincorrect"><br>{$LN_password_incorrect}</span>
</td></tr>
{if $captcha eq 1}
<tr><td>{$LN_CAPTCHA1}:<br/>
    ({$LN_CAPTCHA2})
    </td>
    <td>
    <img src="captcha.php" alt="captcha image"/>
    <input type="text" name="register_captcha" size="3" maxlength="3" id="captcha"/></td></tr>
{/if}
<tr><td></td><td><input type='button' value="{$LN_register}" class="submitsmall floatright" onclick="javascript:submit_registration();"/></td></tr>
</tbody>
<tbody id="pending">
<tr><td><h3 class="title">{$LN_reg_status}</h3></td></tr>
<tr><td>{$LN_reg_pending}</td></tr>
<tr><td><a href="login.php">{$LN_login_title}</a></td></tr>
</tbody>
<tbody id="activated">
<tr><td><h3 class="title">{$LN_reg_status}</h3></td></tr>
<tr><td>{$LN_reg_activated} <a href="login.php">{$LN_reg_activated_link}</a>.</td></tr>
</tbody>
<tbody id="sent">
<tr><td><h3 class="title">{$LN_reg_status}</h3></td></tr>
<tr><td>{$LN_reg_codesent}</td></tr>
<tr><td><a href="login.php">{$LN_login_title}</a></td></tr>
</tbody>
</table>
</div>
{include file="barefoot.tpl"}
