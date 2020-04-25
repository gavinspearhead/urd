<!DOCTYPE html>
{* Smarty *}{*
 *  vim:ts=4:expandtab:cindent
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
 * $LastChangedDate: 2011-01-15 01:03:01 +0100 (Sat, 15 Jan 2011) $
 * $Rev: 2027 $
 * $Author: gavinspearhead $
 * $Id: browse.tpl 2027 2011-01-15 00:03:01Z gavinspearhead $
 *}
<html>
<head>
<link rel="stylesheet" href="{$CSSDIR}/_basic.css" type="text/css"/>
<link rel="stylesheet" href="{$CSSDIR}/{if $stylesheet != ''}{$stylesheet|replace:".css":""}/{$stylesheet}.css{else}light/light.css{/if}" type="text/css"/>
<link rel="stylesheet" href="{$CSSDIR}/{if $stylesheet != ''}{$stylesheet|replace:".css":""}{else}/light{/if}/jquery-ui.css" type="text/css"/>
<link rel="SHORTCUT ICON" href="favicon.ico" type="image/x-icon"/>
<title>{$LN_update_database}</title>
<meta http-equiv="Content-Language" content="en-us"/>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
<meta name="resource-type" content="document"/>

<script type="text/javascript" src="{$JSDIR}/jquery-3.5.0.min.js"></script>
<script type="text/javascript" src="{$JSDIR}/js.js"></script>
</head>
<body>
<div class="Message hidden attop" id="message_bar"></div>
<p></p>
<div id="topcontent">
<div id="contentout">
<div id="textcontent">
<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<h3 class="title">{$LN_update_database}</h3>
<div id="updatedbdiv">
{$LN_loading}
</div>
<script type="text/javascript">
$(document).ready(function() {
    start_updatedb();
    $('#close_button').click(function() { history.go(-1); });
});
</script>
</div>
</div>
</div>
</body>
</html>
