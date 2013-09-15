<?php /* Smarty version Smarty-3.1.14, created on 2013-09-10 23:44:09
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_import_settings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1420540930521fa5b8f14535-01417100%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ca5ee57f7dde31d492cdc0d8c81cea343476a0bc' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_import_settings.tpl',
      1 => 1378849447,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1420540930521fa5b8f14535-01417100',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_521fa5b90cf2b0_60000242',
  'variables' => 
  array (
    'LN_settings_import_file' => 0,
    'referrer' => 0,
    'LN_settings_filename' => 0,
    'LN_settings_upload' => 0,
    'command' => 0,
    'challenge' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_521fa5b90cf2b0_60000242')) {function content_521fa5b90cf2b0_60000242($_smarty_tpl) {?>

<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered"><?php echo $_smarty_tpl->tpl_vars['LN_settings_import_file']->value;?>
 </div>
<br/>
<div class="light">
<form method='post' enctype='multipart/form-data' action='<?php echo $_smarty_tpl->tpl_vars['referrer']->value;?>
' id='uploadform'>
<div>
<br/>
<table class="hmid">
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_settings_filename']->value;?>
:</td>
<td><input type="file" name="filename" id="files" size="60"/></td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
<td class="centered" colspan="2">
<input type="button" class="submitsmall" name="load_settings" id="submit_form" value="<?php echo $_smarty_tpl->tpl_vars['LN_settings_upload']->value;?>
"/>
<input type="hidden" name="cmd" id="command" value="<?php echo $_smarty_tpl->tpl_vars['command']->value;?>
"/>
<input type="hidden" name="challenge" value="<?php echo $_smarty_tpl->tpl_vars['challenge']->value;?>
"/>
<input type="hidden" name="referrer" id="referrer" value="<?php echo $_smarty_tpl->tpl_vars['referrer']->value;?>
"/>
</td>
</tr>
</table>
</div>
</form>
</div>
<?php }} ?>