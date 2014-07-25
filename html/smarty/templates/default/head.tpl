<!DOCTYPE html>
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
{if isset($rssurl) && $rssurl neq ""}
<link rel="alternate" type="application/rss+xml" href="{$rssurl}" title="URD"/> 
{/if}
<script type="text/javascript" src="{$JSDIR}/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="{$JSDIR}/jquery-ui.js"></script>
<script type="text/javascript" src="{$JSDIR}/js.js"></script>
</head>
{urd_flush}
<body>
<noscript><div id="nojs" class="centered_nojs down100">{$LN_login_jserror}</div></noscript>

<div id="message_bar" class="Message hidden">
    <div id="message_icon" class="inline iconsizeplus previewicon buttonlike"></div>
    <div id="message_content" class="inline"></div>
</div>

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
    <div {if $first->get_link_type() == 'jump'} onclick="javascript:jump('{$first->get_url()}');" class="nooverflow down3 buttonlike"{else} class="nooverflow down3" {/if}>{$menuitem->get_name()}</div>
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
    {elseif $first->get_link_type()=='command'} class="buttonlike down3 nooverflow {$category}" onclick="javascript:do_command('{$first->get_url()}' '{$first->get_message()|escape:javascript}');"
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
<div id="sbdiv" onclick="javascript:show_sidebar();"><span id="sidebar_button"></span></div>
<div id="content" class="down3">
<input type="hidden" name="urdd_message" id="urdd_message" value="{$offline_message}"/>
