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
 * $LastChangedDate: 2013-07-07 00:56:13 +0200 (zo, 07 jul 2013) $
 * $Rev: 2867 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_browse.tpl 2867 2013-07-06 22:56:13Z gavinspearhead@gmail.com $
 *}

{* These icon images are a copy of the code in formatsetname.tpl *}
{* Smarty doesn't allow you to 'include' variables from another file... *}
{* Icon images: (Global variable ish) *}
{$btmovie="<img class=\"binicon\" src=\"$IMGDIR/bin_movie.png\" alt=\"\" width=\"48\" height=\"16\"/>"}
{$btmusic="<img class=\"binicon\" src=\"$IMGDIR/bin_music.png\" alt=\"\" width=\"48\" height=\"16\"/>"}
{$btimage="<img class=\"binicon\" src=\"$IMGDIR/bin_image.png\" alt=\"\" width=\"48\" height=\"16\"/>"}
{$btsoftw="<img class=\"binicon\" src=\"$IMGDIR/bin_software.png\" alt=\"\" width=\"48\" height=\"16\"/>"}
{$bttv="<img class=\"binicon\" src=\"$IMGDIR/bin_series.png\" alt=\"\" width=\"48\" height=\"16\"/>"}
{$btdocu="<img class=\"binicon\" src=\"$IMGDIR/bin_documentary.png\" alt=\"\" width=\"48\" height=\"16\"/>"}
{$btgame="<img class=\"binicon\" src=\"$IMGDIR/bin_games.png\" alt=\"\" width=\"48\" height=\"16\"/>"}
{$btebook="<img class=\"binicon\" src=\"$IMGDIR/bin_ebook.png\" alt=\"\" width=\"48\" height=\"16\"/>"}
{$btpw="<img class=\"binicon\" src=\"$IMGDIR/icon_pw.png\" width=\"16\" height=\"16\"/>"}
{$btcopyright="<img class=\"binicon\" src=\"$IMGDIR/icon_copy.png\" width=\"16\" height=\"16\"/>"}

{capture assign=topskipper}{strip}
{if count($pages) > 1}
{urd_skipper current=$currentpage last=$lastpage pages=$pages class=ps js=set_offset extra_class="margin10"}
{else}<br/>
{/if}
{/strip}
{/capture}

{* Making a 'top' and a 'bottom' skipper: *}
{capture assign=bottomskipper}{strip}
{if count($pages) > 1}
{urd_skipper current=$currentpage last=$lastpage pages=$pages class=psb js=set_offset extra_class="margin10"}
{else}<br/>
{/if}
{/strip}
{/capture}

{capture assign=unmark_int_all}{strip}
<div class="floatright">
{if $killflag}
<div class="inline iconsizeplus killicon buttonlike" onclick="javascript:Whichbutton('unmark_kill_all', event);" {urd_popup type="small" text=$LN_browse_resurrectset}></div>
{else}
<div class="inline iconsizeplus deleteicon buttonlike" onclick="javascript:Whichbutton('mark_kill_all', event);" {urd_popup type="small" text=$LN_browse_removeset }></div>
{/if}
{if $isadmin}
<div class="inline iconsizeplus purgeicon buttonlike" onclick="javascript:Whichbutton('wipe_all', event)" {urd_popup type="small" text=$LN_browse_deleteset}></div>
{/if}
{if $isadmin}
<div class="inline iconsizeplus sadicon buttonlike" onclick="javascript:Whichbutton('unmark_int_all', event);" {urd_popup type="small" text=$LN_browse_toggleint }></div>
{/if}
</div>
{/strip}
{/capture}

{$up="<img src='$IMGDIR/small_up.png' alt=''>"}{$down="<img src='$IMGDIR/small_down.png' alt=''>"}
{if $sort.order == "complete"} {if $sort.direction=='desc'}{$complete_sort=$up} {else}{$complete_sort=$down} {/if} {else}{$complete_sort=""} {/if}
{if $sort.order == "better_subject"} {if $sort.direction=='desc'}{$title_sort=$up} {else}{$title_sort=$down} {/if} {else}{$title_sort=""} {/if}
{if $sort.order == "date"} {if $sort.direction=='desc'}{$stamp_sort=$up} {else}{$stamp_sort=$down} {/if} {else}{$stamp_sort=""} {/if}
{if $sort.order == "size"} {if $sort.direction=='desc'}{$size_sort=$up} {else}{$size_sort=$down} {/if} {else}{$size_sort=""} {/if}
{*if $sort.order == "rating"} {if $sort.direction=='desc'}{$url_sort=$up} {else}{$url_sort=$down} {/if} {else}{$url_sort=""} {/if*}


{* And display it here and at the bottom: *}
{capture assign=tableheader}
<table class="articles" id="sets_table">
<tr>
<th class="head round_left">&nbsp;</th>
<th class="head">&nbsp;</th>
<th id="browsesubjecttd" class="head buttonlike" onclick="javascript:change_sort_order('better_subject');">{$LN_browse_subject} {$title_sort}</th>
<th class="fixwidth2a nowrap buttonlike head right" onclick="javascript:change_sort_order('date');">{$LN_browse_age} {$stamp_sort}</th>
<th class="fixwidth3 nowrap buttonlike head right" onclick="javascript:change_sort_order('size');">{$LN_size} {$size_sort}</th>
<th class="fixwidth1 buttonlike head" onclick="javascript:change_sort_order('complete');">{$LN_browse_percent} {*$complete_sort*}</th>
<th class="fixwidth1 buttonlike head right" onclick="javascript:change_sort_order('rating');"><div class="floatleft iconsizeplus followicon buttonlike"></div> </th>
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
{if !$set.added}
<div id="divset_{$set.sid}" class="setimgplus floatleft iconsize buttonlike" onclick="javascript:SelectSet('{$set.sid}', 'group', event);return false;"></div>
<input type="hidden" name="setdata[]" id="set_{$set.sid}" value=""/>
{else}
<div id="divset_{$set.sid}" class="setimgminus floatleft iconsize buttonlike" onclick="javascript:SelectSet('{$set.sid}', 'group', event);return false;"></div>
<input type="hidden" name="setdata[]" id="set_{$set.sid}" value="x"/>
{/if}
{/strip}
{/capture}

{* Store flags to be used in class definition: *}
{$read=''}
{$nzb=''}
{$interesting=''}
{$interestingimg="smileicon"}
{if $set.read == 1}{$read='markedread'}{/if}
{if $show_makenzb neq 0 && $set.nzb == 1}{$nzb='markednzb'}{/if}
{if $set.interesting == 1}{$interesting='interesting'}{/if}
{if $set.interesting == 1}{$interestingimg='sadicon'}{/if}

{$complete="light_yellow"}
{$completion=$set.complete}
{$completion="$completion%"}

{if $set.complete < 120}{$complete="light_green"}{/if}
{if $set.complete < 100}{$complete="light_orange"}{/if}
{if $set.complete < 90}{$complete="light_red"}{/if}
{if $set.complete == -1}{$complete="light_off"}{$completion='Completion unknown'}{/if}

{* Remember this is a copy of formatsetname.tpl; included here for performance reasons (beats 100's of includes) (I think) *}
{capture assign=setdesc}{$set.name|escape:htmlall}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_movie:':$btmovie}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_album:':$btmusic}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_image:':$btimage}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_software:':$btsoftw}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_series:':$bttv}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_tvshow:':$bttv}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_documentary:':$btdocu}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_ebook:':$btebook}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_game:':$btgame}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_pw:':$btpw}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_copyright:':$btcopyright}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_unknown:':''}{/capture}

