<?php /* Smarty version Smarty-3.1.14, created on 2013-09-10 23:46:56
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_editrss.tpl" */ ?>
<?php /*%%SmartyHeaderCode:39805675952056afe1b1f98-01956712%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c96debf190c5a2a56ec732bcafd278e6a2c79849' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_editrss.tpl',
      1 => 1378849608,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '39805675952056afe1b1f98-01956712',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52056afe350ea5_81543596',
  'variables' => 
  array (
    'oldusername' => 0,
    'oldpassword' => 0,
    'id' => 0,
    'LN_feeds_addfeed' => 0,
    'LN_feeds_editfeed' => 0,
    'LN_name' => 0,
    'oldname' => 0,
    'LN_feeds_url' => 0,
    'oldurl' => 0,
    'LN_expire' => 0,
    'oldexpire' => 0,
    'LN_days' => 0,
    'LN_active' => 0,
    'LN_ng_adult' => 0,
    'LN_ng_autoupdate' => 0,
    'periods_keys' => 0,
    'periods_texts' => 0,
    'oldrefresh' => 0,
    'LN_time' => 0,
    'oldtime1' => 0,
    'oldtime2' => 0,
    'LN_usenet_needsauthentication' => 0,
    'authentication' => 0,
    'LN_username' => 0,
    'LN_password' => 0,
    'LN_apply' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52056afe350ea5_81543596')) {function content_52056afe350ea5_81543596($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/function.html_options.php';
?>



<?php $_smarty_tpl->tpl_vars['authentication'] = new Smarty_variable(($_smarty_tpl->tpl_vars['oldusername']->value!=''||$_smarty_tpl->tpl_vars['oldpassword']->value!=''), null, 0);?>

<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered"><?php if ($_smarty_tpl->tpl_vars['id']->value=='new') {?><?php echo $_smarty_tpl->tpl_vars['LN_feeds_addfeed']->value;?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['LN_feeds_editfeed']->value;?>
<?php }?></div>
<div class="light">
<br/>
<input type="hidden" name="id" id="id" value="<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
" />
<table class="renametransfer hmid">
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_name']->value;?>
:</td><td colspan="3"><input type="text" size="40" name="rss_name" id="rss_name" value="<?php echo $_smarty_tpl->tpl_vars['oldname']->value;?>
"/></td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_feeds_url']->value;?>
:</td><td colspan="3"><input type="text" size="40" name="rss_url" id="rss_url" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['oldurl']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"/></td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_expire']->value;?>
:</td><td colspan="3"><input type="text" size="5" name="rss_expire" id="rss_expire" value="<?php echo $_smarty_tpl->tpl_vars['oldexpire']->value;?>
"/> <?php echo $_smarty_tpl->tpl_vars['LN_days']->value;?>
</td></tr>
<tr><td colspan="1"><?php echo $_smarty_tpl->tpl_vars['LN_active']->value;?>
</td>
<td> <?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['oldsubscribed']->value),'name'=>"rss_subscribed",'id'=>"rss_subscribed"),$_smarty_tpl);?>
 </td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_ng_adult']->value;?>
:</td><td><?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['oldadult']->value),'name'=>"rss_adult",'id'=>"rss_adult"),$_smarty_tpl);?>
</td></tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_ng_autoupdate']->value;?>
:</td><td>
<select name="rss_refresh_period" id="rss_refresh_period" size="1" class="update">
<?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['periods_keys']->value,'output'=>$_smarty_tpl->tpl_vars['periods_texts']->value,'selected'=>$_smarty_tpl->tpl_vars['oldrefresh']->value),$_smarty_tpl);?>

</select>
</td><td><?php echo $_smarty_tpl->tpl_vars['LN_time']->value;?>
</td><td>@ <input type="text" id="rss_time1" name="time1" value="<?php if (isset($_smarty_tpl->tpl_vars['oldtime1']->value)) {?><?php echo $_smarty_tpl->tpl_vars['oldtime1']->value;?>
<?php }?>" class="time"/>:<input type="text" id="rss_time2" class="time" name="time2" value="<?php if (isset($_smarty_tpl->tpl_vars['oldtime2']->value)) {?><?php echo sprintf("%02d",$_smarty_tpl->tpl_vars['oldtime2']->value);?>
<?php }?>"/></td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_usenet_needsauthentication']->value;?>
</td><td>
<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['authentication']->value),'name'=>"authentication",'id'=>"needauthentication",'post_js'=>"show_auth();"),$_smarty_tpl);?>

</td></tr>
<tr id="authuser" <?php if (!$_smarty_tpl->tpl_vars['authentication']->value) {?>class="hidden"<?php }?>><td><?php echo $_smarty_tpl->tpl_vars['LN_username']->value;?>
:</td><td colspan="3"><input type="text" size="40" name="rss_username" id="rss_username" value="<?php echo $_smarty_tpl->tpl_vars['oldusername']->value;?>
"/></td></tr>
<tr id="authpass" <?php if (!$_smarty_tpl->tpl_vars['authentication']->value) {?>class="hidden"<?php }?>><td><?php echo $_smarty_tpl->tpl_vars['LN_password']->value;?>
:</td><td colspan="3"><input type="password" size="40" name="rss_password" id="rss_password" value="<?php echo $_smarty_tpl->tpl_vars['oldpassword']->value;?>
"/>&nbsp;&nbsp; 
    <div class="floatright iconsizeplus sadicon buttonlike" onclick="javascript:toggle_show_password('rss_password');"
</td></tr>

<tr><td colspan="4" class="right">&nbsp;</td></tr>
<tr><td colspan="4" class="right">
	<input type="button" onclick="update_rss();" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_apply']->value),$_smarty_tpl);?>
 name="submit_button" value="<?php echo $_smarty_tpl->tpl_vars['LN_apply']->value;?>
" class="submit"/> 
</td></tr>
</table>
</div>
<?php }} ?>