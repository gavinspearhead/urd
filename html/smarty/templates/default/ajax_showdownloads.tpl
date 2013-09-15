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
 * $LastChangedDate: 2013-09-02 23:20:45 +0200 (ma, 02 sep 2013) $
 * $Rev: 2909 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_showdownloads.tpl 2909 2013-09-02 21:20:45Z gavinspearhead@gmail.com $
 *}
{* Ajax page, doesn't need a head/foot tpl *}

{if $show_download neq 0} 
<table class="transfers {if $active_tab != 'downloads'}hidden{/if}" id="downloads_tab">
<thead>
<tr>
<th class="head round_left">{$LN_transfers_head_started}</th>
<th class="head">{$LN_transfers_head_dlname}</th>
<th class="head">{$LN_transfers_head_progress}</th>
<th class="head">{$LN_size}</th>
<th class="head">{$LN_transfers_head_speed}</th>
<th class="head">{$LN_eta}</th>
{if $isadmin neq 0}
<th class="head">{$LN_transfers_head_username}</th>
{/if}
<th class="right head round_right">{$LN_transfers_head_options}</th>
</tr>
</thead>

{function name=display_status status='' infoarray=''}
{$stat=$infoarray[0]->status|replace:' ':'_'}
<tr class="transferstatus">
<td colspan="{if $isadmin neq 0}7{else}6{/if}">{$status}</td>
<td>
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
{if $a->comment != ''}
{$comment=$a->comment}
<div class="inline iconsizeplus infoicon" {urd_popup type="small" text="$comment" } ></div>
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
<div class="inline iconsizeplus previewicon buttonlike" onclick="transfer_edit('reparrar','{$a->dlid}')"{urd_popup type="small" text=$LN_transfers_runparrar }></div>
{/if}

{if ($a->status == "paused" OR $a->status == "ready") AND $urdd_online}
<div class="inline iconsizeplus playicon buttonlike" onclick="transfer_edit('start','{$a->dlid}')" {urd_popup type="small" text=$LN_transfers_linkstart }></div>
{/if}

{if ($a->status == "active" OR $a->status == "queued" OR $a->status == "ready") AND $urdd_online}
<div class="inline iconsizeplus pauseicon buttonlike" onclick="transfer_edit('pause','{$a->dlid}')" {urd_popup type="small" text=$LN_pause }></div>
{/if}

{if ($a->status == "queued" OR $a->status == "paused" OR $a->status == "active" OR $a->status == "ready") AND $urdd_online}
<div class="inline iconsizeplus killicon buttonlike" onclick="transfer_edit('cancel','{$a->dlid}')" {urd_popup type="small" text=$LN_cancel }></div> 
{/if}
{if $urdd_online}
<div class="inline iconsizeplus deleteicon buttonlike" onclick="transfer_edit('delete','{$a->dlid}')" {urd_popup type="small" text=$LN_delete } ></div>
{/if}
</td>
{/strip}
{/capture}

	<tr class="even" onmouseover="javascript:ToggleClass(this,'highlight2');" onmouseout="javascript:ToggleClass(this,'highlight2');">
		<td>{$a->startdate}</td>
		<td><b>{$a->name|truncate:$maxstrlen|escape:htmlall}</b></td>
		<td>

{urd_progressbar width=100 complete=$a->progress}
{$a->progress}%</td>
		<td class="right">{$a->done_size} / {$a->size}</td>
		<td>{$a->speed}</td>
		<td class="center">{$a->ETA}</td>
{if $isadmin neq 0}
		<td>{$a->username|escape:htmlall}</td>
{/if}
		<td class="rightbut"><div class="floatright">{$prio_button} {$options}</div></td>
	</tr>
{/foreach}
</tbody>
{/function}

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
<tr><td colspan="8" class="centered highlight textback">{$LN_error_nodownloadsfound}</td></tr>

{/if}

</table>
{/if}

