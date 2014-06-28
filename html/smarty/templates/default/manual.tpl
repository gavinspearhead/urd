{* Smarty 

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
 * $Id: manual.tpl 2955 2013-11-22 22:41:14Z gavinspearhead@gmail.com $ *}
{include file="head.tpl" title=$title}

<div id="textcontent">
<div class="urdlogo2 floatright noborder buttonlike" onclick="javascript:jump('index.php');"></div>
<h3 class="title">{$title}</h3>

{$i="1"}
{foreach $LN_manual_content as $a}
<h3>{$i}. {$a[0]}</h3>
{$a[1]}
<br/>
{$i="`$i+1`"}
{/foreach}
</div>
<br/>
<script>
$(document).ready(function() {
        update_search_bar_height();
    }
);
</script>

{include file="foot.tpl"}
