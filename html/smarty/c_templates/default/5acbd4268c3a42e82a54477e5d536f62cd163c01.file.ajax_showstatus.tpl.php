<?php /* Smarty version Smarty-3.1.14, created on 2013-09-04 22:44:28
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_showstatus.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7503706452002073650d84-26946025%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5acbd4268c3a42e82a54477e5d536f62cd163c01' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_showstatus.tpl',
      1 => 1378327464,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7503706452002073650d84-26946025',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_520020738dba82_43090850',
  'variables' => 
  array (
    'type' => 0,
    'isadmin' => 0,
    'isconnected' => 0,
    'LN_disableurdd' => 0,
    'startup_perc' => 0,
    'LN_enableurdd' => 0,
    'LN_status' => 0,
    'counter' => 0,
    'nodisk_perc' => 0,
    'disk_perc' => 0,
    'diskfree' => 0,
    'LN_free' => 0,
    'diskused' => 0,
    'LN_inuse' => 0,
    'disktotal' => 0,
    'LN_total' => 0,
    'LN_off' => 0,
    'LN_urdddisabled' => 0,
    'previews' => 0,
    'LN_statusidling' => 0,
    'tasks' => 0,
    'task' => 0,
    'add_class' => 0,
    'tasklink' => 0,
    'LN_eta' => 0,
    'LN_delete_all' => 0,
    'LN_stats_pv' => 0,
    'preview' => 0,
    'LN_delete' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_520020738dba82_43090850')) {function content_520020738dba82_43090850($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_truncate')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.truncate.php';
?>



<?php if ($_smarty_tpl->tpl_vars['type']->value=='icon') {?><?php if ($_smarty_tpl->tpl_vars['isadmin']->value) {?><?php if ($_smarty_tpl->tpl_vars['isconnected']->value) {?><div class="light_green iconsize centered buttonlike down3" <?php echo smarty_function_urd_popup(array('text'=>$_smarty_tpl->tpl_vars['LN_disableurdd']->value,'type'=>"small"),$_smarty_tpl);?>
 onclick="javascript:control_action('poweroff', '', '');load_quick_status();"></div><?php } elseif (($_smarty_tpl->tpl_vars['startup_perc']->value>0)&&($_smarty_tpl->tpl_vars['startup_perc']->value<=99)) {?><div class="light_yellow iconsize centered down3"></div><?php } else { ?><div class="light_red centered iconsize buttonlike down3" <?php echo smarty_function_urd_popup(array('text'=>$_smarty_tpl->tpl_vars['LN_enableurdd']->value,'type'=>"small"),$_smarty_tpl);?>
 onclick="javascript:control_action('poweron', '','');load_quick_status();"></div><?php }?><?php } else { ?><?php if ($_smarty_tpl->tpl_vars['isconnected']->value) {?><div class="light_green centered iconsize down3" <?php echo smarty_function_urd_popup(array('text'=>$_smarty_tpl->tpl_vars['LN_disableurdd']->value,'type'=>"small"),$_smarty_tpl);?>
></div><?php } elseif (($_smarty_tpl->tpl_vars['startup_perc']->value>0)&&($_smarty_tpl->tpl_vars['startup_perc']->value<=99)) {?><div class="light_yellow centered iconsize down3"></div><?php } else { ?><div class="light_red centered iconsize down3" <?php echo smarty_function_urd_popup(array('text'=>$_smarty_tpl->tpl_vars['LN_enableurdd']->value,'type'=>"small"),$_smarty_tpl);?>
></div><?php }?><?php }?><?php }?><?php if ($_smarty_tpl->tpl_vars['type']->value=='quick') {?><div class="centered2"><?php if ($_smarty_tpl->tpl_vars['isconnected']->value) {?><div class="down3 nooverflow"><?php echo $_smarty_tpl->tpl_vars['LN_status']->value;?>
 [<?php echo $_smarty_tpl->tpl_vars['counter']->value;?>
]</div><?php } elseif (($_smarty_tpl->tpl_vars['startup_perc']->value>0)&&($_smarty_tpl->tpl_vars['startup_perc']->value<=99)) {?><div class="down3 nooverflow"><?php echo $_smarty_tpl->tpl_vars['LN_status']->value;?>
 (<?php echo $_smarty_tpl->tpl_vars['startup_perc']->value;?>
%) </div><?php } else { ?><div class="down3 nooverflow"><?php echo $_smarty_tpl->tpl_vars['LN_status']->value;?>
</div><?php }?></div><?php }?><?php if ($_smarty_tpl->tpl_vars['type']->value=='disk') {?>&nbsp;<?php if ($_smarty_tpl->tpl_vars['isconnected']->value) {?><?php echo smarty_function_urd_progress(array('width'=>96,'complete'=>$_smarty_tpl->tpl_vars['nodisk_perc']->value,'done'=>'progress_done2','remain'=>'progress_done'),$_smarty_tpl);?>
<ul class="last plain"><li class="plain"><div class="down3"><span <?php if ($_smarty_tpl->tpl_vars['disk_perc']->value<10) {?> class="warning_highligh"<?php }?>><?php echo $_smarty_tpl->tpl_vars['diskfree']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['LN_free']->value;?>
 (<?php echo $_smarty_tpl->tpl_vars['disk_perc']->value;?>
%)</span></div><li class="plain "><div class="down3"><span><?php echo $_smarty_tpl->tpl_vars['diskused']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['LN_inuse']->value;?>
 (<?php echo $_smarty_tpl->tpl_vars['nodisk_perc']->value;?>
%)</span></div><li class="plain pulldown_last_item"><div class="down3"><span><?php echo $_smarty_tpl->tpl_vars['disktotal']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['LN_total']->value;?>
</span></div></ul><?php } else { ?><?php echo $_smarty_tpl->tpl_vars['LN_off']->value;?>
<?php }?><?php }?><?php if ($_smarty_tpl->tpl_vars['type']->value=='activity') {?><?php if (!$_smarty_tpl->tpl_vars['isconnected']->value) {?><li class="plain"><div class="down3" <?php if ($_smarty_tpl->tpl_vars['isadmin']->value) {?> class="down3 buttonlike" onclick="javascript:control_action('poweron', '','');load_quick_status();"<?php } else { ?>  class="down3" <?php }?>><?php echo $_smarty_tpl->tpl_vars['LN_urdddisabled']->value;?>
</div></li><?php } else { ?><?php if ($_smarty_tpl->tpl_vars['counter']->value<=0) {?><?php if (!empty($_smarty_tpl->tpl_vars['previews']->value)) {?><li class="activity"><?php } else { ?><li class="plain pulldown_last_item"><?php }?><div class="down3 buttonlike" onclick="jump('transfers.php')";><?php echo $_smarty_tpl->tpl_vars['LN_statusidling']->value;?>
!</li><?php } else { ?><?php  $_smarty_tpl->tpl_vars['task'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['task']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tasks']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['task']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['task']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['task']->key => $_smarty_tpl->tpl_vars['task']->value) {
$_smarty_tpl->tpl_vars['task']->_loop = true;
 $_smarty_tpl->tpl_vars['task']->iteration++;
 $_smarty_tpl->tpl_vars['task']->last = $_smarty_tpl->tpl_vars['task']->iteration === $_smarty_tpl->tpl_vars['task']->total;
?><?php if ($_smarty_tpl->tpl_vars['task']->value['type']=='download') {?><?php $_smarty_tpl->tpl_vars['tasklink'] = new Smarty_variable("transfers.php", null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['tasklink'] = new Smarty_variable("admin_tasks.php", null, 0);?><?php }?><?php if ($_smarty_tpl->tpl_vars['task']->last&&empty($_smarty_tpl->tpl_vars['previews']->value)) {?><?php $_smarty_tpl->tpl_vars['add_class'] = new Smarty_variable("pulldown_last_item", null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['add_class'] = new Smarty_variable('', null, 0);?><?php }?><li class="activity <?php echo $_smarty_tpl->tpl_vars['add_class']->value;?>
"><div class="down3 buttonlike" onclick="jump('<?php echo $_smarty_tpl->tpl_vars['tasklink']->value;?>
')"><?php echo $_smarty_tpl->tpl_vars['task']->value['task'];?>
 <b><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['task']->value['args'],32,'',true);?>
</b><?php if ($_smarty_tpl->tpl_vars['task']->value['counter']>1) {?> (x<?php echo $_smarty_tpl->tpl_vars['task']->value['counter'];?>
)<?php }?>:<?php if ($_smarty_tpl->tpl_vars['task']->value['niceeta']!=-1) {?><span class="xxsmall">(<?php echo $_smarty_tpl->tpl_vars['task']->value['progress'];?>
% <?php echo $_smarty_tpl->tpl_vars['LN_eta']->value;?>
: <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['task']->value['niceeta'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
)</span><?php } else { ?><span class="xxsmall">(<?php echo $_smarty_tpl->tpl_vars['task']->value['progress'];?>
%)</span><?php }?></li><?php } ?><?php }?><?php if (!empty($_smarty_tpl->tpl_vars['previews']->value)) {?><li class="activity">&nbsp;</li><li class="activity bold"><div class="down3 buttonlike fixedright deleteicon iconsize" onclick="javascript:delete_preview('all');" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_delete_all']->value),$_smarty_tpl);?>
></div><div class="down3"><?php echo $_smarty_tpl->tpl_vars['LN_stats_pv']->value;?>
</div></li><?php }?><?php  $_smarty_tpl->tpl_vars['preview'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['preview']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['previews']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['preview']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['preview']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['preview']->key => $_smarty_tpl->tpl_vars['preview']->value) {
$_smarty_tpl->tpl_vars['preview']->_loop = true;
 $_smarty_tpl->tpl_vars['preview']->iteration++;
 $_smarty_tpl->tpl_vars['preview']->last = $_smarty_tpl->tpl_vars['preview']->iteration === $_smarty_tpl->tpl_vars['preview']->total;
?><?php if ($_smarty_tpl->tpl_vars['preview']->last) {?><?php $_smarty_tpl->tpl_vars['add_class'] = new Smarty_variable("pulldown_last_item", null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['add_class'] = new Smarty_variable('', null, 0);?><?php }?><li class="activity <?php echo $_smarty_tpl->tpl_vars['add_class']->value;?>
"><div class="down3 buttonlike fixedright deleteicon iconsize" onclick="javascript:delete_preview(<?php echo $_smarty_tpl->tpl_vars['preview']->value['dlid'];?>
);" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_delete']->value),$_smarty_tpl);?>
></div><div class="down3 buttonlike" onclick="javascript:show_preview(<?php echo $_smarty_tpl->tpl_vars['preview']->value['dlid'];?>
, <?php echo $_smarty_tpl->tpl_vars['preview']->value['binary_id'];?>
, <?php echo $_smarty_tpl->tpl_vars['preview']->value['group_id'];?>
)";><?php echo mb_convert_encoding(htmlspecialchars(smarty_modifier_truncate($_smarty_tpl->tpl_vars['preview']->value['name'],32,"...",true), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
 (<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['preview']->value['donesize'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
 / <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['preview']->value['size'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
) - <?php echo $_smarty_tpl->tpl_vars['preview']->value['status'];?>
</div></li><?php } ?><?php }?><?php }?><?php echo $_smarty_tpl->getSubTemplate ("ajax_foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>