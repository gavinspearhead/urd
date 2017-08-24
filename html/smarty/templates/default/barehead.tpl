<!DOCTYPE html>
<html>
<head>
<title>{$title|capitalize|escape}</title>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
{if !$allow_robots}
<meta name="robots" content="noindex, nofollow"/>
{/if}
<script type="text/javascript" src="{$JSDIR}/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="{$JSDIR}/jquery-ui.js"></script>
<script type="text/javascript" src="{$JSDIR}/js.js"></script>
<link rel="stylesheet" href="{$CSSDIR}/_basic.css" type="text/css"/>
<link rel="stylesheet" href="{$CSSDIR}/{if $stylesheet != ''}{$stylesheet|replace:".css":""}/{$stylesheet}.css{else}light/light.css{/if}" type="text/css"/>
<link rel="stylesheet" href="{$CSSDIR}/{if $stylesheet != ''}{$stylesheet|replace:".css":""}{else}/light{/if}/jquery-ui.css" type="text/css"/>
<!--[if IE]>
<link rel="stylesheet" href="{$CSSDIR}/_iehacks.css" type="text/css"/>
<![endif]--> 
<link rel="SHORTCUT ICON" href="favicon.ico" type="image/x-icon"/>

</head>
<body>
<div class="Message hidden" id="message_bar">
<div id="message_icon" class="inline iconsizeplus previewicon buttonlike"></div>
<div id="message_content" class="inline" ></div>
</div>
<div id="overlay_back">
<div id="overlay_content"></div>
</div>
{block name="contents"}
{/block}
<script type="text/javascript">
$(document).ready(function() {
    $('#message_bar').click( function() { hide_message('message_bar', 0); } );
});
</script>

</body>
<!-- URD v{$VERSION} -->
</html>
