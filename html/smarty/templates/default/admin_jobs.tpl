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
 * $LastChangedDate: 2013-12-07 17:40:41 +0100 (za, 07 dec 2013) $
 * $Rev: 2972 $
 * $Author: gavinspearhead@gmail.com $
 * $Id: admin_jobs.tpl 2972 2013-12-07 16:40:41Z gavinspearhead@gmail.com $
 *}
{extends file="head.tpl"}
{block name=contents}

<div id="searchformdiv" class="hidden">
<h3 class="title">{$LN_jobs_title}</h3>
</div>

<div id="jobsdiv">
</div>

<script type="text/javascript">
$(document).ready(function() {
    update_jobs();
    $('#searchbar').html( $('#searchformdiv').html());
    $('#searchformdiv').html('')
});
</script>

{/block}
