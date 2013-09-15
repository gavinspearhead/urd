<?php /* Smarty version Smarty-3.1.14, created on 2013-08-10 23:11:10
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/fatal_error.tpl" */ ?>
<?php /*%%SmartyHeaderCode:9132110005206ac6e1bb7b5-13629191%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8a5bb680e62f776c79dc230fdb6f1504c992c58a' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/fatal_error.tpl',
      1 => 1374792858,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '9132110005206ac6e1bb7b5-13629191',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'showmenu' => 0,
    'title' => 0,
    'msg' => 0,
    'link' => 0,
    'link_msg' => 0,
    '__message' => 0,
    'closelink' => 0,
    'LN_fatal_error_title' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5206ac6e45aaa9_09681717',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5206ac6e45aaa9_09681717')) {function content_5206ac6e45aaa9_09681717($_smarty_tpl) {?>

<?php if ($_smarty_tpl->tpl_vars['showmenu']->value!=0) {?> 
<?php echo $_smarty_tpl->getSubTemplate ("head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0);?>

<?php }?>

<div class="light">
<p>
<?php echo $_smarty_tpl->tpl_vars['msg']->value;?>
</p>
<?php if ($_smarty_tpl->tpl_vars['link']->value!=null&&$_smarty_tpl->tpl_vars['link_msg']->value!=null) {?>
<a href="<?php echo $_smarty_tpl->tpl_vars['link']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['link_msg']->value;?>
</a>
<?php }?>
</div>
<?php if (isset($_smarty_tpl->tpl_vars['__message']->value)&&is_array($_smarty_tpl->tpl_vars['__message']->value)&&count($_smarty_tpl->tpl_vars['__message']->value)>0) {?>

<div id="overlay">
<div id="message">
<div class="closebutton buttonlike noborder fixedright down5" onclick="javascript:hide_overlay('<?php echo $_smarty_tpl->tpl_vars['closelink']->value;?>
');"> </div>
<div class="set_title centered"><?php echo $_smarty_tpl->tpl_vars['LN_fatal_error_title']->value;?>
</div>
<div id="hideoverlay"></div>

<div id="messagecontent" class="light">

<?php  $_smarty_tpl->tpl_vars['msg'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['msg']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['__message']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['msg']->key => $_smarty_tpl->tpl_vars['msg']->value) {
$_smarty_tpl->tpl_vars['msg']->_loop = true;
?>
<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['msg']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<br/><br/>
<?php } ?>

</div>
</div>
</div>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['showmenu']->value!=0) {?> 
<?php echo $_smarty_tpl->getSubTemplate ("foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }?>
<?php }} ?>