<?php /* Smarty version Smarty-3.1.14, created on 2013-08-09 23:43:53
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_showviewfiles.tpl" */ ?>
<?php /*%%SmartyHeaderCode:205701180752056299f30393-12867345%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '19a2ba56ee3ca32ed0835e0b092eb757d8d8487e' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_showviewfiles.tpl',
      1 => 1374274096,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '205701180752056299f30393-12867345',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'lastpage' => 0,
    'currentpage' => 0,
    'pages' => 0,
    'sort' => 0,
    'sort_dir' => 0,
    'up' => 0,
    'down' => 0,
    'LN_filename' => 0,
    'name_sort' => 0,
    'LN_type' => 0,
    'type_sort' => 0,
    'LN_size' => 0,
    'size_sort' => 0,
    'LN_modified' => 0,
    'mtime_sort' => 0,
    'LN_perms' => 0,
    'perms_sort' => 0,
    'LN_owner' => 0,
    'owner_sort' => 0,
    'LN_group' => 0,
    'group_sort' => 0,
    'LN_actions' => 0,
    'only_rows' => 0,
    'topskipper' => 0,
    'tableheader' => 0,
    'offset' => 0,
    'files' => 0,
    'file' => 0,
    'ext' => 0,
    'name' => 0,
    'LN_files' => 0,
    'IMGDIR' => 0,
    'icon' => 0,
    'icon_ln' => 0,
    'counter' => 0,
    'size' => 0,
    'size_ext' => 0,
    'perms' => 0,
    'LN_quickmenu_setpreviewnfo' => 0,
    'allow_edit' => 0,
    'LN_viewfiles_edit' => 0,
    'LN_viewfiles_uploadnzb' => 0,
    'LN_viewfiles_rename' => 0,
    'use_tar' => 0,
    'LN_viewfiles_download' => 0,
    'show_delete' => 0,
    'LN_delete' => 0,
    'bottomskipper' => 0,
    'directory' => 0,
    'last_line' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5205629a5e7bc0_76624795',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5205629a5e7bc0_76624795')) {function content_5205629a5e7bc0_76624795($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.capitalize.php';
?>



<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'topskipper', null); ob_start(); ?>
<?php if ($_smarty_tpl->tpl_vars['lastpage']->value!=1) {?>
<?php echo smarty_function_urd_skipper(array('current'=>$_smarty_tpl->tpl_vars['currentpage']->value,'last'=>$_smarty_tpl->tpl_vars['lastpage']->value,'pages'=>$_smarty_tpl->tpl_vars['pages']->value,'class'=>'ps','js'=>'submit_viewfiles_page','extra_class'=>"margin10"),$_smarty_tpl);?>

<?php } else { ?><br/>
<?php }?>

<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'bottomskipper', null); ob_start(); ?>
<?php if ($_smarty_tpl->tpl_vars['lastpage']->value!=1) {?>
<?php echo smarty_function_urd_skipper(array('current'=>$_smarty_tpl->tpl_vars['currentpage']->value,'last'=>$_smarty_tpl->tpl_vars['lastpage']->value,'pages'=>$_smarty_tpl->tpl_vars['pages']->value,'class'=>'psb','js'=>'submit_viewfiles_page','extra_class'=>"margin10"),$_smarty_tpl);?>

<?php } else { ?><br/>
<?php }?>

<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php $_smarty_tpl->tpl_vars['up'] = new Smarty_variable("<img src='".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/small_up.png' alt=''>", null, 0);?>
<?php $_smarty_tpl->tpl_vars['down'] = new Smarty_variable("<img src='".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/small_down.png' alt=''>", null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="name") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['name_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['name_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['name_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="type") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['type_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['type_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['type_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="mtime") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['mtime_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['mtime_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['mtime_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="size") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['size_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['size_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['size_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="perms") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['perms_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['perms_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['perms_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="owner") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['owner_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['owner_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['owner_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="group") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['group_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['group_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['group_sort'] = new Smarty_variable('', null, 0);?><?php }?>




<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'tableheader', null); ob_start(); ?>
<table class="files" id="files_table">
<tr>
<th class="fixwidth1 head round_left">&nbsp;</th>
<th id="filenametd" class="head buttonlike" onclick="submit_sort_viewfiles('name')"><?php echo $_smarty_tpl->tpl_vars['LN_filename']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['name_sort']->value;?>
</th>
<th class="head fixwidth5 buttonlike" onclick="submit_sort_viewfiles('type')"><?php echo $_smarty_tpl->tpl_vars['LN_type']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['type_sort']->value;?>
</th>
<th class="head fixwidth6 buttonlike right" onclick="submit_sort_viewfiles('size')"><?php echo $_smarty_tpl->tpl_vars['LN_size']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['size_sort']->value;?>
</th>
<th class="head fixwidth8c buttonlike right" onclick="submit_sort_viewfiles('mtime')"><?php echo $_smarty_tpl->tpl_vars['LN_modified']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['mtime_sort']->value;?>
</th>
<th class="head fixwidth5 buttonlike center" onclick="submit_sort_viewfiles('perms')"><?php echo $_smarty_tpl->tpl_vars['LN_perms']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['perms_sort']->value;?>
</th>
<th class="head fixwidth5c buttonlike" onclick="submit_sort_viewfiles('owner')"><?php echo $_smarty_tpl->tpl_vars['LN_owner']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['owner_sort']->value;?>
</th>
<th class="head fixwidth5c buttonlike" onclick="submit_sort_viewfiles('group')"><?php echo $_smarty_tpl->tpl_vars['LN_group']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['group_sort']->value;?>
</th>
<th class="head round_right right fixwidth8"><?php echo $_smarty_tpl->tpl_vars['LN_actions']->value;?>
</th>
</tr>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>


<?php if ($_smarty_tpl->tpl_vars['only_rows']->value==0) {?>

    <?php echo $_smarty_tpl->tpl_vars['topskipper']->value;?>

    <?php echo $_smarty_tpl->tpl_vars['tableheader']->value;?>

<?php }?>

<?php $_smarty_tpl->tpl_vars['counter'] = new Smarty_variable($_smarty_tpl->tpl_vars['offset']->value, null, 0);?>

<?php  $_smarty_tpl->tpl_vars['file'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['file']->_loop = false;
 $_smarty_tpl->tpl_vars['idx'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['files']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['file']->key => $_smarty_tpl->tpl_vars['file']->value) {
$_smarty_tpl->tpl_vars['file']->_loop = true;
 $_smarty_tpl->tpl_vars['idx']->value = $_smarty_tpl->tpl_vars['file']->key;
?>


<?php $_smarty_tpl->tpl_vars['size'] = new Smarty_variable($_smarty_tpl->tpl_vars['file']->value->get_size(), null, 0);?>
<?php $_smarty_tpl->tpl_vars['perms'] = new Smarty_variable($_smarty_tpl->tpl_vars['file']->value->get_perms(), null, 0);?>
<?php $_smarty_tpl->tpl_vars['ext'] = new Smarty_variable($_smarty_tpl->tpl_vars['file']->value->get_type(), null, 0);?>
<?php $_smarty_tpl->tpl_vars['icon'] = new Smarty_variable($_smarty_tpl->tpl_vars['file']->value->get_icon(), null, 0);?>
<?php $_smarty_tpl->tpl_vars['icon_ln'] = new Smarty_variable($_smarty_tpl->tpl_vars['file']->value->get_icon_ln(), null, 0);?>
<?php $_smarty_tpl->tpl_vars['name'] = new Smarty_variable($_smarty_tpl->tpl_vars['file']->value->get_name(), null, 0);?>
<?php $_smarty_tpl->tpl_vars['show_delete'] = new Smarty_variable($_smarty_tpl->tpl_vars['file']->value->get_show_delete(), null, 0);?>

<?php if ($_smarty_tpl->tpl_vars['ext']->value=='dir'&&$_smarty_tpl->tpl_vars['name']->value!='..') {?>
	<?php $_smarty_tpl->tpl_vars['size_ext'] = new Smarty_variable($_smarty_tpl->tpl_vars['LN_files']->value, null, 0);?>
<?php } else { ?>
	<?php $_smarty_tpl->tpl_vars['size_ext'] = new Smarty_variable('', null, 0);?>
<?php }?>

<tr class="even content" onmouseover="javascript:ToggleClass(this,'highlight2');" onmouseout="javascript:ToggleClass(this,'highlight2');" onmouseup="javascript:start_quickmenu('viewfiles','', null, event);">
<td><img class="noborder" src="<?php echo $_smarty_tpl->tpl_vars['IMGDIR']->value;?>
/file_icons/<?php echo $_smarty_tpl->tpl_vars['icon']->value;?>
.png" width="16" height="16" alt="<?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['icon']->value);?>
" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>smarty_modifier_capitalize($_smarty_tpl->tpl_vars['icon_ln']->value)),$_smarty_tpl);?>
 /></td>
<td class="buttonlike" onmouseup="javascript:view_files_follow_link(event, '<?php echo $_smarty_tpl->tpl_vars['file']->value->get_type();?>
', 'file<?php echo $_smarty_tpl->tpl_vars['counter']->value;?>
', '<?php echo $_smarty_tpl->tpl_vars['file']->value->get_index();?>
');return false;" onmousedown="set_mouse_click();">
<div class="donotoverflowdamnit">
<input type="hidden" name="file<?php echo $_smarty_tpl->tpl_vars['counter']->value;?>
" id="file<?php echo $_smarty_tpl->tpl_vars['counter']->value;?>
" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['name']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"/>
<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['name']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
 </div>
</td>
<td><?php echo $_smarty_tpl->tpl_vars['icon_ln']->value;?>
</td>
<td class="right"><?php echo $_smarty_tpl->tpl_vars['size']->value;?>
 <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['size_ext']->value, ENT_QUOTES, 'UTF-8', true);?>
</td>
<td class="right"><?php echo $_smarty_tpl->tpl_vars['file']->value->get_mtime();?>
</td>
<td class="center"><?php echo $_smarty_tpl->tpl_vars['perms']->value;?>
</td>
<td><?php echo $_smarty_tpl->tpl_vars['file']->value->get_owner();?>
</td>
<td><?php echo $_smarty_tpl->tpl_vars['file']->value->get_group();?>
</td>
<td>
<?php if ($_smarty_tpl->tpl_vars['name']->value!='..') {?>
<div class="floatright">
<?php if ($_smarty_tpl->tpl_vars['file']->value->get_nfo_link()!='') {?> 
<div class="floatleft iconsizeplus followicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_quickmenu_setpreviewnfo']->value,'left'=>true),$_smarty_tpl);?>
 onclick="javascript:jump('getfile.php?file=' + encodeURIComponent('<?php echo strtr($_smarty_tpl->tpl_vars['file']->value->get_nfo_link(), array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
') , true);"></div>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['allow_edit']->value&&$_smarty_tpl->tpl_vars['file']->value->get_show_edit()) {?>
    <div class="floatleft iconsizeplus editicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>smarty_modifier_capitalize($_smarty_tpl->tpl_vars['LN_viewfiles_edit']->value),'left'=>true),$_smarty_tpl);?>
 onclick="javascript:edit_file('file<?php echo $_smarty_tpl->tpl_vars['counter']->value;?>
');"></div>
<?php } else { ?>
    <div class="floatleft iconsizeplus"></div>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['icon']->value=='nzb') {?>
    <div class="floatleft iconsizeplus playicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_viewfiles_uploadnzb']->value,'left'=>true),$_smarty_tpl);?>
 onclick="submit_viewfiles_action('file<?php echo $_smarty_tpl->tpl_vars['counter']->value;?>
', 'up_nzb')"></div>
<?php } else { ?>
    <div class="floatleft iconsizeplus"></div>
<?php }?>

<div class="floatleft iconsizeplus foldericon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>smarty_modifier_capitalize($_smarty_tpl->tpl_vars['LN_viewfiles_rename']->value),'left'=>true),$_smarty_tpl);?>
 onclick="javascript:rename_file_form('file<?php echo $_smarty_tpl->tpl_vars['counter']->value;?>
');"></div>

<?php if ($_smarty_tpl->tpl_vars['use_tar']->value!=0) {?>	
    <div class="floatleft iconsizeplus downicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_viewfiles_download']->value,'left'=>true),$_smarty_tpl);?>
 onclick="submit_viewfiles_action('file<?php echo $_smarty_tpl->tpl_vars['counter']->value;?>
', 'zip_dir')"></div>
<?php } else { ?>
    <div class="floatleft iconsizeplus"></div>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['show_delete']->value) {?>
    <div class="floatleft iconsizeplus deleteicon buttonlike" onclick="submit_viewfiles_action_confirm('file<?php echo $_smarty_tpl->tpl_vars['counter']->value;?>
', <?php if ($_smarty_tpl->tpl_vars['ext']->value=='dir') {?>'delete_dir'<?php } else { ?>'delete_file'<?php }?>, '<?php echo $_smarty_tpl->tpl_vars['LN_delete']->value;?>
 \'@@\'?')" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_delete']->value,'left'=>true),$_smarty_tpl);?>
 ></div>
<?php } else { ?>
    <div class="floatleft iconsizeplus"></div>
<?php }?>
</div>
<?php }?>
</td>
</tr>
<?php $_smarty_tpl->tpl_vars['counter'] = new Smarty_variable($_smarty_tpl->tpl_vars['counter']->value+1, null, 0);?>
<?php } ?>


<?php if ($_smarty_tpl->tpl_vars['only_rows']->value==0) {?>
    </table>
    <?php echo $_smarty_tpl->tpl_vars['bottomskipper']->value;?>

    <div><br/></div>
<div>
<input type="hidden" name="offset" id="offset" value="<?php echo $_smarty_tpl->tpl_vars['offset']->value;?>
"/>
<input type="hidden" name="dir" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['directory']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" id="dir"/>
<input type="hidden" name="dir2" value="<?php echo htmlspecialchars(mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['directory']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8'), ENT_QUOTES, 'UTF-8', true);?>
" id="dir2"/>
<input type="hidden" name="sort_dir" value="<?php echo $_smarty_tpl->tpl_vars['sort_dir']->value;?>
" id="searchdir"/>
<input type="hidden" name="sort" value="<?php echo $_smarty_tpl->tpl_vars['sort']->value;?>
" id="searchorder"/>
<input type="hidden" name="filename" value="" id="filename"/>
<input type="hidden" id="last_line" value="<?php echo $_smarty_tpl->tpl_vars['last_line']->value;?>
"/>
</div>

<?php }?>

<?php }} ?>