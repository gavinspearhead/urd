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
 * $LastChangedDate: 2011-03-10 19:37:28 +0100 (Thu, 10 Mar 2011) $
 * $Rev: 2102 $
 * $Author: gavinspearhead $
 * $Id: ajax_browse.tpl 2102 2011-03-10 18:37:28Z gavinspearhead $
 *}
{* These icon images are a copy of the code in formatsetname.tpl *}
{include 'include_bin_image.tpl' scope='parent'}


{if $view_size >= 1024}
{$small=0}
{$skippersize= 30}
{else}
{$small=1}
{$skippersize= 18}
{/if}

{capture assign=topskipper}{strip}
{if $only_rows == 0}
    {if count($pages) > 1}
        {urd_skipper current=$currentpage last=$lastpage pages=$pages position=top js=set_offset extra_class="margin10" size=$skippersize}
    {else}
        <br/>
    {/if}
{/if}
{/strip}
{/capture}

{* Making a 'top' and a 'bottom' skipper: *}
{capture assign=bottomskipper}{strip}
{if $only_rows == 0}
    {if count($pages) > 1}
        {urd_skipper current=$currentpage last=$lastpage pages=$pages position=bottom js=set_offset extra_class="margin10" size=$skippersize}
    {else}
        <br/>
    {/if}
{/if}
{/strip}
{/capture}

{capture assign=unmark_int_all}{strip}
{if $killflag}
    <div class="inline iconsizeplus killicon buttonlike resurrect_button" {urd_popup type="small" text=$LN_browse_resurrectset}></div>
{else}
    <div class="inline iconsizeplus deleteicon buttonlike remove_button" {urd_popup type="small" text=$LN_browse_removeset}></div>
{/if}
{if $isadmin}
    {if not $small}
        <div class="inline iconsizeplus purgeicon buttonlike wipe_button" {urd_popup type="small" text=$LN_browse_deleteset}></div>
    {/if}
    <div class="inline iconsizeplus sadicon buttonlike unmark_int_button" {urd_popup type="small" text=$LN_browse_toggleint}></div>
{/if}
{/strip}
{/capture}

{* And display it here and at the bottom: *}

{capture assign=tableheader}
{$up="<img src='$IMGDIR/small_up.png' width='9' height='6' alt=''>"}
{$down="<img src='$IMGDIR/small_down.png' width='9' height='6' alt=''>"}
{if $sort.order == "title"} {if $sort.direction=='desc'}{$title_sort=$up} {else}{$title_sort=$down} {/if} {else}{$title_sort=""} {/if}
{if $sort.order == "stamp"} {if $sort.direction=='desc'}{$stamp_sort=$up} {else}{$stamp_sort=$down} {/if} {else}{$stamp_sort=""} {/if}
{if $sort.order == "size"} {if $sort.direction=='desc'}{$size_sort=$up} {else}{$size_sort=$down} {/if} {else}{$size_sort=""} {/if}

<table class="articles" id="spots_table">
<tr>
<th class="head round_left">&nbsp;</th>
<th class="head">&nbsp;</th>
<th class="head buttonlike" id="browsesubjecttd">{$LN_browse_subject} {$title_sort}</th>
{if $small == 0}
<th class="fixwidth1 head buttonlike" id="head_reports">{$LN_spamreporttag}</th>
<th class="fixwidth1 head">{$LN_whitelisttag}</th>
{/if}
{if $show_comments > 0 && $small == 0}
<th class="fixwidth1 head buttonlike" id="head_comments">#</th>
{/if}
<th class="fixwidth2a nowrap buttonlike head right" id="head_stamp">{$LN_browse_age} {$stamp_sort}</th>
<th class="fixwidth3 nowrap buttonlike head right" id="head_size">{$LN_size} {$size_sort}</th>
<th class="fixwidth1 buttonlike head right" id="head_url"><div class="inline iconsizeplus followicon buttonlike"></div></th>
<th class="fixwidth4 nowrap head round_right">{$unmark_int_all}</th>
</tr>
{/capture}

{if $only_rows == 0}
    {$topskipper}
    <div id="setstable">
    {$tableheader}
{/if}

{* Display the bunch: *}
{foreach $allsets as $set}

{capture assign=smallbuttons}{strip}	
<input type="hidden" name="setdata[]" id="set_{$set.spotid}" value=""/>
<div id="divset_{$set.spotid}" class="inline iconsize buttonlike"></div>

