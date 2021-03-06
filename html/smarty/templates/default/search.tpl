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
 * $LastChangedDate: 2014-06-12 23:24:27 +0200 (do, 12 jun 2014) $
 * $Rev: 3089 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: search.tpl 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 *}

{extends file="head.tpl"}
{block name=contents}
{* Search form *}
<div id="textcontent">
<div class="urdlogo2 floatright noborder buttonlike" onclick="javascript:jump('index.php');"></div>

{if $show_spots == 1} 
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
{foreach $si.subcats as $k3=>$item2}
{if $item2 != '??'}
{if $cnt == 3}</tr><tr>
{$cnt="0"}
{/if}
<td class="subcat">
{urd_checkbox value="0" name="subcat_{$k1}_{$k2}_{$k3}" id="subcat_{$k1}_{$k2}_{$k3}" data="$item2" tristate="1"} 
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
<tr><td class="nowrap bold">
	{$LN_browse_searchsets}:&nbsp;
    </td>
    <td colspan="2">
	<select name="categoryID" class="search textbox18m" id="select_catid" onchange='javascript:do_select_subcat();'>
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
{if $show_makenzb != 0}
<option value="nzb">{$LN_browse_nzb}</option>
{/if}
<option value="kill">{$LN_browse_killed}</option>
</select>&nbsp;
</td>
<td>
<input type="text" id="search_spots" name="search" class="search textbox18m" placeholder="{$LN_search}" 
 onkeypress="javascript:submit_enter(event, load_sets, { 'offset':'0', 'setid':'' } );"/>&nbsp;
<div class="hidden suggest" id="suggest_div_spots"></div>
</td>
</tr>
<tr>
<td class="nowrap bold">{$LN_setsize}:</td>
<td><input type="text" id="spotminsetsize" name="minsetsize" class="textbox4m" value="{$spotminsetsize|escape}"/></td> 
<td><div id="spotsetsize" class="slider"></div></td>
<td><input type="text" id="spotmaxsetsize" name="maxsetsize" class="textbox4m" value="{$spotmaxsetsize|escape}"/></td>
</tr>
<tr>
<td class="nowrap bold">{$LN_age}:</td>
<td><input type="text" id="spotminage" name="minage" class="textbox4m" value="{$spotminagelimit|escape}"/></td> 
<td><div id="spotsetage" class="slider"></div></td>
<td><input type="text" id="spotmaxage" name="maxage" class="textbox4m" value="{$spotmaxagelimit|escape}"/></td>
</tr>
<tr>
<td class="nowrap bold">{$LN_rating}:</td>
<td><input type="text" id="spotminrating" name="minrating" class="textbox4m" value="{$spotminratinglimit|escape}"/></td> 
<td><div id="spotrating" class="slider"></div></td>
<td><input type="text" id="spotmaxrating" name="maxrating" class="textbox4m" value="{$spotmaxratinglimit|escape}"/></td>
</tr>
<tr>
<td>
<input type="button" value="{$LN_search}" class="submitsmall" onclick='javascript:do_submit("searchform3");'/>
&nbsp;&nbsp;
<input type="button" value="{$LN_reset}" class="submitsmall" onclick='javascript:clear_form("searchform3");do_select_subcat();'/>
&nbsp;&nbsp;
</td>
</tr>
</table>
</form>

<p></p>

<p>&nbsp;</p>
{/if}

{if $show_groups != 0} 
<h3 class="title">{$LN_menugroupsearch}</h3>
<form id="searchform1" action="browse.php" method="get">
<table class="search">
<tr><td class="nowrap bold">
	{$LN_browse_searchsets}:
    </td>
    <td colspan="2">
	<select name="groupID" class="search textbox18m" id="select_groupid">
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
	<input type="text" id="search_groups" name="search" class="search textbox18m" placeholder="{$LN_search}"/>
    <div class="hidden suggest" id="suggest_div_groups"></div>
    </td>

</tr>
<tr>
<td class="nowrap bold">{$LN_setsize}:</td>
<td><input type="text" id="groupminsetsize" name="minsetsize"class="textbox4m"  value="{$groupminsetsizelimit|escape}"/></td> 
<td><div id="groupsetsize" class="slider"></div></td>
<td><input type="text" id="groupmaxsetsize" name="maxsetsize" class="textbox4m" value="{$groupmaxsetsizelimit|escape}"/></td>
</tr>
<tr>
<td class="nowrap bold">{$LN_age}:</td>
<td><input type="text" id="groupminage" name="minage" class="textbox4m" value="{$groupminagelimit|escape}"/></td> 
<td><div id="groupsetage" class="slider"></div></td>
<td><input type="text" id="groupmaxage" name="maxage" class="textbox4m" value="{$groupmaxagelimit|escape}"/></td>
</tr>
<tr>
<td class="nowrap bold">{$LN_rating}:</td>
<td><input type="text" id="groupminrating" name="minrating" class="textbox4m" value="{$groupminratinglimit|escape}"/></td> 
<td><div id="groupsetrating" class="slider"></div></td>
<td><input type="text" id="groupmaxrating" name="maxrating" class="textbox4m" value="{$groupmaxratinglimit|escape}"/></td>
</tr>
<tr>
<td class="nowrap bold">{$LN_complete}:</td>
<td><input type="text" id="groupmincomplete" name="mincomplete" class="textbox4m" value="{$groupmincompletelimit|escape}"/></td> 
<td><div id="groupsetcomplete" class="slider"></div></td>
<td><input type="text" id="groupmaxcomplete" name="maxcomplete" class="textbox4m" value="{$groupmaxcompletelimit|escape}"/></td>
</tr>

