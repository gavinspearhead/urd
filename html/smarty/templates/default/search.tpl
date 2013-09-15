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
 * $LastChangedDate: 2013-09-03 16:28:23 +0200 (di, 03 sep 2013) $
 * $Rev: 2910 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: search.tpl 2910 2013-09-03 14:28:23Z gavinspearhead@gmail.com $
 *}
{include file="head.tpl" title=$title}

{* Search form *}
<div id="textcontent">
<div class="urdlogo2 floatright noborder buttonlike" onclick="javascript:jump('index.php');"></div>

{if $show_spots eq 1} 
{capture assign="subcatdivs"}
{foreach $spot_subcats as $k1=>$item}

<div id="subcat_selector_{$k1}" class="subcat_selector hidden">
<div class="closebutton buttonlike noborder fixedright down5" onclick="javascript:close_subcat_selector();" ></div>
<div class="set_title centered">{$LN_spots_subcategories} - {$item.name}</div>
<div class="reset_button buttonlike on_top"><input type="button" value="{$LN_reset}" onclick="javascript:clear_all_checkboxes({$k1});" class="submitsmall"/></div>
<div class="internal_subcat_selector">
{foreach $item.subcats as $k2=>$si}

<table class="subcat">
<tr><td onclick="javascript:fold_adv_search('subcat_button_{$k1}{$k2}', 'subcat_items_{$k1}{$k2}');" class="subcat_head">
<div id="subcat_button_{$k1}{$k2}" class="floatleft iconsize dynimgplus buttonlike"></div>&nbsp;
{$si.name}
</td></tr>
</table>

<table id="subcat_items_{$k1}{$k2}" class="hidden subcat">
<tr>
{$cnt="0"}
{foreach $si.subcats as $k3=>$item2 }
{if $item2 != '??'}
{if $cnt == 3}</tr><tr>
{$cnt="0"}
{/if}
<td class="subcat">
{urd_checkbox value="0" name="subcat_{$k1}_{$k2}_{$k3}" id="subcat_{$k1}_{$k2}_{$k3}" data="$item2" tristate="1" } 
</td>
{$cnt="`$cnt+1`"}
{/if}
{/foreach}
</tr>            
</table>
{/foreach}
</div>
<div id="save_subcat_{$k1}" class="save_subcat">
</div>

</div>
{/foreach}
{/capture}


<h3 class="title">{$LN_menuspotssearch}</h3>
<form id="searchform3" action="spots.php" method="post">
<table class="search">
<tr><td>
	{$LN_browse_searchsets}:&nbsp;
    </td>
    <td>
	<select name="categoryID" class="search" id="select_catid" onchange='javascript:do_select_subcat();'>
    <option value="">{$LN_spots_allcategories} ({$spots_total_articles})</option>
    {foreach $spot_categories as $item}
		<option value="{$item.id}">
            {$item.name|escape:htmlall} ({$item.article_count})
        </option>
	{/foreach}
	</select>&nbsp;
</td>
<td>

    {$subcatdivs}
    <input type="button" id="subcatbutton" class="submitsmall invisible" value="{$LN_spots_subcategories}" onclick="javascript:show_subcat_selector();" />&nbsp;
</td>
<td>
<select name="flag" class="search" id="flag">
    <option selected="selected" value="">{$LN_browse_allsets}</option>
    <option value="interesting">{$LN_browse_interesting}</option>
    <option value="read">{$LN_browse_downloaded}</option>
{if $show_makenzb neq 0}
<option value="nzb">{$LN_browse_nzb}</option>
{/if}
<option value="kill">{$LN_browse_killed}</option>
</select>&nbsp;
</td>
<td>
<input type="text" id="search" name="search" size="30" class="search" value="&lt;{$LN_search}&gt;" 
onfocus="if (this.value=='&lt;{$LN_search}&gt;') this.value='';" onkeypress="javascript:submit_enter(event, load_sets, { 'offset':'0', 'setid':'' } );"/>&nbsp;
</td>
</tr>
<tr>
<td>{$LN_setsize}:</td>
<td><input type="text" id="spotminsetsize" name="minsetsize"  size="6" value="{$spotminsetsize}"/></td> 
<td><div id="spotsetsize" style="width:100px;"></div></td>
<td><input type="text" id="spotmaxsetsize" name="maxsetsize" size="6" value="{$spotmaxsetsize}"/></td>
</tr>
<tr>
<td>{$LN_age}:</td>
<td><input type="text" id="spotminage" name="minage" size="6" value="{$spotminagelimit}"/></td> 
<td><div id="spotsetage" style="width:100px;"></div></td>
<td><input type="text" id="spotmaxage" name="maxage" size="6" value="{$spotmaxagelimit}"/></td>
</tr>
<tr>
<td>{$LN_rating}:</td>
<td><input type="text" id="spotminrating" name="minrating" size="6" value="{$spotminratinglimit}"/></td> 
<td><div id="spotrating" style="width:100px;"></div></td>
<td><input type="text" id="spotmaxrating" name="maxrating" size="6" value="{$spotmaxratinglimit}"/></td>
</tr>

