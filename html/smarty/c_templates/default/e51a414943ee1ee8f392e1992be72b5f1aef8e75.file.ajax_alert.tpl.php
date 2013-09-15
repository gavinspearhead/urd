<?php /* Smarty version Smarty-3.1.14, created on 2013-08-30 00:10:59
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_alert.tpl" */ ?>
<?php /*%%SmartyHeaderCode:185242806452056068b6b200-56549410%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e51a414943ee1ee8f392e1992be72b5f1aef8e75' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_alert.tpl',
      1 => 1377814255,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '185242806452056068b6b200-56549410',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52056068bfe4d0_45516488',
  'variables' => 
  array (
    'LN_fatal_error_title' => 0,
    'msg' => 0,
    'LN_ok' => 0,
    'allow_cancel' => 0,
    'LN_cancel' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52056068bfe4d0_45516488')) {function content_52056068bfe4d0_45516488($_smarty_tpl) {?>
<div class="set_title centered"><?php echo $_smarty_tpl->tpl_vars['LN_fatal_error_title']->value;?>
</div>
<div id="alert_inner">
    <div id="alert_content">
        <div id="alert_message"><?php echo $_smarty_tpl->tpl_vars['msg']->value;?>
</div>
        <br/>
        <div id="alert_answer" class="centered">
            <input type="button" id="okbutton" value="<?php echo $_smarty_tpl->tpl_vars['LN_ok']->value;?>
" class="submitsmall"/>&nbsp;
<?php if ($_smarty_tpl->tpl_vars['allow_cancel']->value) {?>
            <input type="button" id="cancelbutton" value="<?php echo $_smarty_tpl->tpl_vars['LN_cancel']->value;?>
" id="cancelbutton" class="submitsmall"/>
<?php }?>
        </div>
    </div>
</div>

<?php }} ?>