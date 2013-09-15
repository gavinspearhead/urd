<?php /* Smarty version Smarty-3.1.14, created on 2013-09-10 23:05:54
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_edittransfers.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11244997085206afd3917515-73753421%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ccb6c6e0db7188ddaf85e20feeb151d248691248' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_edittransfers.tpl',
      1 => 1378847150,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11244997085206afd3917515-73753421',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5206afd3c23828_97966563',
  'variables' => 
  array (
    'LN_transfers_details' => 0,
    'id' => 0,
    'LN_transfers_name' => 0,
    'oldname' => 0,
    'LN_transfers_archpass' => 0,
    'oldpw' => 0,
    'LN_browse_download_dir' => 0,
    'dl_dir' => 0,
    'dldir_noedit' => 0,
    'directories' => 0,
    'directory' => 0,
    'LN_browse_schedule_at' => 0,
    'starttime' => 0,
    'starttime_noedit' => 0,
    'LN_transfers_unpar' => 0,
    'LN_transfers_unrar' => 0,
    'LN_transfers_deletefiles' => 0,
    'LN_transfers_subdl' => 0,
    'LN_transfers_add_setname' => 0,
    'LN_apply' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5206afd3c23828_97966563')) {function content_5206afd3c23828_97966563($_smarty_tpl) {?>


<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered"><?php echo $_smarty_tpl->tpl_vars['LN_transfers_details']->value;?>
</div>
<div class="light padding10">
<br/>
<input type="hidden" name="dlid" id="dlid" value="<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
" />
<table class="renametransfer hmid">
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_transfers_name']->value;?>
:</td><td><input type="text" class="width300" name="dlname" id="dlname" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['oldname']->value, ENT_QUOTES, 'UTF-8', true);?>
" autofocus="autofocus"/></td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_transfers_archpass']->value;?>
:</td><td><input type="text" class="width300" name="dlpass" id="dlpass" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['oldpw']->value, ENT_QUOTES, 'UTF-8', true);?>
"/></td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_browse_download_dir']->value;?>
:</td><td>

<span id="dl_dir_span">
    <div class="floatleft"><input name="dl_dir" id="dl_dir" type="text" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['dl_dir']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"  class="width300" <?php if ($_smarty_tpl->tpl_vars['dldir_noedit']->value==1) {?>readonly="readonly"<?php }?>/>&nbsp;</div>
    <?php if ($_smarty_tpl->tpl_vars['dldir_noedit']->value!=1) {?><div class="foldericon iconsize floatleft" onclick="toggle_hide('dir_select_span', 'hidden'); toggle_hide('dl_dir_span', 'hidden');"></div><?php }?>
</span>
<span id="dir_select_span" class="hidden">
<select class="width300" id="dir_select" onchange="select_dir('dir_select', 'dl_dir');">
<option value=""></option>
<?php  $_smarty_tpl->tpl_vars['directory'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['directory']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['directories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['directory']->key => $_smarty_tpl->tpl_vars['directory']->value) {
$_smarty_tpl->tpl_vars['directory']->_loop = true;
?>
<option value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['directory']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['directory']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</option>
<?php } ?>
</select>
</span>

</td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_browse_schedule_at']->value;?>
:</td><td>

<input type="text" class="width300" name="starttime" id="timestamp" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['starttime']->value, ENT_QUOTES, 'UTF-8', true);?>
" <?php if ($_smarty_tpl->tpl_vars['starttime_noedit']->value==1) {?>readonly="readonly"<?php }?>
<?php if ($_smarty_tpl->tpl_vars['starttime_noedit']->value!=1) {?>onclick="javascript:show_calendar(null, null, null);" onkeyup="javascript:hide_popup('calendardiv', 'calendar');"<?php }?>
/>
</td></tr>
<tr><td colspan="2"><br/>
<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['oldunpar']->value),'name'=>"unpar",'id'=>"unpar",'data'=>$_smarty_tpl->tpl_vars['LN_transfers_unpar']->value),$_smarty_tpl);?>

<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['oldunrar']->value),'name'=>"unrar",'id'=>"unrar",'data'=>$_smarty_tpl->tpl_vars['LN_transfers_unrar']->value),$_smarty_tpl);?>

<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['olddelete']->value),'name'=>"delete",'id'=>"delete_files",'data'=>$_smarty_tpl->tpl_vars['LN_transfers_deletefiles']->value),$_smarty_tpl);?>

<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['oldsubdl']->value),'name'=>"subdl",'id'=>"subdl",'data'=>$_smarty_tpl->tpl_vars['LN_transfers_subdl']->value),$_smarty_tpl);?>

</td></tr>
<tr><td colspan="2">
<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['add_setname']->value),'name'=>"add_setname",'id'=>"add_setname",'data'=>$_smarty_tpl->tpl_vars['LN_transfers_add_setname']->value),$_smarty_tpl);?>

<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2" class="centered">
	<input type="button" onclick="rename_transfer();" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_apply']->value),$_smarty_tpl);?>
 name="submit_button" value="<?php echo $_smarty_tpl->tpl_vars['LN_apply']->value;?>
" class="submit"/> 
</td></tr>
</table>

</div>
<div id="calendardiv" class="calendaroff">
</div>

<?php }} ?>