<tr>
<td>
<input type="submit" value="{$LN_search}" class="submitsmall" onclick='javascript:do_submit("searchform3");'/>
&nbsp;&nbsp;
<input type="button" value="{$LN_reset}" class="submitsmall" onclick='javascript:clear_form("searchform3");do_select_subcat();'/>
</td>
</tr>
</table>
</form>

<p></p>

<p>&nbsp;</p>
{/if}


{if $show_groups neq 0} 
<h3 class="title">{$LN_menugroupsearch}</h3>
<form id="searchform1" action="browse.php" method="get">
<table class="search">
<tr><td>
	{$LN_browse_searchsets}:
    </td>
    <td colspan="2">
	<select name="groupID" class="search" id="select_groupid" >
    <option value="">{$LN_browse_allgroups} ({$groups_total_articles})</option>
    {foreach $subscribedgroups as $item}
        {capture name=current assign=current}{$item.type}_{$item.id}{/capture}
		<option value="{$item.type}_{$item.id}">{if $item.type=='category'}{$LN_category}: {/if}{$item.shortname|escape:htmlall} {if $item.type=='group'}({$item.article_count}){/if}
</option>
	{/foreach}
	</select>
    </td>

    <td>
    <select name="flag" class="search">
		<option selected="selected" value="">{$LN_browse_allsets}</option>
		<option value="interesting">{$LN_browse_interesting}</option>
		<option value="read">{$LN_browse_downloaded}</option>
		<option value="nzb">{$LN_browse_nzb}</option>
		<option value="kill">{$LN_browse_killed}</option>
	</select>
    </td>
    <td>
	<input type="text" name="search" size="30" class="search" value="&lt;{$LN_search}&gt;" onfocus="if (this.value=='&lt;{$LN_search}&gt;') this.value='';" onkeypress="javascript:submit_enter(event,do_submit, 'searchform2');"/>
    </td>

</tr>
<tr>
<td>{$LN_setsize}:</td>
<td><input type="text" id="groupminsetsize" name="minsetsize" size="6" value="{$groupminsetsizelimit}"/></td> 
<td><div id="groupsetsize" style="width:100px;"></div></td>
<td><input type="text" id="groupmaxsetsize" name="maxsetsize" size="6" value="{$groupmaxsetsizelimit}"/></td>
</tr>
<tr>
<td>{$LN_age}:</td>
<td><input type="text" id="groupminage" name="minage" size="6" value="{$groupminagelimit}"/></td> 
<td><div id="groupsetage" style="width:100px;"></div></td>
<td><input type="text" id="groupmaxage" name="maxage" size="6" value="{$groupmaxagelimit}"/></td>
</tr>
<tr>
<td>{$LN_rating}:</td>
<td><input type="text" id="groupminrating" name="minrating" size="6" value="{$groupminratinglimit}"/></td> 
<td><div id="groupsetrating" style="width:100px;"></div></td>
<td><input type="text" id="groupmaxrating" name="maxrating" size="6" value="{$groupmaxratinglimit}"/></td>
</tr>
<tr>
<td>{$LN_complete}:</td>
<td><input type="text" id="groupmincomplete" name="mincomplete" size="6" value="{$groupmincompletelimit}"/></td> 
<td><div id="groupsetcomplete" style="width:100px;"></div></td>
<td><input type="text" id="groupmaxcomplete" name="maxcomplete" size="6" value="{$groupmaxcompletelimit}"/></td>
</tr>

<tr>
<td>
<input type="submit" value="{$LN_search}" class="submitsmall" onclick='javascript: do_submit("searchform1");'/>
&nbsp;&nbsp;
<input type="button" value="{$LN_reset}" class="submitsmall" onclick='javascript:clear_form("searchform1");'/>
&nbsp;&nbsp;
</td></tr>
</table>
</form>
<p></p>

<p>&nbsp;</p>
{/if}

