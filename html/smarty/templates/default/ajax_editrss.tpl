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
 * $Id: ajax_editrss.tpl 3095 2014-06-14 22:41:23Z gavinspearhead@gmail.com $
 *}
{* Ajax page, doesn't need a head/foot tpl *}

{extends file="popup.tpl"}
{block name=title}{if $id eq 'new'}{$LN_feeds_addfeed}{else}{$LN_feeds_editfeed}{/if}{/block}
{block name=contents}
{$authentication=($oldusername != '' || $oldpassword != '')}

<div class="light">
<br/>
<input type="hidden" name="id" id="id" value="{$id|escape:htmlall}" />
<table class="renametransfer hmid">
<tr><td>{$LN_name}:</td><td colspan="3"><input type="text" size="40" name="rss_name" id="rss_name" required placeholder="{$LN_name}" value="{$oldname|escape:htmlall}"/></td></tr>
<tr><td>{$LN_feeds_url}:</td><td colspan="3"><input type="text" size="40" name="rss_url" id="rss_url" required placeholder="{$LN_feeds_url}" value="{$oldurl|escape:htmlall}"/></td></tr>
<tr><td>{$LN_expire}:</td><td colspan="3"><input type="text" size="5" name="rss_expire" id="rss_expire" required placeholder="{$LN_expire}"value="{$oldexpire|escape:htmlall}"/> {$LN_days}</td></tr>
<tr><td colspan="1">{$LN_active}</td>
<td> {urd_checkbox value="$oldsubscribed" name="rss_subscribed" id="rss_subscribed" } </td></tr>
<tr><td>{$LN_ng_adult}:</td><td>{urd_checkbox value="$oldadult" name="rss_adult" id="rss_adult" }</td></tr>
<td>{$LN_ng_autoupdate}:</td><td>
<select name="rss_refresh_period" id="rss_refresh_period" size="1" class="update" onchange="javascript:display_timebox('rss_refresh_period');">
{html_options values=$periods_keys output=$periods_texts selected=$oldrefresh}
</select>
</td>
<td id="timebox1">{$LN_time}:</td>
<td id="timebox2"><input type="text" id="rss_time1" name="time1" value="{if isset($oldtime1) }{$oldtime1|escape:htmlall}{/if}" class="time"/>:<input type="text" id="rss_time2" class="time" name="time2" value="{if isset($oldtime2) }{$oldtime2|string_format:"%02d"}{/if}"/></td></tr>

<tr><td>{$LN_usenet_needsauthentication}</td><td>
{urd_checkbox value="$authentication" name="authentication" id="needauthentication" post_js="show_auth();"}
</td></tr>
<tr id="authuser" {if !$authentication}class="hidden"{/if}><td>{$LN_username}:</td><td colspan="3"><input type="text" size="40" name="rss_username" id="rss_username" value="{$oldusername|escape:htmlall}"/></td></tr>
<tr id="authpass" {if !$authentication}class="hidden"{/if}><td>{$LN_password}:</td><td colspan="3"><input type="password" size="40" name="rss_password" id="rss_password" value="{$oldpassword|escape:htmlall}"/>&nbsp;&nbsp; 
    <div class="floatright iconsizeplus sadicon buttonlike" onclick="javascript:toggle_show_password('rss_password');"
</td></tr>

<tr><td colspan="4" class="right">&nbsp;</td></tr>
<tr><td colspan="4" class="right">
	<input type="button" onclick="update_rss();" {urd_popup type="small" text=$LN_apply } name="submit_button" value="{$LN_apply}" class="submit"/> 
</td></tr>
</table>
{/block}
