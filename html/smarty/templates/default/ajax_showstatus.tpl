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
 * $LastChangedDate: 2013-09-04 22:44:19 +0200 (wo, 04 sep 2013) $
 * $Rev: 2919 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_showstatus.tpl 2919 2013-09-04 20:44:19Z gavinspearhead@gmail.com $
 *}
{* Ajax page, doesn't need a head/foot tpl *}
{* Window resize button at top right: *}
{strip}

{if $type == 'icon'}
{if $isadmin}
    {if $isconnected}
    <div class="light_green iconsize centered buttonlike down3" {urd_popup text=$LN_disableurdd type="small"} onclick="javascript:control_action('poweroff', '', '');load_quick_status();"></div>
    {elseif ($startup_perc > 0) && ($startup_perc <= 99) }
    <div class="light_yellow iconsize centered down3"></div>
    {else}
    <div class="light_red centered iconsize buttonlike down3" {urd_popup text=$LN_enableurdd type="small"} onclick="javascript:control_action('poweron', '','');load_quick_status();"></div>
    {/if}
{else}
    {if $isconnected}
    <div class="light_green centered iconsize down3" {urd_popup text=$LN_disableurdd type="small"}></div>
	{elseif ($startup_perc > 0) && ($startup_perc <= 99) }
    <div class="light_yellow centered iconsize down3"></div>
    {else} 
    <div class="light_red centered iconsize down3" {urd_popup text=$LN_enableurdd type="small"}></div>
    {/if}
{/if}
{/if}

{if $type == 'quick'}
<div class="centered2">
    {if $isconnected}
    <div class="down3 nooverflow">{$LN_status} [{$counter}]</div>
    {elseif ($startup_perc > 0) && ($startup_perc <= 99) }
    <div class="down3 nooverflow">{$LN_status} ({$startup_perc}%) </div>
    {else}
    <div class="down3 nooverflow">{$LN_status}</div>
    {/if} 
</div>
{/if}


{if $type == 'disk'}&nbsp;
{if $isconnected}
{urd_progressbar width=96 complete=$nodisk_perc done=progress_done2 remain=progress_done } 
<ul class="last plain">
<li class="plain"><div class="down3"><span {if $disk_perc < 10} class="warning_highligh"{/if}>{$diskfree} {$LN_free} ({$disk_perc}%)</span></div>
<li class="plain "><div class="down3"><span>{$diskused} {$LN_inuse} ({$nodisk_perc}%)</span></div>
<li class="plain pulldown_last_item"><div class="down3"><span>{$disktotal} {$LN_total}</span></div>
</ul>
{else} 
{$LN_off}
{/if}
{/if}

{if $type == 'activity'}
{if not $isconnected}
<li class="plain"><div class="down3" {if $isadmin} class="down3 buttonlike" onclick="javascript:control_action('poweron', '','');load_quick_status();"{else}  class="down3" {/if}>
{$LN_urdddisabled}</div>
</li>
{else}
{if $counter <= 0}
{if !empty($previews)}
    <li class="activity">
{else}
    <li class="plain pulldown_last_item">
{/if}
<div class="down3 buttonlike" onclick="jump('transfers.php')";>
{$LN_statusidling}!
</li>
{else}

{foreach $tasks as $task}
{if $task.type == 'download'}{$tasklink="transfers.php"}
{else}{$tasklink="admin_tasks.php"}
{/if}

{if $task@last && empty($previews)}{$add_class="pulldown_last_item"}{else}{$add_class=""}{/if}
<li class="activity {$add_class}"><div class="down3 buttonlike" onclick="jump('{$tasklink}')">

{$task.task} <b>{$task.args|truncate:32:'':true}</b>{if $task.counter > 1} (x{$task.counter}){/if}:
{if $task.niceeta <> -1}
    {* Show ETA: *}
    <span class="xxsmall">({$task.progress}% {$LN_eta}: {$task.niceeta|escape:htmlall})</span> 
{else}
    <span class="xxsmall">({$task.progress}%)</span>
{/if}
</li>

{/foreach}
{/if}

{if !empty($previews)}<li class="activity">&nbsp;</li>
<li class="activity bold">
<div class="down3 buttonlike fixedright deleteicon iconsize" onclick="javascript:delete_preview('all');" {urd_popup type="small" text=$LN_delete_all }></div>
<div class="down3">{$LN_stats_pv}</div>
</li> 
{/if}

{foreach $previews as $preview}
{if $preview@last}{$add_class="pulldown_last_item"}{else}{$add_class=""}{/if}
<li class="activity {$add_class}">
<div class="down3 buttonlike fixedright deleteicon iconsize" onclick="javascript:delete_preview({$preview.dlid});" {urd_popup type="small" text=$LN_delete }></div>
<div class="down3 buttonlike" onclick="javascript:show_preview({$preview.dlid}, {$preview.binary_id}, {$preview.group_id})";>
{$preview.name|truncate:32:"...":TRUE|escape:htmlall} ({$preview.donesize|escape:htmlall} / {$preview.size|escape:htmlall}) - {$preview.status} 
</div>
</li>

{/foreach}
{/if}
{/if}
{include file="ajax_foot.tpl"}
{/strip}
