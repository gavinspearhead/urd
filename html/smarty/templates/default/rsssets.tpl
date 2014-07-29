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
 * $LastChangedDate: 2014-06-12 23:24:27 +0200 (do, 12 jun 2014) $
 * $Rev: 3089 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: rsssets.tpl 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 *}
{include file="head.tpl" title=$title rssurl=$rssurl}

{* Search form *}
{capture assign="searchform"}
<form id="searchform" method="get">
<div>

{foreach $subscribedfeeds as $item}
<input type="hidden" id="ng_id_{$item.type}_{$item.id}" value="{$item.name|escape:htmlall}"/>
{/foreach}

    <input type="hidden" name="order" value="{$order|escape:htmlall}" id="searchorder"/>
	<input type="hidden" name="save_category" value="" id="save_category"/>
    <input type="button" id="prev_group" value="&lt;" class="submitsmall" {urd_popup type="small" text=$LN_previous }/>&thinsp;
    <select name="feed_id" class="search" id="select_feedid">
    <option value="">{$LN_feeds_allgroups} ({$total_articles})</option>
    {foreach $subscribedfeeds as $item}
        {capture name=current assign=current}{$item.type}_{$item.id}{/capture}
		<option {if $current eq $feed_id && $feed_id neq '' }selected="selected"{/if} value="{$item.type}_{$item.id}"> 
            {if $item.type=='category'}{$LN_category}: {/if}{$item.name|escape:htmlall} ({$item.article_count})
        </option>
    {/foreach}
	</select>&thinsp;
    <input type="button" id="next_group" value="&gt;" class="submitsmall" {urd_popup type="small" text=$LN_next }/>&nbsp;
   	<input type="text" name="search" id="search" size="30" class="search" placeholder="{$LN_search}" value="{$search|escape:htmlall}"/> &nbsp;
	<input type="hidden" value="" name="maxage"/>
    <input type="button" id="search_button" value="{$LN_search}" class="submitsmall"/>
    &nbsp; 
    &nbsp;

<span id="save_search_outer" class="{if count($saved_searches) == 0}hidden{/if}">
<input type="button" id="prev_search" class="submitsmall" value="&lt;" {urd_popup text=$LN_next type="small"}/>
<span id="save_search_span">
<select id="saved_search" _onchange="javascript:update_browse_searches();">
<option value=""></option>
{foreach $saved_searches as $saved_search}
<option value="{$saved_search}" {if $saved_search == $_saved_search}selected="selected"{/if}>{$saved_search|escape}&nbsp;</option>
{/foreach}
</select>
</span>
<input type="button" id="next_search" class="submitsmall" value="&gt;" {urd_popup text=$LN_next type="small"}/>
</span>
&nbsp;
<div id="minibasketdiv" class="hidden"></div>

</div>
</form>
{/capture}

{capture assign="rss_link"}
<div id="rss">
	<table class="rss"><tr><td class="rssleft"><a href="" id="rss_id" class="rss">RSS</a></td><td class="rssright">2.0</td></tr></table>
</div>
{/capture}


{capture assign="basketform"}
<div id="basketdiv" class="down3"></div>

{* We need this stuff to remember any the search options *}
<div>
<input type="hidden" name="usersettype" id="usersettype" value="{$USERSETTYPE|escape:htmlall}"/>
<input type="hidden" name="offset" id="offset" value="{$offset|escape:htmlall}"/>
<input type="hidden" name="feed_id" id="feed_id" value="{$feed_id|escape:htmlall}"/>
<input type="hidden" name="setid" id="setid" value="{$setid|escape:htmlall}"/>
<input type="hidden" name="dlname" value=""/>
<input type="hidden" name="whichbutton" value="" id="whichbutton"/>
<input type="hidden" name="previewBinID" value="" id="previewBinID"/>
<input type="hidden" name="previewGroupID" value="" id="previewGroupID"/>
<input type="hidden" name="lastdivid" id="lastdivid" value=""/>
<input type="hidden" name="curScrollVal" id="curScrollVal" value=""/>
<input type="hidden" name="type" id="type" value="rss"/>
</div>
{/capture}

{$rss_link}
<div id="searchformdiv" class="hidden">
{$searchform}
{$basketform}
</div>


{* Display the content: *}
<div class="innerwaitingdiv" id="waitingdiv">
<div class="waitingimg centered"></div>
<p><br/></p>
<h3 class="centered">{$LN_loading}</h3>
</div>

<div id="setsdiv" class="hidden">
</div>
<input type="hidden" id="minsetsizelimit" value="{$minsetsizelimit}"/>
<input type="hidden" id="maxsetsizelimit" value="{$maxsetsizelimit}"/>
<input type="hidden" id="minratinglimit" value="{$minratinglimit}"/>
<input type="hidden" id="maxratinglimit" value="{$maxratinglimit}"/>
<input type="hidden" id="minagelimit" value="{$minagelimit}"/>
<input type="hidden" id="maxagelimit" value="{$maxagelimit}"/>

{* Load basket: *}
<script type="text/javascript">
$(document).ready(function() {
    load_side_bar( function() {
        set_scroll_handler('#contentout', load_sets);

        update_basket_display();
        
        load_sets( {
           'offset':'0'
           {if $feed_id != ''}          , 'next':'{$feed_id}'  {/if}
           {if $minsetsize != ''}       , 'minsetsize': '{$minsetsize|escape:javascript}' {/if}
           {if $maxsetsize != ''}       , 'maxsetsize': '{$maxsetsize|escape:javascript}' {/if}
           {if $minage != ''}           , 'minage': '{$minage|escape:javascript}' {/if}
           {if $maxage != ''}           , 'maxage': '{$maxage|escape:javascript}' {/if}
           {if $minrating != ''}        , 'minrating': '{$minrating|escape:javascript}' {/if}
           {if $maxrating != ''}        , 'maxrating': '{$maxrating|escape:javascript}' {/if}
           {if $setid == ''}            , 'setid': '' {/if}
           {if $flag != ''}             , 'flag': '{$flag|escape:javascript}' {/if}
           }
        );
        $('#searchbar').html($('#searchformdiv').html());
        $('#searchformdiv').html('');
        $('#search_button').click( function () { load_sets( { 'offset':'0', 'setid':'', 'category':'' } ); return false; } ) ;
        $('#next_group').click( function () { select_next("select_feedid",1); } ) ;
        $('#prev_group').click( function () { select_next("select_feedid",-1); } ) ;
        $('#search').keypress( function (e) { return submit_enter(e, load_sets, { 'offset':'0', 'setid':'', 'category':'' } ); } );
        $('#next_search').click( function () { select_next_search('saved_search',1); } );
        $('#prev_search').click( function () { select_next_search('saved_search',-1); } );
        $('#saved_search').change( function () { update_browse_searches(null); } );

    });
});
</script>

<input type="hidden" id="ln_delete_search" value="{$LN_delete_search}"/>
<input type="hidden" id="perpage" value="{$perpage|escape:htmlall}"/>

{include file="foot.tpl"}
