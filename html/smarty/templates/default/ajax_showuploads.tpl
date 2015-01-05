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
 * $Id: ajax_showuploads.tpl 3122 2014-06-27 21:31:25Z gavinspearhead@gmail.com $
 *}
{* Ajax page, doesn't need a head/foot tpl *}

{function name=display_status status='' infoarray=''}

{$stat=$infoarray['items'][0]->status|replace:' ':'_'}
<tbody>
<tr class="transferstatus"><td colspan="{if $isadmin}5{else}3{/if}">{$status}</td>
<td colspan="2" class="right nowrap">{$infoarray['size']} {$infoarray['suffix']}</td>
<td>
   <div class="black floatright iconsize noborder buttonlike">
        <div id="{$stat}post" class="floatright iconsize blackbg {if $post_hide_status.$stat == 1}dynimgplus{else}dynimgminus{/if} noborder buttonlike" onclick="javascript:fold_transfer('{$stat}', 'post');">        </div>
    </div>
    </td>
</tr>
</tbody>


{foreach $infoarray['items'] as $a}
{$stat=$a->status|replace:' ':'_'}
<tbody id="data_post_{$stat}" class="{if $post_hide_status.$stat == 1}hidden{/if}">

{capture name=opts assign="options"}
{strip}
<div class="inline">
{if $a->nzb != ''}
<div class="inline iconsizeplus downicon buttonlike" onclick="javascript:jump('getfile.php?file={$a->nzb}');" {urd_popup type="small" text=$LN_browse_savenzb }></div>
{/if}
{if $a->status == "rarfailed" && $a->directory != ''}
<div class="inline iconsizeplus infoicon buttonlike" {urd_popup type="small" text=$LN_transfers_badrarinfo } onclick="javascript:show_contents('{$a->destination}/rar.log', 0);"></div><
{/if}
{if $a->status == "par2failed" && $a->directory != ''}
<div class="inline iconsizeplus infoicon buttonlike" {urd_popup type="small" text=$LN_transfers_badparinfo } onclick="javascript:show_contents('{$a->destination}/par2.log', 0);"></div>
{/if}
<div class="inline iconsizeplus editicon buttonlike" onclick="javascript:show_edit_post('{$a->postid}');"></div>
{if ($a->status == "paused" OR $a->status == "ready") AND $urdd_online}
<div class="inline iconsizeplus playicon buttonlike" onclick="javascript:post_edit('start','{$a->postid}')" {urd_popup type="small" text=$LN_transfers_linkstart }></div>
{/if}

{if ($a->status == "active" OR $a->status == "queued") AND $urdd_online}
<div class="inline iconsizeplus pauseicon buttonlike" onclick="javascript:post_edit('pause','{$a->postid}')" {urd_popup type="small" text=$LN_pause }></div>
{/if}

{if ($a->status == "queued" OR $a->status == "paused" OR $a->status == "active" OR $a->status == "ready") AND $urdd_online}
<div class="inline iconsizeplus killicon buttonlike" onclick="javascript:post_edit('cancel','{$a->postid}')" {urd_popup type="small" text=$LN_cancel }></div>
{/if}

{if $urdd_online}
<div class="inline iconsizeplus deleteicon buttonlike" onclick="javascript:post_edit('delete','{$a->postid}')" {urd_popup type="small" text=$LN_delete }></div>
{/if}
</div>
{/strip}
{/capture}

	<tr class="even" name="content">
		<td class="bold nowrap">
        <div class="donotoverflowdamnit inline">{$a->name|escape:htmlall}</div>
        </td>
        {if $isadmin}
            <td>{$a->username|escape:htmlall}</td>
        {/if}
		<td class="nowrap fixwidth8a">
            {urd_progressbar width="100" complete="{$a->progress}" colour="green" background="grey" classes="down2" text="{$a->progress}%"}
        </td>
		<td class="right nowrap">{$a->speed}</td>
		<td class="center nowrap">{$a->ETA}</td>
		<td class="nowrap right">{$a->startdate}</td>
		<td class="right nowrap">{$a->size}</td>
		<td class="rightbut">{$options}</td>
	</tr>
{/foreach}
</tbody>
{/function}

{if ($poster != 0 || $isadmin != 0) && $show_post != 0}
<table class="transfers {if $active_tab != 'uploads'}hidden{/if}" id="uploads_tab">
<thead>
<tr>
<th class="left nowrap head round_left" width="100%" id="browsesubjecttd">{$LN_transfers_head_subject}</th>
{if $isadmin}
<th class="left nowrap head">{$LN_transfers_head_username}</th>
{/if}
<th class="left nowrap head fixwidth8a">{$LN_transfers_head_progress}</th>
<th class="center nowrap head">{$LN_transfers_head_speed}</th>
<th class="left nowrap head">{$LN_eta}</th>
<th class="left nowrap head ">{$LN_transfers_head_started}</th>
<th class="center nowrap head">{$LN_size}</th>
<th class="right head nowrap round_right">{$LN_transfers_head_options}</th>
</tr>
</thead>

{if isset($infoarray_upload['active'])}{display_status status=$LN_transfers_status_postactive infoarray=$infoarray_upload['active']}{/if}
{if isset($infoarray_upload['rarred'])}{display_status status=$LN_transfers_status_rarred infoarray=$infoarray_upload['rarred']}{/if}
{if isset($infoarray_upload['par2ed'])}{display_status status=$LN_transfers_status_par2ed infoarray=$infoarray_upload['par2ed']}{/if}
{if isset($infoarray_upload['ready'])}{display_status status=$LN_transfers_status_ready infoarray=$infoarray_upload['ready']}{/if}
{if isset($infoarray_upload['queued'])}{display_status status=$LN_transfers_status_queued infoarray=$infoarray_upload['queued']}{/if}
{if isset($infoarray_upload['paused'])}{display_status status=$LN_transfers_status_paused infoarray=$infoarray_upload['paused']}{/if}
{if isset($infoarray_upload['finished'])}{display_status status=$LN_transfers_status_finished infoarray=$infoarray_upload['finished']}{/if}
{if isset($infoarray_upload['cancelled'])}{display_status status=$LN_transfers_status_cancelled infoarray=$infoarray_upload['cancelled']}{/if}
{if isset($infoarray_upload['stopped'])}{display_status status=$LN_transfers_status_stopped infoarray=$infoarray_upload['stopped']}{/if}
{if isset($infoarray_upload['error'])}{display_status status=$LN_transfers_status_error infoarray=$infoarray_upload['error']}{/if}
{if isset($infoarray_upload['shutdown'])}{display_status status=$LN_transfers_status_shutdown infoarray=$infoarray_upload['shutdown']}{/if}
{if isset($infoarray_upload['yyencoded'])}{display_status status=$LN_transfers_status_yyencoded infoarray=$infoarray_upload['yyencoded']}{/if}
{if isset($infoarray_upload['rarfailed'])}{display_status status=$LN_transfers_status_rarfailed infoarray=$infoarray_upload['rarfailed']}{/if}
{if isset($infoarray_upload['par2failed'])}{display_status status=$LN_transfers_status_par2failed infoarray=$infoarray_upload['par2failed']}{/if}
{if isset($infoarray_upload['yyencodefailed'])}{display_status status=$LN_transfers_status_yyencodefailed infoarray=$infoarray_upload['yyencodefailed']}{/if}
{if empty($infoarray_upload)}
<tr><td colspan="8" class="centered highlight even bold">{$LN_error_nouploadsfound}</td></tr>
{/if}

<tr><td colspan="8" class="feet round_both_bottom">&nbsp;</td></tr>
</table>
{/if}

