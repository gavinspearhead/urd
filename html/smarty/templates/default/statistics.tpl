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
 * $LastChangedDate: 2013-06-01 00:52:04 +0200 (za, 01 jun 2013) $
 * $Rev: 2832 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: statistics.tpl 2832 2013-05-31 22:52:04Z gavinspearhead@gmail.com $
 *}
{include file="head.tpl" title=$title}
<h3 class="title">{$title}</h3>

{capture assign=selector}
<div class="pref_selector">{strip}
<input type="hidden" id="selected" value="{$thisyear}"/>
<input type="hidden" id="tab" value="{$tab}"/>
<ul class="tabs">
{foreach $years as $year}
<li onclick="javascript:select_tab_stats('{$year|escape:javascript}', 'activity', '{$year|escape:javascript}', 'months')" class="tab" id="{$year}_bar">{$year}
<input type="hidden" name="tabs" value="{$year}"/>
</li>
{/foreach}
<li onclick="javascript:select_tab_stats('activity', 'activity', null, 'years')" class="tab" id="activity_bar">{$LN_stats_overview}
<input type="hidden" name="tabs" value="activity"/></li>
<li onclick="javascript:select_tab_stats('supply', 'supply')" class="tab" id="supply_bar">{$LN_menubrowsesets}
<input type="hidden" name="tabs" value="supply"/></li>
</ul>
</div>
{/strip}
{/capture}

{* Creating the divs as the basis for copy/pasting in javascript later on *}
<div id="ng_headerbox" class="newsgroups">
{$selector}
</div>

<table class="statistics" id="stats_table">
<tr><td>
<div id="show_stats">
</div>
</td></tr></table>

<script type="text/javascript">
$(document).ready(function() {
    select_tab_stats('{$thisyear|escape:javascript}', 'activity', '{$thisyear|escape:javascript}', 'months');
});
</script>

{include file="foot.tpl"}
