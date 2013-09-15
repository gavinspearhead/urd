<?php /* Smarty version Smarty-3.1.14, created on 2013-08-09 23:43:44
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_showbasket.tpl" */ ?>
<?php /*%%SmartyHeaderCode:101652047352056290a06f32-52106895%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '41104859b16f9cfc46f793c39737c8f552014fc0' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_showbasket.tpl',
      1 => 1375653238,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '101652047352056290a06f32-52106895',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'show_download' => 0,
    'LN_browse_download' => 0,
    'show_makenzb' => 0,
    'LN_browse_savenzb' => 0,
    'show_merge' => 0,
    'LN_browse_mergesets' => 0,
    'LN_browse_emptylist' => 0,
    'LN_browse_schedule_at' => 0,
    'download_delay' => 0,
    'LN_browse_download_dir' => 0,
    'dl_dir' => 0,
    'directories' => 0,
    'directory' => 0,
    'LN_basket_setname' => 0,
    'dlsetname' => 0,
    'LN_basket_totalsize' => 0,
    'totalsize' => 0,
    'addedsets' => 0,
    'q' => 0,
    'maxstrlen' => 0,
    'basket' => 0,
    'leftbuttons' => 0,
    'LN_error_diskfull' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52056290bf1510_23228973',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52056290bf1510_23228973')) {function content_52056290bf1510_23228973($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.truncate.php';
?>



<?php $_smarty_tpl->_capture_stack[0][] = array('default', "leftbuttons", null); ob_start(); ?>

<div id="basketbuttondiv" class="hidden">
<table class="basketbuttons">
<tr>
<?php if ($_smarty_tpl->tpl_vars['show_download']->value!=0) {?>
<td class="nowrap vcenter">
<div class="floatleft buttonlike basketbuttonsize noborder downloadbutton" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_browse_download']->value),$_smarty_tpl);?>
 onclick="javascript:Whichbutton('urddownload', event);"/></div>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['show_makenzb']->value!=0) {?>
<div class="floatleft buttonlike basketbuttonsize noborder getnzbbutton" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_browse_savenzb']->value),$_smarty_tpl);?>
 onclick="javascript:Whichbutton('getnzb', event);" /></div>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['show_merge']->value) {?>
<div class="floatleft buttonlike basketbuttonsize noborder mergebutton" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_browse_mergesets']->value),$_smarty_tpl);?>
 onclick="javascript:Whichbutton('mergesets', event);"/></div>
<?php }?>

<div class="floatleft buttonlike basketbuttonsize noborder clearbutton" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_browse_emptylist']->value),$_smarty_tpl);?>
 onclick="javascript:Whichbutton('clearbasket', event);"/></div>
<?php if ($_smarty_tpl->tpl_vars['show_download']->value!=0) {?>
<td class="vcenter"><div class="floatleft">
<?php echo $_smarty_tpl->tpl_vars['LN_browse_schedule_at']->value;?>
:&nbsp;<input name="timestamp" id="timestamp" type="text" value="<?php echo $_smarty_tpl->tpl_vars['download_delay']->value;?>
" size="20" onclick="javascript:show_calendar();" onkeyup="javascript:hide_popup('calendardiv', 'calendar');" onchange="javascript:update_basket_display(1);"/></div></td>
<td class="vcenter"><div class="down4">
<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['add_setname']->value),'name'=>"add_setname",'id'=>"add_setname",'before'=>"1",'data'=>((string) $_smarty_tpl->tpl_vars['LN_browse_add_setname']->value).":&nbsp;",'post_js'=>"update_basket_display(1);"),$_smarty_tpl);?>
 
</div>
</td>
<td class="vcenter"><div class="floatleft"><?php echo $_smarty_tpl->tpl_vars['LN_browse_download_dir']->value;?>
:&nbsp;</div></td>
<td class="vcenter">
<span id="dl_dir_span">
    <div class="floatleft down2"><input name="dl_dir" id="dl_dir" type="text" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['dl_dir']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" class="width300" onchange="javascript:update_basket_display(1);"/>&nbsp;</div>
    <div class="foldericon iconsizeplus floatleft" onclick="toggle_hide('dir_select_span', 'hidden'); toggle_hide('dl_dir_span', 'hidden');"></div>
</span>
<span id="dir_select_span" class="hidden">
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
</span>
</td>

<?php }?>
</tr>
</table>
</div>

<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php $_smarty_tpl->_capture_stack[0][] = array('default', "basket", null); ob_start(); ?><table class="baskettable"><tr><td><div id="innerbasketdiv"><table class="innerbaskettable"><tr><td><?php echo $_smarty_tpl->tpl_vars['LN_basket_setname']->value;?>
: <input name="dlsetname" id="dlsetname" type="text" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['dlsetname']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" size="55" onchange="javascript:update_basket_display(2);"/></td><td class="basketright nowrap bold"><?php echo $_smarty_tpl->tpl_vars['LN_basket_totalsize']->value;?>
:</td><td class="basketright nowrap bold"><?php echo $_smarty_tpl->tpl_vars['totalsize']->value;?>
</td><td width="25px"><div class="closebutton buttonlike noborder" onclick="javascript:update_basket_display(2);" ></div></td></tr><?php $_smarty_tpl->tpl_vars['totalsize'] = new Smarty_variable('0', null, 0);?><?php  $_smarty_tpl->tpl_vars['q'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['q']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['addedsets']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['q']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['loopies']['total'] = $_smarty_tpl->tpl_vars['q']->total;
foreach ($_from as $_smarty_tpl->tpl_vars['q']->key => $_smarty_tpl->tpl_vars['q']->value) {
$_smarty_tpl->tpl_vars['q']->_loop = true;
?><tr><td class="basketleft" colspan="2"><?php echo mb_convert_encoding(htmlspecialchars(smarty_modifier_truncate($_smarty_tpl->tpl_vars['q']->value['subject'],$_smarty_tpl->tpl_vars['maxstrlen']->value,"..."), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</td><td class="basketright nowrap"><?php if ($_smarty_tpl->tpl_vars['q']->value['size']==0) {?>?<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['q']->value['size'];?>
<?php }?></td></tr><?php $_smarty_tpl->tpl_vars['totalsize'] = new Smarty_variable($_smarty_tpl->tpl_vars['totalsize']->value+$_smarty_tpl->tpl_vars['q']->value['size'], null, 0);?><?php } ?></table></div></td><td></td></tr></table></div><?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>


<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['loopies']['total']>0) {?>
<div class="light">
<table width="100%" class="browsetoptable">
<tr><td colspan="3" class="minimalistic"><?php echo $_smarty_tpl->tpl_vars['basket']->value;?>
</td></tr>
<tr><td class="leftbut minimalistic"><?php echo $_smarty_tpl->tpl_vars['leftbuttons']->value;?>
</td></tr>
</table>
<input type="hidden" name="error_diskfull" id="error_diskfull" value="<?php echo $_smarty_tpl->tpl_vars['LN_error_diskfull']->value;?>
"/>
</div>
<?php }?>

<?php }} ?>