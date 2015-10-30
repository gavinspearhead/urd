{strip}
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
 * $LastChangedDate: 2014-06-27 17:26:47 +0200 (vr, 27 jun 2014) $
 * $Rev: 3121 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_showstatus.tpl 3121 2014-06-27 15:26:47Z gavinspearhead@gmail.com $
 *}
{if $type == 'icon'}
    {if $isadmin}
        {if $isconnected}
        <div class="status_light green buttonlike down6" {urd_popup text=$LN_disableurdd type="small"} id="urdd_disable"></div>
        {elseif ($startup_perc > 0) && ($startup_perc <= 99) }
        <div class="status_light yellow down5"></div>
        {else}
        <div class="status_light red buttonlike down6" {urd_popup text=$LN_enableurdd type="small"} id="urdd_enable"></div>
        {/if}
    {else}
        {if $isconnected}
        <div class="status_light green down6" {urd_popup text=$LN_disableurdd type="small"}></div>
        {elseif ($startup_perc > 0) && ($startup_perc <= 99) }
        <div class="status_light yellow down5"></div>
        {else} 
        <div class="status_light red down6" {urd_popup text=$LN_enableurdd type="small"}></div>
        {/if}
    {/if}
{/if}

{if $type == 'quick'}
<div class="centered2">
    <div class="down3 nooverflow">
    {if $isconnected}
        {$LN_status} [{$counter}] 
    {elseif ($startup_perc > 0) && ($startup_perc <= 99) }
        {$LN_status} ({$startup_perc}%)
    {else}
        {$LN_status}
    {/if} 
    </div>
</div>
{/if}

{if $type == 'disk'}
    {if $isconnected}
        <div style="" class="down3 nooverflow">
        {urd_progressbar width=96 complete=$nodisk_perc done=progress_done2 remain=progress_done background=green colour=red} 
        </div>
        <ul class="last plain">
        <li class="plain"><div class="down3"><span {if $disk_perc < 10} class="warning_highligh"{/if}>{$diskfree} {$LN_free} ({$disk_perc}%)</span></div>
        <li class="plain"><div class="down3"><span>{$diskused} {$LN_inuse} ({$nodisk_perc}%)</span></div>
        <li class="plain pulldown_last_item"><div class="down3"><span>{$disktotal} {$LN_total}</span></div>
        </ul>
    {else} 
        {$LN_off}
    {/if}
{/if}

{if $type == 'activity'}
    {if not $isconnected}
        <li class="plain pulldown_last_item"><div class="down3" {if $isadmin}class="down3 buttonlike" id="urdd_poweron"{else}class="down3"{/if}>
        {$LN_urdddisabled}</div>
        </li>
    {else}
        {if $counter <= 0}
            {if !empty($previews)}
                <li class="activity">
            {else}
                <li class="plain pulldown_last_item">
            {/if}
            <div class="down3 buttonlike" onclick="jump('transfers.php');">
            {$LN_statusidling}!
            </li>
        {else}
            {foreach $tasks as $task}
                {if $task.type == 'download'}{$tasklink="transfers.php"}{else}{$tasklink="admin_tasks.php"}
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
            <div class="down3 buttonlike fixedright deleteicon iconsize" id="pv_deleteall" {urd_popup type="small" text=$LN_delete_all}></div>
            <div class="down3">{$LN_stats_pv}</div>
            </li> 
        {/if}

        {foreach $previews as $preview}

            {if $preview@last}{$add_class="pulldown_last_item"}{else}{$add_class=""}{/if}

            <li class="activity {$add_class}">
            <div class="down3 buttonlike fixedright deleteicon iconsize" onclick="javascript:delete_preview({$preview.dlid});" {urd_popup type="small" text=$LN_delete}></div>
            <div class="down3 buttonlike" onclick="javascript:show_preview({$preview.dlid}, {$preview.binary_id}, {$preview.group_id});">
            {$preview.name|truncate:32:"...":TRUE|escape:htmlall} ({$preview.donesize|escape:htmlall} / {$preview.size|escape:htmlall}) - {$preview.status} 
            </div>
            </li>

        {/foreach}
    {/if}
{/if}
{/strip}
