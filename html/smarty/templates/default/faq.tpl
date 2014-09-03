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
 * $Id: faq.tpl 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $
 *}

{extends file="head.tpl"}
{block name=contents}
<div id="textcontent">
<div class="urdlogo2 floatright noborder buttonlike" id="urd_logo"></div>
<h3 class="title">{$LN_faq_title}</h3>

{$i="1"}
{foreach $LN_faq_content as $a}
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
    $('#urd_logo').click( function() { jump('index.php'); });
});
</script>

{/block}