<script type="text/javascript">
$(document).ready(function() {
    {if $set.added} 
        $('#set_{$set.spotid}').val('x'); 
        $('#divset_{$set.spotid}').addClass('setimgminus'); 
    {else} 
        $('#divset_{$set.spotid}').addClass('setimgplus'); 
    {/if}
    $('#divset_{$set.spotid}').click( function (e) { select_set('{$set.spotid}', 'spot', e); return false; } );
    $('#td_set_{$set.spotid}').mouseup( function (e) { start_quickmenu('browse', '{$set.spotid}', {$USERSETTYPE_SPOT}, e); } );
    $('#intimg_{$set.spotid}').click( function () { mark_read('{$set.spotid}', 'interesting', {$USERSETTYPE_SPOT} ); } );
    $('#wipe_img_{$set.spotid}').click( function () { mark_read('{$set.spotid}', 'wipe', {$USERSETTYPE_SPOT} ); } );
    $('#link_img_{$set.spotid}').click( function () { jump('{$set.url|escape:javascript}', true); } );
});
</script>
{/strip}

{/capture}

{* Store flags to be used in class definition: *}
{$read=''}
{$nzb=''}
{$interesting='even'}
{$interestingimg="smileicon"}
{if $set.read == 1}{$read='markedread'}{/if}
{if $show_makenzb != 0 && $set.nzb == 1}{$nzb='markednzb'}{/if}
{if $set.interesting == 1}{$interesting='interesting'}{/if}
{if $set.interesting == 1}{$interestingimg='sadicon'}{/if}

