{* Smarty *}{*
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
 * $LastChangedDate: 2013-09-11 00:48:12 +0200 (wo, 11 sep 2013) $
 * $Rev: 2925 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: rsssets.tpl 2925 2013-09-10 22:48:12Z gavinspearhead@gmail.com $
 *}
{include file="head.tpl" title=$title rssurl=$rssurl}
{* Search form *}
{capture assign="searchform"}
<form id="searchform" method="get">
<div>

{foreach $subscribedfeeds as $item}
<input type="hidden" id="ng_id_{$item.type}_{$item.id}" value="{$item.name|escape:htmlall}"/>
{/foreach}

<div id="advanced_search_button" class="floatleft iconsize dynimgplus noborder buttonlike" onclick="javascript:fold_adv_search('advanced_search_button', 'advanced_search');" {urd_popup type="small" text=$LN_advanced_search}>
</div>&nbsp;
    <input type="hidden" name="order" value="{$order}" id="searchorder"/>
	<input type="hidden" name="save_category" value="" id="save_category"/>
    <input type="button" value="&lt;" class="submitsmall" {urd_popup type="small" text=$LN_previous } onclick='javascript:select_next("select_feedid",-1);'/>&thinsp;
    <select name="feed_id" class="search" id="select_feedid">
    <option value="">{$LN_feeds_allgroups} ({$total_articles})</option>
    {foreach $subscribedfeeds as $item}
        {capture name=current assign=current}{$item.type}_{$item.id}{/capture}
		<option {if $current eq $feed_id && $feed_id neq '' }selected="selected"{/if} value="{$item.type}_{$item.id}"> 
            {if $item.type=='category'}{$LN_category}: {/if}{$item.name|escape:htmlall} ({$item.article_count})
        </option>
    {/foreach}
	</select>&thinsp;
    <input type="button" value="&gt;" class="submitsmall" {urd_popup type="small" text=$LN_next } onclick='javascript:select_next("select_feedid",1);' />&nbsp;
   	<input type="text" name="search" id="search" size="30" class="search" value="{if $search == ''}&lt;{$LN_search}&gt;{else}{$search|escape:htmlall}{/if}" onfocus="if (this.value=='&lt;{$LN_search}&gt;') this.value='';" onkeypress="javascript:submit_enter(event, load_sets, { 'offset':'0', 'setid':'', 'category':'' } );"/> &nbsp;
	<input type="hidden" value="" name="maxage"/>
    <input type="button" value="{$LN_search}" class="submitsmall" onclick="javascript:load_sets( { 'offset':'0', 'setid':'', 'category':'' } );"/>
    &nbsp; 
    
    &nbsp;

<span id="save_search_outer" class="{if count($saved_searches) == 0}hidden{/if}">
<input type="button" class="submitsmall" value="&lt;" {urd_popup text=$LN_next type="small"} onclick="javascript:select_next_search('saved_search',-1);"/>
<span id="save_search_span">
<select id="saved_search" onchange="javascript:update_browse_searches();">
<option value=""></option>
{foreach $saved_searches as $saved_search}
<option value="{$saved_search}" {if $saved_search == $_saved_search}selected="selected"{/if}>{$saved_search|escape}&nbsp;</option>
{/foreach}
</select>
</span>
<input type="button" class="submitsmall" value="&gt;" {urd_popup text=$LN_next type="small"} onclick="javascript:select_next_search('saved_search',1);"/>
</span>
&nbsp;
<div id="minibasketdiv" class="hidden"></div>

<div class="advanced_search hidden" id="advanced_search">
<table>

