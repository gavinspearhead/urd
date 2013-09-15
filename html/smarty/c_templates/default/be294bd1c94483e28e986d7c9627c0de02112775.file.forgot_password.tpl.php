<?php /* Smarty version Smarty-3.1.14, created on 2013-08-29 21:50:54
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/forgot_password.tpl" */ ?>
<?php /*%%SmartyHeaderCode:830989994521fa61e8adac7-45888551%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'be294bd1c94483e28e986d7c9627c0de02112775' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/forgot_password.tpl',
      1 => 1374792858,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '830989994521fa61e8adac7-45888551',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'challenge' => 0,
    'LN_username' => 0,
    'LN_email' => 0,
    'LN_forgot_mail' => 0,
    'LN_forgot_sent' => 0,
    'LN_login_title' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_521fa61e9b5896_44259553',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_521fa61e9b5896_44259553')) {function content_521fa61e9b5896_44259553($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ("barehead.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0);?>


<div id="logindiv" class="light">
<div class="urdlogo2 floatleft noborder buttonlike down3" onclick="javascript:jump('http://www.urdland.com');"></div>
<div><input type="hidden" name="challenge" value="<?php echo $_smarty_tpl->tpl_vars['challenge']->value;?>
"/>
<table class="logintable" id="form_table">
<tbody id="error">
<tr><td colspan="2" id="error_msg" class="head2"></td></tr>
</tbody>
<tbody id="form">
<tr><td colspan="2"><h3 class="title"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h3></td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_username']->value;?>
</td><td><input type="text" name="username" id="username" size="40"/></td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_email']->value;?>
</td><td><input type="text" name="email" id="email" size="40"/></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td colspan="2"><input type="button" value="<?php echo $_smarty_tpl->tpl_vars['LN_forgot_mail']->value;?>
" class="submitsmall floatright" onclick="submit_forgot_password();"/></td></tr>
</tbody>
</table>
</div>

<table class="logintable" id="sent_table">
<tr><td><h3 class="title"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</h3></td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_forgot_sent']->value;?>
</td></tr>
<tr><td><a href="login.php"><?php echo $_smarty_tpl->tpl_vars['LN_login_title']->value;?>
</a></td></tr>
</table>

</div>
<script>
$(document).ready(function() {
        $("#sent_table").hide();
        $("#error_msg").hide();
    }
);

</script>
<?php echo $_smarty_tpl->getSubTemplate ("barefoot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>