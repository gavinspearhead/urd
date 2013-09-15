<?php /* Smarty version Smarty-3.1.14, created on 2013-08-28 00:34:56
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/formatsetname.tpl" */ ?>
<?php /*%%SmartyHeaderCode:336077098521d29909eb2a1-43689493%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b811c93b27073cc8166bc91fc515ab72bb78ec4f' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/formatsetname.tpl',
      1 => 1343512249,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '336077098521d29909eb2a1-43689493',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'newname' => 0,
    'setdesc' => 0,
    'maxstrlen' => 0,
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
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_521d2990c07222_54563579',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_521d2990c07222_54563579')) {function content_521d2990c07222_54563579($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.truncate.php';
if (!is_callable('smarty_modifier_replace')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.replace.php';
?>





<?php $_smarty_tpl->tpl_vars['btmovie'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_movie.png\">", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btmusic'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_music.png\">", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btimage'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_image.png\">", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btsoftw'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_software.png\">", null, 0);?>
<?php $_smarty_tpl->tpl_vars['bttv'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_series.png\">", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btdocu'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_documentary.png\">", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btgame'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_games.png\">", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btebook'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/bin_ebook.png\">", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btpw'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/icon_pw.png\">", null, 0);?>
<?php $_smarty_tpl->tpl_vars['btcopyright'] = new Smarty_variable("<img class=\"binicon\" src=\"".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/icon_copy.png\">", null, 0);?>

<?php $_smarty_tpl->tpl_vars['setdesc'] = new Smarty_variable($_smarty_tpl->tpl_vars['newname']->value, null, 0);?>
<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'setdesc', null); ob_start(); ?><?php echo mb_convert_encoding(htmlspecialchars(smarty_modifier_truncate($_smarty_tpl->tpl_vars['setdesc']->value,$_smarty_tpl->tpl_vars['maxstrlen']->value,'...',true,true), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
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

<?php echo $_smarty_tpl->tpl_vars['setdesc']->value;?>

<?php }} ?>