<tr>
<td>{$LN_setsize}:</td>
<td><input type="text" id="minsetsize" size="6" value="{$minsetsize}"/></td> 
<td><div id="setsize" style="width:100px;"></div></td>
<td><input type="text" id="maxsetsize" size="6" value="{$maxsetsize}"/></td>
<td>{$LN_age}:</td>
<td><input type="text" id="minage" name="minage" size="6" value="{$minage}"/></td> 
<td><div id="setage" style="width:100px;"></div></td>
<td><input type="text" id="maxage" name="maxage" size="6" value="{$maxage}"/></td>
</tr>
<tr>
<td>{$LN_rating}:</td>
<td><input type="text" id="minrating" name="minrating" size="6" value="{$minrating}"/></td> 
<td><div id="setrating" style="width:100px;"></div></td>
<td><input type="text" id="maxrating" name="maxrating" size="6" value="{$maxrating}"/></td>
<td>
 <select name="flag" class="search" id="flag">
		<option {if $flag == ''}selected="selected"{/if} value="">{$LN_browse_allsets}</option>
		<option {if $flag == 'interesting'}selected="selected"{/if} value="interesting">{$LN_browse_interesting}</option>
		<option {if $flag == 'read'}selected="selected"{/if} value="read">{$LN_browse_downloaded}</option>
{if $show_makenzb neq 0}
		<option {if $flag == 'nzb'}selected="selected"{/if} value="nzb">{$LN_browse_nzb}</option>
{/if}
		<option {if $flag == 'kill'}selected="selected"{/if} value="kill">{$LN_browse_killed}</option>
	</select>
</td>
<td></td>
<td>
	<input type="button" value="{$LN_reset}" class="submitsmall" onclick='javascript:clear_form("searchform");'/>
</td>
</tr>
</table>
</div>

</div>
</form>
{/capture}

{capture assign="rss_link"}
<div id="rss">
	<table class="rss"><tr><td class="rssleft"><a href="" id="rss_id" class="rss">RSS</a></td><td class="rssright">2.0</td></tr></table>
</div>
{/capture}

{$searchform}
{$rss_link}

{* Display the content: *}
<div id="basketdiv" class="down3"></div>

{* We need this stuff to remember any the search options *}
<div>
<input type="hidden" name="usersettype" id="usersettype" value="{$USERSETTYPE}"/>
<input type="hidden" name="offset" id="offset" value="{$offset}"/>
<input type="hidden" name="feed_id" id="feed_id" value="{$feed_id}"/>
<input type="hidden" name="setid" id="setid" value="{$setid}"/>
<input type="hidden" name="dlname" value=""/>
<input type="hidden" name="whichbutton" value="" id="whichbutton"/>
<input type="hidden" name="previewBinID" value="" id="previewBinID"/>
<input type="hidden" name="previewGroupID" value="" id="previewGroupID"/>
<input type="hidden" name="lastdivid" id="lastdivid" value=""/>
<input type="hidden" name="curScrollVal" id="curScrollVal" value=""/>
<input type="hidden" name="type" id="type" value="rss"/>
</div>

<div class="innerwaitingdiv" id="waitingdiv">
<div class="waitingimg centered"></div>
<p><br/></p>
<h3 class="centered">{$LN_loading}</h3>
</div>

<div id="setsdiv" class="hidden">
</div>

{* Load basket: *}
<script type="text/javascript">
$(document).ready(function() {
    init_slider({$minsetsizelimit}, {$maxsetsizelimit}, "#setsize", "#minsetsize", "#maxsetsize");
    init_slider({$minagelimit}, {$maxagelimit}, "#setage", "#minage", "#maxage");
    init_slider({$minratinglimit}, {$maxratinglimit}, "#setrating", "#minrating", "#maxrating");

    set_scroll_handler('#contentout', load_sets);

    update_basket_display();
    {if $feed_id != ''}
        load_sets( { 'next':'{$feed_id}' } );
    {else}
        load_sets();
    {/if}
});
</script>

<input type="hidden" id="ln_delete_search" value="{$LN_delete_search}"/>
<input type="hidden" id="perpage" value="{$perpage}"/>
<div>
&nbsp;<br/>
&nbsp;<br/>
&nbsp;<br/>
</div>

{include file="foot.tpl"}
