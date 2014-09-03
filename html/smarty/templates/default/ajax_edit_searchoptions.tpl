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
 * $Id: ajax_edit_searchoptions.tpl 3095 2014-06-14 22:41:23Z gavinspearhead@gmail.com $
 *}
{extends file="popup.tpl"}
{block name=title}{if $id eq 'new'}{$LN_buttons_addbutton}{else}{$LN_buttons_editbutton}{/if}{/block}
{block name=contents}

<br/>
<br/>
<input type="hidden" name="id" id="id" value="{$id|escape:htmlall}"/>

<table class="hmid">
<tr>
<td>{$LN_name}:</td>
<td><input type="text" name="name" id="name" placeholder="{$LN_name}" required value="{$button->get_name()|escape}" size="{$text_box_size}"/></td>
</tr>
<tr>
<td>{$LN_buttons_url}:</td>
<td><input type="text" name="search_url" id="search_url" placeholder="{$LN_buttons_url}" required  value="{$button->get_url()|escape}" size="{$text_box_size}"/></td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2" class="centered">
{if $id eq 'new'}
	<input type="button" name="add" value="{$LN_add}" class="submit" onclick="javascript:update_buttons();"/>
{else}
	<input type="button" value="{$LN_apply}" name="apply" class="submit" onclick="javascript:update_buttons();"/>
	<input type="hidden" name="id" value="{$button->get_id()|escape:htmlall}"/>
{/if}
</td>
</tr>
</table>
{/block}