{* Remember this is a copy of formatsetname.tpl; included here for performance reasons (beats 100's of includes) *}
{capture assign=setdesc}{$set.name|escape:htmlall|replace:':_img_pw:':$btpw|replace:':_img_copyright:':$btcopyright}{/capture}

{capture assign=subcats}{strip}
<table>
{foreach $set.subcata as $k=>$val1}
    <tr><td>{$k}:&nbsp;</td><td>
    {foreach $val1 as $val2}
        {$val2.0}{if not $val2@last}; {/if}
    {/foreach}
    </td></tr>
{/foreach}
{foreach $set.subcatb as $k=>$val1}
    <tr><td>{$k}:&nbsp;</td><td>
    {foreach $val1 as $val2}
        {$val2.0}{if not $val2@last}; {/if}
    {/foreach}
    </td></tr>
{/foreach}
{foreach $set.subcatc as $k=>$val1}
    <tr><td>{$k}:&nbsp;</td><td>
    {foreach $val1 as $val2}
        {$val2.0}{if not $val2@last}; {/if}
    {/foreach}
    </td></tr>
{/foreach}
{foreach $set.subcatd as $k=>$val1}
    <tr><td>{$k}:&nbsp;</td><td>
    {foreach $val1 as $val2}
        {$val2.0}{if not $val2@last}; {/if}
    {/foreach}
    </td></tr>
{/foreach}
{foreach $set.subcatz as $k=>$val1}
    <tr><td>{$k}:&nbsp;</td><td>
    {foreach $val1 as $val2}
        {$val2.0} {if not $val2@last}; {/if}
    {/foreach}
    </td></tr>
{/foreach}

</table>
{/strip}{/capture}

{* Ok now it's time to put it all together: *}	
<tr class="content {$interesting} {$read} {$nzb} set_content" id="base_row_{$set.spotid}">
<td class="fixwidth1">{$set.number}
<input type="hidden" name="set_ids[]" value="{$set.spotid}"/>
    </td>
	<td class="setbuttons">{$smallbuttons}</td>

<td id="td_set_{$set.spotid}" {if $show_subcats}{urd_popup text="$subcats" caption="$LN_spots_subcategories"}{/if}>
    <div class="donotoverflowdamnit inline">
{if $set.extcat == ':_img_movie:'}{$btmovie}
{elseif $set.extcat == ':_img_album:'}{$btmusic}
{elseif $set.extcat == ':_img_image:'}{$btimage}
{elseif $set.extcat == ':_img_software:'}{$btsoftw}
{elseif $set.extcat == ':_img_series:'}{$bttv}
{elseif $set.extcat == ':_img_tvshow:'}{$bttv}
{elseif $set.extcat == ':_img_documentary:'}{$btdocu}
{elseif $set.extcat == ':_img_ebook:'}{$btebook}
{elseif $set.extcat == ':_img_game:'}{$btgame}
{elseif $set.categorynr == 0}{$btmovie}
{elseif $set.categorynr == 1}{$btmusic}
{elseif $set.categorynr == 2}{$btgame}
{elseif $set.categorynr == 3}{$btsoftw}
{/if}
{$rating=$set.rating * 10}
{$linkpic="ratingicon_$rating"}
{if $rating == ""}{$linkpic="followicon"}{/if}

    <div class="inline">{$setdesc}</div> 
    </div>
    </td>
    {if $small == 0}
    <td class="width20">
    {if $set.reports gt 0}{$spamreports=$set.reports}<div {urd_popup type="small" text="$spamreports $LN_spam_reports"} class="highlight_spam inline center width15">{$set.reports}</div>{/if}
    </td>
    <td class="width20">
    {if $set.whitelisted}{$setwhitelisted=$set.whitelisted}{$poster=$set.poster}<div {urd_popup type="small" text="$LN_browse_userwhitelisted:<br> $poster (<i>{$setwhitelisted}</i>)"} class="highlight_whitelist inline center width15">{$LN_whitelisttag}</div>{/if}
    </div>
    </td>
    {/if}
    {if $show_comments > 0 && $small == 0}
    <td class="width32">
        {$setcomments=$set.comments}
        <div class="inline highlight_comments center width25" {urd_popup type="small" text="$setcomments $LN_browse_tag_note"}>{$set.comments}</div>
    </td>
    {/if}
    </div>

<td class="fixwidth2a nowrap {if $set.new_set != 0}newset{/if}">{$set.age}</td>
<td class="fixwidth3 nowrap">{$set.size}</td>
<td class="fixwidth1">
    
    {if $set.url != ''}
    <div id="link_img_{$set.spotid}" class="inline iconsize {$linkpic} buttonlike" {urd_popup type="small" text=$set.url|escape:htmlall}></div>
	{elseif $set.rating != 0}
    <div class="inline iconsize {$linkpic}"></div>
{else}&nbsp;
	{/if}
    </td>  
	<td class="nowrap">
    <div class="floatright">
    {if $isadmin}
    {if not $small}
    <div id="wipe_img_{$set.spotid}" class="inline iconsize purgeicon buttonlike" {urd_popup type="small" text=$LN_browse_deleteset}></div>
    {/if}
    {/if}
    <div id="intimg_{$set.spotid}" class="inline iconsize {$interestingimg} buttonlike" {urd_popup type="small" text=$LN_browse_toggleint }></div>
    </div>
	</td>
</tr>
{foreachelse} 
{if $only_rows == 0}
<tr><td colspan="{if $show_comments > 0}10{else}9{/if}" class="centered highlight even bold">{$LN_error_nosetsfound}</td></tr>
{/if}
{/foreach}
{* Last bit: *}

{if $only_rows == 0}
{if $lastpage > 1}
<tr><td colspan="{if $show_comments > 0}9{else}8{/if}" class="feet round_left_bottom">&nbsp;</td>
<td class="nowrap feet round_right_bottom">{$unmark_int_all}</td>
</tr>
{/if}

</table>
</div>
{$bottomskipper}
<br/>

<input type="hidden" id="rss_url" value="{$rssurl|escape:quotes}"/>
<input type="hidden" id="killflag" value="{$killflag|escape}"/>
<input type="hidden" id="deletedset" value="{$LN_browse_deletedset}"/>

<script type="text/javascript">
$(document).ready(function() {
    $('#browsesubjecttd').click( function () { change_sort_order('title') } );
    $('#head_reports').click( function () { change_sort_order('reports', 'desc') } );
    $('#head_comments').click( function () { change_sort_order('comments', 'desc') } );
    $('#head_stamp').click( function () { change_sort_order('stamp', 'desc') } );
    $('#head_size').click( function () { change_sort_order('size', 'desc') } );
    $('#head_url').click( function () { change_sort_order('url') } );
    $('div.resurrect_button').click( function (e) { which_button('unmark_kill_all', e); } );
    $('div.remove_button').click( function (e) { which_button('mark_kill_all', e); } );
    $('div.wipe_button').click( function (e) { which_button('wipe_all', e) } );
    $("div.unmark_int_button").click( function (e) { which_button('unmark_int_all', e); } );
});
</script>
{/if}
