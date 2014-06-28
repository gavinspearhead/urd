{* Smarty *}{*
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
 * $LastChangedDate: 2014-06-12 23:24:27 +0200 (do, 12 jun 2014) $
 * $Rev: 3089 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: admin_log.tpl 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 *}
{include file="head.tpl" title=$title}

<div id="searchformdiv" class="hidden">
<h3 class="title">{$LN_log_title|capitalize}</h3>

<input type="hidden" value="{$sort_dir|escape:htmlall}" id="order_dir"/>
<input type="hidden" value="{$sort|escape:htmlall}" id="order"/>
<div>
{$LN_log_lines}: <input type="text" id="lines" name="lines" value="{$lines|escape:htmlall}" size="6"/>
{$LN_search}: <input type="text" name="search" id="search" placeholder="{$LN_search}" value="{$search|escape:htmlall}" size="30"/>
{$LN_log_level}: 
<select name="log_level" id="log_level">
{html_options options=$log_str selected=$log_level}
</select>
<input type="submit" name="submit_button" value="{$LN_search}" class="vbot submitsmall" onclick="javascript:show_logs();"/>
</div>
</div>

<div id="logdiv">
</div>
<script type="text/javascript">

$(document).ready(function() {
        show_logs();
        $('#searchbar').html( $('#searchformdiv').html());
});
</script>


{include file="foot.tpl"}
