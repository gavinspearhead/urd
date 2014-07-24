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
 * $LastChangedDate: 2013-07-14 01:12:10 +0200 (zo, 14 jul 2013) $
 * $Rev: 2874 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: settings.tpl 2874 2013-07-13 23:12:10Z gavinspearhead@gmail.com $
 *}
{include file="head.tpl" title=$title}
{* used for both prefs and admin_config !! *}

<h3 class="title">{$heading}</h3>

<script type="text/javascript">
$(document).ready(function() {
    load_prefs();
});
    
</script>
<input type="hidden" id="source" name="source" value="{$source}"/>
<div id="settingsdiv"></div>

{include file="foot.tpl"}
