
</div>
</div>

<script type="text/javascript">
$(document).ready( function() { 
    init();
    update_quick_status();
    update_disk_status();
    $('#message_bar').click(function() { hide_message('message_bar', 0); } );  
    $('#scrollmenuright').click(function(e) { scroll_menu_right(e); } );
    $('#scrollmenuleft').click(function(e) { scroll_menu_left(e); } );
    $('#smalllogo').click(function(e) { jump('index.php'); } );
    $('#status_item').mouseover(function() { load_activity_status(); } );
    $('#topcontent').mouseup( function() { set_selected();} );
    $('#contentout').mouseover( function() { close_quickmenu();} );
});
</script>
</div>
</body>

<!-- URD v{$VERSION} -->

</html>
