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
{* Search form *}
{capture assign="searchform"}
<div>
<form id="searchform" method="get">
    <input type="hidden" name="order" value="{$order|escape:htmlall}" id="searchorder"/>
	<input type="hidden" name="save_category" value="" id="save_category"/>
    <input type="text" id="search" name="search" size="30" placeholder="{$LN_search}" class="search" value="{$search|escape:htmlall}" 
 onkeypress="javascript:return submit_enter(event, load_sets, { 'offset':'0', 'setid':'', 'category':'' } );"/>&nbsp;
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

<input type="hidden" id="minsetsizelimit" value="{$minsetsizelimit}"/>
<input type="hidden" id="maxsetsizelimit" value="{$maxsetsizelimit}"/>
<input type="hidden" id="minratinglimit" value="{$minratinglimit}"/>
<input type="hidden" id="maxratinglimit" value="{$maxratinglimit}"/>
<input type="hidden" id="minagelimit" value="{$minagelimit}"/>
<input type="hidden" id="maxagelimit" value="{$maxagelimit}"/>

<script type="text/javascript">

$(document).ready(function() {
   load_side_bar( function() {
       {if ($categoryID == '') && ($_saved_search != '')}
           update_search_names('{$_saved_search|escape:javascript}');
           update_spot_searches('{$_saved_search|escape:javascript}');
       {else}
           load_sets( {   
               'offset': '0'
               {if $spotid == ''}       , 'setid':'' {/if}
               {if $minsetsize != ''}   , 'minsetsize': '{$minsetsize|escape:javascript}' {/if}
               {if $maxsetsize != ''}   , 'maxsetsize': '{$maxsetsize|escape:javascript}' {/if}
               {if $minage != ''}       , 'minage': '{$minage|escape:javascript}' {/if}
               {if $maxage != ''}       , 'maxage': '{$maxage|escape:javascript}' {/if}
               {if $minrating != ''}    , 'minrating': '{$minrating|escape:javascript}' {/if}
               {if $maxrating != ''}    , 'maxrating': '{$maxrating|escape:javascript}' {/if}
               {if $flag != ''}         , 'flag':'{$flag|escape:javascript}' {/if}
               {if $categoryID !== '' } , 'next':'{$categoryID|escape:javascript}' {/if}
               }
           );
       {/if}
       });
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
