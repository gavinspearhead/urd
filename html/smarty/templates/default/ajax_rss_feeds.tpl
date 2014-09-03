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
 * $LastChangedDate: 2010-12-26 19:09:56 +0100 (zo, 26 dec 2010) $
 * $Rev: 1965 $
 * $Author: gavinspearhead $
 * $Id: rssfeeds.tpl 1965 2010-12-26 18:09:56Z gavinspearhead $
 *}
{if $page_tab=="admin" && $isadmin}
{$admin_hidden=""}
{$user_hidden="hidden"}
{else}
{$admin_hidden="hidden"}
{$user_hidden=""}
{/if}

{capture assign=selector}
<div class="pref_selector">
<ul class="tabs">
<li onclick="javascript:toggle_table('feedstable', 'user', 'admin')" id="button_global" class="tab{if $page_tab == 'admin'} tab_selected{/if}">{$LN_global_settings}</li>
<li onclick="javascript:toggle_table('feedstable', 'admin', 'user')" id="button_user" class="tab{if $page_tab != 'admin'} tab_selected{/if}">{$LN_user_settings}
<input type="hidden" id="page_tab" value="{$page_tab|escape}"/>
</li>
</ul>
</div>
{/capture}

{* Capture the top skipper: *}
{capture assign=topskipper}{strip}
<div class="ng_selector">
{if count($pages) > 1}
{urd_skipper current=$currentpage last=$lastpage pages=$pages position=top js=rss_feeds_page}
{/if}
</div>
{/strip}
{/capture}

{* Capture the bottom skipper: *}
{capture assign=bottomskipper}{strip}
<div class="ng_selector">
{if count($pages) > 1}
{urd_skipper current=$currentpage last=$lastpage pages=$pages position=bottom js=rss_feeds_page}
{/if}
</div>
{/strip}
{/capture}


<div id="bar">
<div id="aform">
<div id="ng_headerbox">
{$topskipper}
{$selector}
</div>

{$up="<img src='$IMGDIR/small_up.png' width='9' height='6' alt=''>"}
{$down="<img src='$IMGDIR/small_down.png' width='9' height='6' alt=''>"}
{if $sort == "name"}{if $sort_dir=='desc'}{$name_sort=$up}{else}{$name_sort=$down}{/if}{else}{$name_sort=""}{/if}
{if $sort == "active"}{if $sort_dir=='desc'}{$active_sort=$up}{else}{$active_sort=$down}{/if}{else}{$active_sort=""}{/if}
{if $sort == "category"}{if $sort_dir=='desc'}{$category_sort=$up}{else}{$category_sort=$down}{/if}{else}{$category_sort=""}{/if}
{if $sort == "adult"}{if $sort_dir=='desc'}{$adult_sort=$up}{else}{$adult_sort=$down}{/if}{else}{$adult_sort=""}{/if}
{if $sort == "last_updated"}{if $sort_dir=='desc'}{$last_updated_sort=$up}{else}{$last_updated_sort=$down}{/if}{else}{$last_updated_sort=""}{/if}
{if $sort == "expire"}{if $sort_dir=='desc'}{$expire_sort=$up}{else}{$expire_sort=$down}{/if}{else}{$expire_sort=""}{/if}
{if $sort == "visible"}{if $sort_dir=='desc'}{$visible_sort=$up}{else}{$visible_sort=$down}{/if}{else}{$visible_sort=""}{/if}
{if $sort == "minsetsize"}{if $sort_dir=='desc'}{$minsetsize_sort=$up}{else}{$minsetsize_sort=$down}{/if}{else}{$minsetsize_sort=""}{/if}
{if $sort == "refresh_period"}{if $sort_dir=='desc'}{$refresh_period_sort=$up}{else}{$refresh_period_sort=$down}{/if}{else}{$refresh_period_sort=""}{/if}
{if $sort == "refresh_time"}{if $sort_dir=='desc'}{$refresh_time_sort=$up}{else}{$refresh_time_sort=$down}{/if}{else}{$refresh_time_sort=""}{/if}
{if $sort == "url"}{if $sort_dir=='desc'}{$url_sort=$up}{else}{$url_sort=$down}{/if}{else}{$url_sort=""}{/if}
{if $sort == "auth"}{if $sort_dir=='desc'}{$auth_sort=$up}{else}{$auth_sort=$down}{/if}{else}{$auth_sort=""}{/if}
{if $sort == "feedcount"}{if $sort_dir=='desc'}{$feedcount_sort=$up}{else}{$feedcount_sort=$down}{/if}{else}{$feedcount_sort=""}{/if}
{if $sort == ""}{if $sort_dir=='desc'}{$_sort=$up}{else}{$_sort=$down}{/if}{else}{$_sort=""}{/if}

<form method="post" id="rssfeedsform">
<div class="hidden">
<input type="hidden" name="order" id="order" value="{$sort|escape}"/>
<input type="hidden" name="page" id="page1" value="{$page_tab|escape}"/>
<input type="hidden" name="order_dir" id="order_dir"  value="{$sort_dir|escape}"/>
</div>

