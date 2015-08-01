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
{extends file="popup.tpl"}
{block name=title}{$title|escape}{/block}

{block name=contents}

{function name=do_show_comments comments=''}
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
{/function}


<script type="text/javascript">
$(document).ready(function() {
    $('#td_sets').mouseup( function(e) { start_quickmenu('setdetails', '', {$USERSETTYPE_SPOT}, e); } ); 
    $('#image_inline').click( function(e) { jump('{$image|escape:javascript}', true); } ); 
    $('#image_db').click( function(e) { jump('show_image.php?spotid={$spotid|escape:javascript}', true); } ); 
    $('#image_file').click( function(e) { show_spot_image('getfile.php?file={$image_file|escape:javascript}&raw=1', true); } ); 
    $('#poster').click( function(e) { load_sets({ 'poster':'{$poster|escape:javascript}' }); } );
    $('#post_comment').click( function(e) { post_spot_comment('{$spotid}'); } );
    $('#report_spam').click( function(e) { report_spam('{$spotid}')} );
    $('#similar_button').click( function(e) { 
            {if $reference == ''}
                load_sets( { 'search' : '{$first_two_words|escape:javascript}' }); });
            {else}
                load_sets( { 'reference' : '{$reference|escape:javascript}' }); });
            {/if}
});
</script>
<input type="hidden" id="spot_subject" value="{$spotid}">
<input type="hidden" id="spot_type" value="{$type}">
<input type="hidden" id="spot_srctype" value="{$srctype}">

<div class="sets_inner" id="td_sets">
{if $show_image}
{if $image != '' && $image_from_db == 0}
<div class="spot_thumbnail noborder buttonlike"><img src="{$image}" id="image_inline" class="max180x180" alt=""/></div>
{/if}
{if image_from_db == 1}
<div class="spot_thumbnail noborder buttonlike"><img src="show_image.php?spotid={$spotid}" id="image_db" class="max180x180" alt=""/></div>
{/if}
{if $image_file != ''}
<div class="spot_thumbnail noborder buttonlike"><img src="getfile.php?raw=1&amp;file={$image_file}" id="image_file" class="max180x180" alt=""/></div>
{/if}
{/if}
<input type="hidden" id="blacklist_confirm_msg" value="{$LN_blacklist_spotter}"/>

<table class="set_details" id="spotdetails_table">
<tr class="comment"><td class="nowrap bold">{$LN_browse_subject}:</td><td>{$title|escape}</td></tr>
<tr class="comment"><td class="nowrap bold">{$LN_size}:</td><td>{$filesize|escape}</td></tr>
<tr class="comment"><td class="nowrap bold">{$LN_browse_age}:</td><td>{$age|escape} ({$timestamp|escape})</td></tr>
<tr class="comment"><td class="nowrap bold">{$LN_showsetinfo_postedby}:</td><td><span id="poster" class="buttonlike">{$poster|escape} ({$spotter_id|escape}){if $whitelisted}&nbsp;</span><div {urd_popup type="small" text="$LN_browse_userwhitelisted"} class="highlight_whitelist inline center width15">{$LN_whitelisttag}</div>{/if}</td></tr>

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

{$hadref=0}
{capture assign=extsetoverview}
	{$looped=0}
	{foreach $display as $vals}
        {if $vals.value != "0" && $vals.value != "" && $vals.value != "name"}
            {$looped="`$looped+1`"}
            <tr class="vtop small comment"><td class="nowrap bold">{$vals.name}:</td><td>
            {if $vals.display == 'text'}{$vals.value|escape}{/if}
            {if $vals.display == 'url'}
                {if $hadref == 0} 
                    <span id="similar_button" class="buttonlike highlight_comments">{$LN_spots_similar}</span>
                    {$hadref=1}
                {/if}
            {if $url.icon == ''} 
                <span class="buttonlike" onclick="javascript:jump('{$url.link|escape:javascript}',1);">{$url.display|escape}</span>
            {else}
                <span class="buttonlike highlight_comments" onclick="javascript:jump('{$url.link|escape:javascript}',1);">{$url.icon|escape}</span>        
            {/if}
            {/if}
            {if $vals.display == 'number'}<b>{$vals.value|escape}</b>{/if}
            {if $vals.display == 'checkbox'}{if $vals.value == 1}Yes{else}No{/if}{/if}
            </td></tr>
        {/if}
	{/foreach}
    {if $hadref == 0} 
        {$looped="`$looped+1`"}
        {$first_two_words}
        <tr class="vtop small comment"><td class="nowrap bold">{$LN_browse_tag_link}:</td><td>
		    <span id="similar_button" class="buttonlike highlight_comments">{$LN_spots_similar}</span>
        </td></tr>
    {/if}

{/capture}

{if $tag != ''}
<tr class="comment"><td class="nowrap bold">{$LN_spots_tag}:</td><td><span class="buttonlike" onclick="javascript:load_sets({ 'search':'{$tag|escape:javascript}' });">{$tag|escape}</span></td></tr>
{/if}
{if $url}
<tr class="comment"><td class="nowrap bold">{$LN_feeds_url}:</td><td>
{if $url.icon == ''} 
<span class="buttonlike" onclick="javascript:jump('{$url.link|escape:javascript}',1);">{$url.display|escape}</span>
{else}
<span class="buttonlike highlight_comments" onclick="javascript:jump('{$url.link|escape:javascript}',1);">{$url.icon|escape}</span>
{/if}
</td></tr>
{/if}
{if $image != ''}
<tr class="comment"><td class="nowrap bold">{$LN_bin_image}:</td><td><span class="buttonlike" onclick="javascript:jump('{$image|escape:javascript}',1);">{$image|escape}</span></td></tr>
{/if}
<tr class="comment"><td class="nowrap bold">{$LN_category}:</td><td><span class="buttonlike" onclick="javascript:load_sets({ 'spot_cat':'{$category_id|escape:javascript}' });">{$category|escape}</span></td></tr>
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

<tr class="comment"><td colspan="2" class='righted'>
<div class="inline iconsizeplus deleteicon buttonlike" id="report_spam" {urd_popup type="small" text=$LN_quickmenu_report_spam }></div>
<div class="inline iconsizeplus mailicon buttonlike" id="post_comment" {urd_popup type="small" text=$LN_post_comment }></div>

</td></tr>
<tr class="comment"><td colspan="2"><br/></td></tr>

{do_show_comments comments=$comments}
</table>
</div>
<input type="hidden" id="comment_offset" value="{$offset}"/>
{/block}
