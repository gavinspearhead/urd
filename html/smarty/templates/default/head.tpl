<!DOCTYPE html>
{* Smarty *}
{*
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
 * $LastChangedDate: 2010-12-26 19:09:56 +0100 (zo, 26 dec 2010) $
 * $Rev: 1965 $
 * $Author: gavinspearhead $
 * $Id: newsgroups.tpl 1965 2010-12-26 18:09:56Z gavinspearhead $
 *}

{capture assign=urdmenu}
<div id="scrollmenuright" class="buttonlike white">&gt;</div>
<div id="scrollmenuleft" class="buttonlike white">&lt;</div>
<div id="menu">
    <div id="pulldown_menu">
        <div id="pulldown" class="pulldown"> 
            <ul>
            <li class="smalllogo"><div id="smalllogo" class="buttonlike">&nbsp;</div>
            <div class="downm8"> 
            <ul><li class="plain pulldown_last_item"><div class="down3 centered2">{$LN_version} {$VERSION}</div></li></ul>
            </div>
            </li>
            <li class="smallstatus"><div id="smallstatus">&nbsp;</div></li>
            <li class="normal" id="status_item">
                <div id="status_msg" class="nooverflow"></div>
                <div class="downm8">
                <ul id="status_activity">
                    <li class="activity plain pulldown_last_item"></li>
                </ul>
                </div>
            </li>

{foreach $menu->get_items() as $menuitem}
{$itemlist=$menuitem->get_items()}
{$first=$itemlist.0}
{if $menuitem->get_category() == ''}{$category='plain'}{else}{$category=$menuitem->get_category()}{/if}
{if $first->get_link_type()=='command'}{$extra="commando"}{else}{$extra=""}{/if}
<li class="normal {$extra}">
    {if $menuitem->get_count() > 1}
    <div {if $first->get_link_type() == 'jump'} onclick="javascript:if (!Modernizr.touch ) { jump('{$first->get_url()}'); }" class="nooverflow down3 buttonlike"{else} class="nooverflow down3" {/if}>{$menuitem->get_name()}</div>
        <ul>
		    {foreach $menuitem->get_items() as $link=>$menuitems}
                {$mainmenuname=$menuitems->get_name()}
                {$mainmenulink=$menuitems->get_url()}
                {$mainmenulinktype=$menuitems->get_link_type()}
                {$mainmenumessage=$menuitems->get_message()}
                {if $menuitems@last}{$add_class="pulldown_last_item"}{else}{$add_class=""}{/if}
                <li 
                 {if $menuitems->get_link_type()=='jump'} class="buttonlike {$category} {$add_class}" onclick="javascript:jump('{$mainmenulink}');" 
                 {elseif $menuitems->get_link_type()=='jumpext'} class="buttonlike {$category} {$add_class}" onclick="javascript:jump('{$mainmenulink}', true);" 
                 {elseif $menuitems->get_link_type()=='command'} class="buttonlike {$category} {$add_class}" onclick="javascript:do_command('{$mainmenulink}', '{$mainmenumessage}');"
                 {else} class="{$category} {$add_class}"  
                 {/if}>
                    <div class="nooverflow down3">{$mainmenuname}</div>
                </li>
			{/foreach}
		</ul>
    {else}
    <div {if $first->get_link_type() == 'jump'}onclick="javascript:jump('{$first->get_url()}');" class="down3 nooverflow buttonlike" 
    {elseif $first->get_link_type()=='command'} class="buttonlike down3 nooverflow {$category}" onclick="javascript:do_command('{$first->get_url()}', '{$first->get_message()|escape:javascript}');"
    {else}class="down3 nooverflow" {/if}>{$first->get_name()}</div>
    {/if}
        </li>
{/foreach}
            <li class="hidden normal" id="disk_li">
                <div id="status_disk" class="down3 centered"></div>   
            </li>
            </ul>
        </div>
    </div>
</div>
{/capture}


<html>
<head>
<title>{$title}</title>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>

{if !$allow_robots}
<meta name="robots" content="noindex, nofollow"/>
{/if}

<link id="basic_css" rel="stylesheet" href="{$CSSDIR}/_basic.css" type="text/css"/>
<link id="urd_css" rel="stylesheet" href="{$CSSDIR}/{if $stylesheet != ''}{$stylesheet|replace:".css":""}/{$stylesheet}.css{else}light/light.css{/if}" type="text/css"/>
<link id="jquery_css" rel="stylesheet" href="{$CSSDIR}/{if $stylesheet != ''}{$stylesheet|replace:".css":""}{else}/light{/if}/jquery-ui.css" type="text/css"/>
<!--[if IE]>
<link rel="stylesheet" id="iehacks_css" href="{$CSSDIR}/_iehacks.css" type="text/css"/>
<![endif]--> 
<link id="icon" rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
{if isset($rssurl) && $rssurl != ""}
<link rel="alternate" type="application/rss+xml" href="{$rssurl}" title="URD"/> 
{/if}
<script type="text/javascript" src="{$JSDIR}/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="{$JSDIR}/jquery-ui.js"></script>
<script type="text/javascript" src="{$JSDIR}/js.js"></script>
</head>
{urd_flush}
<body id="urdbody">
<noscript><div id="nojs" class="centered_nojs down100">{$LN_login_jserror}</div></noscript>

<div id="message_bar" class="Message hidden">
    <div id="message_icon" class="inline iconsizeplus previewicon buttonlike"></div>
    <div id="message_content" class="inline"></div>
</div>
{$urdmenu}

{*this is for the small tooltip thingie *}
<div><div id="smallhelp" class="hidden"></div></div>
{* this is the larger topcentered help window *}
<div id="helpwrapper" class="hidden">
    <div id="helptext" class="helptext">
        <div id="helpheader"></div>
        <div id="helpbody"></div>
</div>
</div>

<input type="hidden" id="challenge" value="{$challenge}"/>
<input type="hidden" id="cssdir" value="{$CSSDIR}"/>
<input type="hidden" name="urdd_status" id="urdd_status" value="{$urdd_online}"/>
<input type="hidden" id="loading_msg" value="{$LN_loading}"/>

<div id="overlay_back">
    <div id="overlay_content"></div>
</div>

<div id="overlay_back2">
    <div id="overlay_content2"></div>
</div>

<div id="quickmenu" class="quickmenuoff"></div>
<div id="quickwindow" class="quickwindowoff"></div>
<div id="contentleft" class="even">
<div id="left_content"></div>
</div>
<div id="searchbar"></div>
<div id="topcontent">
<div id="contentout">
<div id="sbdiv" class="buttonlike floatleft"><span id="sidebar_button"></span></div>
<div id="content" class="down3">
<input type="hidden" name="urdd_message" id="urdd_message" value="{$offline_message}"/>

{block name="contents"}
{/block}

<div>
&nbsp;<br/>
&nbsp;<br/>
&nbsp;<br/>
&nbsp;<br/>
&nbsp;<br/>
</div>

</div>
</div>

<script type="text/javascript">
$(document).ready( function() { 
    init(); 
    $(window).scroll(function(e) { $(window).scrollTop(0); });
    $('#sbdiv').click( function() { show_sidebar(); });
});
</script>
</div>
</body>

<!-- URD v{$VERSION} -->

</html>
