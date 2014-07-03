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
 * $LastChangedDate: 2011-01-15 01:03:01 +0100 (Sat, 15 Jan 2011) $
 * $Rev: 2027 $
 * $Author: gavinspearhead $
 * $Id: browse.tpl 2027 2011-01-15 00:03:01Z gavinspearhead $
 *}
{include file="head.tpl" title=$title rssurl=$rssurl stylesheet=$stylesheet}
{capture assign="subcatdivs"}
{foreach $subcats as $k1=>$item}
<div id="subcat_selector_{$k1}" class="subcat_selector hidden">
<div class="closebutton buttonlike noborder fixedright down5" onclick="javascript:close_subcat_selector();"></div>
<div class="set_title centered">{$LN_spots_subcategories} - {$item.name}</div>
<div class="reset_button buttonlike on_top"><input type="button" value="{$LN_reset}" onclick="javascript:clear_all_checkboxes({$k1});" class="submitsmall"/></div>
<div class="internal_subcat_selector">
{foreach $item.subcats as $k2=>$si}
<table class="subcat">
<tr>
<td onclick="javascript:fold_adv_search('subcat_button_{$k1}{$k2}', 'subcat_items_{$k1}{$k2}');" class="subcat_head">
<div id="subcat_button_{$k1}{$k2}" class="inline iconsize dynimgplus buttonlike"></div>&nbsp;{$si.name}
</td>
</tr>
</table>
<table id="subcat_items_{$k1}{$k2}" class="hidden subcat">
<tr>
{$cnt="0"}
{foreach $si.subcats as $k3=>$item2 }
{if $item2 != '??'}
{capture name=current assign=current}{$subcat_{$k1}_{$k2}_{$k3}|default:''}{/capture}

{if $cnt == 3}</tr><tr>{$cnt="0"}{/if}
<td class="subcat">
{urd_checkbox value="$current" name="subcat_{$k1}_{$k2}_{$k3}" id="subcat_{$k1}_{$k2}_{$k3}" data="$item2" tristate="1" } 
</td>
{$cnt="`$cnt+1`"}
{/if}
{/foreach}
</tr>            
</table>
{/foreach}
<br/>
</div>
</div>
{/foreach}
{/capture}
{* Search form *}
{capture assign="searchform"}
<div>
<form id="searchform" method="get">
<div id="advanced_search_button" class="floatleft iconsize dynimgplus buttonlike" onclick="javascript:fold_adv_search('advanced_search_button', 'advanced_search');" {urd_popup type="small" text=$LN_advanced_search }>
</div>&nbsp;
    <input type="hidden" name="order" value="{$order|escape:htmlall}" id="searchorder"/>
	<input type="hidden" name="save_category" value="" id="save_category"/>
    <input type="button" class="submitsmall" value="&lt;" {urd_popup text=$LN_previous type="small"} onclick='javascript:select_next("select_catid",-1);'/>&nbsp;
	<select name="catID" class="search" id="select_catid" onchange='javascript:do_select_subcat();'>
    <option value="">{$LN_spots_allcategories} ({$total_articles})</option>
    {foreach $categories as $item }
		<option {if $item.id == $categoryID && $categoryID != -1 }selected="selected"{/if} value="{$item.id}">
            {$item.name} ({$item.article_count})
        </option>
	{/foreach}
	</select>&nbsp;
    <input type="button" class="submitsmall" value="&gt;" {urd_popup text=$LN_next type="small"} onclick='javascript:select_next("select_catid",1);'/>&nbsp;
    {$subcatdivs}
    <input type="button" id="subcatbutton" class="submitsmall {if $catid == ''}invisible{/if}" value="{$LN_spots_subcategories}" onclick="javascript:show_subcat_selector();" />&nbsp;
    <input type="text" id="search" name="search" size="30" placeholder="{$LN_search}" class="search" value="{$search|escape:htmlall}" 
 onkeypress="javascript:submit_enter(event, load_sets, { 'offset':'0', 'setid':'', 'category':'' } );"/>&nbsp;
<input type="button" value="{$LN_search}" class="submitsmall" onclick="javascript:load_sets( { 'offset':'0', 'setid':'', 'category':'' } );" />
&nbsp;

