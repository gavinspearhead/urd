{* Smarty *}
{*
 *  vim:ts=4:expandtab:cindent
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
 * $LastChangedDate: 2011-01-15 01:03:01 +0100 (Sat, 15 Jan 2011) $
 * $Rev: 2027 $
 * $Author: gavinspearhead $
 * $Id: browse.tpl 2027 2011-01-15 00:03:01Z gavinspearhead $
 *}
<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered">{$title|escape}</div>
<div class="sets_inner" onmouseup="javascript:start_quickmenu('setdetails', '', {$USERSETTYPE_SPOT}, event);" id="td_sets">
{if $show_image && $image != '' && $image_from_db == 0}
<div class="spot_thumbnail noborder buttonlike"><img src="{$image}" class="max100x100" alt="" onclick="javascript:jump('{$image|escape:javascript}', true);"/> </div>
{/if}
{if  $show_image && $image_from_db == 1}
<div class="spot_thumbnail noborder buttonlike"><img src="show_image.php?spotid={$spotid}" class="max100x100" alt="" onclick="javascript:jump('show_image.php?spotid={$spotid}', true);"/></div>
{/if}
{if  $show_image && $image_file != ''}
<div class="spot_thumbnail noborder buttonlike"><img src="getfile.php?raw=1&amp;file={$image_file}" class="max100x100" alt="" onclick="javascript:show_spot_image('getfile.php?file={$image_file}&amp;raw=1', true);"/></div>
{/if}
<input type="hidden" id="blacklist_confirm_msg" value="{$LN_blacklist_spotter}"/>

<table class="set_details">
<tr class="comment"><td class="nowrap bold">{$LN_browse_subject}:</td><td>{$title|escape}</td></tr>
<tr class="comment"><td class="nowrap bold">{$LN_size}:</td><td>{$filesize|escape}</td></tr>
<tr class="comment"><td class="nowrap bold">{$LN_browse_age}:</td><td>{$age|escape} ({$timestamp|escape})</td></tr>
<tr class="comment"><td class="nowrap bold">{$LN_showsetinfo_postedby}:</td><td><span class="buttonlike" onclick="javascript:load_sets({ 'poster':'{$poster|escape:javascript}' });">{$poster|escape} ({$spotter_id|escape}){if $whitelisted}&nbsp;</span><div {urd_popup type="small" text="$LN_browse_userwhitelisted"} class="highlight_whitelist inline center width15">W</div>{/if}</td></tr>

{foreach $subcata as $k=>$cat}<tr class="comment"><td class="nowrap bold">{$k}:</td>
<td>
{foreach $cat as $val}
<span class="buttonlike" onclick="javascript:load_sets({ 'spot_cat':'{$val.1|escape:javascript}', 'subcat':'subcat_{$val.1|escape:javascript}_{$val.2|escape:javascript}_{$val.3|escape:javascript}' });">{$val.0}</span>
{if not $val@last};{/if} 
{/foreach}
</td></tr>
{foreach $subcatd as $k=>$cat}<tr class="comment"><td class="nowrap bold buttonlike">{$k}:</td><td> 
{foreach $cat as $val}<span  class="buttonlike" onclick="javascript:load_sets({ 'spot_cat':'{$val.1|escape:javascript}', 'subcat':'subcat_{$val.1|escape:javascript}_{$val.2|escape:javascript}_{$val.3|escape:javascript}' });">{$val.0}</span>{if not $val@last};{/if} {/foreach}</td></tr>
{/foreach}
{foreach $subcatb as $k=>$cat}<tr class="comment"><td class="nowrap bold buttonlike">{$k}:</td><td>
{foreach $cat as $val}<span  class="buttonlike" onclick="javascript:load_sets({ 'spot_cat':'{$val.1|escape:javascript}', 'subcat':'subcat_{$val.1|escape:javascript}_{$val.2|escape:javascript}_{$val.3|escape:javascript}' });">{$val.0}</span>{if not $val@last};{/if} {/foreach} </td></tr>
{/foreach}
{foreach $subcatc as $k=>$cat}<tr class="comment"><td class="nowrap bold buttonlike">{$k}:</td><td>
{foreach $cat as $val}<span  class="buttonlike" onclick="javascript:load_sets({ 'spot_cat':'{$val.1|escape:javascript}', 'subcat':'subcat_{$val.1|escape:javascript}_{$val.2|escape:javascript}_{$val.3|escape:javascript}' });">{$val.0}</span>{if not $val@last};{/if} {/foreach}</td></tr>
{/foreach}
{/foreach}

