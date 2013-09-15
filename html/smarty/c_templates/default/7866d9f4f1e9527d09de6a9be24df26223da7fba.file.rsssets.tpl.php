<?php /* Smarty version Smarty-3.1.14, created on 2013-09-10 22:26:33
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/rsssets.tpl" */ ?>
<?php /*%%SmartyHeaderCode:67104445205616ee74662-52705270%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7866d9f4f1e9527d09de6a9be24df26223da7fba' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/rsssets.tpl',
      1 => 1378844792,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '67104445205616ee74662-52705270',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5205616f2a7de9_79513436',
  'variables' => 
  array (
    'title' => 0,
    'rssurl' => 0,
    'subscribedfeeds' => 0,
    'item' => 0,
    'LN_advanced_search' => 0,
    'order' => 0,
    'LN_previous' => 0,
    'LN_feeds_allgroups' => 0,
    'total_articles' => 0,
    'current' => 0,
    'feed_id' => 0,
    'LN_category' => 0,
    'LN_next' => 0,
    'search' => 0,
    'LN_search' => 0,
    'saved_searches' => 0,
    'saved_search' => 0,
    '_saved_search' => 0,
    'LN_setsize' => 0,
    'minsetsize' => 0,
    'maxsetsize' => 0,
    'LN_age' => 0,
    'minage' => 0,
    'maxage' => 0,
    'LN_rating' => 0,
    'minrating' => 0,
    'maxrating' => 0,
    'flag' => 0,
    'LN_browse_allsets' => 0,
    'LN_browse_interesting' => 0,
    'LN_browse_downloaded' => 0,
    'show_makenzb' => 0,
    'LN_browse_nzb' => 0,
    'LN_browse_killed' => 0,
    'LN_reset' => 0,
    'searchform' => 0,
    'rss_link' => 0,
    'USERSETTYPE' => 0,
    'offset' => 0,
    'setid' => 0,
    'LN_loading' => 0,
    'minsetsizelimit' => 0,
    'maxsetsizelimit' => 0,
    'minagelimit' => 0,
    'maxagelimit' => 0,
    'minratinglimit' => 0,
    'maxratinglimit' => 0,
    'LN_delete_search' => 0,
    'perpage' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5205616f2a7de9_79513436')) {function content_5205616f2a7de9_79513436($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ("head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value,'rssurl'=>$_smarty_tpl->tpl_vars['rssurl']->value), 0);?>


<?php $_smarty_tpl->_capture_stack[0][] = array('default', "searchform", null); ob_start(); ?>
<form id="searchform" method="get">
<div>

<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['subscribedfeeds']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
<input type="hidden" id="ng_id_<?php echo $_smarty_tpl->tpl_vars['item']->value['type'];?>
_<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
" value="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
"/>
<?php } ?>

<div id="advanced_search_button" class="floatleft iconsize dynimgplus noborder buttonlike" onclick="javascript:fold_adv_search('advanced_search_button', 'advanced_search');" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_advanced_search']->value),$_smarty_tpl);?>
>
</div>&nbsp;
    <input type="hidden" name="order" value="<?php echo $_smarty_tpl->tpl_vars['order']->value;?>
" id="searchorder"/>
	<input type="hidden" name="save_category" value="" id="save_category"/>
    <input type="button" value="&lt;" class="submitsmall" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_previous']->value),$_smarty_tpl);?>
 onclick='javascript:select_next("select_feedid",-1);'/>&thinsp;
    <select name="feed_id" class="search" id="select_feedid">
    <option value=""><?php echo $_smarty_tpl->tpl_vars['LN_feeds_allgroups']->value;?>
 (<?php echo $_smarty_tpl->tpl_vars['total_articles']->value;?>
)</option>
    <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['subscribedfeeds']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
        <?php $_smarty_tpl->_capture_stack[0][] = array('current', 'current', null); ob_start(); ?><?php echo $_smarty_tpl->tpl_vars['item']->value['type'];?>
_<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>
		<option <?php if ($_smarty_tpl->tpl_vars['current']->value==$_smarty_tpl->tpl_vars['feed_id']->value&&$_smarty_tpl->tpl_vars['feed_id']->value!='') {?>selected="selected"<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['item']->value['type'];?>
_<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
"> 
            <?php if ($_smarty_tpl->tpl_vars['item']->value['type']=='category') {?><?php echo $_smarty_tpl->tpl_vars['LN_category']->value;?>
: <?php }?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
 (<?php echo $_smarty_tpl->tpl_vars['item']->value['article_count'];?>
)
        </option>
    <?php } ?>
	</select>&thinsp;
    <input type="button" value="&gt;" class="submitsmall" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_next']->value),$_smarty_tpl);?>
 onclick='javascript:select_next("select_feedid",1);' />&nbsp;
   	<input type="text" name="search" id="search" size="30" class="search" value="<?php if ($_smarty_tpl->tpl_vars['search']->value=='') {?>&lt;<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
&gt;<?php } else { ?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['search']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php }?>" onfocus="if (this.value=='&lt;<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
&gt;') this.value='';" onkeypress="javascript:submit_enter(event, load_sets, { 'offset':'0', 'setid':'', 'category':'' } );"/> &nbsp;
	<input type="hidden" value="" name="maxage"/>
    <input type="button" value="<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
" class="submitsmall" onclick="javascript:load_sets( { 'offset':'0', 'setid':'', 'category':'' } );"/>
    &nbsp; 
    
    &nbsp;

<span id="save_search_outer" class="<?php if (count($_smarty_tpl->tpl_vars['saved_searches']->value)==0) {?>hidden<?php }?>">
<input type="button" class="submitsmall" value="&lt;" <?php echo smarty_function_urd_popup(array('text'=>$_smarty_tpl->tpl_vars['LN_next']->value,'type'=>"small"),$_smarty_tpl);?>
 onclick="javascript:select_next_search('saved_search',-1);"/>
<span id="save_search_span">
<select id="saved_search" onchange="javascript:update_browse_searches();">
<option value=""></option>
<?php  $_smarty_tpl->tpl_vars['saved_search'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['saved_search']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['saved_searches']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['saved_search']->key => $_smarty_tpl->tpl_vars['saved_search']->value) {
$_smarty_tpl->tpl_vars['saved_search']->_loop = true;
?>
<option value="<?php echo $_smarty_tpl->tpl_vars['saved_search']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['saved_search']->value==$_smarty_tpl->tpl_vars['_saved_search']->value) {?>selected="selected"<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['saved_search']->value, ENT_QUOTES, 'UTF-8', true);?>
&nbsp;</option>
<?php } ?>
</select>
</span>
<input type="button" class="submitsmall" value="&gt;" <?php echo smarty_function_urd_popup(array('text'=>$_smarty_tpl->tpl_vars['LN_next']->value,'type'=>"small"),$_smarty_tpl);?>
 onclick="javascript:select_next_search('saved_search',1);"/>
</span>
&nbsp;
<div id="minibasketdiv" class="hidden"></div>

<div class="advanced_search hidden" id="advanced_search">
<table>

<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_setsize']->value;?>
:</td>
<td><input type="text" id="minsetsize" size="6" value="<?php echo $_smarty_tpl->tpl_vars['minsetsize']->value;?>
"/></td> 
<td><div id="setsize" style="width:100px;"></div></td>
<td><input type="text" id="maxsetsize" size="6" value="<?php echo $_smarty_tpl->tpl_vars['maxsetsize']->value;?>
"/></td>
<td><?php echo $_smarty_tpl->tpl_vars['LN_age']->value;?>
:</td>
<td><input type="text" id="minage" name="minage" size="6" value="<?php echo $_smarty_tpl->tpl_vars['minage']->value;?>
"/></td> 
<td><div id="setage" style="width:100px;"></div></td>
<td><input type="text" id="maxage" name="maxage" size="6" value="<?php echo $_smarty_tpl->tpl_vars['maxage']->value;?>
"/></td>
</tr>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_rating']->value;?>
:</td>
<td><input type="text" id="minrating" name="minrating" size="6" value="<?php echo $_smarty_tpl->tpl_vars['minrating']->value;?>
"/></td> 
<td><div id="setrating" style="width:100px;"></div></td>
<td><input type="text" id="maxrating" name="maxrating" size="6" value="<?php echo $_smarty_tpl->tpl_vars['maxrating']->value;?>
"/></td>
<td>
 <select name="flag" class="search" id="flag">
		<option <?php if ($_smarty_tpl->tpl_vars['flag']->value=='') {?>selected="selected"<?php }?> value=""><?php echo $_smarty_tpl->tpl_vars['LN_browse_allsets']->value;?>
</option>
		<option <?php if ($_smarty_tpl->tpl_vars['flag']->value=='interesting') {?>selected="selected"<?php }?> value="interesting"><?php echo $_smarty_tpl->tpl_vars['LN_browse_interesting']->value;?>
</option>
		<option <?php if ($_smarty_tpl->tpl_vars['flag']->value=='read') {?>selected="selected"<?php }?> value="read"><?php echo $_smarty_tpl->tpl_vars['LN_browse_downloaded']->value;?>
</option>
<?php if ($_smarty_tpl->tpl_vars['show_makenzb']->value!=0) {?>
		<option <?php if ($_smarty_tpl->tpl_vars['flag']->value=='nzb') {?>selected="selected"<?php }?> value="nzb"><?php echo $_smarty_tpl->tpl_vars['LN_browse_nzb']->value;?>
</option>
<?php }?>
		<option <?php if ($_smarty_tpl->tpl_vars['flag']->value=='kill') {?>selected="selected"<?php }?> value="kill"><?php echo $_smarty_tpl->tpl_vars['LN_browse_killed']->value;?>
</option>
	</select>
</td>
<td></td>
<td>
	<input type="button" value="<?php echo $_smarty_tpl->tpl_vars['LN_reset']->value;?>
" class="submitsmall" onclick='javascript:clear_form("searchform");'/>
</td>
</tr>
</table>
</div>

</div>
</form>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php $_smarty_tpl->_capture_stack[0][] = array('default', "rss_link", null); ob_start(); ?>
<div id="rss">
	<table class="rss"><tr><td class="rssleft"><a href="" id="rss_id" class="rss">RSS</a></td><td class="rssright">2.0</td></tr></table>
</div>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php echo $_smarty_tpl->tpl_vars['searchform']->value;?>

<?php echo $_smarty_tpl->tpl_vars['rss_link']->value;?>



<div id="basketdiv" class="down3"></div>


<div>
<input type="hidden" name="usersettype" id="usersettype" value="<?php echo $_smarty_tpl->tpl_vars['USERSETTYPE']->value;?>
"/>
<input type="hidden" name="offset" id="offset" value="<?php echo $_smarty_tpl->tpl_vars['offset']->value;?>
"/>
<input type="hidden" name="feed_id" id="feed_id" value="<?php echo $_smarty_tpl->tpl_vars['feed_id']->value;?>
"/>
<input type="hidden" name="setid" id="setid" value="<?php echo $_smarty_tpl->tpl_vars['setid']->value;?>
"/>
<input type="hidden" name="dlname" value=""/>
<input type="hidden" name="whichbutton" value="" id="whichbutton"/>
<input type="hidden" name="previewBinID" value="" id="previewBinID"/>
<input type="hidden" name="previewGroupID" value="" id="previewGroupID"/>
<input type="hidden" name="lastdivid" id="lastdivid" value=""/>
<input type="hidden" name="curScrollVal" id="curScrollVal" value=""/>
<input type="hidden" name="type" id="type" value="rss"/>
</div>

<div class="innerwaitingdiv" id="waitingdiv">
<div class="waitingimg centered"></div>
<p><br/></p>
<h3 class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_loading']->value;?>
</h3>
</div>

<div id="setsdiv" class="hidden">
</div>


<script type="text/javascript">
$(document).ready(function() {
    init_slider(<?php echo $_smarty_tpl->tpl_vars['minsetsizelimit']->value;?>
, <?php echo $_smarty_tpl->tpl_vars['maxsetsizelimit']->value;?>
, "#setsize", "#minsetsize", "#maxsetsize");
    init_slider(<?php echo $_smarty_tpl->tpl_vars['minagelimit']->value;?>
, <?php echo $_smarty_tpl->tpl_vars['maxagelimit']->value;?>
, "#setage", "#minage", "#maxage");
    init_slider(<?php echo $_smarty_tpl->tpl_vars['minratinglimit']->value;?>
, <?php echo $_smarty_tpl->tpl_vars['maxratinglimit']->value;?>
, "#setrating", "#minrating", "#maxrating");

    set_scroll_handler('#contentout', load_sets);

    update_basket_display();
    <?php if ($_smarty_tpl->tpl_vars['feed_id']->value!='') {?>
        load_sets( { 'next':'<?php echo $_smarty_tpl->tpl_vars['feed_id']->value;?>
' } );
    <?php } else { ?>
        load_sets();
    <?php }?>
});
</script>

<input type="hidden" id="ln_delete_search" value="<?php echo $_smarty_tpl->tpl_vars['LN_delete_search']->value;?>
"/>
<input type="hidden" id="perpage" value="<?php echo $_smarty_tpl->tpl_vars['perpage']->value;?>
"/>
<div>
&nbsp;<br/>
&nbsp;<br/>
&nbsp;<br/>
</div>

<?php echo $_smarty_tpl->getSubTemplate ("foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>