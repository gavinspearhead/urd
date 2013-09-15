<?php /* Smarty version Smarty-3.1.14, created on 2013-09-10 23:06:30
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_show_upload.tpl" */ ?>
<?php /*%%SmartyHeaderCode:111996425552055c6d17b326-91838248%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '362436ddca0846ba31d521f388660487bc9332cc' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_show_upload.tpl',
      1 => 1378847188,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '111996425552055c6d17b326-91838248',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52055c6d2c8af1_64570629',
  'variables' => 
  array (
    'LN_transfers_uploadnzb' => 0,
    'challenge' => 0,
    'LN_transfers_nzblocation' => 0,
    'localfile' => 0,
    'LN_transfers_nzblocationext' => 0,
    'LN_transfers_nzbupload' => 0,
    'LN_transfers_nzbuploadext' => 0,
    'LN_basket_setname' => 0,
    'LN_browse_schedule_at' => 0,
    'download_delay' => 0,
    'LN_browse_download_dir' => 0,
    'dl_dir' => 0,
    'directories' => 0,
    'directory' => 0,
    'LN_transfers_import' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52055c6d2c8af1_64570629')) {function content_52055c6d2c8af1_64570629($_smarty_tpl) {?>


<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered"><?php echo $_smarty_tpl->tpl_vars['LN_transfers_uploadnzb']->value;?>
</div>
<br/>
<div class="light padding10">
<table class="upload hmid">
<tr><td>
<form method="post" action="parsenzb.php" id='parseform'>
<div>
<input type="hidden" name="timestamp" value="" id='timestamp1'/> 
<input type="hidden" name="challenge" value="<?php echo $_smarty_tpl->tpl_vars['challenge']->value;?>
"/>
<input type="hidden" name="dl_dir" value="" id='dl_dir1'/> 
<input type="hidden" name="add_setname" id="add_setname1"/>
<input type="hidden" name="setname" id="setname1"/>
	<?php echo $_smarty_tpl->tpl_vars['LN_transfers_nzblocation']->value;?>
:<br/>
    <?php if ($_smarty_tpl->tpl_vars['localfile']->value!='') {?>
	<input type="text" name="file" id="url" size="30" value="<?php echo $_smarty_tpl->tpl_vars['localfile']->value;?>
" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_transfers_nzblocationext']->value),$_smarty_tpl);?>
 onchange="update_setname('url');" autofocus="autofocus"/>
    <?php } else { ?>
	<input type="text" name="url" id="url" size="30" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_transfers_nzblocationext']->value),$_smarty_tpl);?>
 onchange="update_setname('url');"/>
    <?php }?>
	</div>
</form>
</td>
<td>&nbsp;</td>
<td>
<form method='post' enctype='multipart/form-data' action='upload.php' id='uploadform'>
<div>
<input type="hidden" name="timestamp" value="" id='timestamp2'/> 
<input type="hidden" name="challenge" value="<?php echo $_smarty_tpl->tpl_vars['challenge']->value;?>
"/>
<input type="hidden" name="dl_dir" value="" id='dl_dir2'/> 
<input type="hidden" name="add_setname" id="add_setname2"/>
<input type="hidden" name="setname" id="setname2"/>
<?php echo $_smarty_tpl->tpl_vars['LN_transfers_nzbupload']->value;?>
:<br/>
<input type="file" name="upfile" id="upfile" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_transfers_nzbuploadext']->value),$_smarty_tpl);?>
 onchange="update_setname('upfile');"/>
</div>
</form>
</td>
</tr>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_basket_setname']->value;?>
:<br/><input name="setname" id="setname" type="text" value="" size="30"/></td><td>&nbsp;</td>
<td class="vtop"><?php echo $_smarty_tpl->tpl_vars['LN_browse_schedule_at']->value;?>
:<br/><input name="timestamp" id="timestamp" type="text" value="<?php echo $_smarty_tpl->tpl_vars['download_delay']->value;?>
" size="20" onclick="javascript:show_calendar(null, null, null);" onkeyup="javascript:hide_popup('calendardiv', 'calendar');"/></td></tr>
<tr><td colspan="3">
<?php echo $_smarty_tpl->tpl_vars['LN_browse_download_dir']->value;?>
:<br/>
<div id="dl_dir_span">
    <div class="floatleft"><input name="dl_dir" id="dl_dir" type="text" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['dl_dir']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" class="width300"/>&nbsp;</div>
    <div class="foldericon iconsize floatleft" onclick="toggle_hide('dir_select_span', 'hidden'); toggle_hide('dl_dir_span', 'hidden');"></div>
</div>
<div id="dir_select_span" class="hidden">
<select id="dir_select" onchange="select_dir('dir_select', 'dl_dir');" class="width300">
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
</div>

</td></tr>
<tr><td>
<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['add_setname']->value),'name'=>"add_setname",'id'=>"add_setname",'data'=>((string) $_smarty_tpl->tpl_vars['LN_browse_add_setname']->value)),$_smarty_tpl);?>
 
</td>
<tr><td colspan="3">&nbsp;</td></tr>

<tr><td class="vbot centered" colspan="3"><input type="button" value="<?php echo $_smarty_tpl->tpl_vars['LN_transfers_import']->value;?>
" class="submit" onclick="javascript:submit_upload();"/></td></tr>
</table>
</div>
<div id="calendardiv" class="calendaroff">
</div>
<?php }} ?>