{capture assign=extsetoverview}
	{$looped=0}
	{foreach $display as $vals}
	{if $vals.value != "0" && $vals.value != "" && $vals.value != "name"}
		{$looped="`$looped+1`"}
		<tr class="vtop small comment"><td class="nowrap bold">{$vals.name}:</td><td>
		{if $vals.display == 'text'}{$vals.value|escape}{/if}
		{if $vals.display == 'url'}<span class="buttonlike" onclick="javascript:jump('{$vals.value|escape:javascript}',1);">{$vals.value|escape}</span>{/if}
		{if $vals.display == 'number'}<b>{$vals.value|escape}</b>{/if}
		{if $vals.display == 'checkbox'}{if $vals.value == 1}Yes{else}No{/if}{/if}
		</td></tr>
	{/if}
	{/foreach}
{/capture}

{if $tag != ''}
<tr class="comment"><td class="nowrap bold">{$LN_spots_tag}:</td><td><span class="buttonlike" onclick="javascript:load_sets({ 'search':'{$tag|escape:javascript}' });">{$tag|escape}</span></td></tr>
{/if}
{if $url != ''}
<tr class="comment"><td class="nowrap bold">{$LN_feeds_url}:</td><td><span class="buttonlike" onclick="javascript:jump('{$url|escape:javascript}',1);">{$url|escape}</span></td></tr>
{/if}
{if $image != ''}
<tr class="comment"><td class="nowrap bold">{$LN_bin_image}:</td><td><span class="buttonlike" onclick="javascript:jump('{$image|escape:javascript}',1);">{$image|escape}</span></td></tr>
{/if}
<tr class="comment"><td class="nowrap bold">{$LN_category}:</td><td><span class="buttonlike" onclick="javascript: load_sets({ 'spot_cat':'{$category_id|escape:javascript}' });">{$category|escape}</span></td></tr>
{if $subcat != 0} <tr class="comment"><td class="nowrap bold">{$LN_spot_subcategory}:</td><td>{$subcat|escape}</td></tr>{/if}
<tr class="comment"><td class="nowrap bold">{$LN_spam_reports}:</td><td>
{if $spam_reports gt 0}<div class="highlight_spam inline center width15">{$spam_reports|escape}</div>
{else}0{/if}
</td></tr>
{if $looped > 0}
<tr><td colspan="2">&nbsp;</td></tr>
{$extsetoverview}
{/if}

<tr class="comment"><td colspan="2"><br/></td></tr>
<tr class="comment"><td colspan="2">{$description}</td></tr>
<tr class="comment"><td colspan="2"><br/></td></tr>

{foreach $comments as $comment}
<tr class="comment_poster"><td colspan="2">
<div class="floatleft">
{if $comment.user_avatar != ''}<img class="floatleft" src="{$comment.user_avatar}"/>&nbsp; {/if}
{$LN_showsetinfo_postedby}: {$comment.from|escape} ({$comment.userid|escape})&nbsp; </div>
<div class="floatright"> @ {$comment.stamp|escape}</div>
<div class="inline iconsizeplus deleteicon buttonlike" onclick="javascript:add_blacklist('{$comment.userid|escape:javascript}', 'spotterid');" {urd_popup type="small" text=$LN_quickmenu_addblacklist }></div>
</td></tr>
<tr class="comment"><td colspan="2">

{$comment.comment}
</td></tr>
<tr class="comment"><td colspan="2"><br/></td></tr>
{/foreach}

</table>
</div>
