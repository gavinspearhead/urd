<?php /* Smarty version Smarty-3.1.14, created on 2013-09-10 23:05:33
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_show_post.tpl" */ ?>
<?php /*%%SmartyHeaderCode:56206043752055c7d3344a1-89651136%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '31d52c0d0842db8169e912c3532c39bed39091bb' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_show_post.tpl',
      1 => 1378847123,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '56206043752055c7d3344a1-89651136',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52055c7d68a931_37416251',
  'variables' => 
  array (
    'LN_post_post' => 0,
    'postid' => 0,
    'readonly' => 0,
    'LN_post_directory' => 0,
    'LN_post_directoryext' => 0,
    'disabledstr' => 0,
    'dirs' => 0,
    'name' => 0,
    'dir' => 0,
    'cnt' => 0,
    'LN_post_newsgroupext' => 0,
    'LN_post_newsgroup' => 0,
    'id' => 0,
    'LN_select' => 0,
    'groups' => 0,
    'group' => 0,
    'LN_post_subjectext' => 0,
    'LN_post_subject' => 0,
    'subject' => 0,
    'readonlystr' => 0,
    'LN_post_posternameext' => 0,
    'LN_post_postername' => 0,
    'poster_name' => 0,
    'LN_post_posteremailext' => 0,
    'LN_post_posteremail' => 0,
    'poster_email' => 0,
    'LN_post_recoveryext' => 0,
    'LN_post_recovery' => 0,
    'recovery_size' => 0,
    'LN_post_rarfilesext' => 0,
    'LN_post_rarfiles' => 0,
    'rarfile_size' => 0,
    'LN_post_delete_files' => 0,
    'LN_browse_schedule_at' => 0,
    'start_time' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52055c7d68a931_37416251')) {function content_52055c7d68a931_37416251($_smarty_tpl) {?>


<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered"><?php echo $_smarty_tpl->tpl_vars['LN_post_post']->value;?>
</div>
<div class="light">
<input type="hidden" name="postid" id="postid" value="<?php echo $_smarty_tpl->tpl_vars['postid']->value;?>
"/>

<?php if ($_smarty_tpl->tpl_vars['readonly']->value==1) {?><?php $_smarty_tpl->tpl_vars['readonlystr'] = new Smarty_variable(' readonly="readonly" ', null, 0);?><?php $_smarty_tpl->tpl_vars['disabledstr'] = new Smarty_variable(' disabled="disabled" ', null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['readonlystr'] = new Smarty_variable('', null, 0);?><?php $_smarty_tpl->tpl_vars['disabledstr'] = new Smarty_variable('', null, 0);?><?php }?>
<br/>
<table class="hmid">
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_post_directory']->value;?>
:</td><td>
<select name="directory" id="directory" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_post_directoryext']->value),$_smarty_tpl);?>
 class="width300" <?php echo $_smarty_tpl->tpl_vars['disabledstr']->value;?>
>
<?php $_smarty_tpl->tpl_vars['cnt'] = new Smarty_variable(0, null, 0);?>
<?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['dirs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value) {
$_smarty_tpl->tpl_vars['name']->_loop = true;
?>
    <option value="<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['name']->value==$_smarty_tpl->tpl_vars['dir']->value) {?>selected="selected"<?php $_smarty_tpl->tpl_vars['cnt'] = new Smarty_variable(1, null, 0);?><?php }?>><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</option>
<?php } ?>
<?php if ($_smarty_tpl->tpl_vars['cnt']->value==0) {?>
    <option value="<?php echo $_smarty_tpl->tpl_vars['dir']->value;?>
" selected="selected"><?php echo $_smarty_tpl->tpl_vars['dir']->value;?>
</option>
<?php }?>
</select>

</td></tr>
<tr><td <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_post_newsgroupext']->value),$_smarty_tpl);?>
 ><?php echo $_smarty_tpl->tpl_vars['LN_post_newsgroup']->value;?>
:</td><td><select name="newsgroup" id="groupid" class="width300" <?php echo $_smarty_tpl->tpl_vars['disabledstr']->value;?>
>
    <option value="" <?php if (!isset($_smarty_tpl->tpl_vars['id']->value)||$_smarty_tpl->tpl_vars['id']->value=='') {?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['LN_select']->value;?>
</option>
<?php  $_smarty_tpl->tpl_vars['name'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['name']->_loop = false;
 $_smarty_tpl->tpl_vars['id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['groups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['name']->key => $_smarty_tpl->tpl_vars['name']->value) {
$_smarty_tpl->tpl_vars['name']->_loop = true;
 $_smarty_tpl->tpl_vars['id']->value = $_smarty_tpl->tpl_vars['name']->key;
?>
    <option value="<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['id']->value==$_smarty_tpl->tpl_vars['group']->value) {?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['name']->value;?>
</option>
<?php } ?>
</select></td></tr>
<tr><td <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_post_subjectext']->value),$_smarty_tpl);?>
 ><?php echo $_smarty_tpl->tpl_vars['LN_post_subject']->value;?>
:</td><td><input type="text" name="subject" id="subject" class="width300" value="<?php echo $_smarty_tpl->tpl_vars['subject']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['readonlystr']->value;?>
/></td></tr>
<tr><td <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_post_posternameext']->value),$_smarty_tpl);?>
 ><?php echo $_smarty_tpl->tpl_vars['LN_post_postername']->value;?>
:</td><td><input type="text" name="postername" id="postername"class="width300" value="<?php echo $_smarty_tpl->tpl_vars['poster_name']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['readonlystr']->value;?>
/></td></tr>
<tr><td <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_post_posteremailext']->value),$_smarty_tpl);?>
><?php echo $_smarty_tpl->tpl_vars['LN_post_posteremail']->value;?>
:</td><td><input type="text" name="posteremail" id="posteremail" class="width300" value="<?php echo $_smarty_tpl->tpl_vars['poster_email']->value;?>
" <?php echo $_smarty_tpl->tpl_vars['readonlystr']->value;?>
/></td></tr>
<tr><td <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_post_recoveryext']->value),$_smarty_tpl);?>
 ><?php echo $_smarty_tpl->tpl_vars['LN_post_recovery']->value;?>
:</td><td><input type="text" name="recovery"  id="recovery" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_post_recoveryext']->value),$_smarty_tpl);?>
 value="<?php echo $_smarty_tpl->tpl_vars['recovery_size']->value;?>
" class="width60" <?php echo $_smarty_tpl->tpl_vars['readonlystr']->value;?>
>%</td></tr>
<tr><td <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_post_rarfilesext']->value),$_smarty_tpl);?>
><?php echo $_smarty_tpl->tpl_vars['LN_post_rarfiles']->value;?>
:</td><td><input type="text" name="filesize" id="filesize" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_post_rarfilesext']->value),$_smarty_tpl);?>
 value="<?php echo $_smarty_tpl->tpl_vars['rarfile_size']->value;?>
" class="width60" <?php echo $_smarty_tpl->tpl_vars['readonlystr']->value;?>
/></td></tr>
<tr><td> <?php echo $_smarty_tpl->tpl_vars['LN_post_delete_files']->value;?>
:</td> <td> <?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['delete_files']->value),'name'=>"delete_files",'id'=>"delete_files",'readonly'=>$_smarty_tpl->tpl_vars['readonly']->value),$_smarty_tpl);?>
 </td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_browse_schedule_at']->value;?>
:</td><td><input id="timestamp" name="timestamp" type="text" value="<?php echo $_smarty_tpl->tpl_vars['start_time']->value;?>
" class="width300" <?php if ($_smarty_tpl->tpl_vars['readonly']->value==0) {?> onclick="javascript:show_calendar(null, null, null);" onkeyup="javascript:hide_popup('calendardiv', 'calendar');"<?php }?> <?php echo $_smarty_tpl->tpl_vars['readonlystr']->value;?>
/></td></tr>
<?php if ($_smarty_tpl->tpl_vars['readonly']->value==0) {?>
<tr><td>&nbsp;</td></tr>
<tr><td colspan="2" class="centered"><input type="button" value="<?php echo $_smarty_tpl->tpl_vars['LN_post_post']->value;?>
" class="submit" onclick="javascript:create_post();"/></td></tr>
<?php }?>

</table>
<div id="calendardiv" class="calendaroff">
</div>
</div>
<?php }} ?>