
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
 * $Id: ajax_calendar.tpl 2909 2013-09-02 21:20:45Z gavinspearhead@gmail.com $
 *}

<div class="closebutton buttonlike noborder fixedright down5" id="close_button2"></div>
<div class="set_title centered">&nbsp;</div>

<div><br/>
<div id="leftcalendar" class="light">
<table class="centered">
<tr>
<td colspan="7" class="centered">
<span onclick="javascript:show_calendar({$month}, {$year} - 1, 1)" class="buttonlike" {urd_popup type="small" text="$LN_previous $LN_year"|capitalize }>&lt;&lt;</span>
<span onclick="javascript:show_calendar({$previous_month.0}, {$previous_month.1},1 )" class="buttonlike" {urd_popup type="small" text="$LN_previous $LN_month"|capitalize }>&lt;</span> 
<span class="bold"> {$LN_month_names.$month} {$year}</span>
<span class="buttonlike" onclick="javascript:show_calendar({$next_month.0}, {$next_month.1}, 1)" {urd_popup type="small" text="$LN_next $LN_month"|capitalize }>&gt; </span>
<span class="buttonlike" onclick="javascript:show_calendar({$month}, {$year} + 1),1" {urd_popup type="small" text="$LN_next $LN_year"|capitalize }>&gt;&gt;</span>
</td></tr>
<tr>
{foreach $LN_short_day_names as $name}
<th class="right">{$name}</th>
{/foreach}
</tr>
{foreach $dates as $week}
<tr>
{foreach $week as $day}
<td class="right{if $day==$selected_day and $selected_day neq 0} highlight3{/if}" id="day_{$day}">
{if $day neq 0}
<span class="buttonlike{if $today==$day} highlight{/if}" onclick="javascript:select_calendar({$day});">
{$day}
</span>
{/if}
</td>
{/foreach}
</tr> 
{/foreach}
</table>
</div>
<div id="rightcalendar">
<div class="leftward" id="theleft">
<table>
<tr>
<td>{$LN_hour}:</td>
<td><div id="hours" style="width:100px"></div></td>
</tr>
<tr>
<td><div>&nbsp;</div></td>
</tr>
<tr>
<td>{$LN_minute}:</td>
<td><div id="minutes" style="width:100px"></div></td>
</tr>
<tr>
<td>
<div>
<input name="time" id="time1" type="text" value="{$hour}:{$minute}" size="5"/>
</div></td>
</tr>
</table>
</div>
</div>
</div>
<br/>
<br/>
<br/>
<div class="right" id="calendarbottom">
<input class="submit" type="button" name="submit_no_delay" value="{$LN_atonce}" onclick="javascript:submit_calendar('atonce');"/>
<input class="submit" type="button" name="submit" value="{$LN_ok}" onclick="javascript:submit_calendar();"/>
</div>
<input type="hidden" id="month" value="{$month}"/>
<input type="hidden" id="year" value="{$year}"/>
<input type="hidden" id="day" value="{$selected_day}"/>
<input type="hidden" id="hour" value="{$hour}"/>
<input type="hidden" id="minute" value="{$minute}"/>
<input name="date" id="date1" type="hidden" value="{$year}-{$month}-{$selected_day}"/>
