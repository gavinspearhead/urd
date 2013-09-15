{* Smarty *}{*
 *  This file is part of Urd.
 *  vim:ts=4:expandtab:cindent
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
 * $Id: rssfeeds.tpl 2823 2013-04-27 22:55:09Z gavinspearhead@gmail.com $
 *}
{include file="head.tpl" title=$title}

{capture assign=submit}
<span class="ng_submit">{strip}
<input type="button" value="{$LN_apply}" class="submit" id="rss_apply" onclick="javascript:rss_feeds_update();"/>&nbsp;
</span>
{/strip}
{/capture}

<br/>
<div>
<input type="text" name="search" value="{if $search == ''}&lt;{$LN_search}&gt;{else}{$search|escape:htmlall}{/if}" onfocus="if (this.value=='&lt;{$LN_search}&gt;') this.value='';" id="newsearch" size="30" onkeypress="javascript:submit_enter(event, load_rss_feeds);"/>
{urd_checkbox value="$search_all" name="search_all" id="search_all" data="$LN_feeds_hide_empty"}
&nbsp;
<input type="button" value="{$LN_search}" class="submitsmall" onclick="javascript:load_rss_feeds();"/>
<div class="floatright submit_button_right">{$submit}</div>
</div>

<div class="innerwaitingdiv" id="waitingdiv">
<div class="waitingimg centered"></div>
<p><br/></p>
<h3 class="centered">{$LN_loading}</h3>
</div>

<div id="rss_feeds_div"></div>

<p>&nbsp;</p>
<script type="text/javascript">
$(document).ready(function() {
    load_rss_feeds();
});
</script>

<p>&nbsp;</p>
<input type="hidden" name="type" id="type" value="rss"/>
<input type="hidden" id="ln_saved" value="{$LN_pref_saved}"/>
<input type="hidden" id="ln_failed" value="{$LN_failed}"/>

{include file="foot.tpl"}
