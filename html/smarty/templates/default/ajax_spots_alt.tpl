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
{include 'include_bin_image.tpl' scope='parent'}

{if $view_size >= $max_mobile_viewsize}
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
    {else}
        <br/>
    {/if}
{/if}
{/strip}
{/capture}

{* Making a 'top' and a 'bottom' skipper: *}
{capture assign=bottomskipper}{strip}
{if $only_rows == 0}
    {if count($pages) > 1}
        {urd_skipper current=$currentpage last=$lastpage pages=$pages position=bottom js=set_offset extra_class="margin10" size=$skippersize}
    {else}
        <br/>
    {/if}
{/if}
{/strip}
{/capture}

{if $only_rows == 0}
    {$topskipper}
{/if}

{* Display the bunch: *}
{if $only_rows == 0}
    <div id="sets_list">
{/if}
{foreach $allsets as $set}
{capture assign=similar} 
<span id="similar_button_{$set.spotid}" class="buttonlike highlight_comments">{$LN_spots_similar}</span>
{/capture}

{capture assign=smallbuttons}{strip}	
<input type="hidden" name="setdata[]" id="set_{$set.spotid}" value=""/>
<div id="divset_{$set.spotid}" class="inline iconsize buttonlike"></div>

<script type="text/javascript">
$(document).ready(function() {
    {if $set.added} 
        $('#set_{$set.spotid}').val('x'); 
        $('#divset_{$set.spotid}').addClass('setimgminus'); 
    {else} 
        $('#divset_{$set.spotid}').addClass('setimgplus'); 
    {/if}
    $('#divset_{$set.spotid}').click( function (e) { select_set('{$set.spotid}', 'spot', e); return false; } );
    $('#td_set_{$set.spotid}').mouseup( function (e) { start_quickmenu('browse', '{$set.spotid}', {$USERSETTYPE_SPOT}, e); } );
    $('#intimg_{$set.spotid}').click( function () { mark_read('{$set.spotid}', 'interesting', {$USERSETTYPE_SPOT} ); } );
    $('#wipe_img_{$set.spotid}').click( function () { mark_read('{$set.spotid}', 'wipe', {$USERSETTYPE_SPOT} ); } );
    $('#link_img_{$set.spotid}').click( function () { jump('{$set.anon_url|escape:javascript}', true); } );
    $('#similar_button_{$set.spotid}').click( function(e) { 
        {if $set.reference == ''}
            load_sets( { 'search' : '{$set.first_two_words|escape:javascript}' }); });
        {else}
            load_sets( { 'reference' : '{$set.reference|escape:javascript}' }); });
        {/if}

});
</script>
{/strip}
{/capture}
{$read=''}
{$nzb=''}
{$interesting='even'}
{$interestingimg="smileicon"}
{if $set.read == 1}{$read='markedread'}{/if}
{if $show_makenzb != 0 && $set.nzb == 1}{$nzb='markednzb'}{/if}
{if $set.interesting == 1}{$interesting='interesting'}{/if}
{if $set.interesting == 1}{$interestingimg='sadicon'}{/if}

{capture assign=spot_icon}
{if $set.extcat == ':_img_movie:'}{$btmovie}
{elseif $set.extcat == ':_img_album:'}{$btmusic}
{elseif $set.extcat == ':_img_image:'}{$btimage}
{elseif $set.extcat == ':_img_software:'}{$btsoftw}
{elseif $set.extcat == ':_img_series:'}{$bttv}
{elseif $set.extcat == ':_img_tvshow:'}{$bttv}
{elseif $set.extcat == ':_img_documentary:'}{$btdocu}
{elseif $set.extcat == ':_img_ebook:'}{$btebook}
{elseif $set.extcat == ':_img_game:'}{$btgame}
{elseif $set.categorynr == 0}{$btmovie}
{elseif $set.categorynr == 1}{$btmusic}
{elseif $set.categorynr == 2}{$btgame}
{elseif $set.categorynr == 3}{$btsoftw}
{/if}
{/capture}
{$rating=$set.rating * 10}
{$linkpic="ratingicon_$rating"}
{if $rating == ""}{$linkpic="followicon"}{/if}

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
{/capture}


<div class="spotbox" id="td_set_{$set.spotid}">
<div>

