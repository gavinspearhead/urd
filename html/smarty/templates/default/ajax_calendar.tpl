
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
 * $LastChangedDate: 2014-06-12 23:24:27 +0200 (do, 12 jun 2014) $
 * $Rev: 3089 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: ajax_calendar.tpl 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 *}

{extends file="popup.tpl"}
{block name=title}&nbsp;{/block}
{block name=contents}

<div><br/>
<div id="leftcalendar" class="light">
<table class="centered">
<tr>
<td colspan="7" class="centered">
<span class="buttonlike" id="prev_year" {urd_popup type="small" text="$LN_previous $LN_year"|capitalize }>&lt;&lt;</span>
<span class="buttonlike"id="prev_month"  {urd_popup type="small" text="$LN_previous $LN_month"|capitalize }>&lt;</span> 
<span class="bold">{$LN_month_names.$month} {$year}</span>
<span class="buttonlike"id="next_month" {urd_popup type="small" text="$LN_next $LN_month"|capitalize }>&gt;</span>
<span class="buttonlike" id="next_year" {urd_popup type="small" text="$LN_next $LN_year"|capitalize }>&gt;&gt;</span>
</td></tr>
<tr>
{foreach $LN_short_day_names as $name}
    <th class="right">{$name}</th>
{/foreach}
</tr>
{foreach $dates as $week}
    <tr>
    {foreach $week as $day}
        <td class="right{if $day==$selected_day and $selected_day != 0} highlight3{/if}" id="day_{$day}">
        {if $day != 0}
            <span name="day" class="buttonlike{if $today == $day} highlight{/if}">{$day}</span>
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
<td>&nbsp;</td>

<td>
<div>
<br/>
<input name="time" id="time1" type="text" value="{$hour|escape:htmlall}:{$minute|escape:htmlall}" size="5"/>
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
<input class="submit" type="button" name="submit_no_delay" id="submit_no_delay" value="{$LN_atonce}"/>
<input class="submit" type="button" name="submit" id="submit_cal" value="{$LN_ok}"/>
</div>
<input type="hidden" id="month" value="{$month|escape:htmlall}"/>
<input type="hidden" id="year" value="{$year|escape:htmlall}"/>
<input type="hidden" id="day" value="{$selected_day|escape:htmlall}"/>
<input type="hidden" id="hour" value="{$hour|escape:htmlall}"/>
<input type="hidden" id="minute" value="{$minute|escape:htmlall}"/>
<input name="date" id="date1" type="hidden" value="{$year|escape:htmlall}-{$month|escape:htmlall}-{$selected_day|escape:htmlall}"/>

{/block}
