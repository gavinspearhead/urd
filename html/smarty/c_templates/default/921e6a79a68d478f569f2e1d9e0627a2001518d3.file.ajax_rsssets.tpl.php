<?php /* Smarty version Smarty-3.1.14, created on 2013-08-09 23:38:56
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_rsssets.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4352309165205617013ac16-55887665%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '921e6a79a68d478f569f2e1d9e0627a2001518d3' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_rsssets.tpl',
      1 => 1374274096,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4352309165205617013ac16-55887665',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'pages' => 0,
    'currentpage' => 0,
    'lastpage' => 0,
    'feed_id' => 0,
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
    'rating' => 0,
    'setdesc' => 0,
    'btmovie' => 0,
    'btmusic' => 0,
    'btimage' => 0,
    'btsoftw' => 0,
    'bttv' => 0,
    'btdocu' => 0,
    'btebook' => 0,
    'btgame' => 0,
    'btpw' => 0,
    'btcopyright' => 0,
    'interesting' => 0,
    'read' => 0,
    'nzb' => 0,
    'smallbuttons' => 0,
    'USERSETTYPE_RSS' => 0,
    'imdbpic' => 0,
    'interestingimg' => 0,
    'LN_error_nosetsfound' => 0,
    'bottomskipper' => 0,
    'rssurl' => 0,
    'LN_browse_deletedsets' => 0,
    'LN_browse_deletedset' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_520561707bae19_09355278',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_520561707bae19_09355278')) {function content_520561707bae19_09355278($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.replace.php';
?>






<?php $_smarty_tpl->tpl_vars['btmovie'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_movie.png\" alt=\"\" width=\"48\" height=\"16\"/>", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btmusic'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_music.png\" alt=\"\" width=\"48\" height=\"16\"/>", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btimage'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_image.png\" alt=\"\" width=\"48\" height=\"16\"/>", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btsoftw'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_software.png\" alt=\"\" width=\"48\" height=\"16\"/>", null, 0);?>
<?php $_smarty_tpl->tpl_vars['bttv'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_series.png\" alt=\"\" width=\"48\" height=\"16\"/>", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btdocu'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_documentary.png\" alt=\"\" width=\"48\" height=\"16\"/>", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btgame'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_games.png\" alt=\"\" width=\"48\" height=\"16\"/>", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btebook'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_ebook.png\" alt=\"\" width=\"48\" height=\"16\"/>", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btpw'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/icon_pw.png\" width=\"16\" height=\"16\"/>", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btcopyright'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/icon_copy.png\" width=\"16\" height=\"16\"/>", null, 0);?>
<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'topskipper', null); ob_start(); ?><?php if (count($_smarty_tpl->tpl_vars['pages']->value)>1) {?><?php echo smarty_function_urd_skipper(array('current'=>$_smarty_tpl->tpl_vars['currentpage']->value,'last'=>$_smarty_tpl->tpl_vars['lastpage']->value,'pages'=>$_smarty_tpl->tpl_vars['pages']->value,'class'=>'ps','js'=>'set_offset','extra_class'=>"margin10"),$_smarty_tpl);?>
<?php } else { ?><br/><?php }?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>


<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'bottomskipper', null); ob_start(); ?><?php if (count($_smarty_tpl->tpl_vars['pages']->value)>1) {?><?php echo smarty_function_urd_skipper(array('current'=>$_smarty_tpl->tpl_vars['currentpage']->value,'last'=>$_smarty_tpl->tpl_vars['lastpage']->value,'pages'=>$_smarty_tpl->tpl_vars['pages']->value,'class'=>'psb','js'=>'set_offset','extra_class'=>"margin10"),$_smarty_tpl);?>
<?php } else { ?><div><br/></div><?php }?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'unmark_int_all', null); ob_start(); ?>
<div class="floatright">
<input type="hidden" name="feed_id" value="<?php echo $_smarty_tpl->tpl_vars['feed_id']->value;?>
"/>
<?php if ($_smarty_tpl->tpl_vars['killflag']->value) {?>
<div class="floatleft iconsizeplus killicon buttonlike" onclick="javascript:Whichbutton('unmark_kill_all', event);" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_browse_resurrectset']->value),$_smarty_tpl);?>
 ></div>
<?php } else { ?>
<div class="floatleft iconsizeplus deleteicon buttonlike" onclick="javascript:Whichbutton('mark_kill_all', event);" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_browse_removeset']->value),$_smarty_tpl);?>
 ></div>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['isadmin']->value) {?>
<div class="floatleft iconsizeplus purgeicon buttonlike" onclick="javascript:Whichbutton('wipe_all', event)" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_browse_deleteset']->value),$_smarty_tpl);?>
></div>
<?php }?>
<div class="floatleft iconsizeplus sadicon buttonlike" onclick="javascript:Whichbutton('unmark_int_all', event);" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_browse_toggleint']->value),$_smarty_tpl);?>
 ></div>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php $_smarty_tpl->tpl_vars['up'] = new Smarty_variable("<img src='".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/small_up.png' alt=''>", null, 0);?><?php $_smarty_tpl->tpl_vars['down'] = new Smarty_variable("<img src='".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/small_down.png' alt=''>", null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value['order']=="better_subject") {?> <?php if ($_smarty_tpl->tpl_vars['sort']->value['direction']=='desc') {?><?php $_smarty_tpl->tpl_vars['title_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?> <?php } else { ?><?php $_smarty_tpl->tpl_vars['title_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?> <?php }?> <?php } else { ?><?php $_smarty_tpl->tpl_vars['title_sort'] = new Smarty_variable('', null, 0);?> <?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value['order']=="timestamp") {?> <?php if ($_smarty_tpl->tpl_vars['sort']->value['direction']=='desc') {?><?php $_smarty_tpl->tpl_vars['stamp_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?> <?php } else { ?><?php $_smarty_tpl->tpl_vars['stamp_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?> <?php }?> <?php } else { ?><?php $_smarty_tpl->tpl_vars['stamp_sort'] = new Smarty_variable('', null, 0);?> <?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value['order']=="size") {?> <?php if ($_smarty_tpl->tpl_vars['sort']->value['direction']=='desc') {?><?php $_smarty_tpl->tpl_vars['size_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?> <?php } else { ?><?php $_smarty_tpl->tpl_vars['size_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?> <?php }?> <?php } else { ?><?php $_smarty_tpl->tpl_vars['size_sort'] = new Smarty_variable('', null, 0);?> <?php }?>



<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'tableheader', null); ob_start(); ?>
<table class="articles" id="sets_table">
<tr>
<th class="head fixwidth1 round_left">&nbsp;</th>
<th class="head">&nbsp;</th>
<th id="browsesubjecttd" class="head buttonlike" onclick="javascript:change_sort_order('better_subject');"><?php echo $_smarty_tpl->tpl_vars['LN_browse_subject']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['title_sort']->value;?>
</th>
<th class="fixwidth2a nowrap buttonlike head right" onclick="javascript:change_sort_order('timestamp');"><?php echo $_smarty_tpl->tpl_vars['LN_browse_age']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['stamp_sort']->value;?>
 </th>
<th class="fixwidth3 nowrap buttonlike head right" onclick="javascript:change_sort_order('size');"><?php echo $_smarty_tpl->tpl_vars['LN_size']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['size_sort']->value;?>
</th>
<th class="fixwidth1 buttonlike head right" onclick="javascript:change_sort_order('rating');"><div class="floatleft iconsizeplus followicon buttonlike"></div>
</th>
<th class="head nowrap fixwidth5 round_right"><?php echo $_smarty_tpl->tpl_vars['unmark_int_all']->value;?>
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

<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'smallbuttons', null); ob_start(); ?>	
<?php if (!$_smarty_tpl->tpl_vars['set']->value['added']) {?>
<div id="divset_<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
" class="setimgplus floatleft iconsize buttonlike" onclick="javascript:SelectSet('<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
', 'rss', event);return false;"></div>
<input type="hidden" name="setdata[]" id="set_<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
" value=""/>
<?php } else { ?>
<div id="divset_<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
" class="setimgminus floatleft iconsize buttonlike" onclick="javascript:SelectSet('<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
', 'rss', event);return false;"></div>
<input type="hidden" name="setdata[]" id="set_<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
" value="x"/>
<?php }?>
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
<?php if ($_smarty_tpl->tpl_vars['set']->value['interesting']==1) {?><?php $_smarty_tpl->tpl_vars['interesting'] = new Smarty_variable('interesting', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['set']->value['interesting']==1) {?><?php $_smarty_tpl->tpl_vars['interestingimg'] = new Smarty_variable("sadicon", null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['show_makenzb']->value!=0&&$_smarty_tpl->tpl_vars['set']->value['nzb']==1) {?><?php $_smarty_tpl->tpl_vars['nzb'] = new Smarty_variable('markednzb', null, 0);?><?php }?>

<?php $_smarty_tpl->tpl_vars['rating'] = new Smarty_variable($_smarty_tpl->tpl_vars['set']->value['rating']*10, null, 0);?>
<?php $_smarty_tpl->tpl_vars['imdbpic'] = new Smarty_variable("ratingicon_".((string) $_smarty_tpl->tpl_vars['rating']->value), null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['rating']->value=='') {?><?php $_smarty_tpl->tpl_vars['imdbpic'] = new Smarty_variable("followicon", null, 0);?><?php }?>


<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'setdesc', null); ob_start(); ?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['set']->value['setname'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>
<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'setdesc', null); ob_start(); ?><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['setdesc']->value,':_img_movie:',$_smarty_tpl->tpl_vars['btmovie']->value);?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>
<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'setdesc', null); ob_start(); ?><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['setdesc']->value,':_img_album:',$_smarty_tpl->tpl_vars['btmusic']->value);?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>
<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'setdesc', null); ob_start(); ?><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['setdesc']->value,':_img_image:',$_smarty_tpl->tpl_vars['btimage']->value);?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>
<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'setdesc', null); ob_start(); ?><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['setdesc']->value,':_img_software:',$_smarty_tpl->tpl_vars['btsoftw']->value);?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>
<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'setdesc', null); ob_start(); ?><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['setdesc']->value,':_img_series:',$_smarty_tpl->tpl_vars['bttv']->value);?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>
<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'setdesc', null); ob_start(); ?><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['setdesc']->value,':_img_tvshow:',$_smarty_tpl->tpl_vars['bttv']->value);?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>
<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'setdesc', null); ob_start(); ?><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['setdesc']->value,':_img_documentary:',$_smarty_tpl->tpl_vars['btdocu']->value);?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>
<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'setdesc', null); ob_start(); ?><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['setdesc']->value,':_img_ebook:',$_smarty_tpl->tpl_vars['btebook']->value);?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>
<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'setdesc', null); ob_start(); ?><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['setdesc']->value,':_img_game:',$_smarty_tpl->tpl_vars['btgame']->value);?>
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
<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'setdesc', null); ob_start(); ?><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['setdesc']->value,':_img_unknown:','');?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
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
	onmouseover="javascript:ToggleClass(this, 'highlight2')" 
	onmouseout="javascript:ToggleClass(this, 'highlight2')">
	<td class="fixwidth1"><?php echo $_smarty_tpl->tpl_vars['set']->value['number'];?>

    
<input type="hidden" name="set_ids[]" value="<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
"/>
    </td>
	<td class="setbuttons"><?php echo $_smarty_tpl->tpl_vars['smallbuttons']->value;?>
</td>
	<td onmouseup="javascript:start_quickmenu('browse', '<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
', <?php echo $_smarty_tpl->tpl_vars['USERSETTYPE_RSS']->value;?>
, event);" id="td_set_<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
">
<div class="donotoverflowdamnit"><?php echo $_smarty_tpl->tpl_vars['setdesc']->value;?>
</div>
</td>
	<td class="fixwidth2a nowrap <?php if ($_smarty_tpl->tpl_vars['set']->value['new_set']!=0) {?>newset<?php }?>"><?php echo $_smarty_tpl->tpl_vars['set']->value['age'];?>
</td>
	<td class="fixwidth3 nowrap"><?php if ($_smarty_tpl->tpl_vars['set']->value['size']==0) {?>?<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['set']->value['size'];?>
<?php }?></td>
<td class="fixwidth1">
    <?php if ($_smarty_tpl->tpl_vars['set']->value['imdblink']!='') {?>
    <div class="floatleft iconsizeplus <?php echo $_smarty_tpl->tpl_vars['imdbpic']->value;?>
 buttonlike" onclick="javascript:jump('<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['set']->value['imdblink'], ENT_QUOTES, 'UTF-8', true);?>
', true);" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['set']->value['imdblink']),$_smarty_tpl);?>
></div>
    <?php } elseif ($_smarty_tpl->tpl_vars['set']->value['rating']!=0) {?>
    <div class="floatleft iconsizeplus <?php echo $_smarty_tpl->tpl_vars['imdbpic']->value;?>
 buttonlike"></div>
	<?php }?>
	</td>

	<td class="nowrap">
    <div class="floatright">
    <input type="hidden" id="link_<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
" value="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['set']->value['link']);?>
"/>
    <?php if ($_smarty_tpl->tpl_vars['isadmin']->value) {?>
    <div class="floatleft iconsizeplus purgeicon buttonlike" onclick="javascript:markRead('<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
', 'wipe', <?php echo $_smarty_tpl->tpl_vars['USERSETTYPE_RSS']->value;?>
)" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_browse_deleteset']->value),$_smarty_tpl);?>
></div>
    <?php }?>
	 <div id="intimg_<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
" class="floatright iconsizeplus <?php echo $_smarty_tpl->tpl_vars['interestingimg']->value;?>
 buttonlike" onclick="javascript:markRead('<?php echo $_smarty_tpl->tpl_vars['set']->value['sid'];?>
', 'interesting', <?php echo $_smarty_tpl->tpl_vars['USERSETTYPE_RSS']->value;?>
)" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_browse_toggleint']->value),$_smarty_tpl);?>
></div>
    </div>
	</td>
</tr>
<?php }
if (!$_smarty_tpl->tpl_vars['set']->_loop) {
?> 
<?php if ($_smarty_tpl->tpl_vars['only_rows']->value==0) {?>
<tr><td colspan="8" class="centered highlight textback"><?php echo $_smarty_tpl->tpl_vars['LN_error_nosetsfound']->value;?>
</td></tr>
<?php }?>
<?php } ?>

<?php if ($_smarty_tpl->tpl_vars['only_rows']->value==0) {?>

</table>

<?php echo $_smarty_tpl->tpl_vars['bottomskipper']->value;?>

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