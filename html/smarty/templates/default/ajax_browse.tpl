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
 * $LastChangedDate: 2014-06-26 00:01:04 +0200 (do, 26 jun 2014) $
 * $Rev: 3116 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_browse.tpl 3116 2014-06-25 22:01:04Z gavinspearhead@gmail.com $
 *}

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
{else}<br/>
{/if}
{/if}
{/strip}
{/capture}

{* Making a 'top' and a 'bottom' skipper: *}
{capture assign=bottomskipper}{strip}
{if $only_rows == 0}
{if count($pages) > 1}
{urd_skipper current=$currentpage last=$lastpage pages=$pages position=bottom js=set_offset extra_class="margin10" size=$skippersize}
{else}<br/>
{/if}
{/if}
{/strip}
{/capture}

{capture assign=unmark_int_all}{strip}
<div class="floatright">
{if $killflag}
<div class="inline iconsizeplus killicon buttonlike" id="resurrect_button" {urd_popup type="small" text=$LN_browse_resurrectset}></div>
{else}
<div class="inline iconsizeplus deleteicon buttonlike" id="remove_button" {urd_popup type="small" text=$LN_browse_removeset }></div>
{/if}
{if $isadmin}
<div class="inline iconsizeplus purgeicon buttonlike" id="wipe_button" {urd_popup type="small" text=$LN_browse_deleteset}></div>
{/if}
{if $isadmin}
<div class="inline iconsizeplus sadicon buttonlike" id="unmark_int_button" {urd_popup type="small" text=$LN_browse_toggleint }></div>
{/if}
</div>
{/strip}
{/capture}

{* And display it here and at the bottom: *}
{capture assign=tableheader}

{$up="<img src='$IMGDIR/small_up.png' width='9' height='6' alt=''>"}
{$down="<img src='$IMGDIR/small_down.png' width='9' height='6' alt=''>"}
{if $sort.order == "better_subject"}{if $sort.direction=='desc'}{$title_sort=$up}{else}{$title_sort=$down}{/if}{else}{$title_sort=""}{/if}
{if $sort.order == "date"}{if $sort.direction=='desc'}{$stamp_sort=$up}{else}{$stamp_sort=$down}{/if}{else}{$stamp_sort=""}{/if}
{if $sort.order == "size"}{if $sort.direction=='desc'}{$size_sort=$up}{else}{$size_sort=$down}{/if}{else}{$size_sort=""}{/if}

<table class="articles" id="sets_table">
<tr>
<th class="head round_left">&nbsp;</th>
<th class="head">&nbsp;</th>
<th id="browsesubjecttd" class="head buttonlike">{$LN_browse_subject} {$title_sort}</th>
<th id="head_stamp" class="fixwidth2a nowrap buttonlike head right">{$LN_browse_age} {$stamp_sort}</th>
<th id="head_size" class="fixwidth3 nowrap buttonlike head right">{$LN_size} {$size_sort}</th>
<th id="head_complete" class="fixwidth1 buttonlike head">{$LN_browse_percent}</th>
<th id="head_rating" class="fixwidth1 buttonlike head right"><div class="floatleft iconsizeplus followicon buttonlike"></div></th>
<th class="nowrap fixwidth4 head round_right">{$unmark_int_all}</th>
</tr>
{/capture}
{if $only_rows == 0}
{$topskipper}
{$tableheader}
{/if}

{* Display the bunch: *}
{foreach $allsets as $set}

{capture assign="smallbuttons"}{strip}	
<input type="hidden" name="setdata[]" id="set_{$set.setid}" value=""/>
<div id="divset_{$set.setid}" class="inline iconsize buttonlike"></div>

