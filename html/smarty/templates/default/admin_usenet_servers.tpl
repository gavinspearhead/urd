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
 * $LastChangedDate: 2013-04-28 00:55:09 +0200 (zo, 28 apr 2013) $
 * $Rev: 2823 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: admin_usenet_servers.tpl 2823 2013-04-27 22:55:09Z gavinspearhead@gmail.com $
 *}
{include file="head.tpl" title=$title}
<h3 class="title">{$title}</h3>

<div class="hidden">
<input type="hidden" name="action_id" id="action_id" value=""/>
<input type="hidden" name="action" id="action" value=""/></div>

<div>
<input type="text" name="search" value="&lt;{$LN_search}&gt;" onfocus="javascript:clean_search('search');" id="search" size="30" onkeypress="javascript:submit_enter(event, show_usenet_servers);"/>
<input type="button" value="{$LN_search}" class="submitsmall" onclick="javascript:show_usenet_servers();"/></div>
<br/>

<div id="usenetserversdiv">
<script type="text/javascript">
$(document).ready(function() {
    show_usenet_servers();
});
</script>
</div>
<div><br/></div>

<p>&nbsp;</p>

{include file="foot.tpl"}
