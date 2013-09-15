{if isset($__message) && is_array($__message) && count($__message) > 0 }
<div id="overlay">
<div id="message">
<div id="hideoverlay">
<div class="closebutton buttonlike noborder" 
 onclick="javascript:overlaydiv = document.getElementById('overlay'); overlaydiv.style.zIndex = -100;
           overlaydiv.innerHTML = ''; overlaydiv.style.height = '0px'; overlaydiv.style.width = '0px';"></div>
</div>
<div id="messagecontent">
<h3>{$LN_fatal_error_title}:</h3>
{foreach $__message as $msg}
{$msg}<br/>
{/foreach}
</div>
</div>
</div>
{/if}