<div>
<table class="newsgroups" id="feedstable">
<tr>
<th class="general head round_left">&nbsp;</th>
<th {urd_popup type="small" text=$LN_feeds_tooltip_active } class="general buttonlike head" onclick="javascript:submit_rss_search('subscribed', 'desc');">&nbsp;</th>
<th {urd_popup type="small" text=$LN_feeds_tooltip_name } id="browsesubjecttd" class="fixwidth20p center general buttonlike head" onclick="javascript:submit_rss_search('name', 'asc');" >{$LN_name} {$name_sort}</th>
<th {urd_popup type="small" text=$LN_ng_tooltip_category} class="{$user_hidden} center user buttonlike head" onclick="javascript:submit_rss_search('category', 'asc');" >{$LN_category|capitalize} {$category_sort}</th>
<th {urd_popup type="small" text=$LN_feeds_tooltip_url } class="{$admin_hidden} center admin buttonlike head" onclick="javascript:submit_rss_search('url', 'asc');" >{$LN_feeds_url} {$url_sort}</th>
<th {urd_popup type="small" text=$LN_feeds_tooltip_auth } class="{$admin_hidden} center admin buttonlike head" onclick="javascript:submit_rss_search('auth', 'asc');" >{$LN_feeds_auth} {$auth_sort}</th>
<th {urd_popup type="small" text=$LN_feeds_tooltip_posts } class="general center buttonlike head" onclick="javascript:submit_rss_search('feedcount', 'desc');">{$LN_size} {$feedcount_sort}</th>
<th {urd_popup type="small" text=$LN_ng_tooltip_adult } class="{$admin_hidden} admin buttonlike center head" onclick="javascript:submit_rss_search('adult', 'desc');">{$LN_ng_adult} {$adult_sort}</th>
<th {urd_popup type="small" text=$LN_feeds_tooltip_lastupdated } class="general center buttonlike head" onclick="javascript:submit_rss_search('last_updated', 'desc');">{$LN_feeds_lastupdated} {$last_updated_sort}</th>
<th {urd_popup type="small" text=$LN_feeds_tooltip_expire } class="{$admin_hidden} admin center buttonlike {if $isadmin == 0 or $urdd_online == 0 }round_right{/if}
 head" onclick="javascript:submit_rss_search('expire', 'desc');">{$LN_feeds_expire_time} {$expire_sort}</th>
<th {urd_popup type="small" text=$LN_feeds_tooltip_visible } class="{$user_hidden} user center buttonlike head" onclick="javascript:submit_rss_search('visible', 'desc');">{$LN_feeds_visible} {$visible_sort}</th>
<th {urd_popup type="small" text=$LN_ng_tooltip_minsetsize } class="{$user_hidden} user center buttonlike head round_right" onclick="javascript:submit_rss_search('minsetsize', 'desc');">{$LN_ng_minsetsize} {$minsetsize_sort}</th>
{if $isadmin neq 0 and $urdd_online neq 0 }
<th {urd_popup type="small" text=$LN_feeds_tooltip_autoupdate } class="{$admin_hidden} admin center buttonlike head" onclick="javascript:submit_rss_search('refresh_period','desc');">{$LN_feeds_autoupdate} {$refresh_period_sort}</th>
<th {urd_popup type="small" text=$LN_time } class="fixwidth5c nowrap {$admin_hidden} center admin buttonlike head" onclick="javascript:submit_rss_search('refresh_time', 'asc');">@ {$LN_time} {$refresh_time_sort}</th>
<th {urd_popup type="small" text=$LN_feeds_tooltip_uepev } class="fixwidth6c {$admin_hidden} center admin head round_right">{$LN_actions}</th>
{/if}
</tr>

{foreach $allfeeds as $feed}
<tr class="even content"
	onmouseover="javascript:$(this).toggleClass('highlight2')" 
	onmouseout="javascript:$(this).toggleClass('highlight2')">
<td class="general">{$feed.number}</td>
<td class="general">
{urd_checkbox value="{$feed.active_val}" name="rssfeed[{$feed.id}]" id="rssfeed_{$feed.id}" readonly="{$isadmin eq 0 || $urdd_online eq 0}" post_js="subscribe_rss('{$feed.id}');"} 
<input type="hidden" id="ng_id_{$feed.id}" value="{$feed.name|escape}"/>
</td>
<td class="general"> <div class="donotoverflowdamnit inline">

<span {if $feed.active_val eq $RSS_SUBSCRIBED} class="buttonlike" onclick="javascript:jump('rsssets.php?feed_id=feed_{$feed.id}');"{/if}>
{$feed.name|escape:htmlall}</span>
</div>

</td>
<td class="{$user_hidden} user center">
	<select name="category[{$feed.id}]" id="category_{$feed.id}" onchange="javascript:update_category('rss', {$feed.id});">
    <option value="0">{$LN_nocategory}</option>
    {foreach $categories as $item}		
    <option {if $item.id == $feed.category }selected="selected"{/if} value="{$item.id}">{$item.name|escape:htmlall}</option>
	{/foreach}
    </select>
    </td>

