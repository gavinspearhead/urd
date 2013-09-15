<?php /* Smarty version Smarty-3.1.14, created on 2013-09-10 23:46:07
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_editgroup.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1457536137521d1cf0dffcc1-90771204%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5ed369d1103c4620dc7faa05c2c092d3a27652dd' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_editgroup.tpl',
      1 => 1378849562,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1457536137521d1cf0dffcc1-90771204',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_521d1cf1007682_71572025',
  'variables' => 
  array (
    'id' => 0,
    'LN_feeds_addfeed' => 0,
    'LN_feeds_editfeed' => 0,
    'LN_name' => 0,
    'oldname' => 0,
    'LN_expire' => 0,
    'oldexpire' => 0,
    'LN_days' => 0,
    'LN_pref_minsetsize' => 0,
    'oldminsetsize' => 0,
    'LN_pref_maxsetsize' => 0,
    'oldmaxsetsize' => 0,
    'LN_active' => 0,
    'LN_ng_adult' => 0,
    'LN_ng_autoupdate' => 0,
    'periods_keys' => 0,
    'periods_texts' => 0,
    'oldrefresh' => 0,
    'LN_time' => 0,
    'oldtime1' => 0,
    'oldtime2' => 0,
    'LN_apply' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_521d1cf1007682_71572025')) {function content_521d1cf1007682_71572025($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/function.html_options.php';
?>




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
:</td><td colspan="3"><input type="text" size="40" name="group_name" id="group_name" value="<?php echo $_smarty_tpl->tpl_vars['oldname']->value;?>
" readonly="readonly"/></td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_expire']->value;?>
:</td><td colspan="3"><input type="text" size="5" name="group_expire" id="group_expire" value="<?php echo $_smarty_tpl->tpl_vars['oldexpire']->value;?>
"/> <?php echo $_smarty_tpl->tpl_vars['LN_days']->value;?>
</td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_pref_minsetsize']->value;?>
:</td><td colspan="3"><input type="text" size="5" name="group_minsetsize" id="group_minsetsize" value="<?php echo $_smarty_tpl->tpl_vars['oldminsetsize']->value;?>
"/></td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_pref_maxsetsize']->value;?>
:</td><td colspan="3"><input type="text" size="5" name="group_maxsetsize" id="group_maxsetsize" value="<?php echo $_smarty_tpl->tpl_vars['oldmaxsetsize']->value;?>
"/></td></tr>
<tr><td colspan="1"><?php echo $_smarty_tpl->tpl_vars['LN_active']->value;?>
</td>
<td> <?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['oldsubscribed']->value),'name'=>"group_subscribed",'id'=>"group_subscribed"),$_smarty_tpl);?>
 </td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_ng_adult']->value;?>
:</td><td><?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['oldadult']->value),'name'=>"group_adult",'id'=>"group_adult"),$_smarty_tpl);?>
</td></tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_ng_autoupdate']->value;?>
:</td><td>
<select name="group_refresh_period" id="group_refresh_period" size="1" class="update">
<?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['periods_keys']->value,'output'=>$_smarty_tpl->tpl_vars['periods_texts']->value,'selected'=>$_smarty_tpl->tpl_vars['oldrefresh']->value),$_smarty_tpl);?>

</select>
</td><td><?php echo $_smarty_tpl->tpl_vars['LN_time']->value;?>
</td><td><input type="text" id="group_time1" name="time1" value="<?php if (isset($_smarty_tpl->tpl_vars['oldtime1']->value)) {?><?php echo $_smarty_tpl->tpl_vars['oldtime1']->value;?>
<?php }?>" class="time"/>:<input type="text" id="group_time2" class="time" name="time2" value="<?php if (isset($_smarty_tpl->tpl_vars['oldtime2']->value)) {?><?php echo sprintf("%02d",$_smarty_tpl->tpl_vars['oldtime2']->value);?>
<?php }?>"/></td></tr>

<tr><td colspan="4" class="right">&nbsp;</td></tr>
<tr><td colspan="4" class="right">
	<input type="button" onclick="update_group();" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_apply']->value),$_smarty_tpl);?>
 name="submit_button" value="<?php echo $_smarty_tpl->tpl_vars['LN_apply']->value;?>
" class="submit"/> 
</td></tr>
</table>
</div>
<?php }} ?>