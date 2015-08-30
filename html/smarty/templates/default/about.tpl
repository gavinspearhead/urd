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
 * $LastChangedDate: 2013-11-22 23:41:14 +0100 (vr, 22 nov 2013) $
 * $Rev: 2955 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: about.tpl 2955 2013-11-22 22:41:14Z gavinspearhead@gmail.com $
 *}
{extends file="head.tpl"}
{block name=contents}

<div id="textcontent">
<div class="urdlogo2 floatright noborder buttonlike" id="urd_logo"></div>
<h3 class="title">{$LN_urdname} {$VERSION} ({$status})</h3>

<p>{$copyright}</p>
<p>{$LN_website}: <a href="{$url}">{$url}</a></p>
<p>{$LN_abouttext1}</p>
<p>{$LN_abouttext2}</p>
<p>{$LN_abouttext3}</p>
</div>
<br/>
<script>
$(document).ready(function() {
    update_search_bar_height();
    $('#urd_logo').click( function() { jump('index.php'); });
});
</script>

{/block}
