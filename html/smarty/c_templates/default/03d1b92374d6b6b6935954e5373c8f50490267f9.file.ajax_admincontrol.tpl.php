<?php /* Smarty version Smarty-3.1.14, created on 2013-08-10 22:57:05
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_admincontrol.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4666279125206a9214274e3-62292086%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '03d1b92374d6b6b6935954e5373c8f50490267f9' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_admincontrol.tpl',
      1 => 1375567753,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4666279125206a9214274e3-62292086',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'isconnected' => 0,
    'LN_urdname' => 0,
    'LN_version' => 0,
    'VERSION' => 0,
    'LN_control_uptime' => 0,
    'uptime_info' => 0,
    'LN_control_load' => 0,
    'load_info' => 0,
    'LN_control_diskspace' => 0,
    'nodisk_perc' => 0,
    'disk_perc' => 0,
    'diskfree' => 0,
    'LN_details' => 0,
    'control_status' => 0,
    'LN_control_threads' => 0,
    'threads_info' => 0,
    'LN_id' => 0,
    'LN_pid' => 0,
    'LN_username' => 0,
    'LN_jobs_command' => 0,
    'LN_config_prog_params' => 0,
    'LN_server' => 0,
    'LN_status' => 0,
    'LN_start_time' => 0,
    'LN_queue_time' => 0,
    't' => 0,
    'LN_control_queue' => 0,
    'queue_info' => 0,
    'LN_usenet_priority' => 0,
    'LN_time' => 0,
    'q' => 0,
    'LN_control_jobs' => 0,
    'jobs_info' => 0,
    'LN_recurrence' => 0,
    'j' => 0,
    'LN_control_servers' => 0,
    'servers_info' => 0,
    'LN_usenet_hostname' => 0,
    'LN_usenet_port' => 0,
    'LN_usenet_nrofthreads' => 0,
    'LN_free_threads' => 0,
    'LN_enabled' => 0,
    'LN_usenet_posting' => 0,
    's' => 0,
    'LN_dashboard_max_nntp' => 0,
    'servers_totals' => 0,
    'LN_free_nntp_threads' => 0,
    'LN_dashboard_max_threads' => 0,
    'LN_total_free_threads' => 0,
    'LN_dashboard_max_db_intensive' => 0,
    'LN_free_db_intensive_threads' => 0,
    'LN_urdddisabled' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5206a9218052f1_84734583',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5206a9218052f1_84734583')) {function content_5206a9218052f1_84734583($_smarty_tpl) {?>



<?php if ($_smarty_tpl->tpl_vars['isconnected']->value==1) {?>
<p></p>
<table>
<tr><td><b><?php echo $_smarty_tpl->tpl_vars['LN_urdname']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['LN_version']->value;?>
</b>:</td><td><i><?php echo $_smarty_tpl->tpl_vars['VERSION']->value;?>
</i></td><td></td></tr>
<tr><td><b><?php echo $_smarty_tpl->tpl_vars['LN_control_uptime']->value;?>
</b>:</td><td><i><?php echo $_smarty_tpl->tpl_vars['uptime_info']->value;?>
</i></td><td></td></tr>
<tr><td><b><?php echo $_smarty_tpl->tpl_vars['LN_control_load']->value;?>
</b>:</td><td><i><?php echo $_smarty_tpl->tpl_vars['load_info']->value['load_1'];?>
 --  <?php echo $_smarty_tpl->tpl_vars['load_info']->value['load_5'];?>
 -- <?php echo $_smarty_tpl->tpl_vars['load_info']->value['load_15'];?>
</i></td><td></td></tr>

<tr> <td><b><?php echo $_smarty_tpl->tpl_vars['LN_control_diskspace']->value;?>
</b>:</td>
<td><?php echo smarty_function_urd_progress(array('width'=>200,'complete'=>$_smarty_tpl->tpl_vars['nodisk_perc']->value,'done'=>'progress_done2','remain'=>'progress_done'),$_smarty_tpl);?>
</td>
<td <?php if ($_smarty_tpl->tpl_vars['disk_perc']->value<10) {?> class="warning_highlight"<?php }?>>(<?php echo $_smarty_tpl->tpl_vars['disk_perc']->value;?>
% - <?php echo $_smarty_tpl->tpl_vars['diskfree']->value;?>
)</td>
</tr>
</table>
<br/>

<br/>
<h3>&nbsp;<?php echo $_smarty_tpl->tpl_vars['LN_details']->value;?>
 
<div id="details_button" class="floatleft iconsize <?php if ($_smarty_tpl->tpl_vars['control_status']->value!=1) {?>dynimgplus<?php } else { ?>dynimgminus<?php }?> noborder buttonlike" onclick="javascript:fold_details('details_button', 'details_div');" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_details']->value),$_smarty_tpl);?>
></div></h3>
<div id="details_div" <?php if ($_smarty_tpl->tpl_vars['control_status']->value!=1) {?>class="hidden"<?php }?>>

<h4><?php echo $_smarty_tpl->tpl_vars['LN_control_threads']->value;?>
</h4>
<?php if ($_smarty_tpl->tpl_vars['threads_info']->value) {?>
<table class="width80p">
<tr class='transferhead'>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_id']->value;?>
</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_pid']->value;?>
</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_username']->value;?>
</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_jobs_command']->value;?>
</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_config_prog_params']->value;?>
</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_server']->value;?>
</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_status']->value;?>
</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_start_time']->value;?>
</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_queue_time']->value;?>
</th>
</tr>

<?php  $_smarty_tpl->tpl_vars['t'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['t']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['threads_info']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['t']->key => $_smarty_tpl->tpl_vars['t']->value) {
$_smarty_tpl->tpl_vars['t']->_loop = true;
?>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['t']->value['id'];?>
</td>
<td><?php echo $_smarty_tpl->tpl_vars['t']->value['pid'];?>
</td>
<td><?php echo $_smarty_tpl->tpl_vars['t']->value['username'];?>
</td> 
<td><?php echo $_smarty_tpl->tpl_vars['t']->value['command'];?>
</td>
<td><?php echo $_smarty_tpl->tpl_vars['t']->value['arguments'];?>
</td>
<td><?php if ($_smarty_tpl->tpl_vars['t']->value['server']!=0) {?><?php echo $_smarty_tpl->tpl_vars['t']->value['servername'];?>
 (<?php echo $_smarty_tpl->tpl_vars['t']->value['server'];?>
)<?php }?></td>
<td><?php echo $_smarty_tpl->tpl_vars['t']->value['status'];?>
</td> 
<td class="right"><?php echo $_smarty_tpl->tpl_vars['t']->value['starttime'];?>
</td>
<td class="right"><?php echo $_smarty_tpl->tpl_vars['t']->value['queuetime'];?>
</td>
</tr>
<?php } ?>
</table>
<?php }?>

<h4><?php echo $_smarty_tpl->tpl_vars['LN_control_queue']->value;?>
</h4>
<?php if ($_smarty_tpl->tpl_vars['queue_info']->value) {?>
<table class="width80p">
<tr class='transferhead'>
<th>ID</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_username']->value;?>
</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_usenet_priority']->value;?>
</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_jobs_command']->value;?>
</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_config_prog_params']->value;?>
</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_status']->value;?>
</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_time']->value;?>
</th>
</tr>
<?php  $_smarty_tpl->tpl_vars['q'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['q']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['queue_info']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['q']->key => $_smarty_tpl->tpl_vars['q']->value) {
$_smarty_tpl->tpl_vars['q']->_loop = true;
?>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['q']->value['id'];?>
</td>
<td><?php echo $_smarty_tpl->tpl_vars['q']->value['username'];?>
</td>
<td class="right"><?php echo $_smarty_tpl->tpl_vars['q']->value['priority'];?>
</td>
<td><?php echo $_smarty_tpl->tpl_vars['q']->value['command'];?>
</td>
<td><?php echo $_smarty_tpl->tpl_vars['q']->value['arguments'];?>
</td>
<td><?php echo $_smarty_tpl->tpl_vars['q']->value['status'];?>
</td>
<td><?php echo $_smarty_tpl->tpl_vars['q']->value['time'];?>
</td>
</tr>
<?php } ?>
</table>
<?php }?>

<h4><?php echo $_smarty_tpl->tpl_vars['LN_control_jobs']->value;?>
</h4>
<?php if ($_smarty_tpl->tpl_vars['jobs_info']->value) {?>
<table class="width80p">
<tr class='transferhead'>
<th class="centered">ID</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_username']->value;?>
</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_jobs_command']->value;?>
</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_config_prog_params']->value;?>
</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_time']->value;?>
</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_recurrence']->value;?>
</th>
</tr>
<?php  $_smarty_tpl->tpl_vars['j'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['j']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['jobs_info']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['j']->key => $_smarty_tpl->tpl_vars['j']->value) {
$_smarty_tpl->tpl_vars['j']->_loop = true;
?>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['j']->value['id'];?>
</td>
<td><?php echo $_smarty_tpl->tpl_vars['j']->value['username'];?>
</td>
<td><?php echo $_smarty_tpl->tpl_vars['j']->value['command'];?>
</td> 
<td><?php echo $_smarty_tpl->tpl_vars['j']->value['arguments'];?>
</td>
<td class="right"><?php echo $_smarty_tpl->tpl_vars['j']->value['time'];?>
</td>
<td class="right"><?php echo $_smarty_tpl->tpl_vars['j']->value['recurrence'];?>
</td>
</tr>
<?php } ?>
</table>
<?php }?>

<h4><?php echo $_smarty_tpl->tpl_vars['LN_control_servers']->value;?>
</h4>
<?php if ($_smarty_tpl->tpl_vars['servers_info']->value) {?>
<table class="width80p">
<tr class='transferhead'>
<th class="centered">ID</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_usenet_hostname']->value;?>
:<?php echo $_smarty_tpl->tpl_vars['LN_usenet_port']->value;?>
</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_usenet_priority']->value;?>
</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_usenet_nrofthreads']->value;?>
</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_free_threads']->value;?>
</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_enabled']->value;?>
</th>
<th class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_usenet_posting']->value;?>
</th>
</tr>
<?php  $_smarty_tpl->tpl_vars['s'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['s']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['servers_info']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['s']->key => $_smarty_tpl->tpl_vars['s']->value) {
$_smarty_tpl->tpl_vars['s']->_loop = true;
?>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['s']->value['id'];?>
</td>
<td><?php echo $_smarty_tpl->tpl_vars['s']->value['hostname'];?>
:<?php echo $_smarty_tpl->tpl_vars['s']->value['port'];?>
</td>
<td class="right"><?php echo $_smarty_tpl->tpl_vars['s']->value['priority'];?>
</td> 
<td class="right"><?php echo $_smarty_tpl->tpl_vars['s']->value['max_threads'];?>
</td>
<td class="right"><?php echo $_smarty_tpl->tpl_vars['s']->value['free_threads'];?>
</td>
<td><?php echo $_smarty_tpl->tpl_vars['s']->value['enabled'];?>
</td>
<td><?php echo $_smarty_tpl->tpl_vars['s']->value['posting'];?>
</td>
</tr>
<?php } ?>
</table>
<?php }?>

<p>&nbsp;</p>
<table>
<tr class='transferhead'><th colspan="2" class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_control_threads']->value;?>
</th></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_dashboard_max_nntp']->value;?>
</td><td class="right"><?php echo $_smarty_tpl->tpl_vars['servers_totals']->value['total_nntp'];?>
</td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_free_nntp_threads']->value;?>
</td><td class="right"><?php echo $_smarty_tpl->tpl_vars['servers_totals']->value['free_nntp'];?>
</td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_dashboard_max_threads']->value;?>
</td><td class="right"><?php echo $_smarty_tpl->tpl_vars['servers_totals']->value['total_threads'];?>
</td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_total_free_threads']->value;?>
</td><td class="right"><?php echo $_smarty_tpl->tpl_vars['servers_totals']->value['free_total'];?>
</td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_dashboard_max_db_intensive']->value;?>
</td><td class="right"><?php echo $_smarty_tpl->tpl_vars['servers_totals']->value['db_intensive'];?>
</td></tr>
<tr><td><?php echo $_smarty_tpl->tpl_vars['LN_free_db_intensive_threads']->value;?>
</td><td class="right"><?php echo $_smarty_tpl->tpl_vars['servers_totals']->value['free_db_intensive'];?>
</td></tr>

<?php } else { ?>
<?php echo $_smarty_tpl->tpl_vars['LN_urdddisabled']->value;?>

<?php }?>

<?php }} ?>