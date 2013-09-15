<?php /* Smarty version Smarty-3.1.14, created on 2013-09-10 23:41:27
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_post_message.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1996926458520ff32f06aac0-81639388%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a129b5e6f1b975bd97bdcbc123281ff17afc2b36' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_post_message.tpl',
      1 => 1378848851,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1996926458520ff32f06aac0-81639388',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_520ff32f15af23_81678345',
  'variables' => 
  array (
    'LN_post_message' => 0,
    'LN_post_newsgroup' => 0,
    'LN_post_newsgroupext2' => 0,
    'groups' => 0,
    'groupid' => 0,
    'LN_post_subject' => 0,
    'LN_post_subjectext2' => 0,
    'subject' => 0,
    'LN_post_postername' => 0,
    'LN_post_posternameext' => 0,
    'poster_name' => 0,
    'LN_post_posteremail' => 0,
    'LN_post_posteremailext' => 0,
    'poster_email' => 0,
    'LN_post_messagetext' => 0,
    'LN_post_messagetextext' => 0,
    'content' => 0,
    'LN_post_post' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_520ff32f15af23_81678345')) {function content_520ff32f15af23_81678345($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/function.html_options.php';
?>



<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered"><?php echo $_smarty_tpl->tpl_vars['LN_post_message']->value;?>
</div>
<div class="light">
<br/>
<table class="hmid">
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_post_newsgroup']->value;?>
:</td>
<td>
<select name="newsgroup" id="groupid" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_post_newsgroupext2']->value),$_smarty_tpl);?>
 class="width300">
<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['groups']->value,'selected'=>$_smarty_tpl->tpl_vars['groupid']->value),$_smarty_tpl);?>

</select></td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_post_subject']->value;?>
:</td><td><input type="text" name="subject" id="subject" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_post_subjectext2']->value),$_smarty_tpl);?>
 class="width300" value="<?php echo $_smarty_tpl->tpl_vars['subject']->value;?>
"/></td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_post_postername']->value;?>
:</td><td><input type="text" name="postername" id="postername" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_post_posternameext']->value),$_smarty_tpl);?>
 value="<?php echo $_smarty_tpl->tpl_vars['poster_name']->value;?>
" class="width300"/></td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_post_posteremail']->value;?>
:</td><td><input type="text" name="posteremail" id="posteremail" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_post_posteremailext']->value),$_smarty_tpl);?>
 value="<?php echo $_smarty_tpl->tpl_vars['poster_email']->value;?>
" class="width300"/></td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_post_messagetext']->value;?>
:</td><td><textarea name="messagetext" id="messagetext" rows="8" class="width300" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_post_messagetextext']->value),$_smarty_tpl);?>
 ><?php echo $_smarty_tpl->tpl_vars['content']->value;?>
</textarea>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2" class="centered"><input type="button" value="<?php echo $_smarty_tpl->tpl_vars['LN_post_post']->value;?>
" class="submit" onclick="javascript:post_message();"/></td></tr>

</table>
</div>
<?php }} ?>