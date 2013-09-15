<?php /* Smarty version Smarty-3.1.14, created on 2013-09-02 00:00:28
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_groups.tpl" */ ?>
<?php /*%%SmartyHeaderCode:35838097252056aca6764a6-32232643%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7390b379d04071e4de2e801807fb9ff5a80fb3f7' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_groups.tpl',
      1 => 1378072826,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '35838097252056aca6764a6-32232643',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52056acb1ef372_99024089',
  'variables' => 
  array (
    'page_tab' => 0,
    'isadmin' => 0,
    'lastpage' => 0,
    'currentpage' => 0,
    'pages' => 0,
    'LN_global_settings' => 0,
    'LN_user_settings' => 0,
    'unsubscribed' => 0,
    'LN_ng_subscribed' => 0,
    'LN_ng_newsgroups' => 0,
    'sort' => 0,
    'sort_dir' => 0,
    'topskipper' => 0,
    'selector' => 0,
    'up' => 0,
    'down' => 0,
    'LN_ng_tooltip_active' => 0,
    'active_sort' => 0,
    'LN_ng_tooltip_name' => 0,
    'LN_name' => 0,
    'name_sort' => 0,
    'LN_ng_tooltip_category' => 0,
    'user_hidden' => 0,
    'LN_category' => 0,
    'category_sort' => 0,
    'LN_ng_tooltip_posts' => 0,
    'LN_ng_posts' => 0,
    'postcount_sort' => 0,
    'LN_ng_tooltip_adult' => 0,
    'admin_hidden' => 0,
    'LN_ng_adult' => 0,
    'adult_sort' => 0,
    'LN_ng_tooltip_lastupdated' => 0,
    'LN_ng_lastupdated' => 0,
    'last_updated_sort' => 0,
    'LN_ng_tooltip_expire' => 0,
    'LN_ng_expire_time' => 0,
    'expire_sort' => 0,
    'LN_ng_tooltip_admin_minsetsize' => 0,
    'LN_ng_admin_minsetsize' => 0,
    'admin_minsetsize_sort' => 0,
    'LN_ng_tooltip_admin_maxsetsize' => 0,
    'LN_ng_admin_maxsetsize' => 0,
    'admin_maxsetsize_sort' => 0,
    'LN_ng_tooltip_visible' => 0,
    'LN_ng_visible' => 0,
    'visible_sort' => 0,
    'LN_ng_tooltip_minsetsize' => 0,
    'LN_ng_minsetsize' => 0,
    'minsetsize_sort' => 0,
    'urdd_online' => 0,
    'LN_ng_tooltip_autoupdate' => 0,
    'LN_ng_autoupdate' => 0,
    'refresh_period_sort' => 0,
    'LN_ng_tooltip_time' => 0,
    'LN_time' => 0,
    'refresh_time_sort' => 0,
    'LN_ng_tooltip_action' => 0,
    'LN_actions' => 0,
    'allgroups' => 0,
    'group' => 0,
    'space' => 0,
    'tooltip' => 0,
    'NG_SUBSCRIBED' => 0,
    'LN_nocategory' => 0,
    'categories' => 0,
    'item' => 0,
    'periods_keys' => 0,
    'periods_texts' => 0,
    'LN_feeds_edit' => 0,
    'LN_update' => 0,
    'LN_ng_gensets' => 0,
    'LN_expire' => 0,
    'LN_purge' => 0,
    'LN_error_nogroupsfound' => 0,
    'bottomskipper' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52056acb1ef372_99024089')) {function content_52056acb1ef372_99024089($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/function.html_options.php';
?>


<?php if ($_smarty_tpl->tpl_vars['page_tab']->value=="admin"&&$_smarty_tpl->tpl_vars['isadmin']->value) {?> 
<?php $_smarty_tpl->tpl_vars['admin_hidden'] = new Smarty_variable('', null, 0);?>
<?php $_smarty_tpl->tpl_vars['user_hidden'] = new Smarty_variable("hidden", null, 0);?>
<?php } else { ?> 
<?php $_smarty_tpl->tpl_vars['admin_hidden'] = new Smarty_variable("hidden", null, 0);?>
<?php $_smarty_tpl->tpl_vars['user_hidden'] = new Smarty_variable('', null, 0);?>
<?php }?>


<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'topskipper', null); ob_start(); ?><div class="ng_selector"><?php if ($_smarty_tpl->tpl_vars['lastpage']->value>1) {?><?php echo smarty_function_urd_skipper(array('current'=>$_smarty_tpl->tpl_vars['currentpage']->value,'last'=>$_smarty_tpl->tpl_vars['lastpage']->value,'pages'=>$_smarty_tpl->tpl_vars['pages']->value,'class'=>'ps','js'=>'group_page'),$_smarty_tpl);?>
<?php }?></div>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>


<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'bottomskipper', null); ob_start(); ?><div class="ng_selector"><?php if ($_smarty_tpl->tpl_vars['lastpage']->value>1) {?><?php echo smarty_function_urd_skipper(array('current'=>$_smarty_tpl->tpl_vars['currentpage']->value,'last'=>$_smarty_tpl->tpl_vars['lastpage']->value,'pages'=>$_smarty_tpl->tpl_vars['pages']->value,'class'=>'psb','js'=>'group_page'),$_smarty_tpl);?>
<?php }?></div>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'selector', null); ob_start(); ?><div class="pref_selector"><ul class="tabs"><li onclick="javascript:toggle_table('groupstable', 'user', 'admin')" id="button_global" class="tab<?php if ($_smarty_tpl->tpl_vars['page_tab']->value=='admin') {?> tab_selected<?php }?>"><?php echo $_smarty_tpl->tpl_vars['LN_global_settings']->value;?>
</li><li onclick="javascript:toggle_table('groupstable', 'admin', 'user')" id="button_user" class="tab<?php if ($_smarty_tpl->tpl_vars['page_tab']->value!='admin') {?> tab_selected<?php }?>"><?php echo $_smarty_tpl->tpl_vars['LN_user_settings']->value;?>
<input type="hidden" id="page_tab" value="<?php echo $_smarty_tpl->tpl_vars['page_tab']->value;?>
"/></li></ul></div>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<h3 class="title"><?php if ($_smarty_tpl->tpl_vars['unsubscribed']->value==0) {?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['LN_ng_subscribed']->value, ENT_QUOTES, 'UTF-8', true);?>
: <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['LN_ng_newsgroups']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php } else { ?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['LN_ng_newsgroups']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php }?></h3>

<form action="ajax_groups.php" method="post" id="newsgroupform">
<div class="hidden">
<input type="hidden" name="page" id="page1" value="<?php echo $_smarty_tpl->tpl_vars['page_tab']->value;?>
"/>
<input type="hidden" id="order" name="order" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['sort']->value, ENT_QUOTES, 'UTF-8', true);?>
"/>
<input type="hidden" id="order_dir" name="order_dir" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['sort_dir']->value, ENT_QUOTES, 'UTF-8', true);?>
"/>
</div>



<div id="ng_headerbox">
<?php echo $_smarty_tpl->tpl_vars['topskipper']->value;?>

<?php echo $_smarty_tpl->tpl_vars['selector']->value;?>
 
</div>

<?php $_smarty_tpl->tpl_vars['up'] = new Smarty_variable("<img src='".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/small_up.png' alt=''/>", null, 0);?>
<?php $_smarty_tpl->tpl_vars['down'] = new Smarty_variable("<img src='".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/small_down.png' alt=''/>", null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="name") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['name_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['name_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['name_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="active") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['active_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['active_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['active_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="category") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['category_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['category_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['category_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="postcount") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['postcount_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['postcount_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['postcount_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="adult") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['adult_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['adult_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['adult_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="last_updated") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['last_updated_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['last_updated_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['last_updated_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="expire") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['expire_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['expire_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['expire_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="admin_minsetsize") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['admin_minsetsize_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['admin_minsetsize_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['admin_minsetsize_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="admin_maxsetsize") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['admin_maxsetsize_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['admin_maxsetsize_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['admin_maxsetsize_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="visible") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['visible_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['visible_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['visible_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="minsetsize") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['minsetsize_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['minsetsize_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['minsetsize_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="refresh_period") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['refresh_period_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['refresh_period_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['refresh_period_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="refresh_time") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['refresh_time_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['refresh_time_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['refresh_time_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=='') {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['_sort'] = new Smarty_variable('', null, 0);?><?php }?>

<table class="newsgroups" id="groupstable">
<tr>
<th class="general head round_left">&nbsp;</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_ng_tooltip_active']->value),$_smarty_tpl);?>
 class="general buttonlike head" onclick="javascript:load_groups( { order: 'active', defsort: 'desc' } );">&nbsp;<?php echo $_smarty_tpl->tpl_vars['active_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_ng_tooltip_name']->value),$_smarty_tpl);?>
 class="general buttonlike head" onclick="javascript:load_groups( { order: 'name', defsort: 'desc' } );"><?php echo $_smarty_tpl->tpl_vars['LN_name']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['name_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_ng_tooltip_category']->value),$_smarty_tpl);?>
 class="<?php echo $_smarty_tpl->tpl_vars['user_hidden']->value;?>
 center user buttonlike head" onclick="javascript:load_groups( { order : 'category', defsort: 'asc' } );"><?php echo $_smarty_tpl->tpl_vars['LN_category']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['category_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_ng_tooltip_posts']->value),$_smarty_tpl);?>
 class="center general buttonlike head" onclick="javascript:load_groups( { order : 'postcount', defsort: 'desc' } );"><?php echo $_smarty_tpl->tpl_vars['LN_ng_posts']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['postcount_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_ng_tooltip_adult']->value),$_smarty_tpl);?>
 class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 admin buttonlike center head" onclick="javascript:load_groups( { order: 'adult', defsort: 'desc' } );"><?php echo $_smarty_tpl->tpl_vars['LN_ng_adult']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['adult_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_ng_tooltip_lastupdated']->value),$_smarty_tpl);?>
 class="center general buttonlike head" onclick="javascript:load_groups( { order : 'last_updated', defsort: 'desc' } );"><?php echo $_smarty_tpl->tpl_vars['LN_ng_lastupdated']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['last_updated_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_ng_tooltip_expire']->value),$_smarty_tpl);?>
 class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 center admin buttonlike head" onclick="javascript:load_groups( { order : 'expire', defsort: 'desc' } );"><?php echo $_smarty_tpl->tpl_vars['LN_ng_expire_time']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['expire_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_ng_tooltip_admin_minsetsize']->value),$_smarty_tpl);?>
 class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 center admin head buttonlike" onclick="javascript:load_groups( { order : 'admin_minsetsize', defsort: 'desc' } );"><?php echo $_smarty_tpl->tpl_vars['LN_ng_admin_minsetsize']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['admin_minsetsize_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_ng_tooltip_admin_maxsetsize']->value),$_smarty_tpl);?>
 class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 center admin head buttonlike" onclick="javascript:load_groups( { order : 'admin_maxsetsize', defsort: 'desc' } );"><?php echo $_smarty_tpl->tpl_vars['LN_ng_admin_maxsetsize']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['admin_maxsetsize_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_ng_tooltip_visible']->value),$_smarty_tpl);?>
 class="<?php echo $_smarty_tpl->tpl_vars['user_hidden']->value;?>
 center user buttonlike head" onclick="javascript:load_groups( { order : 'visible', defsort: 'desc' } );"><?php echo $_smarty_tpl->tpl_vars['LN_ng_visible']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['visible_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_ng_tooltip_minsetsize']->value),$_smarty_tpl);?>
 class="<?php echo $_smarty_tpl->tpl_vars['user_hidden']->value;?>
 center user buttonlike head round_right" onclick="javascript:load_groups( { order : 'minsetsize', defsort: 'desc' } );"><?php echo $_smarty_tpl->tpl_vars['LN_ng_minsetsize']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['minsetsize_sort']->value;?>
</th>
<?php if ($_smarty_tpl->tpl_vars['isadmin']->value!=0&&$_smarty_tpl->tpl_vars['urdd_online']->value!=0) {?>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_ng_tooltip_autoupdate']->value),$_smarty_tpl);?>
 class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 admin buttonlike head" onclick="javascript:load_groups( { order : 'refresh_period', defsort: 'desc' } );"><?php echo $_smarty_tpl->tpl_vars['LN_ng_autoupdate']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['refresh_period_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_ng_tooltip_time']->value),$_smarty_tpl);?>
 class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 admin buttonlike head" onclick="javascript:load_groups( { order : 'refresh_time', defsort: 'desc' } );">@ <?php echo $_smarty_tpl->tpl_vars['LN_time']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['refresh_time_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_ng_tooltip_action']->value),$_smarty_tpl);?>
 class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 admin head round_right"><?php echo $_smarty_tpl->tpl_vars['LN_actions']->value;?>
</th>
<?php }?>
</tr>

<?php  $_smarty_tpl->tpl_vars['group'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['group']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['allgroups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['group']->key => $_smarty_tpl->tpl_vars['group']->value) {
$_smarty_tpl->tpl_vars['group']->_loop = true;
?>
<tr class="even content" onmouseover="javascript:ToggleClass(this,'highlight2')" onmouseout="javascript:ToggleClass(this,'highlight2')">

<td class="general"><?php echo $_smarty_tpl->tpl_vars['group']->value['number'];?>
 </td>
<td class="general">
<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['group']->value['active_val']),'name'=>"newsgroup[".((string) $_smarty_tpl->tpl_vars['group']->value['id'])."]",'id'=>"newsgroup_".((string) $_smarty_tpl->tpl_vars['group']->value['id']),'readonly'=>((string) ($_smarty_tpl->tpl_vars['isadmin']->value==0||$_smarty_tpl->tpl_vars['urdd_online']->value==0))),$_smarty_tpl);?>
 
<input type="hidden" id="ng_id_<?php echo $_smarty_tpl->tpl_vars['group']->value['id'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['group']->value['name'];?>
"/>
</td>
<?php if ($_smarty_tpl->tpl_vars['group']->value['description']!='') {?>
<?php $_smarty_tpl->tpl_vars['space'] = new Smarty_variable('<br/>', null, 0);?>
<?php $_smarty_tpl->tpl_vars['tooltip'] = new Smarty_variable(((string) $_smarty_tpl->tpl_vars['group']->value['name']).((string) $_smarty_tpl->tpl_vars['space']->value).((string) $_smarty_tpl->tpl_vars['group']->value['description']), null, 0);?>
<?php } else { ?>
<?php $_smarty_tpl->tpl_vars['tooltip'] = new Smarty_variable('', null, 0);?>
<?php }?>
<td <?php if ($_smarty_tpl->tpl_vars['tooltip']->value!='') {?><?php echo smarty_function_urd_popup(array('text'=>htmlspecialchars($_smarty_tpl->tpl_vars['tooltip']->value, ENT_QUOTES, 'UTF-8', true)),$_smarty_tpl);?>
<?php }?> class="general" > 
<span <?php if ($_smarty_tpl->tpl_vars['group']->value['active_val']==$_smarty_tpl->tpl_vars['NG_SUBSCRIBED']->value) {?> class="buttonlike" onclick="javascript:jump('browse.php?groupID=group_<?php echo $_smarty_tpl->tpl_vars['group']->value['id'];?>
');"<?php }?>>
<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['group']->value['name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</span>
</td>
<td class="<?php echo $_smarty_tpl->tpl_vars['user_hidden']->value;?>
 user center">
<select name="category[<?php echo $_smarty_tpl->tpl_vars['group']->value['id'];?>
]" id="category_<?php echo $_smarty_tpl->tpl_vars['group']->value['id'];?>
">
    <option value="0"><?php echo $_smarty_tpl->tpl_vars['LN_nocategory']->value;?>
</option>
    <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['categories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>		
        <option <?php if ($_smarty_tpl->tpl_vars['item']->value['id']==$_smarty_tpl->tpl_vars['group']->value['category']) {?>selected="selected"<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
"><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</option>
	<?php } ?>
</select>
    </td>
<td class="general right"><?php echo $_smarty_tpl->tpl_vars['group']->value['postcount'];?>
</td>
<td class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 admin center">

<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['group']->value['adult']),'name'=>"adult[".((string) $_smarty_tpl->tpl_vars['group']->value['id'])."]",'id'=>"adult_".((string) $_smarty_tpl->tpl_vars['group']->value['id']),'readonly'=>((string) ($_smarty_tpl->tpl_vars['isadmin']->value==0)),'post_js'=>"update_adult('group', '".((string) $_smarty_tpl->tpl_vars['group']->value['id'])."')"),$_smarty_tpl);?>
 
</td>
<td class="general right"><?php echo $_smarty_tpl->tpl_vars['group']->value['lastupdated'];?>
</td>
<td class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 admin right"><input type="text" size="2" value="<?php echo $_smarty_tpl->tpl_vars['group']->value['expire'];?>
" name="expire[<?php echo $_smarty_tpl->tpl_vars['group']->value['id'];?>
]" <?php if ($_smarty_tpl->tpl_vars['isadmin']->value!=1||$_smarty_tpl->tpl_vars['urdd_online']->value!=1) {?> readonly="readonly"<?php }?>/></td>
<td class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 admin right"><input type="text" size="4" value="<?php echo $_smarty_tpl->tpl_vars['group']->value['admin_minsetsize'];?>
" name="admin_minsetsize[<?php echo $_smarty_tpl->tpl_vars['group']->value['id'];?>
]" <?php if ($_smarty_tpl->tpl_vars['isadmin']->value!=1||$_smarty_tpl->tpl_vars['urdd_online']->value!=1) {?> readonly="readonly"<?php }?>/></td>
<td class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 admin right"><input type="text" size="4" value="<?php echo $_smarty_tpl->tpl_vars['group']->value['admin_maxsetsize'];?>
" name="admin_maxsetsize[<?php echo $_smarty_tpl->tpl_vars['group']->value['id'];?>
]" <?php if ($_smarty_tpl->tpl_vars['isadmin']->value!=1||$_smarty_tpl->tpl_vars['urdd_online']->value!=1) {?> readonly="readonly"<?php }?>/></td>
<td class="<?php echo $_smarty_tpl->tpl_vars['user_hidden']->value;?>
 user center">
<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['group']->value['visible']),'name'=>"visible[".((string) $_smarty_tpl->tpl_vars['group']->value['id'])."]",'id'=>"visible_".((string) $_smarty_tpl->tpl_vars['group']->value['id'])),$_smarty_tpl);?>
 
</td>
<td class="<?php echo $_smarty_tpl->tpl_vars['user_hidden']->value;?>
 user center"><input type="text" size="2" value="<?php echo $_smarty_tpl->tpl_vars['group']->value['minsetsize'];?>
" name="minsetsize[<?php echo $_smarty_tpl->tpl_vars['group']->value['id'];?>
]"/>
<input type="text" size="2" value="<?php echo $_smarty_tpl->tpl_vars['group']->value['maxsetsize'];?>
" name="maxsetsize[<?php echo $_smarty_tpl->tpl_vars['group']->value['id'];?>
]"/>
</td>

<?php if ($_smarty_tpl->tpl_vars['isadmin']->value!=0&&$_smarty_tpl->tpl_vars['urdd_online']->value!=0) {?>
<td class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 admin center"> 
<select name="period[<?php echo $_smarty_tpl->tpl_vars['group']->value['id'];?>
]" size="1" class="update">
<?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['periods_keys']->value,'output'=>$_smarty_tpl->tpl_vars['periods_texts']->value,'selected'=>$_smarty_tpl->tpl_vars['group']->value['select']),$_smarty_tpl);?>

</select>
</td>
<td class="nowrap <?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 admin center">@ <input type="text" id="time1_<?php echo $_smarty_tpl->tpl_vars['group']->value['id'];?>
" name="time1[<?php echo $_smarty_tpl->tpl_vars['group']->value['id'];?>
]" value="<?php echo $_smarty_tpl->tpl_vars['group']->value['time1'];?>
" class="time"/>:<input type="text" id="time2_<?php echo $_smarty_tpl->tpl_vars['group']->value['id'];?>
" class="time" name="time2[<?php echo $_smarty_tpl->tpl_vars['group']->value['id'];?>
]" value="<?php if (isset($_smarty_tpl->tpl_vars['group']->value['time2'])) {?><?php echo sprintf("%02d",$_smarty_tpl->tpl_vars['group']->value['time2']);?>
<?php }?>"/>
</td>
<?php if ($_smarty_tpl->tpl_vars['group']->value['active_val']==$_smarty_tpl->tpl_vars['NG_SUBSCRIBED']->value&&$_smarty_tpl->tpl_vars['isadmin']->value!=0&&$_smarty_tpl->tpl_vars['urdd_online']->value!=0) {?> 
<td class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 right admin">
<div>
<div class="floatright">
<div class="inline iconsizeplus editicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_feeds_edit']->value),$_smarty_tpl);?>
 onclick="javascript:edit_group(<?php echo $_smarty_tpl->tpl_vars['group']->value['id'];?>
);"></div>
<div class="inline iconsizeplus upicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_update']->value),$_smarty_tpl);?>
 onclick="javascript:ng_action('updategroup', <?php echo $_smarty_tpl->tpl_vars['group']->value['id'];?>
);"></div>
<div class="inline iconsizeplus gensetsicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_ng_gensets']->value),$_smarty_tpl);?>
 onclick="javascript:ng_action('gensetsgroup', <?php echo $_smarty_tpl->tpl_vars['group']->value['id'];?>
);"></div>
<div class="inline iconsizeplus killicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_expire']->value),$_smarty_tpl);?>
 onclick="javascript:ng_action('expiregroup', <?php echo $_smarty_tpl->tpl_vars['group']->value['id'];?>
);"></div>
<div class="inline iconsizeplus deleteicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_purge']->value),$_smarty_tpl);?>
 onclick="javascript:ng_action_confirm('purgegroup', <?php echo $_smarty_tpl->tpl_vars['group']->value['id'];?>
, '<?php echo $_smarty_tpl->tpl_vars['LN_purge']->value;?>
 \'@@\'?');"></div>
</div>
</div>
</td>
<?php } else { ?> 
	<?php if ($_smarty_tpl->tpl_vars['group']->value['active_val']!=$_smarty_tpl->tpl_vars['NG_SUBSCRIBED']->value&&$_smarty_tpl->tpl_vars['isadmin']->value==1&&$_smarty_tpl->tpl_vars['urdd_online']->value!=0) {?>
        <td class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 admin">
        <div class="floatright">
        <div class="inline iconsizeplus editicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_feeds_edit']->value),$_smarty_tpl);?>
 onclick="javascript:edit_group(<?php echo $_smarty_tpl->tpl_vars['group']->value['id'];?>
);"></div>
        </div>
        </td>
	<?php }?>
<?php }?>
<?php }?>
</tr>
<?php }
if (!$_smarty_tpl->tpl_vars['group']->_loop) {
?>
<tr><td colspan="11" class="centered highlight textback"><?php echo $_smarty_tpl->tpl_vars['LN_error_nogroupsfound']->value;?>
</td></tr>
<?php } ?>
</table>
</form>
<?php echo $_smarty_tpl->tpl_vars['bottomskipper']->value;?>


<div>
<br/> 
<input type="hidden" id="urddonline" value="<?php echo $_smarty_tpl->tpl_vars['urdd_online']->value;?>
"/>
</div>
<?php }} ?>