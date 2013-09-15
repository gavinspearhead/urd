<?php /* Smarty version Smarty-3.1.14, created on 2013-08-17 23:59:50
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_spot_search.tpl" */ ?>
<?php /*%%SmartyHeaderCode:738830428520ff256adbb41-19220971%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6e6ea55d1c06d5c58d9189d281303dc746e749d4' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_spot_search.tpl',
      1 => 1362334537,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '738830428520ff256adbb41-19220971',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'usersettype' => 0,
    'USERSETTYPE_SPOT' => 0,
    'saved_searches' => 0,
    'saved_search' => 0,
    'current' => 0,
    'USERSETTYPE_GROUP' => 0,
    'USERSETTYPE_RSS' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_520ff256bbe763_62998734',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_520ff256bbe763_62998734')) {function content_520ff256bbe763_62998734($_smarty_tpl) {?>

<?php if ($_smarty_tpl->tpl_vars['usersettype']->value==$_smarty_tpl->tpl_vars['USERSETTYPE_SPOT']->value) {?><select id="saved_search" onchange="javascript:update_spot_searches();"><option value=""></option><?php  $_smarty_tpl->tpl_vars['saved_search'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['saved_search']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['saved_searches']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['saved_search']->key => $_smarty_tpl->tpl_vars['saved_search']->value) {
$_smarty_tpl->tpl_vars['saved_search']->_loop = true;
?><option value="<?php echo $_smarty_tpl->tpl_vars['saved_search']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['current']->value==$_smarty_tpl->tpl_vars['saved_search']->value) {?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['saved_search']->value;?>
&nbsp;</option><?php } ?></select><?php } elseif ($_smarty_tpl->tpl_vars['usersettype']->value==$_smarty_tpl->tpl_vars['USERSETTYPE_GROUP']->value||$_smarty_tpl->tpl_vars['usersettype']->value==$_smarty_tpl->tpl_vars['USERSETTYPE_RSS']->value) {?><select id="saved_search" onchange="javascript:update_browse_searches();"><option value=""></option><?php  $_smarty_tpl->tpl_vars['saved_search'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['saved_search']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['saved_searches']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['saved_search']->key => $_smarty_tpl->tpl_vars['saved_search']->value) {
$_smarty_tpl->tpl_vars['saved_search']->_loop = true;
?><option value="<?php echo $_smarty_tpl->tpl_vars['saved_search']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['saved_search']->value==$_smarty_tpl->tpl_vars['current']->value) {?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['saved_search']->value;?>
&nbsp;</option><?php } ?></select><?php } else { ?>not found<?php }?>
<?php }} ?>