<script type="text/javascript">
$(document).ready(function() {
    {if $set.added} 
        $('#set_{$set.setid}').val('x'); 
        $('#divset_{$set.setid}').addClass('setimgminus'); 
    {else} 
        $('#divset_{$set.setid}').addClass('setimgplus'); 
    {/if}
    $('#divset_{$set.setid}').click( function (e) { select_set('{$set.setid}', 'group', e); return false; } );
    $('#td_set_{$set.setid}').mouseup( function (e) { start_quickmenu('browse', '{$set.setid}', {$USERSETTYPE_GROUP}, e); } );
    $('#intimg_{$set.setid}').click( function () { mark_read('{$set.setid}', 'interesting', {$USERSETTYPE_GROUP} ); } );
    $('#wipe_img_{$set.setid}').click( function () { mark_read('{$set.setid}', 'wipe', {$USERSETTYPE_GROUP} ); } );
    $('#link_img_{$set.setid}').click( function () { jump('{$set.imdblink|escape:javascript}', true); } );
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

{$complete="yellow"}
{$completion=$set.complete}
{$completion="$completion%"}

{if $set.complete < 120}{$complete="green"}{/if}
{if $set.complete < 100}{$complete="orange"}{/if}
{if $set.complete < 90}{$complete="red"}{/if}
{if $set.complete == -1}{$complete="grey"}{$completion='Completion unknown'}{/if}

{* Remember this is a copy of formatsetname.tpl; included here for performance reasons (beats 100's of includes) (I think) *}
{capture assign=setdesc}{$set.name|escape:htmlall|replace:':_img_pw:':$btpw|replace:':_img_copyright:':$btcopyright}{/capture}

{$bintype=''}
{if $set.binary_type == 1} {$bintype=$btmovie}
{elseif $set.binary_type == 2} {$bintype=$btmusic}
{elseif $set.binary_type == 3} {$bintype=$btimage}
{elseif $set.binary_type == 4} {$bintype=$btsoftw}
{elseif $set.binary_type == 5} {$bintype=$bttv}
{elseif $set.binary_type == 6} {$bintype=$btebook}
{elseif $set.binary_type == 7} {$bintype=$btdocu}
{elseif $set.binary_type == 8} {$bintype=$btgame}
{elseif $set.binary_type == 9} {$bintype=$bttv}
{/if}

{* Ok now it's time to put it all together: *}	
<tr class="content {$interesting} {$read} {$nzb}" id="base_row_{$set.setid}" name="content">
	<td class="fixwidth1">{$set.number}
    
    {* We need this stuff to remember any the search options *}
    <input type="hidden" name="set_ids[]" value="{$set.setid}"/>
    </td>
	<td class="setbuttons">{$smallbuttons}</td>
    <td id="td_set_{$set.setid}">
    <div class="donotoverflowdamnit inline">{$bintype} {$setdesc}</div>
    </td>
{$rating=$set.rating * 10}
{$imdbpic="ratingicon_$rating"}
{if $rating == ""}{$imdbpic="followicon"}{/if}
	<td class="fixwidth2a nowrap {if $set.new_set != 0}newset{/if}">{$set.age}</td>
	<td class="fixwidth3 nowrap">{$set.size}</td>
	<td class="fixwidth1"> <div class="{$complete} status_light down2" {urd_popup type="small" text="$completion"}></div></td>
    <td class="fixwidth1">
    {if $set.imdblink != ''}
    <div id="link_img_{$set.setid}" class="inline iconsize {$imdbpic} buttonlike" {urd_popup type="small" text=$set.imdblink}></div>
	{elseif $set.rating != 0}
    <div class="inline iconsize {$imdbpic} buttonlike"></div>
	{/if}</td>

	<td class="nowrap">
    <div class="floatright">
    {if $isadmin}
    <div id="wipe_img_{$set.setid}" class="inline iconsize purgeicon buttonlike" {urd_popup type="small" text=$LN_browse_deleteset}></div>
    {/if}
    <div id="intimg_{$set.setid}" class="inline iconsize {$interestingimg} buttonlike" {urd_popup type="small" text=$LN_browse_toggleint}></div>
    </div>
	</td>
</tr>
{foreachelse} 
{if $only_rows == 0}
<tr><td colspan="8" class="centered highlight even bold">{$LN_error_nosetsfound}</td></tr>
{/if}
{/foreach}
{* Last bit: *}

{if $only_rows == 0}
{if $lastpage > 1}
<tr><td colspan="7" class="feet round_left_bottom">&nbsp;</td>
<td class="nowrap feet round_right_bottom">{$unmark_int_all}</td>
</tr>
{/if}
    </table>
    {$bottomskipper}
<br/>
<input type="hidden" id="rss_url" value="{$rssurl|escape:quotes}"/>
<input type="hidden" id="killflag" value="{$killflag|escape:htmlall}"/>
<input type="hidden" id="deletedsets" value="{$LN_browse_deletedsets}"/>
<input type="hidden" id="deletedset" value="{$LN_browse_deletedset}"/>

<script type="text/javascript">
$(document).ready(function() {
    $('#resurrect_button').click( function (e) { which_button('unmark_kill_all', e); } );
    $('#remove_button').click( function (e) { which_button('mark_kill_all', e); } );
    $('#wipe_button').click( function (e) { which_button('wipe_all', e) } );
    $('#unmark_int_button').click( function (e) { which_button('unmark_int_all', e); } );
    $('#browsesubjecttd').click( function () { change_sort_order('better_subject', 'asc') } );
    $('#head_stamp').click( function () { change_sort_order('date', 'desc') } );
    $('#head_size').click( function () { change_sort_order('size', 'desc') } );
    $('#head_complete').click( function () { change_sort_order('complete', 'desc') } );
    $('#head_rating').click( function () { change_sort_order('rating', 'desc') } );
});
</script>
{/if}