{strip}
<div class="content {$interesting} {$read} {$nzb} inline set_content" style="overflow-x: hidden; overflow-y: hidden; white-space:nowrap; " {if $show_subcats}{urd_popup text="$subcats" caption="$LN_spots_subcategories" xpos='$(this).offset().left' ypos='$(this).offset().top'}{/if}>
{capture assign=setdesc}{$set.name|escape:htmlall|replace:':_img_pw:':$btpw|replace:':_img_copyright:':$btcopyright}{/capture}
{if $set.reports gt 0}{$spamreports=$set.reports}<div {urd_popup type="small" text="$spamreports $LN_spam_reports"} class="highlight_spam center width15 inline floatright">{$set.reports}</div>{/if}
{if $show_comments > 0}
    {$setcomments=$set.comments}
    <div class=" highlight_comments center width25 inline floatright" {urd_popup type="small" text="$setcomments $LN_browse_tag_note"}>{$setcomments}</div>
{/if}
<div class="inlineblock floatleft" style="margin-right:0.2em;">{$spot_icon}</div>
<div class="inlineblock floatleft width31 nowrap down2">{$set.number} -- {$setdesc}</div>
<input type="hidden" name="set_ids[]" value="{$set.spotid}"/>
</div>
{/strip}

{if $show_image}
{if $set.image != '' && $set.image_from_db == 0}
<div class="spot_thumbnail2  buttonlike floatright"><img src="{$set.image}" id="image_inline_{$set.spotid}" class="max100x100 spot_thumbimg" alt=""/></div>
<script type="text/javascript">
$(document).ready(function() {
    $("#image_inline_{$set.spotid}").click( function(e) { jump('{$set.image|escape:javascript}', true);return false; } ); 
});
</script>
{/if} 
{if $set.image_from_db == 1}
<div class="spot_thumbnail2  buttonlike floatright"><img src="show_image.php?spotid={$set.spotid}" id="image_db_{$set.spotid}" class="max100x100 spot_thumbimg" alt=""/></div>
<script type="text/javascript">
$(document).ready(function() {
    $("#image_db_{$set.spotid}").click( function(e) { jump('show_image.php?spotid={$set.spotid|escape:javascript}', true);return false; } ); 
});
</script>
{/if}
{if $set.image_file != ''}
<div class="spot_thumbnail2  buttonlike floatright"><img src="getfile.php?raw=1&amp;file={$set.image_file}" id="image_file_{$set.spotid}" class="max100x100 spot_thumbimg" alt=""/></div>
<script type="text/javascript">
$(document).ready(function() {
    $("#image_file_{$set.spotid}").click( function(e) { show_spot_image('getfile.php?file={$set.image_file|escape:javascript}&raw=1', true); e.stopPropagation(); return false;} ); 
});
</script>
{/if}
{/if}
 
<span class="bold">{$LN_browse_age}:</span> <span class="nowrap {if $set.new_set != 0}newset{/if}">{$set.age}</span><br>
<span class="bold">{$LN_size}:</span> <span class="nowrap">{$set.size}</span><br>

<div class="nooverflow spot_descriptionbox">
{$set.description}
</div>

<div class="buttonbox">&nbsp;
<div class="floatleft">{$smallbuttons}</div>
 
<div class="floatright">
{$similar}
{if $set.url != ''}
    <div id="link_img_{$set.spotid}" class="inline iconsize {$linkpic} buttonlike" {urd_popup type="small" text=$set.url|escape:htmlall}></div>
	{elseif $set.rating != 0}
    <div class="inline iconsize {$linkpic}"></div>
{else}&nbsp;
	{/if}
    {if $isadmin}
    <div id="wipe_img_{$set.spotid}" class="inline iconsize purgeicon buttonlike" {urd_popup type="small" text=$LN_browse_deleteset}></div>
    {/if}
    <div id="intimg_{$set.spotid}" class="inline iconsize {$interestingimg} buttonlike" {urd_popup type="small" text=$LN_browse_toggleint }></div>
</div>
</div>

</div>
</div>
{foreachelse}
{if $only_rows == 0}
<div class="centered highlight even bold">{$LN_error_nosetsfound}</div>
{/if}
{/foreach}
{if $only_rows == 0}
</div>
</div>
<div class="clearfix"></div>
{/if}
{if $only_rows == 0}
{$bottomskipper}
{/if}

<script type="text/javascript">
$(document).ready(function() {
    /*$('#browsesubjecttd').click( function () { change_sort_order('title') } );
    $('#head_reports').click( function () { change_sort_order('reports', 'desc') } );
    $('#head_comments').click( function () { change_sort_order('comments', 'desc') } );
    $('#head_stamp').click( function () { change_sort_order('stamp', 'desc') } );
    $('#head_size').click( function () { change_sort_order('size', 'desc') } );
    $('#head_url').click( function () { change_sort_order('url') } );*/
    $('div.resurrect_button').click( function (e) { which_button('unmark_kill_all', e); } );
    $('div.remove_button').click( function (e) { which_button('mark_kill_all', e); } );
    $('div.wipe_button').click( function (e) { which_button('wipe_all', e) } );
    $("div.unmark_int_button").click( function (e) { which_button('unmark_int_all', e); } );

});
</script>
