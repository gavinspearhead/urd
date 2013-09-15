<?php /* Smarty version Smarty-3.1.14, created on 2013-09-02 00:05:13
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_rss_feeds.tpl" */ ?>
<?php /*%%SmartyHeaderCode:89202194752056af9a813f0-67922357%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3a2b7788c6b6755e7a3c1b4de19fef71eb6874f0' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_rss_feeds.tpl',
      1 => 1378073112,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '89202194752056af9a813f0-67922357',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52056afa57cff2_77208775',
  'variables' => 
  array (
    'page_tab' => 0,
    'isadmin' => 0,
    'LN_global_settings' => 0,
    'LN_user_settings' => 0,
    'pages' => 0,
    'currentpage' => 0,
    'lastpage' => 0,
    'unsubscribed' => 0,
    'LN_ng_subscribed' => 0,
    'LN_feeds_rss' => 0,
    'topskipper' => 0,
    'selector' => 0,
    'sort' => 0,
    'sort_dir' => 0,
    'up' => 0,
    'down' => 0,
    'LN_feeds_tooltip_active' => 0,
    'LN_feeds_tooltip_name' => 0,
    'LN_name' => 0,
    'name_sort' => 0,
    'LN_ng_tooltip_category' => 0,
    'user_hidden' => 0,
    'LN_category' => 0,
    'category_sort' => 0,
    'LN_feeds_tooltip_url' => 0,
    'admin_hidden' => 0,
    'LN_feeds_url' => 0,
    'url_sort' => 0,
    'LN_feeds_tooltip_auth' => 0,
    'LN_feeds_auth' => 0,
    'auth_sort' => 0,
    'LN_feeds_tooltip_posts' => 0,
    'LN_size' => 0,
    'feedcount_sort' => 0,
    'LN_ng_tooltip_adult' => 0,
    'LN_ng_adult' => 0,
    'adult_sort' => 0,
    'LN_feeds_tooltip_lastupdated' => 0,
    'LN_feeds_lastupdated' => 0,
    'last_updated_sort' => 0,
    'LN_feeds_tooltip_expire' => 0,
    'LN_feeds_expire_time' => 0,
    'expire_sort' => 0,
    'LN_feeds_tooltip_visible' => 0,
    'LN_feeds_visible' => 0,
    'visible_sort' => 0,
    'LN_ng_tooltip_minsetsize' => 0,
    'LN_ng_minsetsize' => 0,
    'minsetsize_sort' => 0,
    'urdd_online' => 0,
    'LN_feeds_tooltip_autoupdate' => 0,
    'LN_feeds_autoupdate' => 0,
    'refresh_period_sort' => 0,
    'LN_time' => 0,
    'refresh_time_sort' => 0,
    'LN_feeds_tooltip_uepev' => 0,
    'LN_actions' => 0,
    'allfeeds' => 0,
    'feed' => 0,
    'RSS_SUBSCRIBED' => 0,
    'maxstrlen' => 0,
    'LN_nocategory' => 0,
    'categories' => 0,
    'item' => 0,
    'LN_usenet_needsauthentication' => 0,
    'periods_keys' => 0,
    'periods_texts' => 0,
    'LN_feeds_editfeed' => 0,
    'LN_update' => 0,
    'LN_expire' => 0,
    'LN_purge' => 0,
    'LN_delete' => 0,
    'LN_feeds_edit' => 0,
    'LN_error_nofeedsfound' => 0,
    'bottomskipper' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52056afa57cff2_77208775')) {function content_52056afa57cff2_77208775($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_capitalize')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.capitalize.php';
if (!is_callable('smarty_modifier_truncate')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.truncate.php';
if (!is_callable('smarty_function_html_options')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/function.html_options.php';
?>

<?php if ($_smarty_tpl->tpl_vars['page_tab']->value=="admin"&&$_smarty_tpl->tpl_vars['isadmin']->value) {?>
<?php $_smarty_tpl->tpl_vars['admin_hidden'] = new Smarty_variable('', null, 0);?>
<?php $_smarty_tpl->tpl_vars['user_hidden'] = new Smarty_variable("hidden", null, 0);?>
<?php } else { ?>
<?php $_smarty_tpl->tpl_vars['admin_hidden'] = new Smarty_variable("hidden", null, 0);?>
<?php $_smarty_tpl->tpl_vars['user_hidden'] = new Smarty_variable('', null, 0);?>
<?php }?>

<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'selector', null); ob_start(); ?>
<div class="pref_selector">
<ul class="tabs">
<li onclick="javascript:toggle_table('feedstable', 'user', 'admin')" id="button_global" class="tab<?php if ($_smarty_tpl->tpl_vars['page_tab']->value=='admin') {?> tab_selected<?php }?>"><?php echo $_smarty_tpl->tpl_vars['LN_global_settings']->value;?>
</li>
<li onclick="javascript:toggle_table('feedstable', 'admin', 'user')" id="button_user" class="tab<?php if ($_smarty_tpl->tpl_vars['page_tab']->value!='admin') {?> tab_selected<?php }?>"><?php echo $_smarty_tpl->tpl_vars['LN_user_settings']->value;?>

<input type="hidden" id="page_tab" value="<?php echo $_smarty_tpl->tpl_vars['page_tab']->value;?>
"/>
</li>
</ul>
</div>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>


<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'topskipper', null); ob_start(); ?><div class="ng_selector"><?php if (count($_smarty_tpl->tpl_vars['pages']->value)>1) {?><?php echo smarty_function_urd_skipper(array('current'=>$_smarty_tpl->tpl_vars['currentpage']->value,'last'=>$_smarty_tpl->tpl_vars['lastpage']->value,'pages'=>$_smarty_tpl->tpl_vars['pages']->value,'class'=>'ps','js'=>'rss_feeds_page'),$_smarty_tpl);?>
<?php }?></div>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>


<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'bottomskipper', null); ob_start(); ?><div class="ng_selector"><?php if (count($_smarty_tpl->tpl_vars['pages']->value)>1) {?><?php echo smarty_function_urd_skipper(array('current'=>$_smarty_tpl->tpl_vars['currentpage']->value,'last'=>$_smarty_tpl->tpl_vars['lastpage']->value,'pages'=>$_smarty_tpl->tpl_vars['pages']->value,'class'=>'psb','js'=>'rss_feeds_page'),$_smarty_tpl);?>
<?php }?></div>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<h3 class="title"><?php if ($_smarty_tpl->tpl_vars['unsubscribed']->value==0) {?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['LN_ng_subscribed']->value, ENT_QUOTES, 'UTF-8', true);?>
: <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['LN_feeds_rss']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php } else { ?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['LN_feeds_rss']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php }?></h3>

<div id="bar">
<div id="aform">
<div id="ng_headerbox">
<?php echo $_smarty_tpl->tpl_vars['topskipper']->value;?>

<?php echo $_smarty_tpl->tpl_vars['selector']->value;?>

</div>

<?php $_smarty_tpl->tpl_vars['up'] = new Smarty_variable("<img src='".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/small_up.png' alt=''>", null, 0);?>
<?php $_smarty_tpl->tpl_vars['down'] = new Smarty_variable("<img src='".((string) $_smarty_tpl->tpl_vars['IMGDIR']->value)."/small_down.png' alt=''>", null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="name") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['name_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['name_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['name_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="active") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['active_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['active_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['active_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="category") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['category_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['category_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['category_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="adult") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['adult_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['adult_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['adult_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="last_updated") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['last_updated_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['last_updated_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['last_updated_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="expire") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['expire_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['expire_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['expire_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="visible") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['visible_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['visible_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['visible_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="minsetsize") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['minsetsize_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['minsetsize_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['minsetsize_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="refresh_period") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['refresh_period_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['refresh_period_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['refresh_period_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="refresh_time") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['refresh_time_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['refresh_time_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['refresh_time_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="url") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['url_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['url_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['url_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="auth") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['auth_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['auth_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['auth_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=="feedcount") {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['feedcount_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['feedcount_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['feedcount_sort'] = new Smarty_variable('', null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['sort']->value=='') {?><?php if ($_smarty_tpl->tpl_vars['sort_dir']->value=='desc') {?><?php $_smarty_tpl->tpl_vars['_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['up']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['_sort'] = new Smarty_variable($_smarty_tpl->tpl_vars['down']->value, null, 0);?><?php }?><?php } else { ?><?php $_smarty_tpl->tpl_vars['_sort'] = new Smarty_variable('', null, 0);?><?php }?>


<form method="post" id="rssfeedsform">
<div class="hidden">
<input type="hidden" name="order" id="order" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['sort']->value, ENT_QUOTES, 'UTF-8', true);?>
"/>
<input type="hidden" name="page" id="page1" value="<?php echo $_smarty_tpl->tpl_vars['page_tab']->value;?>
"/>
<input type="hidden" name="order_dir" id="order_dir"  value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['sort_dir']->value, ENT_QUOTES, 'UTF-8', true);?>
"/>
</div>

<div>
<table class="newsgroups" id="feedstable">
<tr>
<th class="general head round_left">&nbsp;</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_feeds_tooltip_active']->value),$_smarty_tpl);?>
 class="general buttonlike head" onclick="javascript:submit_rss_search('subscribed', 'desc');">&nbsp;</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_feeds_tooltip_name']->value),$_smarty_tpl);?>
 class="fixwidth20p center general buttonlike head" onclick="javascript:submit_rss_search('name', 'asc');" ><?php echo $_smarty_tpl->tpl_vars['LN_name']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['name_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_ng_tooltip_category']->value),$_smarty_tpl);?>
 class="<?php echo $_smarty_tpl->tpl_vars['user_hidden']->value;?>
 center user buttonlike head" onclick="javascript:submit_rss_search('category', 'asc');" ><?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['LN_category']->value);?>
 <?php echo $_smarty_tpl->tpl_vars['category_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_feeds_tooltip_url']->value),$_smarty_tpl);?>
 class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 center admin buttonlike head" onclick="javascript:submit_rss_search('url', 'asc');" ><?php echo $_smarty_tpl->tpl_vars['LN_feeds_url']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['url_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_feeds_tooltip_auth']->value),$_smarty_tpl);?>
 class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 center admin buttonlike head" onclick="javascript:submit_rss_search('auth', 'asc');" ><?php echo $_smarty_tpl->tpl_vars['LN_feeds_auth']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['auth_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_feeds_tooltip_posts']->value),$_smarty_tpl);?>
 class="general center buttonlike head" onclick="javascript:submit_rss_search('feedcount', 'desc');"><?php echo $_smarty_tpl->tpl_vars['LN_size']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['feedcount_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_ng_tooltip_adult']->value),$_smarty_tpl);?>
 class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 admin buttonlike center head" onclick="javascript:submit_rss_search('adult', 'desc');"><?php echo $_smarty_tpl->tpl_vars['LN_ng_adult']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['adult_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_feeds_tooltip_lastupdated']->value),$_smarty_tpl);?>
 class="general center buttonlike head" onclick="javascript:submit_rss_search('last_updated', 'desc');"><?php echo $_smarty_tpl->tpl_vars['LN_feeds_lastupdated']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['last_updated_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_feeds_tooltip_expire']->value),$_smarty_tpl);?>
 class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 admin center buttonlike head" onclick="javascript:submit_rss_search('expire', 'desc');"><?php echo $_smarty_tpl->tpl_vars['LN_feeds_expire_time']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['expire_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_feeds_tooltip_visible']->value),$_smarty_tpl);?>
 class="<?php echo $_smarty_tpl->tpl_vars['user_hidden']->value;?>
 user center buttonlike head" onclick="javascript:submit_rss_search('visible', 'desc');"><?php echo $_smarty_tpl->tpl_vars['LN_feeds_visible']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['visible_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_ng_tooltip_minsetsize']->value),$_smarty_tpl);?>
 class="<?php echo $_smarty_tpl->tpl_vars['user_hidden']->value;?>
 user center buttonlike head round_right" onclick="javascript:submit_rss_search('minsetsize', 'desc');"><?php echo $_smarty_tpl->tpl_vars['LN_ng_minsetsize']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['minsetsize_sort']->value;?>
</th>
<?php if ($_smarty_tpl->tpl_vars['isadmin']->value!=0&&$_smarty_tpl->tpl_vars['urdd_online']->value!=0) {?>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_feeds_tooltip_autoupdate']->value),$_smarty_tpl);?>
 class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 admin center buttonlike head" onclick="javascript:submit_rss_search('refresh_period','desc');"><?php echo $_smarty_tpl->tpl_vars['LN_feeds_autoupdate']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['refresh_period_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_time']->value),$_smarty_tpl);?>
 class="fixwidth5c nowrap <?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 center admin buttonlike head" onclick="javascript:submit_rss_search('refresh_time', 'asc');">@ <?php echo $_smarty_tpl->tpl_vars['LN_time']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['refresh_time_sort']->value;?>
</th>
<th <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_feeds_tooltip_uepev']->value),$_smarty_tpl);?>
 class="fixwidth6c <?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 center admin head round_right"><?php echo $_smarty_tpl->tpl_vars['LN_actions']->value;?>
</th>
<?php }?>
</tr>

<?php  $_smarty_tpl->tpl_vars['feed'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['feed']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['allfeeds']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['feed']->key => $_smarty_tpl->tpl_vars['feed']->value) {
$_smarty_tpl->tpl_vars['feed']->_loop = true;
?>
<tr class="even content"
	onmouseover="javascript:ToggleClass(this,'highlight2')" 
	onmouseout="javascript:ToggleClass(this,'highlight2')">
<td class="general"><?php echo $_smarty_tpl->tpl_vars['feed']->value['number'];?>
</td>
<td class="general">
<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['feed']->value['active_val']),'name'=>"rssfeed[".((string) $_smarty_tpl->tpl_vars['feed']->value['id'])."]",'id'=>"rssfeed_".((string) $_smarty_tpl->tpl_vars['feed']->value['id']),'readonly'=>((string) ($_smarty_tpl->tpl_vars['isadmin']->value==0||$_smarty_tpl->tpl_vars['urdd_online']->value==0))),$_smarty_tpl);?>
 
<input type="hidden" id="ng_id_<?php echo $_smarty_tpl->tpl_vars['feed']->value['id'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['feed']->value['name'];?>
"/>
</td>
<td class="general"> 
<span <?php if ($_smarty_tpl->tpl_vars['feed']->value['active_val']==$_smarty_tpl->tpl_vars['RSS_SUBSCRIBED']->value) {?> class="buttonlike" onclick="javascript:jump('rsssets.php?feed_id=feed_<?php echo $_smarty_tpl->tpl_vars['feed']->value['id'];?>
');"<?php }?>>
<?php echo smarty_modifier_truncate(htmlspecialchars($_smarty_tpl->tpl_vars['feed']->value['name'], ENT_QUOTES, 'UTF-8', true),$_smarty_tpl->tpl_vars['maxstrlen']->value);?>
</span>

</td>
<td class="<?php echo $_smarty_tpl->tpl_vars['user_hidden']->value;?>
 user center">
	<select name="category[<?php echo $_smarty_tpl->tpl_vars['feed']->value['id'];?>
]" id="category_<?php echo $_smarty_tpl->tpl_vars['feed']->value['id'];?>
" >
    <option value="0"><?php echo $_smarty_tpl->tpl_vars['LN_nocategory']->value;?>
</option>
    <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['categories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>		
    <option <?php if ($_smarty_tpl->tpl_vars['item']->value['id']==$_smarty_tpl->tpl_vars['feed']->value['category']) {?>selected="selected"<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
"><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</option>
	<?php } ?>
    </select>
    </td>

<td class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 admin"> 
<span class="buttonlike" onclick="javascript:jump('<?php echo $_smarty_tpl->tpl_vars['feed']->value['url'];?>
', 1);"> <?php echo smarty_modifier_truncate(htmlspecialchars($_smarty_tpl->tpl_vars['feed']->value['url'], ENT_QUOTES, 'UTF-8', true),$_smarty_tpl->tpl_vars['maxstrlen']->value);?>
</span>
</td>

<td class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 admin center" <?php if ($_smarty_tpl->tpl_vars['feed']->value['authentication']==1) {?><?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_usenet_needsauthentication']->value),$_smarty_tpl);?>
<?php }?>>
<?php ob_start();?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['feed']->value['id'], ENT_QUOTES, 'UTF-8', true);?>
<?php $_tmp1=ob_get_clean();?><?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['feed']->value['authentication']),'name'=>"feed_auth",'id'=>"auth_".$_tmp1,'readonly'=>1),$_smarty_tpl);?>
 
</td>

<td class="general right"><?php echo $_smarty_tpl->tpl_vars['feed']->value['feedcount'];?>
</td>
<td class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 admin center">

<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['feed']->value['adult']),'name'=>"adult[".((string) $_smarty_tpl->tpl_vars['feed']->value['id'])."]",'id'=>"adult_".((string) $_smarty_tpl->tpl_vars['feed']->value['id']),'readonly'=>((string) ($_smarty_tpl->tpl_vars['isadmin']->value==0)),'post_js'=>"update_adult('rss', '".((string) $_smarty_tpl->tpl_vars['feed']->value['id'])."')"),$_smarty_tpl);?>
 
</td>

<td class="general right"><?php echo $_smarty_tpl->tpl_vars['feed']->value['lastupdated'];?>
</td>
<td class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 admin center">
<input type="text" size="2" value="<?php echo $_smarty_tpl->tpl_vars['feed']->value['expire'];?>
" name="expire[<?php echo $_smarty_tpl->tpl_vars['feed']->value['id'];?>
]" <?php if ($_smarty_tpl->tpl_vars['isadmin']->value!=1||$_smarty_tpl->tpl_vars['urdd_online']->value!=1) {?> readonly="readonly"<?php }?>/>
</td>
<td class="<?php echo $_smarty_tpl->tpl_vars['user_hidden']->value;?>
 user center">
<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['feed']->value['visible']),'name'=>"visible[".((string) $_smarty_tpl->tpl_vars['feed']->value['id'])."]",'id'=>"visible_".((string) $_smarty_tpl->tpl_vars['feed']->value['id'])),$_smarty_tpl);?>
 
<td class="<?php echo $_smarty_tpl->tpl_vars['user_hidden']->value;?>
 user center">
<input type="text" size="3" value="<?php echo $_smarty_tpl->tpl_vars['feed']->value['minsetsize'];?>
" name="minsetsize[<?php echo $_smarty_tpl->tpl_vars['feed']->value['id'];?>
]"/>
<input type="text" size="3" value="<?php echo $_smarty_tpl->tpl_vars['feed']->value['maxsetsize'];?>
" name="maxsetsize[<?php echo $_smarty_tpl->tpl_vars['feed']->value['id'];?>
]"/>
</td>

<?php if ($_smarty_tpl->tpl_vars['isadmin']->value!=0&&$_smarty_tpl->tpl_vars['urdd_online']->value!=0) {?>
<td class="<?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 admin center"> 
<select name="period[<?php echo $_smarty_tpl->tpl_vars['feed']->value['id'];?>
]" size="1" class="update">
<?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['feed']->value['select'];?>
<?php $_tmp2=ob_get_clean();?><?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['periods_keys']->value,'output'=>$_smarty_tpl->tpl_vars['periods_texts']->value,'selected'=>$_tmp2),$_smarty_tpl);?>

</select>
</td>
<td class="nowrap <?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 admin center">@ <input type="text" id="time1_<?php echo $_smarty_tpl->tpl_vars['feed']->value['id'];?>
" name="time1[<?php echo $_smarty_tpl->tpl_vars['feed']->value['id'];?>
]" value="<?php echo $_smarty_tpl->tpl_vars['feed']->value['time1'];?>
" class="time"/>:<input type="text" id="time2_<?php echo $_smarty_tpl->tpl_vars['feed']->value['id'];?>
" class="time" name="time2[<?php echo $_smarty_tpl->tpl_vars['feed']->value['id'];?>
]" value="<?php if (isset($_smarty_tpl->tpl_vars['feed']->value['time2'])) {?><?php echo sprintf("%02d",$_smarty_tpl->tpl_vars['feed']->value['time2']);?>
<?php }?>"/>
</td>

<td class="nowrap <?php echo $_smarty_tpl->tpl_vars['admin_hidden']->value;?>
 admin right">
<div class="floatright">
<?php if ($_smarty_tpl->tpl_vars['feed']->value['active_val']==$_smarty_tpl->tpl_vars['RSS_SUBSCRIBED']->value&&$_smarty_tpl->tpl_vars['isadmin']->value!=0&&$_smarty_tpl->tpl_vars['urdd_online']->value!=0) {?> 
<div class="inline iconsizeplus editicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_feeds_editfeed']->value),$_smarty_tpl);?>
 onclick="javascript:edit_rss(<?php echo $_smarty_tpl->tpl_vars['feed']->value['id'];?>
);"></div>
<div class="inline iconsizeplus upicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_update']->value),$_smarty_tpl);?>
 onclick="javascript:ng_action('updaterss', <?php echo $_smarty_tpl->tpl_vars['feed']->value['id'];?>
);"></div>
<div class="inline iconsizeplus killicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_expire']->value),$_smarty_tpl);?>
 onclick="javascript:ng_action('expirerss', <?php echo $_smarty_tpl->tpl_vars['feed']->value['id'];?>
);"></div>
<div class="inline iconsizeplus purgeicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_purge']->value),$_smarty_tpl);?>
 onclick="javascript:ng_action_confirm('purgerss', <?php echo $_smarty_tpl->tpl_vars['feed']->value['id'];?>
, '<?php echo $_smarty_tpl->tpl_vars['LN_purge']->value;?>
 \'@@\'');"></div>
<div class="inline iconsizeplus deleteicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_delete']->value),$_smarty_tpl);?>
 onclick="javascript:remove_rss(<?php echo $_smarty_tpl->tpl_vars['feed']->value['id'];?>
, '<?php echo $_smarty_tpl->tpl_vars['LN_delete']->value;?>
 \'<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['feed']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
\'?');"></div>
<?php } elseif ($_smarty_tpl->tpl_vars['isadmin']->value!=0&&$_smarty_tpl->tpl_vars['urdd_online']->value!=0) {?>
<div class="inline iconsizeplus editicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_feeds_edit']->value),$_smarty_tpl);?>
 onclick="javascript:edit_rss(<?php echo $_smarty_tpl->tpl_vars['feed']->value['id'];?>
);"></div>
<div class="inline iconsizeplus deleteicon buttonlike" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_delete']->value),$_smarty_tpl);?>
 onclick="javascript:remove_rss(<?php echo $_smarty_tpl->tpl_vars['feed']->value['id'];?>
, '<?php echo $_smarty_tpl->tpl_vars['LN_delete']->value;?>
 \'<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['feed']->value['name'], ENT_QUOTES, 'UTF-8', true);?>
\'?');"></div>
<?php }?>

</div>
</td>
<?php }?>
</tr>
<?php }
if (!$_smarty_tpl->tpl_vars['feed']->_loop) {
?>
<tr><td colspan="12" class="centered highlight textback"><?php echo $_smarty_tpl->tpl_vars['LN_error_nofeedsfound']->value;?>
</td></tr>
<?php } ?>
</table>
</form>
</div>
<?php echo $_smarty_tpl->tpl_vars['bottomskipper']->value;?>

<div>
<br/>
<input type="hidden" id="urddonline" value="<?php echo $_smarty_tpl->tpl_vars['urdd_online']->value;?>
"/>
</div>


<?php }} ?>