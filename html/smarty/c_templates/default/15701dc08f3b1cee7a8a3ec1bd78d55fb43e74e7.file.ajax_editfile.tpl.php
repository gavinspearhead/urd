<?php /* Smarty version Smarty-3.1.14, created on 2013-09-02 22:21:31
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_editfile.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1333441695224f34b42d551-01384245%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '15701dc08f3b1cee7a8a3ec1bd78d55fb43e74e7' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_editfile.tpl',
      1 => 1366653088,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1333441695224f34b42d551-01384245',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'new_file' => 0,
    'LN_viewfiles_newfile' => 0,
    'LN_edit_file' => 0,
    'filename' => 0,
    'maxstrlen' => 0,
    'message' => 0,
    'LN_filename' => 0,
    'LN_post_directory' => 0,
    'LN_error_needfilenames' => 0,
    'directory' => 0,
    'file_contents' => 0,
    'LN_viewfiles_savefile' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5224f34b5427b3_87715872',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5224f34b5427b3_87715872')) {function content_5224f34b5427b3_87715872($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.truncate.php';
?>


<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered">
<?php if ($_smarty_tpl->tpl_vars['new_file']->value) {?>
<?php echo $_smarty_tpl->tpl_vars['LN_viewfiles_newfile']->value;?>

<?php } else { ?>
<?php echo $_smarty_tpl->tpl_vars['LN_edit_file']->value;?>
 - <?php echo smarty_modifier_truncate(mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['filename']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8'),$_smarty_tpl->tpl_vars['maxstrlen']->value,'...',false,true);?>

<?php }?>
</div>
<div class="light padding10">
<?php if (isset($_smarty_tpl->tpl_vars['message']->value)&&$_smarty_tpl->tpl_vars['message']->value!='') {?>
<?php echo $_smarty_tpl->tpl_vars['message']->value;?>

<?php } else { ?>
<br/>
<table>
<?php if ($_smarty_tpl->tpl_vars['new_file']->value) {?>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_filename']->value;?>
:
</td>
<td >
<input type="text" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['filename']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" name="filename" id="filename_editfile" size="40"/>
<?php echo smarty_function_urd_checkbox(array('name'=>"newdir",'id'=>"newdir",'post_js'=>"toggle_textarea('filecontents_editfile', 'newdir');"),$_smarty_tpl);?>
 <?php echo $_smarty_tpl->tpl_vars['LN_post_directory']->value;?>

<input type="hidden" value="new" name="newfile" id="newfile"/>
<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['LN_error_needfilenames']->value;?>
" name="filename_err" id="filename_err"/>
</td></tr>
<tr>
<td colspan="2">
<?php } else { ?>
<tr>
<td colspan="2">
<input type="hidden" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['filename']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" name="filename" id="filename_editfile"/>
<?php }?>
<input type="hidden" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['directory']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" name="directory" id="directory_editfile"/>
<textarea class="filecontents" name="filecontents" id="filecontents_editfile"><?php echo $_smarty_tpl->tpl_vars['file_contents']->value;?>
</textarea>
</td></tr>
<tr><td colspan="2"><input type="button" class="submitsmall floatright" value="<?php echo $_smarty_tpl->tpl_vars['LN_viewfiles_savefile']->value;?>
" onclick="javascript:save_file();"/>
</td>
</tr>

</table>
</div>
<?php }?>
<?php }} ?>