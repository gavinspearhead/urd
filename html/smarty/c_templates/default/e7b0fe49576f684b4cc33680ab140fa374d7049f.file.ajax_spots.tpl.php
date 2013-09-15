<?php /* Smarty version Smarty-3.1.14, created on 2013-08-16 23:24:59
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_spots.tpl" */ ?>
<?php /*%%SmartyHeaderCode:19069750315200219cecee45-27799334%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e7b0fe49576f684b4cc33680ab140fa374d7049f' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_spots.tpl',
      1 => 1376688296,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19069750315200219cecee45-27799334',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5200219d34c167_80138618',
  'variables' => 
  array (
    'pages' => 0,
    'currentpage' => 0,
    'lastpage' => 0,
    'killflag' => 0,
    'LN_browse_resurrectset' => 0,
    'LN_browse_removeset' => 0,
    'isadmin' => 0,
    'LN_browse_deleteset' => 0,
    'LN_browse_toggleint' => 0,
    'sort' => 0,
    'up' => 0,
    'down' => 0,
    'LN_browse_subject' => 0,
    'title_sort' => 0,
    'LN_spamreporttag' => 0,
    'LN_whitelisttag' => 0,
    'show_comments' => 0,
    'LN_browse_age' => 0,
    'stamp_sort' => 0,
    'LN_size' => 0,
    'size_sort' => 0,
    'unmark_int_all' => 0,
    'only_rows' => 0,
    'topskipper' => 0,
    'tableheader' => 0,
    'allsets' => 0,
    'set' => 0,
    'show_makenzb' => 0,
    'setdesc' => 0,
    'btpw' => 0,
    'btcopyright' => 0,
    'k' => 0,
    'val1' => 0,
    'val2' => 0,
    'interesting' => 0,
    'read' => 0,
    'nzb' => 0,
    'smallbuttons' => 0,
    'USERSETTYPE_SPOT' => 0,
    'show_subcats' => 0,
    'btmovie' => 0,
    'btmusic' => 0,
    'btimage' => 0,
    'btsoftw' => 0,
    'bttv' => 0,
    'btdocu' => 0,
    'btebook' => 0,
    'btgame' => 0,
    'rating' => 0,
    'setwhitelisted' => 0,
    'linkpic' => 0,
    'interestingimg' => 0,
    'LN_error_nosetsfound' => 0,
    'bottomskipper' => 0,
    'rssurl' => 0,
    'LN_browse_deletedsets' => 0,
    'LN_browse_deletedset' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5200219d34c167_80138618')) {function content_5200219d34c167_80138618($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.replace.php';
?>




<?php $_smarty_tpl->tpl_vars['btmovie'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_movie.png\" alt=\"\" width=\"48\" height=\"16\"/> ", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btmusic'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_music.png\" alt=\"\" width=\"48\" height=\"16\"/> ", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btimage'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_image.png\" alt=\"\" width=\"48\" height=\"16\"/> ", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btsoftw'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_software.png\" alt=\"\" width=\"48\" height=\"16\"/> ", null, 0);?>
<?php $_smarty_tpl->tpl_vars['bttv'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_series.png\" alt=\"\" width=\"48\" height=\"16\"/> ", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btdocu'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_documentary.png\" alt=\"\" width=\"48\" height=\"16\"/> ", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btgame'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_games.png\" alt=\"\" width=\"48\" height=\"16\"/> ", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btebook'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_ebook.png\" alt=\"\" width=\"48\" height=\"16\"/> ", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btpw'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/icon_pw.png\" width=\"16\" height=\"16\"/> ", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btcopyright'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/icon_copy.png\" width=\"16\" height=\"16\"/> ", null, 0);?>


<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'topskipper', null); ob_start(); ?><?php if (count($_smarty_tpl->tpl_vars['pages']->value)>1) {?><?php echo smarty_function_urd_skipper(array('current'=>$_smarty_tpl->tpl_vars['currentpage']->value,'last'=>$_smarty_tpl->tpl_vars['lastpage']->value,'pages'=>$_smarty_tpl->tpl_vars['pages']->value,'class'=>'ps','js'=>'set_offset','extra_class'=>"margin10"),$_smarty_tpl);?>
<?php } else { ?><br/><?php }?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>


<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'bottomskipper', null); ob_start(); ?><?php if (count($_smarty_tpl->tpl_vars['pages']->value)>1) {?><?php echo smarty_function_urd_skipper(array('current'=>$_smarty_tpl->tpl_vars['currentpage']->value,'last'=>$_smarty_tpl->tpl_vars['lastpage']->value,'pages'=>$_smarty_tpl->tpl_vars['pages']->value,'class'=>'psb','js'=>'set_offset','extra_class'=>"margin10"),$_smarty_tpl);?>
<?php } else { ?><br/><?php }?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'unmark_int_all', null); ob_start(); ?><?php if ($_smarty_tpl->tpl_vars['killflag']->value) {?><div class="inline iconsizeplus killicon buttonlike" onclick="javascript:Whichbutton('unmark_kill_all', event);" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_browse_resurrectset']->value),$_smarty_tpl);?>
 ></div><?php } else { ?><div class="inline iconsizeplus deleteicon buttonlike" onclick="javascript:Whichbutton('mark_kill_all', event);" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_browse_removeset']->value),$_smarty_tpl);?>
 ></div><?php }?><?php if ($_smarty_tpl->tpl_vars['isadmin']->value) {?><div class="inline iconsizeplus purgeicon buttonlike" onclick="javascript:Whichbutton('wipe_all', event)" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_browse_deleteset']->value),$_smarty_tpl);?>
 ></div><?php }?><?php if ($_smarty_tpl->tpl_vars['isadmin']->value) {?><div class="inline iconsizeplus sadicon buttonlike" onclick="javascript:Whichbutton('unmark_int_all', event);" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_browse_toggleint']->value),$_smarty_tpl);?>
 ></div><?php }?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>






<?php $_smarty_tpl->tpl_vars['up'] = new Smarty_variable("<img src='".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/small_up.png' alt=''>", null, 0);?><?php $_smarty_tpl->tpl_vars['down'] = new Smarty_variable("<img src='".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/small_down.png' alt=''>", null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value['order']=="title") {?> <?php if ($_smarty_tpl->tpl_vars['sort']->value['direction']=='desc') {?><?php $_smarty_tpl->tpl_vars['title_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?> <?php } else { ?><?php $_smarty_tpl->tpl_vars['title_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?> <?php }?> <?php } else { ?><?php $_smarty_tpl->tpl_vars['title_sort'] = new Smarty_variable('', null, 0);?> <?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value['order']=="stamp") {?> <?php if ($_smarty_tpl->tpl_vars['sort']->value['direction']=='desc') {?><?php $_smarty_tpl->tpl_vars['stamp_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?> <?php } else { ?><?php $_smarty_tpl->tpl_vars['stamp_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?> <?php }?> <?php } else { ?><?php $_smarty_tpl->tpl_vars['stamp_sort'] = new Smarty_variable('', null, 0);?> <?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value['order']=="size") {?> <?php if ($_smarty_tpl->tpl_vars['sort']->value['direction']=='desc') {?><?php $_smarty_tpl->tpl_vars['size_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?> <?php } else { ?><?php $_smarty_tpl->tpl_vars['size_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?> <?php }?> <?php } else { ?><?php $_smarty_tpl->tpl_vars['size_sort'] = new Smarty_variable('', null, 0);?> <?php }?>

<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'tableheader', null); ob_start(); ?>
<table class="articles" id="spots_table">
<tr>
<th class="head round_left">&nbsp;</th>
<th class="head">&nbsp;</th>
<th id="browsesubjecttd" class="head buttonlike" onclick="javascript:change_sort_order('title');"><?php echo $_smarty_tpl->tpl_vars['LN_browse_subject']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['title_sort']->value;?>
</th>
<th class="head fixwidth1 buttonlike" onclick="javascript:change_sort_order('reports');"><?php echo $_smarty_tpl->tpl_vars['LN_spamreporttag']->value;?>
</th>
<th class="head"><?php echo $_smarty_tpl->tpl_vars['LN_whitelisttag']->value;?>
</th>
<?php if ($_smarty_tpl->tpl_vars['show_comments']->value>0) {?>
<th class="head fixwidth1 buttonlike" onclick="javascript:change_sort_order('comments');">#</th>
<?php }?>
<th class="fixwidth2a nowrap buttonlike head right" onclick="javascript:change_sort_order('stamp');"><?php echo $_smarty_tpl->tpl_vars['LN_browse_age']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['stamp_sort']->value;?>
</th>
<th class="fixwidth3 nowrap buttonlike head right" onclick="javascript:change_sort_order('size');"><?php echo $_smarty_tpl->tpl_vars['LN_size']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['size_sort']->value;?>
</th>
<th class="fixwidth1 buttonlike head right" onclick="javascript:change_sort_order('url');"><div class="inline iconsizeplus followicon buttonlike"></div>
</th>
<th class="nowrap head fixwidth4 round_right"><?php echo $_smarty_tpl->tpl_vars['unmark_int_all']->value;?>
</th>
</tr>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php if ($_smarty_tpl->tpl_vars['only_rows']->value==0) {?>
    <?php echo $_smarty_tpl->tpl_vars['topskipper']->value;?>

    <?php echo $_smarty_tpl->tpl_vars['tableheader']->value;?>

<?php }?>



<?php  $_smarty_tpl->tpl_vars['set'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['set']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['allsets']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['set']->key => $_smarty_tpl->tpl_vars['set']->value) {
$_smarty_tpl->tpl_vars['set']->_loop = true;
?>

<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'smallbuttons', null); ob_start(); ?><?php if (!$_smarty_tpl->tpl_vars['set']->value['added']) {?><div id="divset_<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
" class="setimgplus inline iconsize buttonlike" onclick="javascript:SelectSet('<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
', 'spot', event);return false;"></div><input type="hidden" name="setdata[]" id="set_<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
" value=""/><?php } else { ?><div id="divset_<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
" class="setimgminus inline iconsize buttonlike" onclick="javascript:SelectSet('<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
', 'spot', event);return false;"></div><input type="hidden" name="setdata[]" id="set_<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
" value="x"/><?php }?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>


<?php $_smarty_tpl->tpl_vars['read'] = new Smarty_variable('', null, 0);?>
<?php $_smarty_tpl->tpl_vars['nzb'] = new Smarty_variable('', null, 0);?>
<?php $_smarty_tpl->tpl_vars['interesting'] = new Smarty_variable('', null, 0);?>
<?php $_smarty_tpl->tpl_vars['interestingimg'] = new Smarty_variable("smileicon", null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['set']->value['read']==1) {?><?php $_smarty_tpl->tpl_vars['read'] = new Smarty_variable('markedread', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['show_makenzb']->value!=0&&$_smarty_tpl->tpl_vars['set']->value['nzb']==1) {?><?php $_smarty_tpl->tpl_vars['nzb'] = new Smarty_variable('markednzb', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['set']->value['interesting']==1) {?><?php $_smarty_tpl->tpl_vars['interesting'] = new Smarty_variable('interesting', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['set']->value['interesting']==1) {?><?php $_smarty_tpl->tpl_vars['interestingimg'] = new Smarty_variable('sadicon', null, 0);?><?php }?>


<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'setdesc', null); ob_start(); ?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['set']->value['name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>
<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'setdesc', null); ob_start(); ?><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['setdesc']->value,':_img_pw:',$_smarty_tpl->tpl_vars['btpw']->value);?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>
<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'setdesc', null); ob_start(); ?><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['setdesc']->value,':_img_copyright:',$_smarty_tpl->tpl_vars['btcopyright']->value);?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'subcats', null); ob_start(); ?><table><?php  $_smarty_tpl->tpl_vars['val1'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val1']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['set']->value['subcata']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val1']->key => $_smarty_tpl->tpl_vars['val1']->value) {
$_smarty_tpl->tpl_vars['val1']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['val1']->key;
?><tr><td><?php echo $_smarty_tpl->tpl_vars['k']->value;?>
:&nbsp;</td><td><?php  $_smarty_tpl->tpl_vars['val2'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val2']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['val1']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['val2']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['val2']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['val2']->key => $_smarty_tpl->tpl_vars['val2']->value) {
$_smarty_tpl->tpl_vars['val2']->_loop = true;
 $_smarty_tpl->tpl_vars['val2']->iteration++;
 $_smarty_tpl->tpl_vars['val2']->last = $_smarty_tpl->tpl_vars['val2']->iteration === $_smarty_tpl->tpl_vars['val2']->total;
?><?php echo $_smarty_tpl->tpl_vars['val2']->value[0];?>
<?php if (!$_smarty_tpl->tpl_vars['val2']->last) {?>; <?php }?><?php } ?></td></tr><?php } ?><?php  $_smarty_tpl->tpl_vars['val1'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val1']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['set']->value['subcatb']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val1']->key => $_smarty_tpl->tpl_vars['val1']->value) {
$_smarty_tpl->tpl_vars['val1']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['val1']->key;
?><tr><td><?php echo $_smarty_tpl->tpl_vars['k']->value;?>
:&nbsp;</td><td><?php  $_smarty_tpl->tpl_vars['val2'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val2']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['val1']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['val2']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['val2']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['val2']->key => $_smarty_tpl->tpl_vars['val2']->value) {
$_smarty_tpl->tpl_vars['val2']->_loop = true;
 $_smarty_tpl->tpl_vars['val2']->iteration++;
 $_smarty_tpl->tpl_vars['val2']->last = $_smarty_tpl->tpl_vars['val2']->iteration === $_smarty_tpl->tpl_vars['val2']->total;
?><?php echo $_smarty_tpl->tpl_vars['val2']->value[0];?>
<?php if (!$_smarty_tpl->tpl_vars['val2']->last) {?>; <?php }?><?php } ?></td></tr><?php } ?><?php  $_smarty_tpl->tpl_vars['val1'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val1']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['set']->value['subcatc']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val1']->key => $_smarty_tpl->tpl_vars['val1']->value) {
$_smarty_tpl->tpl_vars['val1']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['val1']->key;
?><tr><td><?php echo $_smarty_tpl->tpl_vars['k']->value;?>
:&nbsp;</td><td><?php  $_smarty_tpl->tpl_vars['val2'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val2']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['val1']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['val2']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['val2']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['val2']->key => $_smarty_tpl->tpl_vars['val2']->value) {
$_smarty_tpl->tpl_vars['val2']->_loop = true;
 $_smarty_tpl->tpl_vars['val2']->iteration++;
 $_smarty_tpl->tpl_vars['val2']->last = $_smarty_tpl->tpl_vars['val2']->iteration === $_smarty_tpl->tpl_vars['val2']->total;
?><?php echo $_smarty_tpl->tpl_vars['val2']->value[0];?>
<?php if (!$_smarty_tpl->tpl_vars['val2']->last) {?>; <?php }?><?php } ?></td></tr><?php } ?><?php  $_smarty_tpl->tpl_vars['val1'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val1']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['set']->value['subcatd']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val1']->key => $_smarty_tpl->tpl_vars['val1']->value) {
$_smarty_tpl->tpl_vars['val1']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['val1']->key;
?><tr><td><?php echo $_smarty_tpl->tpl_vars['k']->value;?>
:&nbsp;</td><td><?php  $_smarty_tpl->tpl_vars['val2'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val2']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['val1']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['val2']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['val2']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['val2']->key => $_smarty_tpl->tpl_vars['val2']->value) {
$_smarty_tpl->tpl_vars['val2']->_loop = true;
 $_smarty_tpl->tpl_vars['val2']->iteration++;
 $_smarty_tpl->tpl_vars['val2']->last = $_smarty_tpl->tpl_vars['val2']->iteration === $_smarty_tpl->tpl_vars['val2']->total;
?><?php echo $_smarty_tpl->tpl_vars['val2']->value[0];?>
<?php if (!$_smarty_tpl->tpl_vars['val2']->last) {?>; <?php }?><?php } ?></td></tr><?php } ?><?php  $_smarty_tpl->tpl_vars['val1'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val1']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['set']->value['subcatz']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val1']->key => $_smarty_tpl->tpl_vars['val1']->value) {
$_smarty_tpl->tpl_vars['val1']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['val1']->key;
?><tr><td><?php echo $_smarty_tpl->tpl_vars['k']->value;?>
:&nbsp;</td><td><?php  $_smarty_tpl->tpl_vars['val2'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val2']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['val1']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['val2']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['val2']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['val2']->key => $_smarty_tpl->tpl_vars['val2']->value) {
$_smarty_tpl->tpl_vars['val2']->_loop = true;
 $_smarty_tpl->tpl_vars['val2']->iteration++;
 $_smarty_tpl->tpl_vars['val2']->last = $_smarty_tpl->tpl_vars['val2']->iteration === $_smarty_tpl->tpl_vars['val2']->total;
?><?php echo $_smarty_tpl->tpl_vars['val2']->value[0];?>
 <?php if (!$_smarty_tpl->tpl_vars['val2']->last) {?>; <?php }?><?php } ?></td></tr><?php } ?></table><?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

	
<tr class="content even <?php echo $_smarty_tpl->tpl_vars['interesting']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['read']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['nzb']->value;?>
" id="base_row_<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
" 
	onmouseover="javascript:ToggleClass(this,'highlight2')" 
	onmouseout="javascript:ToggleClass(this,'highlight2')">
	<td class="fixwidth1"><?php echo $_smarty_tpl->tpl_vars['set']->value['number'];?>

<input type="hidden" name="set_ids[]" value="<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
"/>
    </td>
	<td class="setbuttons"><?php echo $_smarty_tpl->tpl_vars['smallbuttons']->value;?>
</td>
<td onmouseup="javascript:start_quickmenu('browse','<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
', <?php echo $_smarty_tpl->tpl_vars['USERSETTYPE_SPOT']->value;?>
, event);" id="td_set_<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
" <?php if ($_smarty_tpl->tpl_vars['show_subcats']->value) {?><?php echo smarty_function_urd_popup(array('text'=>((string) $_smarty_tpl->tpl_vars['subcats']->value),'caption'=>((string) $_smarty_tpl->tpl_vars['LN_spots_subcategories']->value)),$_smarty_tpl);?>
<?php }?>>
    <div class="donotoverflowdamnit inline">
<?php if ($_smarty_tpl->tpl_vars['set']->value['extcat']==':_img_movie:') {?><?php echo $_smarty_tpl->tpl_vars['btmovie']->value;?>

<?php } elseif ($_smarty_tpl->tpl_vars['set']->value['extcat']==':_img_album:') {?><?php echo $_smarty_tpl->tpl_vars['btmusic']->value;?>

<?php } elseif ($_smarty_tpl->tpl_vars['set']->value['extcat']==':_img_image:') {?><?php echo $_smarty_tpl->tpl_vars['btimage']->value;?>

<?php } elseif ($_smarty_tpl->tpl_vars['set']->value['extcat']==':_img_software:') {?><?php echo $_smarty_tpl->tpl_vars['btsoftw']->value;?>

<?php } elseif ($_smarty_tpl->tpl_vars['set']->value['extcat']==':_img_series:') {?><?php echo $_smarty_tpl->tpl_vars['bttv']->value;?>

<?php } elseif ($_smarty_tpl->tpl_vars['set']->value['extcat']==':_img_tvshow:') {?><?php echo $_smarty_tpl->tpl_vars['bttv']->value;?>

<?php } elseif ($_smarty_tpl->tpl_vars['set']->value['extcat']==':_img_documentary:') {?><?php echo $_smarty_tpl->tpl_vars['btdocu']->value;?>

<?php } elseif ($_smarty_tpl->tpl_vars['set']->value['extcat']==':_img_ebook:') {?><?php echo $_smarty_tpl->tpl_vars['btebook']->value;?>

<?php } elseif ($_smarty_tpl->tpl_vars['set']->value['extcat']==':_img_game:') {?><?php echo $_smarty_tpl->tpl_vars['btgame']->value;?>

<?php } elseif ($_smarty_tpl->tpl_vars['set']->value['categorynr']==0) {?><?php echo $_smarty_tpl->tpl_vars['btmovie']->value;?>

<?php } elseif ($_smarty_tpl->tpl_vars['set']->value['categorynr']==1) {?><?php echo $_smarty_tpl->tpl_vars['btmusic']->value;?>

<?php } elseif ($_smarty_tpl->tpl_vars['set']->value['categorynr']==2) {?><?php echo $_smarty_tpl->tpl_vars['btgame']->value;?>

<?php } elseif ($_smarty_tpl->tpl_vars['set']->value['categorynr']==3) {?><?php echo $_smarty_tpl->tpl_vars['btsoftw']->value;?>

<?php }?>
<?php $_smarty_tpl->tpl_vars['rating'] = new Smarty_variable($_smarty_tpl->tpl_vars['set']->value['rating']*10, null, 0);?>
<?php $_smarty_tpl->tpl_vars['linkpic'] = new Smarty_variable("ratingicon_".((string) $_smarty_tpl->tpl_vars['rating']->value), null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['rating']->value=='') {?><?php $_smarty_tpl->tpl_vars['linkpic'] = new Smarty_variable("followicon", null, 0);?><?php }?>

    <div class="inline"><?php echo $_smarty_tpl->tpl_vars['setdesc']->value;?>
</div>
    </td>
    <td class="width20">
    <?php if ($_smarty_tpl->tpl_vars['set']->value['reports']>0) {?><?php $_smarty_tpl->tpl_vars['spamreports'] = new Smarty_variable($_smarty_tpl->tpl_vars['set']->value['reports'], null, 0);?><div <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>((string) $_smarty_tpl->tpl_vars['spamreports']->value)." ".((string) $_smarty_tpl->tpl_vars['LN_spam_reports']->value)),$_smarty_tpl);?>
 class="highlight_spam inline center width15"><?php echo $_smarty_tpl->tpl_vars['set']->value['reports'];?>
</div><?php }?>
    </td>
    <td class="width20">
    <?php if ($_smarty_tpl->tpl_vars['set']->value['whitelisted']) {?><?php $_smarty_tpl->tpl_vars['setwhitelisted'] = new Smarty_variable($_smarty_tpl->tpl_vars['set']->value['whitelisted'], null, 0);?><?php $_smarty_tpl->tpl_vars['poster'] = new Smarty_variable($_smarty_tpl->tpl_vars['set']->value['poster'], null, 0);?><div <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>((string) $_smarty_tpl->tpl_vars['LN_browse_userwhitelisted']->value).":<br> ".((string) $_smarty_tpl->tpl_vars['poster']->value)." (<i>".((string) $_smarty_tpl->tpl_vars['setwhitelisted']->value)."</i>)"),$_smarty_tpl);?>
 class="highlight_whitelist inline center width15"><?php echo $_smarty_tpl->tpl_vars['LN_whitelisttag']->value;?>
</div><?php }?>
    </div>
    </td>
    <?php if ($_smarty_tpl->tpl_vars['show_comments']->value>0) {?>
    <td class="width32">
        <?php $_smarty_tpl->tpl_vars['setcomments'] = new Smarty_variable($_smarty_tpl->tpl_vars['set']->value['comments'], null, 0);?>
        <div class="inline highlight_comments center width25" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>((string) $_smarty_tpl->tpl_vars['setcomments']->value)." ".((string) $_smarty_tpl->tpl_vars['LN_browse_tag_note']->value)),$_smarty_tpl);?>
><?php echo $_smarty_tpl->tpl_vars['set']->value['comments'];?>
</div>
    </td>
    <?php }?>
    </div>
 
<td class="fixwidth2a nowrap <?php if ($_smarty_tpl->tpl_vars['set']->value['new_set']!=0) {?>newset<?php }?>"><?php echo $_smarty_tpl->tpl_vars['set']->value['age'];?>
</td>
<td class="fixwidth3 nowrap"><?php echo $_smarty_tpl->tpl_vars['set']->value['size'];?>
</td>
<td class="fixwidth1">
    
    <?php if ($_smarty_tpl->tpl_vars['set']->value['url']!='') {?>
    <div class="inline iconsize <?php echo $_smarty_tpl->tpl_vars['linkpic']->value;?>
 buttonlike" onclick="javascript:jump('<?php echo strtr($_smarty_tpl->tpl_vars['set']->value['url'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', true);" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['set']->value['url'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8')),$_smarty_tpl);?>
></div>
	<?php } elseif ($_smarty_tpl->tpl_vars['set']->value['rating']!=0) {?>
    <div class="inline iconsize <?php echo $_smarty_tpl->tpl_vars['linkpic']->value;?>
 buttonlike"></div>
<?php } else { ?>&nbsp;
	<?php }?>
    </td>  
	<td class="nowrap">
    <div class="floatright">
    <?php if ($_smarty_tpl->tpl_vars['isadmin']->value) {?>
    <div class="inline iconsize purgeicon buttonlike" onclick="javascript:markRead('<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
', 'wipe', <?php echo $_smarty_tpl->tpl_vars['USERSETTYPE_SPOT']->value;?>
)" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_browse_deleteset']->value),$_smarty_tpl);?>
></div>
    <?php }?>
    <div id="intimg_<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
" class="inline iconsize <?php echo $_smarty_tpl->tpl_vars['interestingimg']->value;?>
 buttonlike" onclick="javascript:markRead('<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
', 'interesting', <?php echo $_smarty_tpl->tpl_vars['USERSETTYPE_SPOT']->value;?>
)" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_browse_toggleint']->value),$_smarty_tpl);?>
></div>
    </div>
	</td>
</tr>
<?php }
if (!$_smarty_tpl->tpl_vars['set']->_loop) {
?> 
<?php if ($_smarty_tpl->tpl_vars['only_rows']->value==0) {?>
<tr><td colspan=<?php if ($_smarty_tpl->tpl_vars['show_comments']->value>0) {?>"10"<?php } else { ?>"9"<?php }?> class="centered highlight textback"><?php echo $_smarty_tpl->tpl_vars['LN_error_nosetsfound']->value;?>
</td></tr>
<?php }?>
<?php } ?>



<?php if ($_smarty_tpl->tpl_vars['only_rows']->value==0) {?>

</table>

<?php echo $_smarty_tpl->tpl_vars['bottomskipper']->value;?>

<br/>

<input type="hidden" id="rss_url" value="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['rssurl']->value);?>
"/>
<input type="hidden" id="killflag" value="<?php echo $_smarty_tpl->tpl_vars['killflag']->value;?>
"/>
<input type="hidden" id="deletedsets" value="<?php echo $_smarty_tpl->tpl_vars['LN_browse_deletedsets']->value;?>
"/>
<input type="hidden" id="deletedset" value="<?php echo $_smarty_tpl->tpl_vars['LN_browse_deletedset']->value;?>
"/>
<input type="hidden" id="last_line" value="<?php echo $_smarty_tpl->tpl_vars['set']->value['number'];?>
"/>
<?php }?>
<?php }} ?>