<tr>
<td>
<input type="button" value="{$LN_search}" class="submitsmall" onclick='javascript:do_submit("searchform1");'/>
&nbsp;&nbsp;
<input type="button" value="{$LN_reset}" class="submitsmall" onclick='javascript:clear_form("searchform1");'/>
&nbsp;&nbsp;
</td></tr>
</table>
</form>
<p></p>

<p>&nbsp;</p>
{/if}

{if $show_rss != 0} 
<h3 class="title">{$LN_menursssearch}</h3>
<form id="searchform2" action="rsssets.php" method="post">
<table class="search">
<tr><td class="nowrap bold">
	{$LN_browse_searchsets}:
    </td>
    <td colspan="2">
    <select name="feed_id" class="search textbox18m" id="select_feedid">
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

	<input type="text" id="search_rss" name="search" class="textbox18m search" placeholder="{$LN_search}"/>
    <div class="hidden suggest" id="suggest_div_rss"></div>
	<input type="hidden" value="" name="maxage"/>
    </td>
    </tr>
<tr>
<td class="nowrap bold">{$LN_setsize}:</td>
<td><input type="text" id="rssminsetsize" class="textbox4m" name="minsetsize" value="{$rssminsetsizelimit|escape}"/></td> 
<td><div id="rsssetsize" class="slider"></div></td>
<td><input type="text" id="rssmaxsetsize" class="textbox4m" name="maxsetsize" value="{$rssmaxsetsizelimit|escape}"/></td>
</tr>
<tr>
<td class="nowrap bold">{$LN_age}:</td>
<td><input type="text" id="rssminage" name="minage" class="textbox4m" value="{$rssminagelimit|escape}"/></td> 
<td><div id="rsssetage" class="slider"></div></td>
<td><input type="text" id="rssmaxage" name="maxage" class="textbox4m" value="{$rssmaxagelimit|escape}"/></td>
</tr>
<tr>
<td class="nowrap bold">{$LN_rating}:</td>
<td><input type="text" id="rssminrating" name="minrating" class="textbox4m" value="{$rssminratinglimit|escape}"/></td> 
<td><div id="rsssetrating" class="slider"></div></td>
<td><input type="text" id="rssmaxrating" name="maxrating" class="textbox4m" value="{$rssmaxratinglimit|escape}"/></td>

<tr>
<td>
<input type="submit" value="{$LN_search}" class="submitsmall" onclick='javascript:do_submit("searchform2");'/>
&nbsp;&nbsp;
<input type="button" value="{$LN_reset}" class="submitsmall" onclick='javascript:clear_form("searchform2");'/>
&nbsp;&nbsp;
</td>
</tr>
</table>
</form>

<p>&nbsp;</p>

{/if}
</div>

<script type="text/javascript">

$(document).ready(function() {
    {if $show_spots == 1} 
       init_slider({$spotminsetsizelimit}, {$spotmaxsetsizelimit}, "#spotsetsize", "#spotminsetsize", "#spotmaxsetsize");
       init_slider({$spotminagelimit}, {$spotmaxagelimit}, "#spotsetage", "#spotminage", "#spotmaxage");
       init_slider({$spotminratinglimit}, {$spotmaxratinglimit}, "#spotrating", "#spotminrating", "#spotmaxrating");
       $('#search_spots').keyup( function (e) { suggest( {$USERSETTYPE_SPOT},'suggest_div_spots', $('#search_spots')) } );
       $('#search_spots').attr( 'autocomplete', 'off' );
    {/if}
    {if $show_rss == 1} 
       init_slider({$rssminsetsizelimit}, {$rssmaxsetsizelimit}, "#rsssetsize", "#rssminsetsize", "#rssmaxsetsize");
       init_slider({$rssminagelimit}, {$rssmaxagelimit}, "#rsssetage", "#rssminage", "#rssmaxage");
       init_slider({$rssminratinglimit}, {$rssmaxratinglimit}, "#rsssetrating", "#rssminrating", "#rssmaxrating");
       $('#search_rss').keypress( function (e) { submit_enter(e, do_submit, 'searchform2'); } );
       $('#search_rss').keyup( function (e) { suggest({$USERSETTYPE_RSS}, 'suggest_div_rss', $('#search_rss'))  } );
       $('#search_rss').attr( 'autocomplete', 'off' );
    {/if}
    {if $show_groups == 1} 
       init_slider({$groupminsetsizelimit}, {$groupmaxsetsizelimit}, "#groupsetsize", "#groupminsetsize", "#groupmaxsetsize");
       init_slider({$groupminagelimit}, {$groupmaxagelimit}, "#groupsetage", "#groupminage", "#groupmaxage");
       init_slider({$groupminratinglimit}, {$groupmaxratinglimit}, "#groupsetrating", "#groupminrating", "#groupmaxrating");
       init_slider({$groupmincompletelimit}, {$groupmaxcompletelimit}, "#groupsetcomplete", "#groupmincomplete", "#groupmaxcomplete");
       $('#search_groups').keypress( function (e) { submit_enter(e, do_submit, 'searchform1'); } );
       $('#search_groups').keyup( function (e) { suggest({$USERSETTYPE_GROUP}, 'suggest_div_groups', $('#search_groups')) } );
       $('#search_groups').attr( 'autocomplete', 'off' );
    {/if}
});

</script>
{/block}