<span id="save_search_outer" class="{if count($saved_searches) == 0}hidden{/if}">
<input type="button" class="submitsmall" value="&lt;" {urd_popup text=$LN_previous type="small"} onclick="javascript:select_next_search('saved_search',-1);"/>
<span id="save_search_span">
<select id="saved_search" onchange="javascript:update_spot_searches(null);" >
<option value=""></option>
{foreach $saved_searches as $k1=>$saved_search}
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
<td><input type="text" id="minsetsize" name="minsetsize" size="6" value="{$minsetsize|escape:htmlall}"/></td> 
<td><div id="setsize" style="width:100px;"></div></td>
<td><input type="text" id="maxsetsize" name="maxsetsize" size="6" value="{$maxsetsize|escape:htmlall}"/></td>
<td>{$LN_age}:</td>
<td><input type="text" id="minage" name="minage" size="6" value="{$minage|escape:htmlall}"/></td> 
<td><div id="setage" style="width:100px;"></div></td>
<td><input type="text" id="maxage" name="maxage" size="6" value="{$maxage|escape:htmlall}"/></td>
</tr>

<tr>
<td>{$LN_poster_name}:</td>
<td><input type="text" id="poster" name="poster" size="10" value="{$poster|escape:htmlall}"/></td>
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
<td>{$LN_rating}:</td>
<td><input type="text" id="minrating" name="minrating" size="6" value="{$minrating|escape:htmlall}"/></td> 
<td><div id="setrating" style="width:100px;"></div></td>
<td><input type="text" id="maxrating" name="maxrating" size="6" value="{$maxrating|escape:htmlall}"/></td>
<td colspan="3"></td>
<td><input type="button" value="{$LN_reset}" class="submitsmall" onclick='clear_form("searchform");do_select_subcat();'/></td>
</tr>
</table>
</div>
</form>
</div>
{/capture}

{capture assign="rss_link"}
{strip}
<div id="rss"><table class="rss"><tr><td class="rssleft"><a href="rss.php" id="rss_id" class="rss">RSS</a></td><td class="rssright">2.0</td></tr></table> </div>
{/strip}
{/capture}

{capture assign="basketform"}
<form method="post" id="setform">
<div id="basketdiv" class="down3"></div>

{* We need this stuff to remember any the search options *}
<div>
<input type="hidden" name="usersettype" id="usersettype" value="{$USERSETTYPE}"/>
<input type="hidden" name="offset" id="offset" value="{$offset|escape:htmlall}"/>
<input type="hidden" name="spotid" id="spotid" value="{$spotid|escape:htmlall}"/>
<input type="hidden" name="cat_id" id="cat_id" value="{$catid|escape:htmlall}"/>
<input type="hidden" name="dlname" id="dlname" value=""/>
<input type="hidden" name="whichbutton" value="" id="whichbutton"/>
<input type="hidden" name="previewBinID" value="" id="previewBinID"/>
<input type="hidden" name="previewGroupID" value="" id="previewGroupID"/>
<input type="hidden" name="lastdivid" id="lastdivid" value=""/>
<input type="hidden" name="curScrollVal" id="curScrollVal" value=""/>
<input type="hidden" name="type" id="type" value="spots"/>
</div>
</form>
{/capture}

{$rss_link}

<div id="searchformdiv" class="hidden">
{$searchform}
{$basketform}
</div>

{* And display it here and at the bottom: *}
<div class="innerwaitingdiv" id="waitingdiv">
<div class="waitingimg centered"></div>
<p><br/></p>
<h3 class="centered">{$LN_loading}</h3>
</div>

<div id="setsdiv" class="hidden">
</div>
{strip}
<script type="text/javascript">

$(document).ready(function() {
   init_slider({$minsetsizelimit}, {$maxsetsizelimit}, "#setsize", "#minsetsize", "#maxsetsize");
   init_slider({$minratinglimit}, {$maxratinglimit}, "#setrating", "#minrating", "#maxrating");
   init_slider({$minagelimit}, {$maxagelimit}, "#setage", "#minage", "#maxage");
    {if ($categoryID == '') && ($_saved_search != '')}
        update_search_names('{$_saved_search|escape:javascript}');
        update_spot_searches('{$_saved_search|escape:javascript}');
    {else}
        load_sets( {  
            'offset':'0'
            {if $spotid == ''}
            , 'setid':'' 
            {/if}
            {if $categoryID !== '' }
                 , 'next':'{$categoryID}'
            {/if}
            }
        );
    {/if}
    set_scroll_handler('#contentout', load_sets);
    {* Load basket: *}
    update_basket_display();
    $('#searchbar').html($('#searchformdiv').html());
    $('#searchformdiv').html('');
});
</script>
{/strip}

<input type="hidden" id="ln_delete_search" value="{$LN_delete_search}"/>
<input type="hidden" id="perpage" value="{$perpage}"/>
<div>
&nbsp;<br/>
&nbsp;<br/>
&nbsp;<br/>
</div>
{include file="foot.tpl"}
