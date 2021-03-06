{* Smarty *}
{*
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
 * $LastChangedDate: 2014-06-15 00:41:23 +0200 (zo, 15 jun 2014) $
 * $Rev: 3095 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_edit_users.tpl 3095 2014-06-14 22:41:23Z gavinspearhead@gmail.com $
 *}
{extends file="popup.tpl"}
{block name=title}{if $id == 'new'}{$LN_users_addnew}{else}{$LN_users_edit}{/if}{/block}
{block name=contents}
<br/>
<div><input type="hidden" name="id" id="id" value="{$id|escape}"/></td></div>
<table class="hmid">
<tr><td class="nowrap bold" colspan="2">{$LN_username}</td><td colspan="2"><input type="text" name="username" id="username" placeholder="{$LN_username}" required value="{$name|escape}" class="textbox28m"/></td></tr>
<tr><td class="nowrap bold" colspan="2">{$LN_fullname}</td><td colspan="2"><input type="text" name="fullname" id="fullname" placeholder="{$LN_fullname}" required value="{$fullname|escape}" class="textbox28m"/></td></tr>
<tr><td class="nowrap bold" colspan="2">{$LN_email}</td><td colspan="2"><input type="email" name="email" id="email" placeholder="{$LN_email}" required value="{$email|escape}" class="textbox28m"/></td></tr>
{if $id == 'new' || $emailallowed == 0}
<tr><td class="nowrap bold" colspan="2">{$LN_password}</td>
<td colspan="2"> 
    <input type="text" name="password" id="password" class="textbox28m" placeholder="{$LN_password}" required/>
</td> </tr>

{/if}
<tr><td class="nowrap bold">{$LN_users_isadmin}</td><td>
{if $isadmin == $USER_ADMIN}{$_isadmin=1}{else}{$_isadmin=0}{/if}
{urd_checkbox value="$_isadmin" name="isadmin" id="isadmin" }
</td>

<td class="nowrap bold">{$LN_users_rights}</td><td>
{urd_checkbox value="$rights" name="seteditor" id="seteditor"}
</td>
</tr>

<tr><td class="nowrap bold">{$LN_users_post}</td><td>
{urd_checkbox value="$post" name="post" id="post"}
</td>

<td class="nowrap bold">{$LN_active}</td><td>
{if $isactive == $USER_ACTIVE}{$isactive=1}{else}{$isactive=0}{/if}
{urd_checkbox value="$isactive" name="isactive" id="isactive"}
</td>

</tr>
<tr>
<td class="nowrap bold">{$LN_users_autodownload}</td><td>
{urd_checkbox value="$autodownload" name="autodownload" id="autodownload"}
</td>

<td class="nowrap bold">{$LN_users_fileedit}</td><td>
{urd_checkbox value="$file_edit" name="fileedit" id="fileedit"}
</td>
</tr>

<tr>
<td class="nowrap bold">{$LN_users_allow_erotica}</td><td>
{urd_checkbox value="$allow_erotica" name="allow_erotica" id="allow_erotica"}
</td>

<td class="nowrap bold">{$LN_users_allow_update}</td><td>
{urd_checkbox value="$allow_update" name="allow_update" id="allow_update"}
</td>
</tr>
<tr><td colspan="4">&nbsp;</td></tr>
<tr><td colspan="4" class="centered">
{if $id == 'new'}
	<input type="button" name="add" id="apply_button" value="{$LN_add}" class="submitsmall"/>
{else}
	<input type="button" name="apply"id="apply_button" value="{$LN_apply}" class="submitsmall"/>
{/if}
</td>
</tr>

</table>
{/block}
