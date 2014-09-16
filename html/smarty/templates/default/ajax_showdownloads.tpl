{* Smarty *}
{*
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
 * $LastChangedDate: 2014-06-27 23:31:25 +0200 (vr, 27 jun 2014) $
 * $Rev: 3122 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_showdownloads.tpl 3122 2014-06-27 21:31:25Z gavinspearhead@gmail.com $
 *}
{* Ajax page, doesn't need a head/foot tpl *}

{function name=display_status status='' infoarray=''}
{$stat=$infoarray[0]->status|replace:' ':'_'}
<tr class="transferstatus">
<td colspan="{if $isadmin}8{else}7{/if}">{$status}
    <div class="black floatright iconsize noborder buttonlike">
    <div id="{$stat}down" class="inline iconsize noborder buttonlike {if $transfer_hide_status.$stat == 1}dynimgplus{else}dynimgminus{/if}" onclick="javascript:fold_transfer('{$stat}', 'down');">
    </div>
    </div>
</td>
</tr>

<tbody id="data_down_{$stat}" class="{if $transfer_hide_status.$stat == 1}hidden{/if}">
{foreach $infoarray as $a}
{capture name=prio assign="prio_button"}
{if $a->status == "queued" OR $a->status == "paused"}
<div class="inline iconsizeplus upicon buttonlike" onclick="javascript:transfer_edit('move_up','{$a->dlid}');"></div>
<div class="inline iconsizeplus downicon buttonlike" onclick="javascript:transfer_edit('move_down', '{$a->dlid}');"></div>
{/if}
{/capture}

{capture name=opts assign="options"}
{strip}
{if $a->nfo_link != ''} 
<div class="floatleft iconsizeplus followicon buttonlike" {urd_popup type="small" text=$LN_quickmenu_setpreviewnfo left=true} onclick="javascript:show_contents('{$a->nfo_link|escape:javascript}', 0);"></div>
{/if}
{if $a->comment != ''}
{$comment=$a->comment}
<div class="inline iconsizeplus infoicon" {urd_popup type="small" text="$comment" }></div>
{/if}
{if $a->status == "rarfailed"}
<div class="inline iconsizeplus infoicon buttonlike" {urd_popup type="small" text=$LN_transfers_badrarinfo } onclick="javascript:show_contents('{$a->destination}/rar.log', 0);"></div>
{/if}
{if $a->status == "par2failed"}
<div class="inline iconsizeplus infoicon buttonlike" {urd_popup type="small" text=$LN_transfers_badparinfo } onclick="javascript:show_contents('{$a->destination}/par2.log',0);"></div>{/if}
{if $urdd_online}
<div class="inline iconsizeplus editicon buttonlike" onclick="show_rename_transfer('{$a->dlid}');" {urd_popup type="small" text=$LN_transfers_linkedit }></div>
{/if}
{if $show_viewfiles}
<div class="inline iconsizeplus foldericon buttonlike" {urd_popup type="small" text=$LN_transfers_linkview } onclick="javascript:jump('viewfiles.php?dir={$a->destination}');"></div>
{/if}

{if ($a->status == "par2failed" OR $a->status == "rarfailed" OR $a->status == "finished" OR $a->status =="cancelled") AND $urdd_online} 
<div class="inline iconsizeplus previewicon buttonlike" onclick="javascript:transfer_edit('reparrar','{$a->dlid}')"{urd_popup type="small" text=$LN_transfers_runparrar }></div>
{/if}

{if ($a->status == "paused" OR $a->status == "ready") AND $urdd_online}
<div class="inline iconsizeplus playicon buttonlike" onclick="javascript:transfer_edit('start','{$a->dlid}')" {urd_popup type="small" text=$LN_transfers_linkstart }></div>
{/if}

{if ($a->status == "active" OR $a->status == "queued" OR $a->status == "ready") AND $urdd_online}
<div class="inline iconsizeplus pauseicon buttonlike" onclick="javascript:transfer_edit('pause','{$a->dlid}')" {urd_popup type="small" text=$LN_pause }></div>
{/if}

{if ($a->status == "queued" OR $a->status == "paused" OR $a->status == "active" OR $a->status == "ready") AND $urdd_online}
<div class="inline iconsizeplus killicon buttonlike" onclick="javascript:transfer_edit('cancel','{$a->dlid}')" {urd_popup type="small" text=$LN_cancel }></div> 
{/if}
{if $urdd_online}
<div class="inline iconsizeplus deleteicon buttonlike" onclick="javascript:transfer_edit('delete','{$a->dlid}')" {urd_popup type="small" text=$LN_delete } ></div>
{/if}
</td>
{/strip}
{/capture}
<tr class="even content" name="content">
		<td class="bold">
        <div class="donotoverflowdamnit inline"><span>{$a->name|escape:htmlall}</span></div>
        </td>
		<td class="nowrap">
            {urd_progressbar width="100" complete="{$a->progress}" colour="green" text="{$a->progress}%" background="grey" classes="down2"}
        </td>
		<td class="right nowrap">{$a->done_size} / {$a->size}</td>
		<td class="right nowrap">{$a->speed}</td>
		<td class="center nowrap">{$a->ETA}</td>
		<td class="right nowrap">{$a->startdate}</td>
{if $isadmin}
		<td>{$a->username|escape:htmlall}</td>
{/if}
		<td class="rightbut"><div class="floatright">{$prio_button} {$options}</div></td>
	</tr>
{/foreach}
</tbody>
{/function}

{if $show_download} 
<table class="transfers {if $active_tab != 'downloads'}hidden{/if}" id="downloads_tab">
<thead>
<tr>
<th class="head round_left nowrap" width="100%" id="browsesubjecttd">{$LN_transfers_head_dlname}</th>
<th class="head nowrap">{$LN_transfers_head_progress}</th>
<th class="head nowrap">{$LN_size}</th>
<th class="head nowrap">{$LN_transfers_head_speed}</th>
<th class="head nowrap">{$LN_eta}</th>
<th class="head nowrap">{$LN_transfers_head_started}</th>
{if $isadmin}
<th class="head nowrap fixwidth8">{$LN_transfers_head_username}</th>
{/if}
<th class="right nowrap head round_right">{$LN_transfers_head_options}</th>
</tr>
</thead>

{if isset($infoarray_download['active'])}{display_status status=$LN_transfers_status_active infoarray=$infoarray_download['active']}{/if}
{if isset($infoarray_download['ready'])}{display_status status=$LN_transfers_status_ready infoarray=$infoarray_download['ready']}{/if}
{if isset($infoarray_download['queued'])}{display_status status=$LN_transfers_status_queued infoarray=$infoarray_download['queued']}{/if}
{if isset($infoarray_download['paused'])}{display_status status=$LN_transfers_status_paused infoarray=$infoarray_download['paused']}{/if}
{if isset($infoarray_download['finished'])}{display_status status=$LN_transfers_status_finished infoarray=$infoarray_download['finished']}{/if}
{if isset($infoarray_download['complete'])}{display_status status=$LN_transfers_status_complete infoarray=$infoarray_download['complete']}{/if}
{if isset($infoarray_download['cancelled'])}{display_status status=$LN_transfers_status_cancelled infoarray=$infoarray_download['cancelled']}{/if}
{if isset($infoarray_download['stopped'])}{display_status status=$LN_transfers_status_stopped infoarray=$infoarray_download['stopped']}{/if}
{if isset($infoarray_download['error'])}{display_status status=$LN_transfers_status_error infoarray=$infoarray_download['error']}{/if}
{if isset($infoarray_download['shutdown'])}{display_status status=$LN_transfers_status_shutdown infoarray=$infoarray_download['shutdown']}{/if}
{if isset($infoarray_download['rarfailed'])}{display_status status=$LN_transfers_status_unrarfailed infoarray=$infoarray_download['rarfailed']}{/if}
{if isset($infoarray_download['par2failed'])}{display_status status=$LN_transfers_status_par2failed infoarray=$infoarray_download['par2failed']}{/if}
{if isset($infoarray_download['cksfvfailed'])}{display_status status=$LN_transfers_status_cksfvfailed infoarray=$infoarray_download['cksfvfailed']}{/if}
{if isset($infoarray_download['dlfailed'])}{display_status status=$LN_transfers_status_dlfailed infoarray=$infoarray_download['dlfailed']}{/if}
{if empty($infoarray_download)}
<tr><td colspan="8" class="centered highlight even bold">{$LN_error_nodownloadsfound}</td></tr>
{/if}

<tr><td colspan="8" class="feet round_both_bottom">&nbsp;</td></tr>
</table>
{/if}

