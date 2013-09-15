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
 * $LastChangedDate: 2013-09-11 00:48:12 +0200 (wo, 11 sep 2013) $
 * $Rev: 2925 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_edit_searchoptions.tpl 2925 2013-09-10 22:48:12Z gavinspearhead@gmail.com $
 *}

<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered">{if $id eq 'new'}{$LN_buttons_addbutton}{else}{$LN_buttons_editbutton}{/if} </div>
<div class="light">
<br/>
<br/>
<input type="hidden" name="id" id="id" value="{$id}"/>

<table class="hmid">
<tr>
<td>{$LN_name}:</td>
<td><input type="text" name="name" id="name" value="{$button->get_name()|escape}" size="{$text_box_size}"/></td>
</tr>
<tr>
<td>{$LN_buttons_url}:</td>
<td><input type="text" name="search_url" id="search_url" value="{$button->get_url()|escape}" size="{$text_box_size}"/></td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2" class="centered">
{if $id eq 'new'}
	<input type="button" name="add" value="{$LN_add}" class="submit" onclick="javascript:update_buttons();"/>
{else}
	<input type="button" value="{$LN_apply}" name="apply" class="submit" onclick="javascript:update_buttons();"/>
	<input type="hidden" name="id" value="{$button->get_id()}"/>
{/if}
</td>
</tr>
</table>
</div>
