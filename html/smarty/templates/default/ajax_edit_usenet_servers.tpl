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
{extends file="popup.tpl"}
{block name=title}{if $id == 'new'}{$LN_usenet_addserver}{else}{$LN_usenet_editserver}{/if}{/block}
{block name=contents}

{if $only_auth}{$auth_class="hidden"}{else}{$auth_class=""}{/if}

<br/>
<input type="hidden" name="id" id="id" value="{$id|escape:htmlall}"/></td>
<table class="hmid">

<tr class="{$auth_class}">
<td class="nowrap bold" {urd_popup type="small" text=$LN_usenet_name_msg|escape }>{$LN_name}:</td>
<td colspan="3"><input type="text" name="name" id="name" value="{$name|escape}" placeholder="{$LN_name}" required class="textbox28m"/></td>
</tr>
<tr class="{$auth_class}">
<td class="nowrap bold" {urd_popup type="small" text=$LN_usenet_hostname_msg|escape }>{$LN_usenet_hostname}:</td>
<td colspan="3"><input type="text" name="hostname" id="hostname" value="{$hostname|escape}" placeholder="{$LN_usenet_hostname}" required class="textbox28m"/></td>
</tr>
<tr class="{$auth_class}">
<td class="nowrap bold" {urd_popup type="small" text=$LN_usenet_port_msg|escape }>{$LN_usenet_port}:</td>
<td><input type="text" name="port" value="{$port|escape}" id="port" required class="textbox4m"/></td>
<td class="nowrap bold" {urd_popup type="small" text=$LN_usenet_secport_msg|escape }>{$LN_usenet_secport}:</td>
<td><input type="text" id="sec_port" name="secure_port" value="{$sec_port|escape}" required class="textbox4m"/> </td>
</tr>
<tr class="{$auth_class}">
<td class="nowrap bold" {urd_popup type="small" text=$LN_usenet_connectiontype_msg|escape }>{$LN_usenet_connectiontype}:</td>
<td>{html_options name="connection" id="connection" options=$connection_types selected=$connection}</td>
</tr>
<tr><td class="nowrap bold" {urd_popup type="small" text=$LN_usenet_needsauthentication_msg|escape }>
{$LN_usenet_needsauthentication}:</td>
<td colspan="3">
{urd_checkbox value="$authentication" name="authentication" id="needauthentication" post_js="show_auth();"}
</tr>
<tr id="authuser" class="{if $authentication != 1}hidden{/if}">
<td class="nowrap bold" {urd_popup type="small" text=$LN_usenet_username_msg|escape }>{$LN_username}:</td>
{if $only_auth}</tr><tr>{/if}
<td colspan="3"><input type="text" name="username" value="{$username|escape}" id="username" placeholder="{$LN_username}" class="textbox28m"/></td>
</tr>
<tr id="authpass" class="{if $authentication != 1}hidden{/if}">
<td class="nowrap bold" {urd_popup type="small" text=$LN_usenet_password_msg|escape }>{$LN_password}:</td>
{if $only_auth}</tr><tr>{/if}
<td colspan="3"><input type="password" name="password" value="{$password|escape}" id="password" placeholder="{$LN_password}" class="textbox28m"/>&nbsp;&nbsp; 
    <div class="floatright iconsizeplus sadicon buttonlike" id="toggle_password">
</td>
</tr>

<tr class="{$auth_class}">
<td class="nowrap bold" {urd_popup type="small" text=$LN_usenet_nrofthreads_msg|escape }>{$LN_usenet_nrofthreads}:</td>
<td><input type="text" name="threads" value="{$threads|escape}" id="threads" class="textbox4m"/></td>
<td class="nowrap bold" {urd_popup type="small" text=$LN_usenet_priority_msg|escape }>{$LN_usenet_priority}:</td>
<td><input type="text" name="priority" value="{$priority|escape}" id="priority" class="textbox4m"/></td>
</tr>
<tr class="{$auth_class}">
<td class="nowrap bold" {urd_popup type="small" text=$LN_usenet_compressed_headers_msg|escape }>{$LN_usenet_compressed_headers}:</td>
<td>
{urd_checkbox value="$compressed_headers" name="compressed_headers" id="compressed_headers"}
</td>
{if $show_post}
<td class="nowrap bold" {urd_popup type="small" text=$LN_usenet_posting|escape }>
{$LN_usenet_posting}:</td>
<td>
{urd_checkbox value="$posting" name="posting" id="posting" }
</td>
{/if}
</tr>
<tr><td colspan="4">&nbsp;</td></tr>
<tr><td colspan="4" class="centered">
{if $id == 'new'}
	<input type="button" name="add" value="{$LN_add}" id="submit_server" class="submitsmall"/>
{else}
	<input type="button" name="apply" value="{$LN_apply}" id="submit_server" class="submitsmall"/>
{/if}
</td>
</tr>
</table>

{/block}
