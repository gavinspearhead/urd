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
 * $LastChangedDate: 2013-07-09 00:30:22 +0200 (di, 09 jul 2013) $
 * $Rev: 2870 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: viewfiles.tpl 2870 2013-07-08 22:30:22Z gavinspearhead@gmail.com $
 *}
{include file="head.tpl" title=$title}

<h3 class="title">{$LN_viewfilesheading}: <span id="directory_top">{$directory|escape:htmlall|truncate:$maxstrlen:'...':false:true}</span></h3>
<div>
<input type="text" name="search" size="30" value="&lt;{$LN_search}&gt;" id="search"/>
<input type="button" value="{$LN_search}" class="submitsmall" onclick="javascript:show_files_clean();"/>
</div>
<div id="viewfilesdiv">

<div class="innerwaitingdiv" id="waitingdiv">
<div class="waitingimg centered"></div>
<p><br/></p>

<h3 class="centered">{$LN_loading_files}</h3>
</div>
<script type="text/javascript">
$(document).ready(function() {
    show_files( { 'curdir':'{$directory|escape:javascript}' } );
    set_scroll_handler('#contentout', show_files);
});
</script>
</div>

{if $show_usenzb}
<div id="uploadnzbdiv" class="uploadnzboff">
</div>
{/if}

<input type="hidden" id="perpage" value="{$perpage}"/>

{include file="foot.tpl"}
