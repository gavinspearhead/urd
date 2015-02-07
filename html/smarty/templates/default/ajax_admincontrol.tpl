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
 * $LastChangedDate: 2014-06-25 18:31:48 +0200 (wo, 25 jun 2014) $
 * $Rev: 3115 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_admincontrol.tpl 3115 2014-06-25 16:31:48Z gavinspearhead@gmail.com $
 *}

{if $isconnected == 1}
<p></p>
<table>
<tr><td><b>{$LN_urdname} {$LN_version}</b>:</td><td><i>{$VERSION}</i></td><td></td></tr>
<tr><td><b>{$LN_control_uptime}</b>:</td><td><i>{$uptime_info}</i></td><td></td></tr>
<tr><td><b>{$LN_control_load}</b>:</td><td><i>{$load_info.load_1} -- {$load_info.load_5} -- {$load_info.load_15}</i></td><td></td></tr>
<tr> <td><b>{$LN_control_diskspace}</b>:</td>
{strip}
<td>
{urd_progressbar width=200 complete=$nodisk_perc done=progress_done2 remain=progress_done colour=red background=green } 
</td>
{/strip}
<td {if $disk_perc < 10} class="warning_highlight"{/if}>({$disk_perc}% - {$diskfree})</td>
</tr>
</table>
<br/>

<br/>
<h3>&nbsp;{$LN_details} 
<div id="details_button" class="floatleft iconsize {if $control_status != 1}dynimgplus{else}dynimgminus{/if} noborder buttonlike" {urd_popup type="small" text=$LN_details }></div></h3>
<div id="details_div" {if $control_status != 1 }class="hidden"{/if}>

<h4>{$LN_control_threads}</h4>
{if $threads_info}
<table class="width80p">
<tr>
<th class="centered head round_left">{$LN_id}</th>
<th class="centered head">{$LN_pid}</th>
<th class="centered head">{$LN_username}</th>
<th class="centered head">{$LN_jobs_command}</th>
<th class="centered head">{$LN_config_prog_params}</th>
<th class="centered head">{$LN_server}</th>
<th class="centered head">{$LN_status}</th>
<th class="centered head">{$LN_start_time}</th>
<th class="centered head round_right">{$LN_queue_time}</th>
</tr>

{foreach $threads_info as $t}
<tr>
<td>{$t.id}</td>
<td>{$t.pid}</td>
<td>{$t.username}</td> 
<td>{$t.command}</td>
<td>{$t.arguments}</td>
<td>{if $t.server != 0}{$t.servername} ({$t.server}){/if}</td>
<td>{$t.status}</td> 
<td class="right">{$t.starttime}</td>
<td class="right">{$t.queuetime}</td>
</tr>
{/foreach}
</table>
{/if}

<h4>{$LN_control_queue}</h4>
{if $queue_info}
<table class="width80p">
<tr>
<th class="centered head round_left">ID</th>
<th class="centered head">{$LN_username}</th>
<th class="centered head">{$LN_usenet_priority}</th>
<th class="centered head">{$LN_jobs_command}</th>
<th class="centered head">{$LN_config_prog_params}</th>
<th class="centered head">{$LN_status}</th>
<th class="centered head round_right">{$LN_time}</th>
</tr>
{foreach $queue_info as $q}
<tr>
<td>{$q.id}</td>
<td>{$q.username}</td>
<td class="right">{$q.priority}</td>
<td>{$q.command}</td>
<td>{$q.arguments}</td>
<td>{$q.status}</td>
<td>{$q.time}</td>
</tr>
{/foreach}
</table>
{/if}

<h4>{$LN_control_jobs}</h4>
{if $jobs_info}
<table class="width80p">
<tr>
<th class="centered head round_left">ID</th>
<th class="centered head">{$LN_username}</th>
<th class="centered head">{$LN_jobs_command}</th>
<th class="centered head">{$LN_config_prog_params}</th>
<th class="centered head">{$LN_time}</th>
<th class="centered head round_right">{$LN_recurrence}</th>
</tr>
{foreach $jobs_info as $j}
<tr>
<td>{$j.id}</td>
<td>{$j.username}</td>
<td>{$j.command}</td> 
<td>{$j.arguments}</td>
<td class="right">{$j.time}</td>
<td class="right">{$j.recurrence}</td>
</tr>
{/foreach}
</table>
{/if}

<h4>{$LN_control_servers}</h4>
{if $servers_info}
<table class="width80p">
<tr>
<th class="centered head round_left">ID</th>
<th class="centered head">{$LN_usenet_hostname}:{$LN_usenet_port}</th>
<th class="centered head">{$LN_usenet_priority}</th>
<th class="centered head">{$LN_usenet_nrofthreads}</th>
<th class="centered head">{$LN_free_threads}</th>
<th class="centered head">{$LN_enabled}</th>
<th class="centered head">{$LN_usenet_posting}</th>
<th class="centered head round_right">{$LN_usenet_indexing}</th>
</tr>
{$servers_info|count}
{foreach $servers_info as $s}
<tr>
<td>{$s.id}</td>
<td>{$s.hostname}:{$s.port}</td>
<td class="right">{$s.priority}</td> 
<td class="right">{$s.max_threads}</td>
<td class="right">{$s.free_threads}</td>
<td>{if $s.enabled == 'TRUE'}{$LN_on}{else}{$LN_off}{/if}</td>
<td>{if $s.posting == 'TRUE'}{$LN_on}{else}{$LN_off}{/if}</td>
<td>{if $s.preferred == 'TRUE'}{$LN_on}{else}{$LN_off}{/if}</td>
</tr>
{/foreach}
</table>
{/if}

<p>&nbsp;</p>
<table>
<tr><th colspan="2" class="head centered round_both">{$LN_control_threads}</th></tr>
<tr><td>{$LN_dashboard_max_nntp}</td><td class="right">{$servers_totals.total_nntp}</td></tr>
<tr><td>{$LN_free_nntp_threads}</td><td class="right">{$servers_totals.free_nntp}</td></tr>
<tr><td>{$LN_dashboard_max_threads}</td><td class="right">{$servers_totals.total_threads}</td></tr>
<tr><td>{$LN_total_free_threads}</td><td class="right">{$servers_totals.free_total}</td></tr>
<tr><td>{$LN_dashboard_max_db_intensive}</td><td class="right">{$servers_totals.db_intensive}</td></tr>
<tr><td>{$LN_free_db_intensive_threads}</td><td class="right">{$servers_totals.free_db_intensive}</td></tr>

{else}
{$LN_urdddisabled}
{/if}

