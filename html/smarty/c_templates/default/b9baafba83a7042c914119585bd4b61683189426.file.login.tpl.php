<?php /* Smarty version Smarty-3.1.14, created on 2013-08-10 23:02:16
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6550267005206aa588e4fd9-23791578%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b9baafba83a7042c914119585bd4b61683189426' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/login.tpl',
      1 => 1361577709,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6550267005206aa588e4fd9-23791578',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'LN_login_jserror' => 0,
    'token' => 0,
    'curr_language' => 0,
    'LN_login_title2' => 0,
    'message' => 0,
    'LN_username' => 0,
    'username' => 0,
    'LN_password' => 0,
    'LN_login_remember' => 0,
    'period' => 0,
    'LN_login_closebrowser' => 0,
    'LN_login_oneweek' => 0,
    'LN_login_onemonth' => 0,
    'LN_login_oneyear' => 0,
    'LN_login_forever' => 0,
    'LN_login_bindip' => 0,
    'ip_address' => 0,
    'LN_login_login' => 0,
    'languages' => 0,
    'LN_login_forgot_password' => 0,
    'register' => 0,
    'LN_login_register' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5206aa58a49b12_39526408',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5206aa58a49b12_39526408')) {function content_5206aa58a49b12_39526408($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/function.html_options.php';
?>
<?php echo $_smarty_tpl->getSubTemplate ("barehead.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0);?>

<div id="logindiv" class="light">
<div class="urdlogo2 floatleft noborder buttonlike down3" onclick="javascript:jump('http://www.urdland.com');"></div>
<!-- javascript enabled check -->
<noscript><div id="nojs"><?php echo $_smarty_tpl->tpl_vars['LN_login_jserror']->value;?>
</div></noscript>
<form method="post" id="urd_login_form">
<div>
<input type="hidden" id="language_change" value=""/>
<input type="hidden" name="token" id="token" value="<?php echo $_smarty_tpl->tpl_vars['token']->value;?>
"/>
<input type="hidden" name="curr_language" id="curr_language" value="<?php echo $_smarty_tpl->tpl_vars['curr_language']->value;?>
"/>
</div>
<table class="logintable">
<tr><td colspan="2"><h3 class="title"><?php echo $_smarty_tpl->tpl_vars['LN_login_title2']->value;?>
 <a href="http://www.urdland.com">URD</a></h3></td></tr>
<?php if ($_smarty_tpl->tpl_vars['message']->value!='') {?>
<tr><td colspan="2"><span class="warning_highlight"><?php echo $_smarty_tpl->tpl_vars['message']->value;?>
</span></td></tr>
<?php }?>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_username']->value;?>
</td><td><input type="text" name="username" value="<?php echo $_smarty_tpl->tpl_vars['username']->value;?>
" autofocus="autofocus"/></td></tr>
<tr><td ><?php echo $_smarty_tpl->tpl_vars['LN_password']->value;?>
</td><td><input type="password" id="pass" name="pass"/>&nbsp;&nbsp; 
    <div class="floatright iconsizeplus sadicon buttonlike" onclick="javascript:toggle_show_password('pass');"></div></td></tr>
<tr><td colspan="2"></td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_login_remember']->value;?>
</td><td>
<select name="period">
<option value="0" <?php if ($_smarty_tpl->tpl_vars['period']->value==0) {?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['LN_login_closebrowser']->value;?>
</option>
<option value="1" <?php if ($_smarty_tpl->tpl_vars['period']->value==1) {?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['LN_login_oneweek']->value;?>
</option>
<option value="2" <?php if ($_smarty_tpl->tpl_vars['period']->value==2) {?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['LN_login_onemonth']->value;?>
</option>
<option value="3" <?php if ($_smarty_tpl->tpl_vars['period']->value==3) {?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['LN_login_oneyear']->value;?>
</option>
<option value="4" <?php if ($_smarty_tpl->tpl_vars['period']->value==4) {?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['LN_login_forever']->value;?>
</option>
</select>
</td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_login_bindip']->value;?>
</td><td>
<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['bind_ip_address']->value),'name'=>"ipaddr",'id'=>"ipaddr",'data'=>((string) $_smarty_tpl->tpl_vars['ip_address']->value)),$_smarty_tpl);?>
 

</td></tr>
<tr><td colspan="2"></td></tr>
<tr><td colspan="1"><input type="submit" value="<?php echo $_smarty_tpl->tpl_vars['LN_login_login']->value;?>
" class="submit"/></td>
<td>
<select name="language_name" id="language_select" onchange="javascript:submit_language_login();" >
<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['languages']->value,'selected'=>$_smarty_tpl->tpl_vars['curr_language']->value),$_smarty_tpl);?>

</select>
</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td><a href="forgot_password.php"><?php echo $_smarty_tpl->tpl_vars['LN_login_forgot_password']->value;?>
</a></td><?php if ($_smarty_tpl->tpl_vars['register']->value==1) {?>
<td><a href="register.php"><?php echo $_smarty_tpl->tpl_vars['LN_login_register']->value;?>
</a></td></tr>
<?php }?>
</table>
</form>
</div>

<?php echo $_smarty_tpl->getSubTemplate ("barefoot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>