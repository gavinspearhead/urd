<?php /* Smarty version Smarty-3.1.14, created on 2013-09-03 00:17:45
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_showdownloads.tpl" */ ?>
<?php /*%%SmartyHeaderCode:65612308552055c6a1731a0-53648433%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '171a1ecacc38106c2ae97ea3425deb775946729e' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_showdownloads.tpl',
      1 => 1378156929,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '65612308552055c6a1731a0-53648433',
  'function' => 
  array (
    'display_status' => 
    array (
      'parameter' => 
      array (
        'status' => '',
        'infoarray' => '',
      ),
      'compiled' => '',
    ),
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52055c6a88b1d4_48759538',
  'variables' => 
  array (
    'show_download' => 0,
    'active_tab' => 0,
    'LN_transfers_head_started' => 0,
    'LN_transfers_head_dlname' => 0,
    'LN_transfers_head_progress' => 0,
    'LN_size' => 0,
    'LN_transfers_head_speed' => 0,
    'LN_eta' => 0,
    'isadmin' => 0,
    'LN_transfers_head_username' => 0,
    'LN_transfers_head_options' => 0,
    'infoarray' => 0,
    'status' => 0,
    'stat' => 0,
    'transfer_hide_status' => 0,
    'a' => 0,
    'LN_transfers_badrarinfo' => 0,
    'LN_transfers_badparinfo' => 0,
    'urdd_online' => 0,
    'LN_transfers_linkedit' => 0,
    'show_viewfiles' => 0,
    'LN_transfers_linkview' => 0,
    'LN_transfers_runparrar' => 0,
    'LN_transfers_linkstart' => 0,
    'LN_pause' => 0,
    'LN_cancel' => 0,
    'LN_delete' => 0,
    'maxstrlen' => 0,
    'prio_button' => 0,
    'options' => 0,
    'infoarray_download' => 0,
    'LN_transfers_status_active' => 0,
    'LN_transfers_status_ready' => 0,
    'LN_transfers_status_queued' => 0,
    'LN_transfers_status_paused' => 0,
    'LN_transfers_status_finished' => 0,
    'LN_transfers_status_complete' => 0,
    'LN_transfers_status_cancelled' => 0,
    'LN_transfers_status_stopped' => 0,
    'LN_transfers_status_error' => 0,
    'LN_transfers_status_shutdown' => 0,
    'LN_transfers_status_unrarfailed' => 0,
    'LN_transfers_status_par2failed' => 0,
    'LN_transfers_status_cksfvfailed' => 0,
    'LN_transfers_status_dlfailed' => 0,
    'LN_error_nodownloadsfound' => 0,
  ),
  'has_nocache_code' => 0,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52055c6a88b1d4_48759538')) {function content_52055c6a88b1d4_48759538($_smarty_tpl) {?>



<?php if ($_smarty_tpl->tpl_vars['show_download']->value!=0) {?> 
<table class="transfers <?php if ($_smarty_tpl->tpl_vars['active_tab']->value!='downloads') {?>hidden<?php }?>" id="downloads_tab">
<thead>
<tr>
<th class="head round_left"><?php echo $_smarty_tpl->tpl_vars['LN_transfers_head_started']->value;?>
</th>
<th class="head"><?php echo $_smarty_tpl->tpl_vars['LN_transfers_head_dlname']->value;?>
</th>
<th class="head"><?php echo $_smarty_tpl->tpl_vars['LN_transfers_head_progress']->value;?>
</th>
<th class="head"><?php echo $_smarty_tpl->tpl_vars['LN_size']->value;?>
</th>
<th class="head"><?php echo $_smarty_tpl->tpl_vars['LN_transfers_head_speed']->value;?>
</th>
<th class="head"><?php echo $_smarty_tpl->tpl_vars['LN_eta']->value;?>
</th>
<?php if ($_smarty_tpl->tpl_vars['isadmin']->value!=0) {?>
<th class="head"><?php echo $_smarty_tpl->tpl_vars['LN_transfers_head_username']->value;?>
</th>
<?php }?>
<th class="right head round_right"><?php echo $_smarty_tpl->tpl_vars['LN_transfers_head_options']->value;?>
</th>
</tr>
</thead>

<?php if (!is_callable('smarty_modifier_replace')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.replace.php';
if (!is_callable('smarty_modifier_truncate')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.truncate.php';
?><?php if (!function_exists('smarty_template_function_display_status')) {
    function smarty_template_function_display_status($_smarty_tpl,$params) {
    $saved_tpl_vars = $_smarty_tpl->tpl_vars;
    foreach ($_smarty_tpl->smarty->template_functions['display_status']['parameter'] as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);};
    foreach ($params as $key => $value) {$_smarty_tpl->tpl_vars[$key] = new Smarty_variable($value);}?>
<?php $_smarty_tpl->tpl_vars['stat'] = new Smarty_variable(smarty_modifier_replace($_smarty_tpl->tpl_vars['infoarray']->value[0]->status,' ','_'), null, 0);?>
<tr class="transferstatus">
<td colspan="<?php if ($_smarty_tpl->tpl_vars['isadmin']->value!=0) {?>7<?php } else { ?>6<?php }?>"><?php echo $_smarty_tpl->tpl_vars['status']->value;?>
</td>
<td>
    <div class="black floatright iconsize noborder buttonlike">
    <div id="<?php echo $_smarty_tpl->tpl_vars['stat']->value;?>
down" class="inline iconsize noborder buttonlike <?php if ($_smarty_tpl->tpl_vars['transfer_hide_status']->value[$_smarty_tpl->tpl_vars['stat']->value]==1) {?>dynimgplus<?php } else { ?>dynimgminus<?php }?>" onclick="javascript:fold_transfer('<?php echo $_smarty_tpl->tpl_vars['stat']->value;?>
', 'down');">
    </div>
    </div>
</td>
</tr>

<tbody id="data_down_<?php echo $_smarty_tpl->tpl_vars['stat']->value;?>
" class="<?php if ($_smarty_tpl->tpl_vars['transfer_hide_status']->value[$_smarty_tpl->tpl_vars['stat']->value]==1) {?>hidden<?php }?>">
<?php  $_smarty_tpl->tpl_vars['a'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['a']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['infoarray']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['a']->key => $_smarty_tpl->tpl_vars['a']->value) {
$_smarty_tpl->tpl_vars['a']->_loop = true;
?>
<?php $_smarty_tpl->_capture_stack[0][] = array('prio', "prio_button", null); ob_start(); ?>
<?php if ($_smarty_tpl->tpl_vars['a']->value->status=="queued"||$_smarty_tpl->tpl_vars['a']->value->status=="paused") {?>
<div class="inline iconsizeplus upicon buttonlike" onclick="javascript:transfer_edit('move_up','<?php echo $_smarty_tpl->tpl_vars['a']->value->dlid;?>
');"></div>
<div class="inline iconsizeplus downicon buttonlike" onclick="javascript:transfer_edit('move_down', '<?php echo $_smarty_tpl->tpl_vars['a']->value->dlid;?>
');"></div>
<?php }?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php $_smarty_tpl->_capture_stack[0][] = array('opts', "options", null); ob_start(); ?>
<?php if ($_smarty_tpl->tpl_vars['a']->value->comment!='') {?><?php $_smarty_tpl->tpl_vars['comment'] = new Smarty_variable($_smarty_tpl->tpl_vars['a']->value->comment, null, 0);?><div class="inline iconsizeplus infoicon" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>((string) $_smarty_tpl->tpl_vars['comment']->value)),$_smarty_tpl);?>
 ></div><?php }?><?php if ($_smarty_tpl->tpl_vars['a']->value->status=="rarfailed") {?><div class="inline iconsizeplus infoicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_transfers_badrarinfo']->value),$_smarty_tpl);?>
 onclick="javascript:show_contents('<?php echo $_smarty_tpl->tpl_vars['a']->value->destination;?>
/rar.log', 0);"></div><?php }?><?php if ($_smarty_tpl->tpl_vars['a']->value->status=="par2failed") {?><div class="inline iconsizeplus infoicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_transfers_badparinfo']->value),$_smarty_tpl);?>
 onclick="javascript:show_contents('<?php echo $_smarty_tpl->tpl_vars['a']->value->destination;?>
/par2.log',0);"></div><?php }?><?php if ($_smarty_tpl->tpl_vars['urdd_online']->value) {?><div class="inline iconsizeplus editicon buttonlike" onclick="show_rename_transfer('<?php echo $_smarty_tpl->tpl_vars['a']->value->dlid;?>
');" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_transfers_linkedit']->value),$_smarty_tpl);?>
></div><?php }?><?php if ($_smarty_tpl->tpl_vars['show_viewfiles']->value) {?><div class="inline iconsizeplus foldericon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_transfers_linkview']->value),$_smarty_tpl);?>
 onclick="javascript:jump('viewfiles.php?dir=<?php echo $_smarty_tpl->tpl_vars['a']->value->destination;?>
');"></div><?php }?><?php if (($_smarty_tpl->tpl_vars['a']->value->status=="par2failed"||$_smarty_tpl->tpl_vars['a']->value->status=="rarfailed"||$_smarty_tpl->tpl_vars['a']->value->status=="finished"||$_smarty_tpl->tpl_vars['a']->value->status=="cancelled")&&$_smarty_tpl->tpl_vars['urdd_online']->value) {?><div class="inline iconsizeplus previewicon buttonlike" onclick="transfer_edit('reparrar','<?php echo $_smarty_tpl->tpl_vars['a']->value->dlid;?>
')"<?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_transfers_runparrar']->value),$_smarty_tpl);?>
></div><?php }?><?php if (($_smarty_tpl->tpl_vars['a']->value->status=="paused"||$_smarty_tpl->tpl_vars['a']->value->status=="ready")&&$_smarty_tpl->tpl_vars['urdd_online']->value) {?><div class="inline iconsizeplus playicon buttonlike" onclick="transfer_edit('start','<?php echo $_smarty_tpl->tpl_vars['a']->value->dlid;?>
')" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_transfers_linkstart']->value),$_smarty_tpl);?>
></div><?php }?><?php if (($_smarty_tpl->tpl_vars['a']->value->status=="active"||$_smarty_tpl->tpl_vars['a']->value->status=="queued"||$_smarty_tpl->tpl_vars['a']->value->status=="ready")&&$_smarty_tpl->tpl_vars['urdd_online']->value) {?><div class="inline iconsizeplus pauseicon buttonlike" onclick="transfer_edit('pause','<?php echo $_smarty_tpl->tpl_vars['a']->value->dlid;?>
')" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_pause']->value),$_smarty_tpl);?>
></div><?php }?><?php if (($_smarty_tpl->tpl_vars['a']->value->status=="queued"||$_smarty_tpl->tpl_vars['a']->value->status=="paused"||$_smarty_tpl->tpl_vars['a']->value->status=="active"||$_smarty_tpl->tpl_vars['a']->value->status=="ready")&&$_smarty_tpl->tpl_vars['urdd_online']->value) {?><div class="inline iconsizeplus killicon buttonlike" onclick="transfer_edit('cancel','<?php echo $_smarty_tpl->tpl_vars['a']->value->dlid;?>
')" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_cancel']->value),$_smarty_tpl);?>
></div><?php }?><?php if ($_smarty_tpl->tpl_vars['urdd_online']->value) {?><div class="inline iconsizeplus deleteicon buttonlike" onclick="transfer_edit('delete','<?php echo $_smarty_tpl->tpl_vars['a']->value->dlid;?>
')" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_delete']->value),$_smarty_tpl);?>
 ></div><?php }?></td>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

	<tr class="even" onmouseover="javascript:ToggleClass(this,'highlight2');" onmouseout="javascript:ToggleClass(this,'highlight2');">
		<td><?php echo $_smarty_tpl->tpl_vars['a']->value->startdate;?>
</td>
		<td><b><?php echo mb_convert_encoding(htmlspecialchars(smarty_modifier_truncate($_smarty_tpl->tpl_vars['a']->value->name,$_smarty_tpl->tpl_vars['maxstrlen']->value), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</b></td>
		<td>

<?php echo smarty_function_urd_progress(array('width'=>100,'complete'=>$_smarty_tpl->tpl_vars['a']->value->progress),$_smarty_tpl);?>

<?php echo $_smarty_tpl->tpl_vars['a']->value->progress;?>
%</td>
		<td class="right"><?php echo $_smarty_tpl->tpl_vars['a']->value->done_size;?>
 / <?php echo $_smarty_tpl->tpl_vars['a']->value->size;?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['a']->value->speed;?>
</td>
		<td class="center"><?php echo $_smarty_tpl->tpl_vars['a']->value->ETA;?>
</td>
<?php if ($_smarty_tpl->tpl_vars['isadmin']->value!=0) {?>
		<td><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['a']->value->username, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</td>
<?php }?>
		<td class="rightbut"><div class="floatright"><?php echo $_smarty_tpl->tpl_vars['prio_button']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['options']->value;?>
</div></td>
	</tr>
<?php } ?>
</tbody>
<?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>


<?php if (isset($_smarty_tpl->tpl_vars['infoarray_download']->value['active'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_active']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_download']->value['active']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_download']->value['ready'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_ready']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_download']->value['ready']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_download']->value['queued'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_queued']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_download']->value['queued']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_download']->value['paused'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_paused']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_download']->value['paused']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_download']->value['finished'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_finished']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_download']->value['finished']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_download']->value['complete'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_complete']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_download']->value['complete']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_download']->value['cancelled'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_cancelled']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_download']->value['cancelled']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_download']->value['stopped'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_stopped']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_download']->value['stopped']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_download']->value['error'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_error']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_download']->value['error']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_download']->value['shutdown'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_shutdown']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_download']->value['shutdown']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_download']->value['rarfailed'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_unrarfailed']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_download']->value['rarfailed']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_download']->value['par2failed'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_par2failed']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_download']->value['par2failed']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_download']->value['cksfvfailed'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_cksfvfailed']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_download']->value['cksfvfailed']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_download']->value['dlfailed'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_dlfailed']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_download']->value['dlfailed']));?>
<?php }?>
<?php if (empty($_smarty_tpl->tpl_vars['infoarray_download']->value)) {?>
<tr><td colspan="8" class="centered highlight textback"><?php echo $_smarty_tpl->tpl_vars['LN_error_nodownloadsfound']->value;?>
</td></tr>

<?php }?>

</table>
<?php }?>

<?php }} ?>