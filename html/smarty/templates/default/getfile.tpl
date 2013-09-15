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
 * $LastChangedDate: 2013-05-20 00:39:39 +0200 (ma, 20 mei 2013) $
 * $Rev: 2828 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: getfile.tpl 2828 2013-05-19 22:39:39Z gavinspearhead@gmail.com $ 
*}{include file=$header title=$title|escape:htmlall}
<div id="textcontent">
</div>
<script type="text/javascript">
$(document).ready(function() {
    show_image("{$file|escape:javascript}", "{$idx|escape:javascript}");
});
</script>
<input type="hidden" id="filename" value="{$file}"/>
<input type="hidden" id="preview" value="{$preview}"/>
<input type="hidden" id="idx" value="{$idx}"/>
<br/>

{include file=$footer}
