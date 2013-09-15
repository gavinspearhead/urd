<?php /* Smarty version Smarty-3.1.14, created on 2013-08-06 00:00:19
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_foot.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1308783643520020738e42c7-39258906%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0c135158b7b7953f581e163207d48a2be3cd841d' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_foot.tpl',
      1 => 1343409180,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1308783643520020738e42c7-39258906',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    '__message' => 0,
    'LN_fatal_error_title' => 0,
    'msg' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52002073900b97_12515600',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52002073900b97_12515600')) {function content_52002073900b97_12515600($_smarty_tpl) {?><?php if (isset($_smarty_tpl->tpl_vars['__message']->value)&&is_array($_smarty_tpl->tpl_vars['__message']->value)&&count($_smarty_tpl->tpl_vars['__message']->value)>0) {?>
<div id="overlay">
<div id="message">
<div id="hideoverlay">
<div class="closebutton buttonlike noborder" 
 onclick="javascript:overlaydiv = document.getElementById('overlay'); overlaydiv.style.zIndex = -100;
           overlaydiv.innerHTML = ''; overlaydiv.style.height = '0px'; overlaydiv.style.width = '0px';"></div>
</div>
<div id="messagecontent">
<h3><?php echo $_smarty_tpl->tpl_vars['LN_fatal_error_title']->value;?>
:</h3>
<?php  $_smarty_tpl->tpl_vars['msg'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['msg']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['__message']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['msg']->key => $_smarty_tpl->tpl_vars['msg']->value) {
$_smarty_tpl->tpl_vars['msg']->_loop = true;
?>
<?php echo $_smarty_tpl->tpl_vars['msg']->value;?>
<br/>
<?php } ?>
</div>
</div>
</div>
<?php }?>

<?php }} ?>