{if $show_rss neq 0} 
<h3 class="title">{$LN_menursssearch}</h3>
<form id="searchform2" action="rsssets.php" method="post">
<table class="search">
<tr><td>
	{$LN_browse_searchsets}:
    </td>
    <td>
    <select name="feed_id" class="search" id="select_feedid">
    <option value="">{$LN_feeds_allgroups} ({$rss_total_articles})</option>
    {foreach $subscribedfeeds as $item}
        {capture name=current assign=current}{$item.type}_{$item.id}{/capture}
		<option value="{$item.type}_{$item.id}">{if $item.type=='category'}{$LN_category}: {/if}{$item.name|escape:htmlall} ({$item.article_count})</option>
    {/foreach}
	</select>
    </td>
    <td>
    <select name="flag" class="search">
		<option selected="selected" value="">{$LN_browse_allsets}</option>
		<option value="interesting">{$LN_browse_interesting}</option>
		<option value="read">{$LN_browse_downloaded}</option>
		<option value="nzb">{$LN_browse_nzb}</option>
		<option value="kill">{$LN_browse_killed}</option>
	</select>
    </td>
    <td>

	<input type="text" name="search" size="30" class="search" value="&lt;{$LN_search}&gt;" onfocus="if (this.value=='&lt;{$LN_search}&gt;') this.value='';" onkeypress="javascript:submit_enter(event,do_submit, 'searchform2');"/>
	<input type="hidden" value="" name="maxage"/>
    </td>
    </tr>
<tr>
<td>{$LN_setsize}:</td>
<td><input type="text" id="rssminsetsize" size="6" name="minsetsize" value="{$rssminsetsizelimit}"/></td> 
<td><div id="rsssetsize" style="width:100px;"></div></td>
<td><input type="text" id="rssmaxsetsize" size="6" name="maxsetsize" value="{$rssmaxsetsizelimit}"/></td>
</tr>
<tr>
<td>{$LN_age}:</td>
<td><input type="text" id="rssminage" name="minage" size="6" value="{$rssminagelimit}"/></td> 
<td><div id="rsssetage" style="width:100px;"></div></td>
<td><input type="text" id="rssmaxage" name="maxage" size="6" value="{$rssmaxagelimit}"/></td>
</tr>
<tr>
<td>{$LN_rating}:</td>
<td><input type="text" id="rssminrating" name="minrating" size="6" value="{$rssminratinglimit}"/></td> 
<td><div id="rsssetrating" style="width:100px;"></a></div></td>
<td><input type="text" id="rssmaxrating" name="maxrating" size="6" value="{$rssmaxratinglimit}"/></td>
<td>

<tr>
<td>
<input type="submit" value="{$LN_search}" class="submitsmall" onclick='javascript:do_submit("searchform2");'/>
&nbsp;&nbsp;
<input type="button" value="{$LN_reset}" class="submitsmall" onclick='javascript:clear_form("searchform2");'/>
</td>
</tr>
</table>
</form>

<p>&nbsp;</p>

{/if}
</div>

<script type="text/javascript">

$(document).ready(function() {
{if $show_spots eq 1} 
       init_slider({$spotminsetsizelimit}, {$spotmaxsetsizelimit}, "#spotsetsize", "#spotminsetsize", "#spotmaxsetsize");
       init_slider({$spotminagelimit}, {$spotmaxagelimit}, "#spotsetage", "#spotminage", "#spotmaxage");
       init_slider({$spotminratinglimit}, {$spotmaxratinglimit}, "#spotrating", "#spotminrating", "#spotmaxrating");
{/if}
{if $show_rss eq 1} 
       init_slider({$rssminsetsizelimit}, {$rssmaxsetsizelimit}, "#rsssetsize", "#rssminsetsize", "#rssmaxsetsize");
       init_slider({$rssminagelimit}, {$rssmaxagelimit}, "#rsssetage", "#rssminage", "#rssmaxage");
       init_slider({$rssminratinglimit}, {$rssmaxratinglimit}, "#rsssetrating", "#rssminrating", "#rssmaxrating");
{/if}
{if $show_groups eq 1} 
       init_slider({$groupminsetsizelimit}, {$groupmaxsetsizelimit}, "#groupsetsize", "#groupminsetsize", "#groupmaxsetsize");
       init_slider({$groupminagelimit}, {$groupmaxagelimit}, "#groupsetage", "#groupminage", "#groupmaxage");
       init_slider({$groupminratinglimit}, {$groupmaxratinglimit}, "#groupsetrating", "#groupminrating", "#groupmaxrating");
       init_slider({$groupmincompletelimit}, {$groupmaxcompletelimit}, "#groupsetcomplete", "#groupmincomplete", "#groupmaxcomplete");
{/if}
});

</script>

{include file="foot.tpl"}
