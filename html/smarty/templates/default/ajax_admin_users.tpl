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
 * $LastChangedDate: 2014-06-12 23:24:27 +0200 (do, 12 jun 2014) $
 * $Rev: 3089 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_admin_users.tpl 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 *}

<div>
<input type="hidden" name="order" id="order" value="{$sort|escape:htmlall}"/>
<input type="hidden" name="order_dir" id="order_dir" value="{$sort_dir|escape:htmlall}"/>
</div>

{$up="<img src='$IMGDIR/small_up.png' alt=''>"}
{$down="<img src='$IMGDIR/small_down.png' alt=''>"}
{if $sort == ""}{if $sort_dir=='desc'}{$_sort=$up}{else}{$_sort=$down}{/if}{else}{$_sort=""}{/if}
{if $sort == "id"}{if $sort_dir=='desc'}{$id_sort=$up}{else}{$id_sort=$down}{/if}{else}{$id_sort=""}{/if}
{if $sort == "fullname"}{if $sort_dir=='desc'}{$fullname_sort=$up}{else}{$fullname_sort=$down}{/if}{else}{$fullname_sort=""}{/if}
{if $sort == "email"}{if $sort_dir=='desc'}{$email_sort=$up}{else}{$email_sort=$down}{/if}{else}{$email_sort=""}{/if}
{if $sort == "last_active"}{if $sort_dir=='desc'}{$last_active_sort=$up}{else}{$last_active_sort=$down}{/if}{else}{$last_active_sort=""}{/if}
{if $sort == "isadmin"}{if $sort_dir=='desc'}{$isadmin_sort=$up}{else}{$isadmin_sort=$down}{/if}{else}{$isadmin_sort=""}{/if}
{if $sort == "rights"}{if $sort_dir=='desc'}{$rights_sort=$up}{else}{$rights_sort=$down}{/if}{else}{$rights_sort=""}{/if}
{if $sort == "active"}{if $sort_dir=='desc'}{$active_sort=$up}{else}{$active_sort=$down}{/if}{else}{$active_sort=""}{/if}
{if $sort == "name"}{if $sort_dir=='desc'}{$name_sort=$up}{else}{$name_sort=$down}{/if}{else}{$name_sort=""}{/if}

<table class="articles">
<tr>
<th onclick="javascript:submit_search_users('id', 'asc');" class="buttonlike head round_left"># {$id_sort}</th>
<th onclick="javascript:submit_search_users('name', 'asc');" class="buttonlike head">{$LN_username} {$name_sort}</th>
<th onclick="javascript:submit_search_users('fullname', 'asc');" class="buttonlike head">{$LN_fullname} {$fullname_sort}</th>
<th onclick="javascript:submit_search_users('email', 'asc');" class="buttonlike head">{$LN_email} {$email_sort}</th>
<th onclick="javascript:submit_search_users('last_active', 'asc');" class="buttonlike head">{$LN_users_last_active} {$last_active_sort}</th>
<th onclick="javascript:submit_search_users('isadmin', 'asc');" class="buttonlike center head">{$LN_users_isadmin} {$isadmin_sort}</th>
<th onclick="javascript:submit_search_users('rights', 'asc');" class="buttonlike center head">{$LN_users_rights} {$rights_sort}</th>
<th class="center head">{$LN_users_post}</th>
<th onclick="javascript:submit_search_users('active', 'asc');" class="buttonlike center head">{$LN_active} {$active_sort}</th>
<th class="head round_right right">{$LN_actions}</th>
</tr>
{foreach $users as $user}
<tr class="even content" onmouseover="javascript:$(this).toggleClass('highlight2');" onmouseout="javascript:$(this).toggleClass('highlight2');">
	<td>{$user->id|escape:htmlall|truncate:$maxstrlen}</td>
	<td>{$user->username|escape:htmlall|truncate:$maxstrlen}</td>
	<td>{$user->fullname|escape:htmlall|truncate:$maxstrlen}</td>
	<td>{$user->email|escape:htmlall|truncate:$maxstrlen}</td>
	<td>{$user->last_active|capitalize|escape}</td>
	<td class="center" {urd_popup type="small" text=$LN_users_isadmin}>
    {urd_checkbox value="{if $user->admin eq $USER_ADMIN}1{else}0{/if}" name="user_is_admin" id="user_{$user->id}_is_admin" post_js="user_update_setting({$user->id}, 'admin', {if $user->admin eq $USER_ADMIN}0{ELSE}1{/if});"}
</td>
	<td class="center" {urd_popup type="small" text=$LN_users_rights_help}>
{urd_checkbox value="{if isset($user->rights.c) && $user->rights.c == 1}1{else}0{/if}" name="user_is_setedit" id="user_{$user->id}_is_setedit" post_js="user_update_setting({$user->id}, 'set_editor', {if isset($user->rights.c) && $user->rights.c == 1}0{ELSE}1{/if});"}
	
	</td>
	<td class="center" {urd_popup type="small" text=$LN_users_post_help} >
    {urd_checkbox value="{if isset($user->rights.p) && $user->rights.p == 1}1{else}0{/if}" name="user_post" id="user_{$user->id}_post" post_js="user_update_setting({$user->id}, 'posting', {if isset($user->rights.p) && $user->rights.p == 1}0{ELSE}1{/if});"}
    
	</td>
	<td class="center" {urd_popup type="small" text=$LN_active} >
    {urd_checkbox value="{if $user->active eq $USER_ACTIVE}1{else}0{/if}" name="user_is_active" id="user_{$user->id}_is_active" post_js="user_update_setting({$user->id}, 'active', {if $user->active eq $USER_ACTIVE}0{ELSE}1{/if});"}
    </td>
	<td><div class="floatright">

    <div class="inline iconsizeplus editicon buttonlike" {urd_popup type="small" text=$LN_users_edit } onclick="javascript:user_action('edit',{$user->id});"></div>
	{if $emailallowed neq 0} 
    <div class="inline iconsizeplus mailicon buttonlike" {urd_popup type="small" text=$LN_users_resetpw } onclick="javascript:user_action('resetpw',{$user->id});"></div>
	{/if}
    <div class="inline iconsizeplus deleteicon buttonlike" {urd_popup type="small" text=$LN_users_delete } onclick="javascript:user_action_confirm('delete',{$user->id}, '{$LN_delete} {$user->username|escape}?');"></div>
    </div>
	</td>
</tr>
{foreachelse}
{if $only_rows == 0}
<tr><td colspan="10" class="centered highlight even bold">{$LN_error_nousersfound}</td></tr>
{/if}
{/foreach}
{if count($users) > 12}
<tr><td colspan="10" class="feet round_both_bottom">&nbsp;</td>
{/if}
</table>
