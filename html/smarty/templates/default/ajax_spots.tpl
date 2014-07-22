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
{* Smarty doesn't allow you to 'include' variables from another file... *}
{* Icon images: (Global variable ish) *}
{$btmovie="<img class=\"binicon\" src=\"$IMGDIR/bin_movie.png\" alt=\"\" width=\"48\" height=\"16\"/> "}
{$btmusic="<img class=\"binicon\" src=\"$IMGDIR/bin_music.png\" alt=\"\" width=\"48\" height=\"16\"/> "}
{$btimage="<img class=\"binicon\" src=\"$IMGDIR/bin_image.png\" alt=\"\" width=\"48\" height=\"16\"/> "}
{$btsoftw="<img class=\"binicon\" src=\"$IMGDIR/bin_software.png\" alt=\"\" width=\"48\" height=\"16\"/> "}
{$bttv="<img class=\"binicon\" src=\"$IMGDIR/bin_series.png\" alt=\"\" width=\"48\" height=\"16\"/> "}
{$btdocu="<img class=\"binicon\" src=\"$IMGDIR/bin_documentary.png\" alt=\"\" width=\"48\" height=\"16\"/> "}
{$btgame="<img class=\"binicon\" src=\"$IMGDIR/bin_games.png\" alt=\"\" width=\"48\" height=\"16\"/> "}
{$btebook="<img class=\"binicon\" src=\"$IMGDIR/bin_ebook.png\" alt=\"\" width=\"48\" height=\"16\"/> "}
{$btpw="<img class=\"binicon\" src=\"$IMGDIR/icon_pw.png\" width=\"16\" height=\"16\"/> "}
{$btcopyright="<img class=\"binicon\" src=\"$IMGDIR/icon_copy.png\" width=\"16\" height=\"16\"/> "}

{capture assign=topskipper}{strip}
{if $only_rows == 0}
{if count($pages) > 1}
{urd_skipper current=$currentpage last=$lastpage pages=$pages position=top js=set_offset extra_class="margin10"}
{else}<br/>
{/if}
{/if}
{/strip}
{/capture}

{* Making a 'top' and a 'bottom' skipper: *}
{capture assign=bottomskipper}{strip}
{if $only_rows == 0}
{if count($pages) > 1}
{urd_skipper current=$currentpage last=$lastpage pages=$pages position=bottom js=set_offset extra_class="margin10"}
{else}<br/>
{/if}
{/if}
{/strip}
{/capture}

{capture assign=unmark_int_all}{strip}
{if $killflag}
<div class="inline iconsizeplus killicon buttonlike" onclick="javascript:which_button('unmark_kill_all', event);" {urd_popup type="small" text=$LN_browse_resurrectset} ></div>
{else}
<div class="inline iconsizeplus deleteicon buttonlike" onclick="javascript:which_button('mark_kill_all', event);" {urd_popup type="small" text=$LN_browse_removeset } ></div>
{/if}
{if $isadmin}
<div class="inline iconsizeplus purgeicon buttonlike" onclick="javascript:which_button('wipe_all', event)" {urd_popup type="small" text=$LN_browse_deleteset} ></div>
{/if}
{if $isadmin}
<div class="inline iconsizeplus sadicon buttonlike" onclick="javascript:which_button('unmark_int_all', event);" {urd_popup type="small" text=$LN_browse_toggleint } ></div>
{/if}
{/strip}
{/capture}

{* And display it here and at the bottom: *}

{$up="<img src='$IMGDIR/small_up.png' width='9' height='6' alt=''>"}{$down="<img src='$IMGDIR/small_down.png' width='9' height='6' alt=''>"}
{if $sort.order == "title"} {if $sort.direction=='desc'}{$title_sort=$up} {else}{$title_sort=$down} {/if} {else}{$title_sort=""} {/if}
{if $sort.order == "stamp"} {if $sort.direction=='desc'}{$stamp_sort=$up} {else}{$stamp_sort=$down} {/if} {else}{$stamp_sort=""} {/if}
{if $sort.order == "size"} {if $sort.direction=='desc'}{$size_sort=$up} {else}{$size_sort=$down} {/if} {else}{$size_sort=""} {/if}

{capture assign=tableheader}
<table class="articles" id="spots_table">
<tr>
<th class="head round_left">&nbsp;</th>
<th class="head">&nbsp;</th>
<th id="browsesubjecttd" class="head buttonlike" onclick="javascript:change_sort_order('title');">{$LN_browse_subject} {$title_sort}</th>
<th class="head fixwidth1 buttonlike" onclick="javascript:change_sort_order('reports', 'desc');">{$LN_spamreporttag}</th>
<th class="head">{$LN_whitelisttag}</th>
{if $show_comments > 0}
<th class="head fixwidth1 buttonlike" onclick="javascript:change_sort_order('comments', 'desc');">#</th>
{/if}
<th class="fixwidth2a nowrap buttonlike head right" onclick="javascript:change_sort_order('stamp', 'desc');">{$LN_browse_age} {$stamp_sort}</th>
<th class="fixwidth3 nowrap buttonlike head right" onclick="javascript:change_sort_order('size', 'desc');">{$LN_size} {$size_sort}</th>
<th class="fixwidth1 buttonlike head right" onclick="javascript:change_sort_order('url');"><div class="inline iconsizeplus followicon buttonlike"></div>
</th>
<th class="nowrap head fixwidth4 round_right">{$unmark_int_all}</th>
</tr>
{/capture}

{if $only_rows == 0}
    {$topskipper}
    <div id="setstable">
    {$tableheader}
{/if}

{* Display the bunch: *}
{foreach from=$allsets item=set}

{capture assign=smallbuttons}{strip}	
{if !$set.added}
<div id="divset_{$set.sid}" class="setimgplus inline iconsize buttonlike" onclick="javascript:select_set('{$set.sid}', 'spot', event);return false;"></div>
<input type="hidden" name="setdata[]" id="set_{$set.sid}" value=""/>
{else}
<div id="divset_{$set.sid}" class="setimgminus inline iconsize buttonlike" onclick="javascript:select_set('{$set.sid}', 'spot', event);return false;"></div>
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

{* Remember this is a copy of formatsetname.tpl; included here for performance reasons (beats 100's of includes) *}
{capture assign=setdesc}{$set.name|escape:htmlall}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_pw:':$btpw}{/capture}
{capture assign=setdesc}{$setdesc|replace:':_img_copyright:':$btcopyright}{/capture}

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
<tr class="content even {$interesting} {$read} {$nzb}" id="base_row_{$set.sid}" 
	onmouseover="javascript:$(this).toggleClass('highlight2');" 
	onmouseout="javascript:$(this).toggleClass('highlight2');">
	<td class="fixwidth1">{$set.number}
<input type="hidden" name="set_ids[]" value="{$set.sid}"/>
    </td>
	<td class="setbuttons">{$smallbuttons}</td>

<td onmouseup="javascript:start_quickmenu('browse','{$set.sid}', {$USERSETTYPE_SPOT}, event);" id="td_set_{$set.sid}" {if $show_subcats}{urd_popup text="$subcats" caption="$LN_spots_subcategories" }{/if}>
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
{elseif $set.categorynr eq 0}{$btmovie}
{elseif $set.categorynr eq 1}{$btmusic}
{elseif $set.categorynr eq 2}{$btgame}
{elseif $set.categorynr eq 3}{$btsoftw}
{/if}
{$rating=$set.rating * 10}
{$linkpic="ratingicon_$rating"}
{if $rating == ""}{$linkpic="followicon"}{/if}

    <div class="inline">{$setdesc}</div>
    </td>
    <td class="width20">
    {if $set.reports gt 0}{$spamreports=$set.reports}<div {urd_popup type="small" text="$spamreports $LN_spam_reports"} class="highlight_spam inline center width15">{$set.reports}</div>{/if}
    </td>
    <td class="width20">
    {if $set.whitelisted}{$setwhitelisted=$set.whitelisted}{$poster=$set.poster}<div {urd_popup type="small" text="$LN_browse_userwhitelisted:<br> $poster (<i>{$setwhitelisted}</i>)"} class="highlight_whitelist inline center width15">{$LN_whitelisttag}</div>{/if}
    </div>
    </td>
    {if $show_comments > 0}
    <td class="width32">
        {$setcomments=$set.comments}
        <div class="inline highlight_comments center width25" {urd_popup type="small" text="$setcomments $LN_browse_tag_note"}>{$set.comments}</div>
    </td>
    {/if}
    </div>

<td class="fixwidth2a nowrap {if $set.new_set neq 0}newset{/if}">{$set.age}</td>
<td class="fixwidth3 nowrap">{$set.size}</td>
<td class="fixwidth1">
    
    {if $set.url neq ''}
    <div class="inline iconsize {$linkpic} buttonlike" onclick="javascript:jump('{$set.url|escape:javascript}', true);" {urd_popup type="small" text=$set.url|escape:htmlall}></div>
	{elseif $set.rating neq 0}
    <div class="inline iconsize {$linkpic}"></div>
{else}&nbsp;
	{/if}
    </td>  
	<td class="nowrap">
    <div class="floatright">
    {if $isadmin}
    <div class="inline iconsize purgeicon buttonlike" onclick="javascript:mark_read('{$set.sid}', 'wipe', {$USERSETTYPE_SPOT})" {urd_popup type="small" text=$LN_browse_deleteset}></div>
    {/if}
    <div id="intimg_{$set.sid}" class="inline iconsize {$interestingimg} buttonlike" onclick="javascript:mark_read('{$set.sid}', 'interesting', {$USERSETTYPE_SPOT})" {urd_popup type="small" text=$LN_browse_toggleint }></div>
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
{if count($allsets) > 12}
<tr><td colspan="{if $show_comments > 0}9{else}8{/if}" class="feet round_left_bottom">&nbsp;</td>
<td class="nowrap feet round_right_bottom">{$unmark_int_all}</td>
</tr>
{/if}

</table>
</div>
{$bottomskipper}
<br/>

<input type="hidden" id="last_line" value="{$set.number}"/>
<input type="hidden" id="rss_url" value="{$rssurl|escape:quotes}"/>
<input type="hidden" id="killflag" value="{$killflag|escape}"/>
<input type="hidden" id="deletedsets" value="{$LN_browse_deletedsets}"/>
<input type="hidden" id="deletedset" value="{$LN_browse_deletedset}"/>
{/if}
