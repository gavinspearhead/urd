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
 * $LastChangedDate: 2013-07-20 00:48:03 +0200 (za, 20 jul 2013) $
 * $Rev: 2878 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: admin_log.tpl 2878 2013-07-19 22:48:03Z gavinspearhead@gmail.com $
 *}
{include file="head.tpl" title=$title}
<h3 class="title">{$LN_log_title|capitalize}</h3>

<input type="hidden" value="{$sort_dir}" id="sort_dir"/>
<input type="hidden" value="{$sort}" id="sort_order"/>
<div>
{$LN_log_lines}: <input type="text" id="lines" name="lines" value="{$lines}" size="6"/>
{$LN_search}: <input type="text" name="search" id="search" value="{if $search == ''}&lt;{$LN_search}&gt;{else}{$search|escape:htmlall}{/if}" onfocus="javascript:clean_search('search');" size="30"/>
{$LN_log_level}: 
<select name="log_level" id="log_level">
{html_options options=$log_str selected=$log_level}
</select>
<input type="submit" name="submit_button" value="{$LN_search}" class="vbot submitsmall" onclick="javascript:show_logs();"/>
</div>
<div><br/></div>

<div id="logdiv">
</div>
<script type="text/javascript">

$(document).ready(function() {
        show_logs();
});
</script>


{include file="foot.tpl"}
