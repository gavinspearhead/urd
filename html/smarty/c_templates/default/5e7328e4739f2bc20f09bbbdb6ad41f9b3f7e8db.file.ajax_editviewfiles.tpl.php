<?php /* Smarty version Smarty-3.1.14, created on 2013-08-09 23:45:25
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_editviewfiles.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1171834943520562f56d3042-89460702%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5e7328e4739f2bc20f09bbbdb6ad41f9b3f7e8db' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_editviewfiles.tpl',
      1 => 1366653088,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1171834943520562f56d3042-89460702',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'LN_edit_file' => 0,
    'filename' => 0,
    'maxstrlen' => 0,
    'LN_filename' => 0,
    'directory' => 0,
    'textboxsize' => 0,
    'LN_rights' => 0,
    'rights' => 0,
    'LN_group' => 0,
    'groups' => 0,
    'group' => 0,
    'LN_apply' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_520562f57a81f3_78460826',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_520562f57a81f3_78460826')) {function content_520562f57a81f3_78460826($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.truncate.php';
if (!is_callable('smarty_function_html_options')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/function.html_options.php';
?>


<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered"><?php echo $_smarty_tpl->tpl_vars['LN_edit_file']->value;?>
 - <?php echo smarty_modifier_truncate(mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['filename']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8'),$_smarty_tpl->tpl_vars['maxstrlen']->value,'...',false,true);?>
</div>
<div class="light">
<br/>
<table>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_filename']->value;?>
:
</td>
<td>
<input type="hidden" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['directory']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" name="directory" id="directory_editfile"/>
<input type="hidden" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['filename']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" name="oldfilename" id="oldfilename_editfile"/>
<input type="text" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['filename']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" name="newfilename" id="newfilename_editfile" size="<?php echo $_smarty_tpl->tpl_vars['textboxsize']->value;?>
"/>
</td>
</tr>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_rights']->value;?>
:
</td>
<td><input type="text" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['rights']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" id="rights_editfile" name="rights"/>
</td>
</tr>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_group']->value;?>
:
</td>
<td> <?php echo smarty_function_html_options(array('name'=>"group",'id'=>"group_editfile",'options'=>$_smarty_tpl->tpl_vars['groups']->value,'selected'=>$_smarty_tpl->tpl_vars['group']->value),$_smarty_tpl);?>

</td>
</tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr>
<td class="centered" colspan="2">
<input type="button" value="<?php echo $_smarty_tpl->tpl_vars['LN_apply']->value;?>
" name="apply" class="submit" onclick="javascript:update_filename();"/>
</td>
</tr>

</table>
</div>
<?php }} ?>