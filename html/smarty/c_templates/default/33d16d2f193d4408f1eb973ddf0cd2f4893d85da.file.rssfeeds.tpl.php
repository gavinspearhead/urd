<?php /* Smarty version Smarty-3.1.14, created on 2013-08-10 00:19:36
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/rssfeeds.tpl" */ ?>
<?php /*%%SmartyHeaderCode:134783156652056af8b99cf3-17119829%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '33d16d2f193d4408f1eb973ddf0cd2f4893d85da' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/rssfeeds.tpl',
      1 => 1367103342,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '134783156652056af8b99cf3-17119829',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'LN_apply' => 0,
    'search' => 0,
    'LN_search' => 0,
    'submit' => 0,
    'LN_loading' => 0,
    'LN_pref_saved' => 0,
    'LN_failed' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52056af8ccb9d0_80798360',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52056af8ccb9d0_80798360')) {function content_52056af8ccb9d0_80798360($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ("head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0);?>


<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'submit', null); ob_start(); ?>
<span class="ng_submit"><input type="button" value="<?php echo $_smarty_tpl->tpl_vars['LN_apply']->value;?>
" class="submit" id="rss_apply" onclick="javascript:rss_feeds_update();"/>&nbsp;</span>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<br/>
<div>
<input type="text" name="search" value="<?php if ($_smarty_tpl->tpl_vars['search']->value=='') {?>&lt;<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
&gt;<?php } else { ?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['search']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php }?>" onfocus="if (this.value=='&lt;<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
&gt;') this.value='';" id="newsearch" size="30" onkeypress="javascript:submit_enter(event, load_rss_feeds);"/>
<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['search_all']->value),'name'=>"search_all",'id'=>"search_all",'data'=>((string) $_smarty_tpl->tpl_vars['LN_feeds_hide_empty']->value)),$_smarty_tpl);?>

&nbsp;
<input type="button" value="<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
" class="submitsmall" onclick="javascript:load_rss_feeds();"/>
<div class="floatright submit_button_right"><?php echo $_smarty_tpl->tpl_vars['submit']->value;?>
</div>
</div>

<div class="innerwaitingdiv" id="waitingdiv">
<div class="waitingimg centered"></div>
<p><br/></p>
<h3 class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_loading']->value;?>
</h3>
</div>

<div id="rss_feeds_div"></div>

<p>&nbsp;</p>
<script type="text/javascript">
$(document).ready(function() {
    load_rss_feeds();
});
</script>

<p>&nbsp;</p>
<input type="hidden" name="type" id="type" value="rss"/>
<input type="hidden" id="ln_saved" value="<?php echo $_smarty_tpl->tpl_vars['LN_pref_saved']->value;?>
"/>
<input type="hidden" id="ln_failed" value="<?php echo $_smarty_tpl->tpl_vars['LN_failed']->value;?>
"/>

<?php echo $_smarty_tpl->getSubTemplate ("foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>