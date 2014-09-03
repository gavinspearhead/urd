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
 * $Id: ajax_editgroup.tpl 3095 2014-06-14 22:41:23Z gavinspearhead@gmail.com $
 *}
{* Ajax page, doesn't need a head/foot tpl *}

{extends file="popup.tpl"}
{block name=title}{$LN_ng_edit_group}{/block}
{block name=contents}

<br/>
<input type="hidden" name="id" id="id" value="{$id|escape:htmlall}"/>
<table class="renametransfer hmid">
<tr><td>{$LN_name}:</td><td colspan="3"><input type="text" size="40" name="group_name" id="group_name" value="{$oldname|escape:htmlall}" readonly="readonly"/></td></tr>
<tr><td>{$LN_expire}:</td><td colspan="3"><input type="text" size="5" name="group_expire" id="group_expire" value="{$oldexpire}" required/> {$LN_days|escape:htmlall}</td></tr>
<tr><td>{$LN_pref_minsetsize}:</td><td colspan="3"><input type="text" size="5" name="group_minsetsize" id="group_minsetsize" value="{$oldminsetsize|escape:htmlall}"/></td></tr>
<tr><td>{$LN_pref_maxsetsize}:</td><td colspan="3"><input type="text" size="5" name="group_maxsetsize" id="group_maxsetsize" value="{$oldmaxsetsize|escape:htmlall}"/></td></tr>
<tr><td colspan="1">{$LN_active}</td>
<td> {urd_checkbox value="$oldsubscribed" name="group_subscribed" id="group_subscribed" } </td></tr>
<tr><td>{$LN_ng_adult}:</td><td>{urd_checkbox value="$oldadult" name="group_adult" id="group_adult" }</td></tr>
<td>{$LN_ng_autoupdate}:</td><td>
<select name="group_refresh_period" id="group_refresh_period" size="1" class="update" onchange="javascript:display_timebox('group_refresh_period');">
{html_options values=$periods_keys output=$periods_texts selected=$oldrefresh}
</select>
</td>
<td id="timebox1">{$LN_time}:</td>
<td id="timebox2"><input type="text" id="group_time1" name="time1" value="{if isset($oldtime1) }{$oldtime1}{/if}" class="time"/>:<input type="text" id="group_time2" class="time" name="time2" value="{if isset($oldtime2) }{$oldtime2|string_format:"%02d"}{/if}"/></td></tr>

<tr><td colspan="4" class="right">&nbsp;</td></tr>
<tr><td colspan="4" class="right">
	<input type="button" onclick="update_group();" {urd_popup type="small" text=$LN_apply } name="submit_button" value="{$LN_apply}" class="submit"/> 
</td></tr>
</table>

{/block}