<td class="{$admin_hidden} admin"> <span class="buttonlike" onclick="javascript:jump('{$feed.url}', 1);">{$feed.url|escape:htmlall|truncate:$maxstrlen}</span>
</td>

<td class="{$admin_hidden} admin center" {if $feed.authentication == 1 }{urd_popup type="small" text=$LN_usenet_needsauthentication }{/if}>
{urd_checkbox value="{$feed.authentication}" name="feed_auth" id="auth_{$feed.id|escape}" readonly=1} 
</td>

<td class="general right">{$feed.feedcount|escape}</td>
<td class="{$admin_hidden} admin center">

{urd_checkbox value="{$feed.adult}" name="adult[{$feed.id}]" id="adult_{$feed.id}" readonly="{$isadmin eq 0}" post_js="update_adult('rss', '{$feed.id}')"} 
</td>

<td class="general right">{$feed.lastupdated|escape}</td>
<td class="{$admin_hidden} admin center">
<input type="text" size="2" value="{$feed.expire}" name="expire[{$feed.id}]" id="expire_{$feed.id}"  {if $isadmin neq 1 or $urdd_online neq 1} readonly="readonly"{/if} onchange="javascript:update_ng_value('rss', 'expire', {$feed.id});"/>
</td>
<td class="{$user_hidden} user center">
{urd_checkbox value="{$feed.visible}" name="visible[{$feed.id}]" id="visible_{$feed.id}" post_js="toggle_visibility('rss','{$feed.id}');"} 
<td class="{$user_hidden} user center">
<input type="text" size="3" value="{$feed.minsetsize|escape}" name="minsetsize[{$feed.id}]" id="user_minsetsize_{$feed.id}"  onchange="javascript:update_user_ng_value('rss', 'user_minsetsize', {$feed.id});"/>
<input type="text" size="3" value="{$feed.maxsetsize|escape}" name="maxsetsize[{$feed.id}]" id="user_maxsetsize_{$feed.id}"  onchange="javascript:update_user_ng_value('rss', 'user_maxsetsize', {$feed.id});"/>
</td>

{if $isadmin neq 0 and $urdd_online neq 0}
<td class="{$admin_hidden} admin center"> 
<select name="period[{$feed.id}]" id="period_{$feed.id}"  size="1" class="update" onchange="javascript:update_ng_time('rss', {$feed.id});">
{html_options values=$periods_keys output=$periods_texts selected={$feed.select}}
</select>
</td>
<td class="nowrap {$admin_hidden} admin center">@ <input type="text" id="time1_{$feed.id}" name="time1[{$feed.id}]" value="{$feed.time1}" class="time" onchange="javascript:update_ng_time('rss', {$feed.id});"/>:<input type="text" id="time2_{$feed.id}" class="time" name="time2[{$feed.id}]" value="{if isset($feed.time2)}{$feed.time2|string_format:"%02d"}{/if}" onchange="javascript:update_ng_time('rss', {$feed.id});"/>
</td>

<td class="nowrap {$admin_hidden} admin right">
<div class="floatright">
{if $feed.active_val eq $RSS_SUBSCRIBED and $isadmin neq 0 and $urdd_online neq 0} 
<div class="inline iconsizeplus editicon buttonlike" {urd_popup type="small" text=$LN_feeds_editfeed } onclick="javascript:edit_rss({$feed.id});"></div>
<div class="inline iconsizeplus upicon buttonlike" {urd_popup type="small" text=$LN_update } onclick="javascript:ng_action('updaterss', {$feed.id});"></div>
<div class="inline iconsizeplus killicon buttonlike" {urd_popup type="small" text=$LN_expire } onclick="javascript:ng_action('expirerss', {$feed.id});"></div>
<div class="inline iconsizeplus purgeicon buttonlike" {urd_popup type="small" text=$LN_purge } onclick="javascript:ng_action_confirm('purgerss', {$feed.id}, '{$LN_purge} \'@@\'');"></div>
<div class="inline iconsizeplus deleteicon buttonlike" {urd_popup type="small" text=$LN_delete } onclick="javascript:remove_rss({$feed.id}, '{$LN_delete} \'{$feed.name|escape}\'?');"></div>
{else if $isadmin neq 0 and $urdd_online neq 0}
<div class="inline iconsizeplus editicon buttonlike" {urd_popup type="small" text=$LN_feeds_edit } onclick="javascript:edit_rss({$feed.id});"></div>
<div class="inline iconsizeplus deleteicon buttonlike" {urd_popup type="small" text=$LN_delete } onclick="javascript:remove_rss({$feed.id}, '{$LN_delete} \'{$feed.name|escape}\'?');"></div>
{/if}

</div>
</td>
{/if}
</tr>
{foreachelse}
<tr><td colspan="12" class="centered highlight even bold">{$LN_error_nofeedsfound}</td></tr>
{/foreach}
{if count($allfeeds) > 12}
<tr><td colspan="12" class="feet round_both_bottom">&nbsp;</td>
{/if}
</tr>
</table>
</form>
</div>
{$bottomskipper}
<div>
<br/>
<input type="hidden" id="urddonline" value="{$urdd_online}"/>
</div>


