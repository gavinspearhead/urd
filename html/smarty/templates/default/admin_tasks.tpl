{* Smarty *}{*
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
 * $Id: admin_tasks.tpl 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 *}
{include file="head.tpl" title=$title}

<div id="searchformdiv" class="hidden">
<h3 class="title">{$LN_tasks_title}</h3>

<select name="status" id="status_select" size="1">
{html_options options=$allstatus}
</select>
<select name="time" id="time_select" size="1">
{html_options options=$alltimes}
</select>

<input type="text" name="_search" placeholder="{$LN_search}" id="tasksearch"/>
<input type="button" id="search" value="{$LN_search}" class="submitsmall"/>
</div>

<div id="tasksdiv">
</div>

<script type="text/javascript">
$(document).ready(function() {
    update_tasks();
    $('#searchbar').html( $('#searchformdiv').html());
    $('#search').click(function() { load_tasks_no_offset(null, null); } );
    $('#tasksearch').keypress(function() { return submit_enter(event, load_tasks_no_offset); } );
});
</script>

<p>&nbsp;</p>

{include file="foot.tpl"}
