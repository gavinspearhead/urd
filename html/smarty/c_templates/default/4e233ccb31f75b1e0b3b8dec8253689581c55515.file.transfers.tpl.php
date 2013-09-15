<?php /* Smarty version Smarty-3.1.14, created on 2013-08-06 00:00:21
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/transfers.tpl" */ ?>
<?php /*%%SmartyHeaderCode:902513626520020756a94c2-65331061%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4e233ccb31f75b1e0b3b8dec8253689581c55515' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/transfers.tpl',
      1 => 1367103342,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '902513626520020756a94c2-65331061',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'show_download' => 0,
    'active_tab' => 0,
    'LN_transfers_downloads' => 0,
    'poster' => 0,
    'isadmin' => 0,
    'show_post' => 0,
    'LN_transfers_posts' => 0,
    'selector' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_520020757271b2_89514258',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_520020757271b2_89514258')) {function content_520020757271b2_89514258($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ("head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0);?>

<br/>
<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'selector', null); ob_start(); ?>
<div class="pref_selector"><ul class="tabs"><?php if ($_smarty_tpl->tpl_vars['show_download']->value!=0) {?><li onclick="javascript:select_tab_transfers('downloads', 'transfers', 'downloads')" class="tab<?php if ($_smarty_tpl->tpl_vars['active_tab']->value=='downloads') {?> tab_selected<?php }?>" id="downloads_bar"><?php echo $_smarty_tpl->tpl_vars['LN_transfers_downloads']->value;?>
<input type="hidden" name="tabs" value="downloads"/></li><?php }?><?php if (($_smarty_tpl->tpl_vars['poster']->value!=0||$_smarty_tpl->tpl_vars['isadmin']->value!=0)&&$_smarty_tpl->tpl_vars['show_post']->value!=0) {?><li onclick="javascript:select_tab_transfers('uploads', 'transfers', 'uploads')" class="tab<?php if ($_smarty_tpl->tpl_vars['active_tab']->value=='uploads') {?> tab_selected<?php }?>" id="uploads_bar"><?php echo $_smarty_tpl->tpl_vars['LN_transfers_posts']->value;?>
<input type="hidden" name="tabs" value="uploads"/></li><?php }?></ul><input type="hidden" id="active_tab" value="<?php echo $_smarty_tpl->tpl_vars['active_tab']->value;?>
"/>
</div>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php echo $_smarty_tpl->tpl_vars['selector']->value;?>

<div id="transfersdiv" class="prefix_transfers">
</div>

<script type="text/javascript">
$(document).ready(function() {
    update_transfers();
});
</script>

<?php echo $_smarty_tpl->getSubTemplate ("foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>