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
 * $Id: register.tpl 3094 2014-06-13 23:20:27Z gavinspearhead@gmail.com $
 *}

{extends file="barehead.tpl"}
{block name=contents}
<div id="logindiv" class="light">
<div class="urdlogo2 floatleft noborder buttonlike down3" id="urd_logo"></div>
<!-- javascript enabled check -->
<noscript><div id="nojs">{$LN_login_jserror}</div></noscript>

<table class="logintable">

<tbody id="form">
<tr><td colspan="2"><h3 class="title">{$LN_reg_form}</h3></td></tr>
<tr><td>{$LN_username}</td><td><input name="username" type="text" size="40" id="username" placeholder="Username" required/></td></tr>
<tr><td>{$LN_fullname}</td><td><input name="fullname" type="text" size="40" id="fullname" placeholder="Full name" required/></td></tr>
<tr><td>{$LN_email}</td><td><input name="email" type="email" size="40" id="email" placeholder="Email address" required/></td></tr>
<tr><td valign="top">{$LN_password}</td><td><input name="password1" type="password" size="40" id="pass1" placeholder="Password" required/>
 &nbsp;&nbsp; 
    <div class="floatright iconsizeplus sadicon buttonlike" id="pw_button"></div> 
    <span id="pwweak"><br>{$LN_password_weak}</span>
    <span id="pwmedium"><br>{$LN_password_medium}</span>
    <span id="pwstrong"><br>{$LN_password_strong}</span>
</td></tr>
<tr><td valign="top">{$LN_password} {$LN_reg_again}</td><td><input name="password2" type="password" size="40" id="pass2" placeholder="Password" required/>
    <span id="pwcorrect"><br>{$LN_password_correct}</span>
    <span id="pwincorrect"><br>{$LN_password_incorrect}</span>
</td></tr>
{if $captcha == 1}
<tr><td>{$LN_CAPTCHA1}:<br/>
    ({$LN_CAPTCHA2})
    </td>
    <td>
    <img src="captcha.php" alt="captcha image"/>
    <input type="text" name="register_captcha" size="3" maxlength="3" id="captcha" required/></td></tr>
{/if}
<tr><td></td><td><input type='button' value="{$LN_register}" id="register_button" class="submitsmall floatright"/></td></tr>
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
    $('#register_button').click( function() { submit_registration(); });
    $('#pw_button').click( function() { toggle_show_password('pass1');toggle_show_password('pass2');});
    $('#urd_logo').click( function() { jump('http://www.urdland.com'); });
});
</script>

{/block}