{* Ok now it's time to put it all together: *}	
<tr class="content even {$interesting} {$read} {$nzb}" id="base_row_{$set.sid}" 
	onmouseover="javascript:ToggleClass(this,'highlight2')" 
	onmouseout="javascript:ToggleClass(this,'highlight2')">
	<td class="fixwidth1">{$set.number}
    
    {* We need this stuff to remember any the search options *}
    <input type="hidden" name="set_ids[]" value="{$set.sid}"/>
    </td>
	<td class="setbuttons">{$smallbuttons}</td>
    <td onmouseup="javascript:start_quickmenu('browse','{$set.sid}', {$USERSETTYPE_GROUP}, event);" id="td_set_{$set.sid}">
<div class="donotoverflowdamnit">{$setdesc}</div>
</td>
{$rating=$set.rating * 10}
{$imdbpic="ratingicon_$rating"}
{if $rating == ""}{$imdbpic="followicon"}{/if}

	<td class="fixwidth2a nowrap {if $set.new_set neq 0}newset{/if}">{$set.age}</td>
	<td class="fixwidth3 nowrap">{$set.size}</td>
	<td class="fixwidth1"> <div class="{$complete} iconsize noborder" {urd_popup type="small" text="$completion" }></div></td>
    <td class="fixwidth1">
    {if $set.imdblink neq ''}
    <div class="inline iconsize {$imdbpic} buttonlike" onclick="javascript:jump('{$set.imdblink|escape}', true);" {urd_popup type="small" text=$set.imdblink}></div>
	{elseif $set.rating neq 0}
    <div class="inline iconsize {$imdbpic} buttonlike"></div>
	{/if}</td>

	<td class="nowrap">
    <div class="floatright">
    {if $isadmin}
    <div class="inline iconsize purgeicon buttonlike" onclick="javascript:markRead('{$set.sid}', 'wipe', {$USERSETTYPE_GROUP})" {urd_popup type="small" text=$LN_browse_deleteset}></div>
    {/if}
    <div id="intimg_{$set.sid}" class="inline iconsize {$interestingimg} buttonlike" onclick="javascript:markRead('{$set.sid}', 'interesting', {$USERSETTYPE_GROUP})" {urd_popup type="small" text=$LN_browse_toggleint }></div>
    </div>
	</td>
</tr>
{foreachelse} 
{if $only_rows == 0}
<tr><td colspan="8" class="centered highlight textback">{$LN_error_nosetsfound}</td></tr>
{/if}
{/foreach}
{* Last bit: *}

{if $only_rows == 0}
    </table>
    {$bottomskipper}
<br/>
<input type="hidden" id="rss_url" value="{$rssurl|escape:quotes}"/>
<input type="hidden" id="killflag" value="{$killflag}"/>
<input type="hidden" id="deletedsets" value="{$LN_browse_deletedsets}"/>
<input type="hidden" id="deletedset" value="{$LN_browse_deletedset}"/>
<input type="hidden" id="last_line" value="{$set.number}"/>

{/if}


