{* Smarty *}
{*
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
 * $LastChangedDate: 2014-06-15 00:41:23 +0200 (zo, 15 jun 2014) $
 * $Rev: 3095 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_edit_usenet_servers.tpl 3095 2014-06-14 22:41:23Z gavinspearhead@gmail.com $
 *}
{* Ajax page, doesn't need a head/foot tpl *}

<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered">{if $id eq 'new'}{$LN_usenet_addserver}{else}{$LN_usenet_editserver}{/if}</div>
{if $only_auth}{$auth_class="hidden"}{else}{$auth_class=""}{/if}

<div class="light">
<br/>
<input type="hidden" name="id" id="id" value="{$id|escape:htmlall}"/></td>
<table class="hmid">

<tr class="{$auth_class}">
<td {urd_popup type="small" text=$LN_usenet_name_msg|escape }>{$LN_name}:</td>
<td colspan="3"><input type="text" name="name" id="name" value="{$name|escape}" placeholder="{$LN_name}" required size="{$text_box_size}"/></td>
</tr>
<tr class="{$auth_class}">
<td {urd_popup type="small" text=$LN_usenet_hostname_msg|escape }>{$LN_usenet_hostname}:</td>
<td colspan="3"><input type="text" name="hostname" id="hostname" value="{$hostname|escape}" placeholder="{$LN_usenet_hostname}" required size="{$text_box_size}"/></td>
</tr>
<tr class="{$auth_class}">
<td {urd_popup type="small" text=$LN_usenet_port_msg|escape }>{$LN_usenet_port}:</td>
<td ><input type="text" name="port" value="{$port|escape}" id="port" required size="{$number_box_size}"/></td>
<td {urd_popup type="small" text=$LN_usenet_secport_msg|escape }>{$LN_usenet_secport}:</td>
<td> <input type="text" id="sec_port" name="secure_port" value="{$sec_port|escape}" required size="{$number_box_size}"/> </td>
</tr>
<tr class="{$auth_class}">
<td {urd_popup type="small" text=$LN_usenet_connectiontype_msg|escape }>{$LN_usenet_connectiontype}:</td>
<td> {html_options name="connection" id="connection" options=$connection_types selected=$connection }</td>
</tr>
<tr><td {urd_popup type="small" text=$LN_usenet_needsauthentication_msg|escape }>
{$LN_usenet_needsauthentication}:</td>
<td colspan="3">
{urd_checkbox value="$authentication" name="authentication" id="needauthentication" post_js="show_auth();"}
</tr>
<tr id="authuser" class="{if $authentication neq 1}hidden{/if}">
<td {urd_popup type="small" text=$LN_usenet_username_msg|escape }>{$LN_username}:</td>
<td colspan="3"><input type="text" name="username" value="{$username|escape}" id="username" placeholder="{$LN_username}" size="{$text_box_size}"/></td>
</tr>
<tr id="authpass" class="{if $authentication neq 1}hidden{/if}">
<td {urd_popup type="small" text=$LN_usenet_password_msg|escape }>{$LN_password}:</td>
<td colspan="3"><input type="password" name="password" value="{$password|escape}" id="password" placeholder="{$LN_password}" size="{$text_box_size}"/>&nbsp;&nbsp; 
    <div class="floatright iconsizeplus sadicon buttonlike" onclick="javascript:toggle_show_password('password');"
    </td>
</tr>

<tr class="{$auth_class}">
<td {urd_popup type="small" text=$LN_usenet_nrofthreads_msg|escape }>{$LN_usenet_nrofthreads}:</td>
<td><input type="text" name="threads" value="{$threads|escape}" id="threads" size="{$number_box_size}"/></td>
<td {urd_popup type="small" text=$LN_usenet_priority_msg|escape }>{$LN_usenet_priority}:</td>
<td><input type="text" name="priority" value="{$priority|escape}" id="priority" size="{$number_box_size}"/></td></tr>
<tr class="{$auth_class}">
<td {urd_popup type="small" text=$LN_usenet_compressed_headers_msg|escape }>{$LN_usenet_compressed_headers}:</td>
<td>
{urd_checkbox value="$compressed_headers" name="compressed_headers" id="compressed_headers" }
</td>
{if $show_post}
<td {urd_popup type="small" text=$LN_usenet_posting|escape }>
{$LN_usenet_posting}:</td>
<td>
{urd_checkbox value="$posting" name="posting" id="posting" }
</td>
{/if}
</tr>
<tr><td colspan="4">&nbsp;</td></tr>
<tr><td colspan="4" class="centered">
{if $id eq 'new'}
	<input type="button" name="add" value="{$LN_add}" onclick="javascript:update_usenet_server();" class="submit"/>
{else}
	<input type="button" name="apply" value="{$LN_apply}" class="submit" onclick="javascript:update_usenet_server();"/>
{/if}
</td>
</tr>
</table>

</div>

