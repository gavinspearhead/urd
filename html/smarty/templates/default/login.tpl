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
 * $Id: login.tpl 3094 2014-06-13 23:20:27Z gavinspearhead@gmail.com $ *}
{include file="barehead.tpl" title=$title}
<div id="logindiv" class="light">
<div class="urdlogo2 floatleft noborder buttonlike down3" onclick="javascript:jump('http://www.urdland.com');"></div>
<!-- javascript enabled check -->
<noscript><div id="nojs">{$LN_login_jserror}</div></noscript>
<form method="post" id="urd_login_form">
<div>
<input type="hidden" id="language_change" value=""/>
<input type="hidden" name="token" id="token" value="{$token|escape}"/>
<input type="hidden" name="curr_language" id="curr_language" value="{$curr_language|escape}"/>
</div>
<table class="logintable">
<tr><td colspan="2"><h3 class="title">{$LN_login_title2} <a href="http://www.urdland.com">URD</a></h3></td></tr>
{if $message != ''}
<tr><td colspan="2"><span class="warning_highlight">{$message}</span></td></tr>
{/if}
<tr><td>{$LN_username}</td><td><input type="text" name="username" value="{$username|escape}" autofocus="autofocus" placeholder="{$LN_username}" required/></td></tr>
<tr><td >{$LN_password}</td><td><input type="password" id="pass" name="pass" placeholder="{$LN_password}" required/>&nbsp;&nbsp; 
    <div class="floatright iconsizeplus sadicon buttonlike" onclick="javascript:toggle_show_password('pass');"></div></td></tr>
<tr><td colspan="2"></td></tr>
<tr><td>{$LN_login_remember}</td><td>
<select name="period">
<option value="0" {if $period == 0} selected="selected"{/if}>{$LN_login_closebrowser}</option>
<option value="1" {if $period == 1} selected="selected"{/if}>{$LN_login_oneweek}</option>
<option value="2" {if $period == 2} selected="selected"{/if}>{$LN_login_onemonth}</option>
<option value="3" {if $period == 3} selected="selected"{/if}>{$LN_login_oneyear}</option>
<option value="4" {if $period == 4} selected="selected"{/if}>{$LN_login_forever}</option>
</select>
</td></tr>
<tr><td>{$LN_login_bindip}</td><td>
{urd_checkbox value="$bind_ip_address" name="ipaddr" id="ipaddr" data="{$ip_address}"} 

</td></tr>
<tr><td colspan="2"></td></tr>
<tr><td colspan="1"><input type="submit" value="{$LN_login_login}" class="submit"/></td>
<td>
<select name="language_name" id="language_select" onchange="javascript:submit_language_login();" >
{html_options options=$languages selected=$curr_language}
</select>
</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td><a href="forgot_password.php">{$LN_login_forgot_password}</a></td>{if $register eq 1}
<td><a href="register.php">{$LN_login_register}</a></td></tr>
{/if}
</table>
</form>
</div>

{include file="barefoot.tpl"}
