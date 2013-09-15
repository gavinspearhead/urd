<?php /* Smarty version Smarty-3.1.14, created on 2013-08-28 00:44:23
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/register.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2035870348521d2bc7d07ff7-83951878%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '649bdab3ae8d344cfd050e1167f983b9052c5c93' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/register.tpl',
      1 => 1374792858,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2035870348521d2bc7d07ff7-83951878',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'LN_login_jserror' => 0,
    'subpage' => 0,
    'LN_reg_form' => 0,
    'LN_username' => 0,
    'LN_fullname' => 0,
    'LN_email' => 0,
    'LN_password' => 0,
    'LN_password_weak' => 0,
    'LN_password_medium' => 0,
    'LN_password_strong' => 0,
    'LN_reg_again' => 0,
    'LN_password_correct' => 0,
    'LN_password_incorrect' => 0,
    'captcha' => 0,
    'LN_CAPTCHA1' => 0,
    'LN_CAPTCHA2' => 0,
    'LN_register' => 0,
    'LN_reg_status' => 0,
    'LN_reg_pending' => 0,
    'LN_login_title' => 0,
    'LN_reg_activated' => 0,
    'LN_reg_activated_link' => 0,
    'LN_reg_codesent' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_521d2bc7e3ccc6_43973741',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_521d2bc7e3ccc6_43973741')) {function content_521d2bc7e3ccc6_43973741($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ("barehead.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0);?>


<div id="logindiv" class="light">
<div class="urdlogo2 floatleft noborder buttonlike down3" onclick="javascript:jump('http://www.urdland.com');"></div>
<!-- javascript enabled check -->
<noscript><div id="nojs"><?php echo $_smarty_tpl->tpl_vars['LN_login_jserror']->value;?>
</div></noscript>

<table class="logintable">
<script>
$(document).ready(function() {
        <?php if ($_smarty_tpl->tpl_vars['subpage']->value=='activated') {?>
        $('#pending').hide();
        $('#sent').hide();
        $('#form').hide();
        <?php } elseif ($_smarty_tpl->tpl_vars['subpage']->value=='pending') {?>
        $('#sent').hide();
        $('#form').hide();
        $('#activated').hide();
        <?php } else { ?> 
        $('#sent').hide();
        $('#activated').hide();
        $('#pending').hide();
        handle_passwords_register('pass1', 'pass2', 'username');
        <?php }?>
 });
</script>

<tbody id="form">
<tr><td colspan="2"><h3 class="title"><?php echo $_smarty_tpl->tpl_vars['LN_reg_form']->value;?>
</h3></td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_username']->value;?>
</td><td><input name="username" type="text" size="40" id="username"/></td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_fullname']->value;?>
</td><td><input name="fullname" type="text" size="40" id="fullname"/></td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_email']->value;?>
</td><td><input name="email" type="text" size="40" id="email"/></td></tr>
<tr><td valign="top"><?php echo $_smarty_tpl->tpl_vars['LN_password']->value;?>
</td><td><input name="password1" type="password" size="40" id="pass1"/>
 &nbsp;&nbsp; 
    <div class="floatright iconsizeplus sadicon buttonlike" onclick="javascript:toggle_show_password('pass1');toggle_show_password('pass2');"></div> 
    <span id="pwweak"><br><?php echo $_smarty_tpl->tpl_vars['LN_password_weak']->value;?>
</span>
    <span id="pwmedium"><br><?php echo $_smarty_tpl->tpl_vars['LN_password_medium']->value;?>
</span>
    <span id="pwstrong"><br><?php echo $_smarty_tpl->tpl_vars['LN_password_strong']->value;?>
</span>
</td></tr>
<tr><td valign="top"><?php echo $_smarty_tpl->tpl_vars['LN_password']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['LN_reg_again']->value;?>
</td><td><input name="password2" type="password" size="40" id="pass2"/>
    <span id="pwcorrect"><br><?php echo $_smarty_tpl->tpl_vars['LN_password_correct']->value;?>
</span>
    <span id="pwincorrect"><br><?php echo $_smarty_tpl->tpl_vars['LN_password_incorrect']->value;?>
</span>
</td></tr>
<?php if ($_smarty_tpl->tpl_vars['captcha']->value==1) {?>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_CAPTCHA1']->value;?>
:<br/>
    (<?php echo $_smarty_tpl->tpl_vars['LN_CAPTCHA2']->value;?>
)
    </td>
    <td>
    <img src="captcha.php" alt="captcha image"/>
    <input type="text" name="register_captcha" size="3" maxlength="3" id="captcha"/></td></tr>
<?php }?>
<tr><td></td><td><input type='button' value="<?php echo $_smarty_tpl->tpl_vars['LN_register']->value;?>
" class="submitsmall floatright" onclick="javascript:submit_registration();"/></td></tr>
</tbody>
<tbody id="pending">
<tr><td><h3 class="title"><?php echo $_smarty_tpl->tpl_vars['LN_reg_status']->value;?>
</h3></td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_reg_pending']->value;?>
</td></tr>
<tr><td><a href="login.php"><?php echo $_smarty_tpl->tpl_vars['LN_login_title']->value;?>
</a></td></tr>
</tbody>
<tbody id="activated">
<tr><td><h3 class="title"><?php echo $_smarty_tpl->tpl_vars['LN_reg_status']->value;?>
</h3></td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_reg_activated']->value;?>
 <a href="login.php"><?php echo $_smarty_tpl->tpl_vars['LN_reg_activated_link']->value;?>
</a>.</td></tr>
</tbody>
<tbody id="sent">
<tr><td><h3 class="title"><?php echo $_smarty_tpl->tpl_vars['LN_reg_status']->value;?>
</h3></td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_reg_codesent']->value;?>
</td></tr>
<tr><td><a href="login.php"><?php echo $_smarty_tpl->tpl_vars['LN_login_title']->value;?>
</a></td></tr>
</tbody>
</table>
</div>
<?php echo $_smarty_tpl->getSubTemplate ("barefoot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>