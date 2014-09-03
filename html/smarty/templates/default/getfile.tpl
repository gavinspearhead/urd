{* Smarty *}
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
 * $Id: getfile.tpl 3089 2014-06-12 21:24:27Z gavinspearhead@gmail.com $ 
*}

{extends file="head.tpl"}
{block name=contents}
<div id="textcontent">
</div>

<input type="hidden" id="filename" value="{$file|escape}}"/>
<input type="hidden" id="preview" value="{$preview|escape}}"/>
<input type="hidden" id="idx" value="{$idx|escape}}"/>
<br/>

<script type="text/javascript">
$(document).ready(function() {
    show_image("{$file|escape:javascript}", "{$idx|escape:javascript}");
});
</script>
{/block}
