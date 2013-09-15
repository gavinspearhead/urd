{* Smarty *}{*
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
 * $LastChangedDate: 2013-08-18 00:17:37 +0200 (zo, 18 aug 2013) $
 * $Rev: 2900 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: browse.tpl 2900 2013-08-17 22:17:37Z gavinspearhead@gmail.com $
 *}
{include file="head.tpl" title=$title rssurl=$rssurl stylesheet=$stylesheet}

{* Search form *}
{capture assign="searchform"}
<form id="searchform" method="get">
<div id="ng_subscribedgroups">
{foreach $subscribedgroups as $item}
<input type="hidden" id="ng_id_{$item.type}_{$item.id}" value="{$item.shortname|escape:htmlall}"/>
{/foreach}
</div>

<div id="advanced_search_button" class="floatleft iconsize dynimgplus buttonlike" onclick="javascript:fold_adv_search('advanced_search_button', 'advanced_search');" {urd_popup type="small" text=$LN_advanced_search }>
</div>&nbsp;
	&nbsp;
	<input type="hidden" name="order" value="{$order}" id="searchorder"/>
	<input type="hidden" name="save_category" value="" id="save_category"/>
    <input type="button" class="submitsmall" value="&lt;" {urd_popup text=$LN_previous type="small"} onclick='javascript:select_next("select_groupid",-1);'/>&nbsp;
	<select name="groupID" class="search" id="select_groupid" >
    <option value="">{$LN_browse_allgroups} ({$total_articles})</option>
    {foreach $subscribedgroups as $item}
        {capture name=current assign=current}{$item.type}_{$item.id}{/capture}
		<option {if $current == $groupID && $groupID != 0 }selected="selected"{/if} value="{$item.type}_{$item.id}">
            {if $item.type=='category'}{$LN_category}: {/if}{$item.shortname|escape:htmlall} ({$item.article_count})
        </option>
	{/foreach}
	</select>&nbsp;

    <input type="button" class="submitsmall" value="&gt;" {urd_popup text=$LN_next type="small"} onclick='javascript:select_next("select_groupid",1);'/>&nbsp;
    &nbsp;

<input type="text" id="search" name="search" size="30" class="search" value="{if $search == ''}&lt;{$LN_search}&gt;{else}{$search|escape:htmlall}{/if}" onfocus="if (this.value=='&lt;{$LN_search}&gt;') this.value='';" onkeypress="javascript:submit_enter(event, load_sets, { 'offset':'0', 'setid':'', 'category':'' } );"/>&nbsp;
<input type="button" value="{$LN_search}" class="submitsmall" onclick="javascript:clean_search('search');load_sets( { 'offset':'0', 'setid':'', 'category':'' } ); return false;" />
&nbsp;
&nbsp;
&nbsp;

<span id="save_search_outer" class="{if count($saved_searches) == 0}hidden{/if}">
<input type="button" class="submitsmall " value="&lt;" {urd_popup text=$LN_previous type="small"} onclick="javascript:select_next_search('saved_search',-1);"/> 
<span id="save_search_span">
<select id="saved_search" onchange="javascript:update_browse_searches(null);">
<option value=""></option>
{foreach $saved_searches as $saved_search}
<option value="{$saved_search}" {if $saved_search == $_saved_search}selected="selected"{/if}>{$saved_search|escape}&nbsp;</option>
{/foreach}
</select>
</span> 
<input type="button" class="submitsmall" value="&gt;" {urd_popup text=$LN_next type="small"} onclick="javascript:select_next_search('saved_search',1);"/>
</span> 

<div id="minibasketdiv" class="hidden"></div>

<div class="advanced_search hidden" id="advanced_search">
<table>
<tr>
<td>{$LN_setsize}:</td>
<td><input type="text" id="minsetsize"  size="6" value="{$minsetsize}"/></td> 
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
<td>{$LN_complete}:</td>
<td><input type="text" id="mincomplete" name="mincomplete" size="6" value="{$mincomplete}"/></td> 
<td><div id="setcomplete" style="width:100px;"></div></td>
<td><input type="text" id="maxcomplete" name="maxcomplete" size="6" value="{$maxcomplete}"/></td>
</tr>

<tr>
<td></td>
<td>
<select name="flag" class="search" id="flag">
    <option {if $flag == ''} selected="selected" {/if} value="">{$LN_browse_allsets}</option>
    <option {if $flag == 'interesting'} selected="selected" {/if} value="interesting">{$LN_browse_interesting}</option>
    <option {if $flag == 'read'} selected="selected" {/if} value="read">{$LN_browse_downloaded}</option>
{if $show_makenzb neq 0}
<option {if $flag == 'nzb'} selected="selected" {/if} value="nzb">{$LN_browse_nzb}</option>
{/if}
<option {if $flag == 'kill'} selected="selected" {/if} value="kill">{$LN_browse_killed}</option>
</select>&nbsp;
</td>
<td colspan="5"></td>
<td><input type="button" value="{$LN_reset}" class="submitsmall" onclick='javascript:clear_form("searchform");'/></td>
</tr>

</table>
</div>
</form>
{/capture}

{capture assign="rss_link"}
<div id="rss"><table class="rss"><tr><td class="rssleft"><a href="rss.php" id="rss_id" class="rss">RSS</a></td><td class="rssright">2.0</td></tr></table> </div>
{/capture}

{$rss_link}
{$searchform}

<form method="post" id="setform">
<div id="basketdiv" class="down3"></div>

{* We need this stuff to remember any the search options *}
<div>
<input type="hidden" id="save_name" value=""/> 
<input type="hidden" name="offset" id="offset" value="{$offset}"/>
<input type="hidden" name="setid" id="setid" value="{$setid}"/>
<input type="hidden" name="group_id" id="group_id" value="{$groupID}"/>
<input type="hidden" name="dlname" id="dlname" value=""/>
<input type="hidden" name="whichbutton" value="" id="whichbutton"/>
<input type="hidden" name="previewBinID" value="" id="previewBinID"/>
<input type="hidden" name="previewGroupID" value="" id="previewGroupID"/>
<input type="hidden" name="lastdivid" id="lastdivid" value=""/>
<input type="hidden" name="curScrollVal" id="curScrollVal" value=""/>
<input type="hidden" name="type" id="type" value="groups"/>
<input type="hidden" name="usersettype" id="usersettype" value="{$USERSETTYPE}"/>
</div>
</form>

{* And display it here and at the bottom: *}
<div class="innerwaitingdiv" id="waitingdiv">
<div class="waitingimg centered"></div>
<p><br/></p>
<h3 class="centered">{$LN_loading}</h3>
</div>

<div id="setsdiv" class="hidden">
</div>

<script type="text/javascript">
$(document).ready(function() {
   init_slider({$minsetsizelimit}, {$maxsetsizelimit}, "#setsize", "#minsetsize", "#maxsetsize");
   init_slider({$minagelimit}, {$maxagelimit}, "#setage", "#minage", "#maxage");
   init_slider({$minratinglimit}, {$maxratinglimit}, "#setrating", "#minrating", "#maxrating");
   init_slider({$mincompletelimit}, {$maxcompletelimit}, "#setcomplete", "#mincomplete", "#maxcomplete");

    {if $groupID != ''}
        load_sets( { 'next':'{$groupID}' } );
    {else}
        load_sets();
    {/if}

    set_scroll_handler('#contentout', load_sets);
    {* Load basket: *}
    update_basket_display();

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
