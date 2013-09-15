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
 * $Id: admin_jobs.tpl 2823 2013-04-27 22:55:09Z gavinspearhead@gmail.com $
 *}
{include file="head.tpl" title=$title}

<h3 class="title">{$LN_jobs_title}</h3>

<div id="jobsdiv">
</div>

<script type="text/javascript">
$(document).ready(function() {
    update_jobs();
});
</script>

{include file="foot.tpl"}
