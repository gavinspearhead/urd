<?php /* Smarty version Smarty-3.1.14, created on 2013-09-03 23:22:13
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_showuploads.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1401628526520021466f0e74-76169736%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5e279e62ced8d39b5b951dbe1bd4367887177c0f' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_showuploads.tpl',
      1 => 1378156929,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1401628526520021466f0e74-76169736',
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
  'unifunc' => 'content_52002146962045_02522387',
  'variables' => 
  array (
    'poster' => 0,
    'isadmin' => 0,
    'show_post' => 0,
    'active_tab' => 0,
    'LN_transfers_head_started' => 0,
    'LN_transfers_head_subject' => 0,
    'LN_transfers_head_progress' => 0,
    'LN_size' => 0,
    'LN_transfers_head_speed' => 0,
    'LN_eta' => 0,
    'LN_transfers_head_username' => 0,
    'LN_transfers_head_options' => 0,
    'infoarray' => 0,
    'status' => 0,
    'stat' => 0,
    'post_hide_status' => 0,
    'a' => 0,
    'LN_transfers_badrarinfo' => 0,
    'LN_transfers_badparinfo' => 0,
    'urdd_online' => 0,
    'LN_transfers_linkstart' => 0,
    'LN_pause' => 0,
    'LN_cancel' => 0,
    'LN_delete' => 0,
    'maxstrlen' => 0,
    'options' => 0,
    'infoarray_upload' => 0,
    'LN_transfers_status_postactive' => 0,
    'LN_transfers_status_rarred' => 0,
    'LN_transfers_status_par2ed' => 0,
    'LN_transfers_status_ready' => 0,
    'LN_transfers_status_queued' => 0,
    'LN_transfers_status_paused' => 0,
    'LN_transfers_status_finished' => 0,
    'LN_transfers_status_cancelled' => 0,
    'LN_transfers_status_stopped' => 0,
    'LN_transfers_status_error' => 0,
    'LN_transfers_status_shutdown' => 0,
    'LN_transfers_status_yyencoded' => 0,
    'LN_transfers_status_rarfailed' => 0,
    'LN_transfers_status_par2failed' => 0,
    'LN_transfers_status_yyencodefailed' => 0,
    'LN_error_nouploadsfound' => 0,
  ),
  'has_nocache_code' => 0,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52002146962045_02522387')) {function content_52002146962045_02522387($_smarty_tpl) {?>



<?php if (($_smarty_tpl->tpl_vars['poster']->value!=0||$_smarty_tpl->tpl_vars['isadmin']->value!=0)&&$_smarty_tpl->tpl_vars['show_post']->value!=0) {?>
<table class="transfers <?php if ($_smarty_tpl->tpl_vars['active_tab']->value!='uploads') {?>hidden<?php }?>" id="uploads_tab">
<thead>
<tr>
<th class="left head round_left"><?php echo $_smarty_tpl->tpl_vars['LN_transfers_head_started']->value;?>
</th>
<th class="left head"><?php echo $_smarty_tpl->tpl_vars['LN_transfers_head_subject']->value;?>
</th>
<th class="left head"><?php echo $_smarty_tpl->tpl_vars['LN_transfers_head_progress']->value;?>
</th>
<th class="center head"><?php echo $_smarty_tpl->tpl_vars['LN_size']->value;?>
</th>
<th class="center head"><?php echo $_smarty_tpl->tpl_vars['LN_transfers_head_speed']->value;?>
</th>
<th class="left head"><?php echo $_smarty_tpl->tpl_vars['LN_eta']->value;?>
</th>
<?php if ($_smarty_tpl->tpl_vars['isadmin']->value!=0) {?>
<th class="left head"><?php echo $_smarty_tpl->tpl_vars['LN_transfers_head_username']->value;?>
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
<tbody>
<tr class="transferstatus"><td colspan="<?php if ($_smarty_tpl->tpl_vars['isadmin']->value!=0) {?>7<?php } else { ?>6<?php }?>"><?php echo $_smarty_tpl->tpl_vars['status']->value;?>
</td>
		<td>
        <div class="black floatright iconsize noborder buttonlike">
		<div id="<?php echo $_smarty_tpl->tpl_vars['stat']->value;?>
post" class="floatright iconsize blackbg <?php if ($_smarty_tpl->tpl_vars['post_hide_status']->value[$_smarty_tpl->tpl_vars['stat']->value]==1) {?>dynimgplus<?php } else { ?>dynimgminus<?php }?> noborder buttonlike" onclick="javascript:fold_transfer('<?php echo $_smarty_tpl->tpl_vars['stat']->value;?>
', 'post');">        </div>
        </div>
		</td>
</tr>
</tbody>


<?php  $_smarty_tpl->tpl_vars['a'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['a']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['infoarray']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['a']->key => $_smarty_tpl->tpl_vars['a']->value) {
$_smarty_tpl->tpl_vars['a']->_loop = true;
?>
<?php $_smarty_tpl->tpl_vars['stat'] = new Smarty_variable(smarty_modifier_replace($_smarty_tpl->tpl_vars['a']->value->status,' ','_'), null, 0);?>
<tbody id="data_post_<?php echo $_smarty_tpl->tpl_vars['stat']->value;?>
" class="<?php if ($_smarty_tpl->tpl_vars['post_hide_status']->value[$_smarty_tpl->tpl_vars['stat']->value]==1) {?>hidden<?php }?>">

<?php $_smarty_tpl->_capture_stack[0][] = array('opts', "options", null); ob_start(); ?>
<div class="floatright"><?php if ($_smarty_tpl->tpl_vars['a']->value->status=="rarfailed"&&$_smarty_tpl->tpl_vars['a']->value->directory!='') {?><div class="inline iconsizeplus infoicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_transfers_badrarinfo']->value),$_smarty_tpl);?>
 alt="" onclick="javascript:show_contents('<?php echo $_smarty_tpl->tpl_vars['a']->value->destination;?>
/rar.log', 0);"></div><<?php }?><?php if ($_smarty_tpl->tpl_vars['a']->value->status=="par2failed"&&$_smarty_tpl->tpl_vars['a']->value->directory!='') {?><div class="inline iconsizeplus infoicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_transfers_badparinfo']->value),$_smarty_tpl);?>
 alt="" onclick="javascript:show_contents('<?php echo $_smarty_tpl->tpl_vars['a']->value->destination;?>
/par2.log', 0);"></div><?php }?><div class="inline iconsizeplus editicon buttonlike" onclick="ShowEditPost('<?php echo $_smarty_tpl->tpl_vars['a']->value->postid;?>
');"></div><?php if (($_smarty_tpl->tpl_vars['a']->value->status=="paused"||$_smarty_tpl->tpl_vars['a']->value->status=="ready")&&$_smarty_tpl->tpl_vars['urdd_online']->value) {?><div class="inline iconsizeplus playicon buttonlike" onclick="post_edit('start','<?php echo $_smarty_tpl->tpl_vars['a']->value->postid;?>
')" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_transfers_linkstart']->value),$_smarty_tpl);?>
></div><?php }?><?php if (($_smarty_tpl->tpl_vars['a']->value->status=="active"||$_smarty_tpl->tpl_vars['a']->value->status=="queued")&&$_smarty_tpl->tpl_vars['urdd_online']->value) {?><div class="inline iconsizeplus pauseicon buttonlike" onclick="post_edit('pause','<?php echo $_smarty_tpl->tpl_vars['a']->value->postid;?>
')" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_pause']->value),$_smarty_tpl);?>
></div><?php }?><?php if (($_smarty_tpl->tpl_vars['a']->value->status=="queued"||$_smarty_tpl->tpl_vars['a']->value->status=="paused"||$_smarty_tpl->tpl_vars['a']->value->status=="active"||$_smarty_tpl->tpl_vars['a']->value->status=="ready")&&$_smarty_tpl->tpl_vars['urdd_online']->value) {?><div class="inline iconsizeplus killicon buttonlike" onclick="post_edit('cancel','<?php echo $_smarty_tpl->tpl_vars['a']->value->postid;?>
')" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_cancel']->value),$_smarty_tpl);?>
></div><?php }?><?php if ($_smarty_tpl->tpl_vars['urdd_online']->value) {?><div class="inline iconsizeplus deleteicon buttonlike" onclick="post_edit('delete','<?php echo $_smarty_tpl->tpl_vars['a']->value->postid;?>
')" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_delete']->value),$_smarty_tpl);?>
></div><?php }?></div>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

	<tr class="even" onmouseover="javascript:ToggleClass(this, 'highlight2');" onmouseout="javascript:ToggleClass(this,'highlight2');">
		<td><?php echo $_smarty_tpl->tpl_vars['a']->value->startdate;?>
</td>
		<td><b><?php echo mb_convert_encoding(htmlspecialchars(smarty_modifier_truncate($_smarty_tpl->tpl_vars['a']->value->name,$_smarty_tpl->tpl_vars['maxstrlen']->value), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</b></td>
		<td>

<?php echo smarty_function_urd_progress(array('width'=>100,'complete'=>$_smarty_tpl->tpl_vars['a']->value->progress),$_smarty_tpl);?>

 <?php echo $_smarty_tpl->tpl_vars['a']->value->progress;?>
%</td>
		<td class="right"><?php echo $_smarty_tpl->tpl_vars['a']->value->size;?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['a']->value->speed;?>
</td>
		<td class="center"><?php echo $_smarty_tpl->tpl_vars['a']->value->ETA;?>
</td>
<?php if ($_smarty_tpl->tpl_vars['isadmin']->value!=0) {?>
		<td><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['a']->value->username, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</td>
<?php }?>
		<td class="rightbut"><?php echo $_smarty_tpl->tpl_vars['options']->value;?>
</td>
	</tr>
<?php } ?>
</tbody>
<?php $_smarty_tpl->tpl_vars = $saved_tpl_vars;
foreach (Smarty::$global_tpl_vars as $key => $value) if(!isset($_smarty_tpl->tpl_vars[$key])) $_smarty_tpl->tpl_vars[$key] = $value;}}?>


<?php if (isset($_smarty_tpl->tpl_vars['infoarray_upload']->value['active'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_postactive']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_upload']->value['active']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_upload']->value['rarred'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_rarred']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_upload']->value['rarred']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_upload']->value['par2ed'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_par2ed']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_upload']->value['par2ed']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_upload']->value['ready'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_ready']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_upload']->value['ready']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_upload']->value['queued'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_queued']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_upload']->value['queued']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_upload']->value['paused'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_paused']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_upload']->value['paused']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_upload']->value['finished'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_finished']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_upload']->value['finished']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_upload']->value['cancelled'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_cancelled']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_upload']->value['cancelled']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_upload']->value['stopped'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_stopped']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_upload']->value['stopped']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_upload']->value['error'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_error']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_upload']->value['error']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_upload']->value['shutdown'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_shutdown']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_upload']->value['shutdown']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_upload']->value['yyencoded'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_yyencoded']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_upload']->value['yyencoded']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_upload']->value['rarfailed'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_rarfailed']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_upload']->value['rarfailed']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_upload']->value['par2failed'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_par2failed']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_upload']->value['par2failed']));?>
<?php }?>
<?php if (isset($_smarty_tpl->tpl_vars['infoarray_upload']->value['yyencodefailed'])) {?><?php smarty_template_function_display_status($_smarty_tpl,array('status'=>$_smarty_tpl->tpl_vars['LN_transfers_status_yyencodefailed']->value,'infoarray'=>$_smarty_tpl->tpl_vars['infoarray_upload']->value['yyencodefailed']));?>
<?php }?>
<?php if (empty($_smarty_tpl->tpl_vars['infoarray_upload']->value)) {?>
<tr><td colspan="8" class="centered highlight textback"><?php echo $_smarty_tpl->tpl_vars['LN_error_nouploadsfound']->value;?>
</td></tr>
<?php }?>

</table>
<?php }?>

<?php }} ?>