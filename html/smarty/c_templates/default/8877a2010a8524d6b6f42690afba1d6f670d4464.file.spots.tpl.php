<?php /* Smarty version Smarty-3.1.14, created on 2013-09-10 23:06:40
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/spots.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4106259305200219264aec6-64004227%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8877a2010a8524d6b6f42690afba1d6f670d4464' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/spots.tpl',
      1 => 1378845214,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4106259305200219264aec6-64004227',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52002192864d36_05461334',
  'variables' => 
  array (
    'title' => 0,
    'rssurl' => 0,
    'stylesheet' => 0,
    'subcats' => 0,
    'k1' => 0,
    'LN_spots_subcategories' => 0,
    'item' => 0,
    'LN_reset' => 0,
    'k2' => 0,
    'si' => 0,
    'item2' => 0,
    'k3' => 0,
    'cnt' => 0,
    'LN_advanced_search' => 0,
    'order' => 0,
    'LN_previous' => 0,
    'LN_spots_allcategories' => 0,
    'total_articles' => 0,
    'categories' => 0,
    'categoryID' => 0,
    'LN_next' => 0,
    'subcatdivs' => 0,
    'catid' => 0,
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
    'LN_poster_name' => 0,
    'poster' => 0,
    'flag' => 0,
    'LN_browse_allsets' => 0,
    'LN_browse_interesting' => 0,
    'LN_browse_downloaded' => 0,
    'show_makenzb' => 0,
    'LN_browse_nzb' => 0,
    'LN_browse_killed' => 0,
    'LN_rating' => 0,
    'minrating' => 0,
    'maxrating' => 0,
    'rss_link' => 0,
    'searchform' => 0,
    'USERSETTYPE' => 0,
    'offset' => 0,
    'spotid' => 0,
    'LN_loading' => 0,
    'minsetsizelimit' => 0,
    'maxsetsizelimit' => 0,
    'minratinglimit' => 0,
    'maxratinglimit' => 0,
    'minagelimit' => 0,
    'maxagelimit' => 0,
    'LN_delete_search' => 0,
    'perpage' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52002192864d36_05461334')) {function content_52002192864d36_05461334($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ("head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value,'rssurl'=>$_smarty_tpl->tpl_vars['rssurl']->value,'stylesheet'=>$_smarty_tpl->tpl_vars['stylesheet']->value), 0);?>

<?php $_smarty_tpl->_capture_stack[0][] = array('default', "subcatdivs", null); ob_start(); ?>
<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['k1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['subcats']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['k1']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
<div id="subcat_selector_<?php echo $_smarty_tpl->tpl_vars['k1']->value;?>
" class="subcat_selector hidden">
<div class="closebutton buttonlike noborder fixedright down5" onclick="javascript:close_subcat_selector();"></div>
<div class="set_title centered"><?php echo $_smarty_tpl->tpl_vars['LN_spots_subcategories']->value;?>
 - <?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</div>
<div class="reset_button buttonlike on_top"><input type="button" value="<?php echo $_smarty_tpl->tpl_vars['LN_reset']->value;?>
" onclick="javascript:clear_all_checkboxes(<?php echo $_smarty_tpl->tpl_vars['k1']->value;?>
);" class="submitsmall"/></div>
<div class="internal_subcat_selector">
<?php  $_smarty_tpl->tpl_vars['si'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['si']->_loop = false;
 $_smarty_tpl->tpl_vars['k2'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['item']->value['subcats']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['si']->key => $_smarty_tpl->tpl_vars['si']->value) {
$_smarty_tpl->tpl_vars['si']->_loop = true;
 $_smarty_tpl->tpl_vars['k2']->value = $_smarty_tpl->tpl_vars['si']->key;
?>
<table class="subcat">
<tr>
<td onclick="javascript:fold_adv_search('subcat_button_<?php echo $_smarty_tpl->tpl_vars['k1']->value;?>
<?php echo $_smarty_tpl->tpl_vars['k2']->value;?>
', 'subcat_items_<?php echo $_smarty_tpl->tpl_vars['k1']->value;?>
<?php echo $_smarty_tpl->tpl_vars['k2']->value;?>
');" class="subcat_head">
<div id="subcat_button_<?php echo $_smarty_tpl->tpl_vars['k1']->value;?>
<?php echo $_smarty_tpl->tpl_vars['k2']->value;?>
" class="inline iconsize dynimgplus buttonlike"></div>&nbsp;<?php echo $_smarty_tpl->tpl_vars['si']->value['name'];?>

</td>
</tr>
</table>
<table id="subcat_items_<?php echo $_smarty_tpl->tpl_vars['k1']->value;?>
<?php echo $_smarty_tpl->tpl_vars['k2']->value;?>
" class="hidden subcat">
<tr>
<?php $_smarty_tpl->tpl_vars['cnt'] = new Smarty_variable("0", null, 0);?>
<?php  $_smarty_tpl->tpl_vars['item2'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item2']->_loop = false;
 $_smarty_tpl->tpl_vars['k3'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['si']->value['subcats']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item2']->key => $_smarty_tpl->tpl_vars['item2']->value) {
$_smarty_tpl->tpl_vars['item2']->_loop = true;
 $_smarty_tpl->tpl_vars['k3']->value = $_smarty_tpl->tpl_vars['item2']->key;
?>
<?php if ($_smarty_tpl->tpl_vars['item2']->value!='??') {?>
<?php $_smarty_tpl->_capture_stack[0][] = array('current', 'current', null); ob_start(); ?><?php echo (($tmp = @$_smarty_tpl->tpl_vars['subcat_'.($_smarty_tpl->tpl_vars['k1']->value).'_'.($_smarty_tpl->tpl_vars['k2']->value).'_'.($_smarty_tpl->tpl_vars['k3']->value)]->value)===null||$tmp==='' ? '' : $tmp);?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php if ($_smarty_tpl->tpl_vars['cnt']->value==3) {?></tr><tr><?php $_smarty_tpl->tpl_vars['cnt'] = new Smarty_variable("0", null, 0);?><?php }?>
<td class="subcat">
<?php echo smarty_function_urd_checkbox(array('value'=>((string) $_smarty_tpl->tpl_vars['current']->value),'name'=>"subcat_".((string) $_smarty_tpl->tpl_vars['k1']->value)."_".((string) $_smarty_tpl->tpl_vars['k2']->value)."_".((string) $_smarty_tpl->tpl_vars['k3']->value),'id'=>"subcat_".((string) $_smarty_tpl->tpl_vars['k1']->value)."_".((string) $_smarty_tpl->tpl_vars['k2']->value)."_".((string) $_smarty_tpl->tpl_vars['k3']->value),'data'=>((string) $_smarty_tpl->tpl_vars['item2']->value),'tristate'=>"1"),$_smarty_tpl);?>
 
</td>
<?php $_smarty_tpl->tpl_vars['cnt'] = new Smarty_variable(((string) $_smarty_tpl->tpl_vars['cnt']->value+1), null, 0);?>
<?php }?>
<?php } ?>
</tr>            
</table>
<?php } ?>
<br/>
</div>
</div>
<?php } ?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>


<?php $_smarty_tpl->_capture_stack[0][] = array('default', "searchform", null); ob_start(); ?>
<form id="searchform" method="get">
<div id="advanced_search_button" class="floatleft iconsize dynimgplus buttonlike" onclick="javascript:fold_adv_search('advanced_search_button', 'advanced_search');" <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>$_smarty_tpl->tpl_vars['LN_advanced_search']->value),$_smarty_tpl);?>
>
</div>&nbsp;
    <input type="hidden" name="order" value="<?php echo $_smarty_tpl->tpl_vars['order']->value;?>
" id="searchorder"/>
	<input type="hidden" name="save_category" value="" id="save_category"/>
    <input type="button" class="submitsmall" value="&lt;" <?php echo smarty_function_urd_popup(array('text'=>$_smarty_tpl->tpl_vars['LN_previous']->value,'type'=>"small"),$_smarty_tpl);?>
 onclick='javascript:select_next("select_catid",-1);'/>&nbsp;
	<select name="catID" class="search" id="select_catid" onchange='javascript:do_select_subcat();'>
    <option value=""><?php echo $_smarty_tpl->tpl_vars['LN_spots_allcategories']->value;?>
 (<?php echo $_smarty_tpl->tpl_vars['total_articles']->value;?>
)</option>
    <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['categories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
		<option <?php if ($_smarty_tpl->tpl_vars['item']->value['id']==$_smarty_tpl->tpl_vars['categoryID']->value&&$_smarty_tpl->tpl_vars['categoryID']->value!=-1) {?>selected="selected"<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
">
            <?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
 (<?php echo $_smarty_tpl->tpl_vars['item']->value['article_count'];?>
)
        </option>
	<?php } ?>
	</select>&nbsp;
    <input type="button" class="submitsmall" value="&gt;" <?php echo smarty_function_urd_popup(array('text'=>$_smarty_tpl->tpl_vars['LN_next']->value,'type'=>"small"),$_smarty_tpl);?>
 onclick='javascript:select_next("select_catid",1);'/>&nbsp;
    <?php echo $_smarty_tpl->tpl_vars['subcatdivs']->value;?>

    <input type="button" id="subcatbutton" class="submitsmall <?php if ($_smarty_tpl->tpl_vars['catid']->value=='') {?>invisible<?php }?>" value="<?php echo $_smarty_tpl->tpl_vars['LN_spots_subcategories']->value;?>
" onclick="javascript:show_subcat_selector();" />&nbsp;
<input type="text" id="search" name="search" size="30" class="search" value="<?php if ($_smarty_tpl->tpl_vars['search']->value=='') {?>&lt;<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
&gt;<?php } else { ?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['search']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
<?php }?>" 
onfocus="if (this.value=='&lt;<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
&gt;') this.value='';" onkeypress="javascript:submit_enter(event, load_sets, { 'offset':'0', 'setid':'', 'category':'' } );"/>&nbsp;
<input type="button" value="<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
" class="submitsmall" onclick="javascript:load_sets( { 'offset':'0', 'setid':'', 'category':'' } );" />
&nbsp;

<span id="save_search_outer" class="<?php if (count($_smarty_tpl->tpl_vars['saved_searches']->value)==0) {?>hidden<?php }?>">
<input type="button" class="submitsmall" value="&lt;" <?php echo smarty_function_urd_popup(array('text'=>$_smarty_tpl->tpl_vars['LN_previous']->value,'type'=>"small"),$_smarty_tpl);?>
 onclick="javascript:select_next_search('saved_search',-1);"/>
<span id="save_search_span">
<select id="saved_search" onchange="javascript:update_spot_searches(null);" >
<option value=""></option>
<?php  $_smarty_tpl->tpl_vars['saved_search'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['saved_search']->_loop = false;
 $_smarty_tpl->tpl_vars['k1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['saved_searches']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['saved_search']->key => $_smarty_tpl->tpl_vars['saved_search']->value) {
$_smarty_tpl->tpl_vars['saved_search']->_loop = true;
 $_smarty_tpl->tpl_vars['k1']->value = $_smarty_tpl->tpl_vars['saved_search']->key;
?>
<option value="<?php echo $_smarty_tpl->tpl_vars['saved_search']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['saved_search']->value==$_smarty_tpl->tpl_vars['_saved_search']->value) {?>selected="selected"<?php }?>><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['saved_search']->value, ENT_QUOTES, 'UTF-8', true);?>
&nbsp;</option>
<?php } ?>
</select>
</span>
<input type="button" class="submitsmall" value="&gt;" <?php echo smarty_function_urd_popup(array('text'=>$_smarty_tpl->tpl_vars['LN_next']->value,'type'=>"small"),$_smarty_tpl);?>
 onclick="javascript:select_next_search('saved_search',1); "/>
</span>

<div id="minibasketdiv" class="hidden"></div>

<div class="advanced_search hidden" id="advanced_search">
<table>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_setsize']->value;?>
:</td>
<td><input type="text" id="minsetsize"  size="6" value="<?php echo $_smarty_tpl->tpl_vars['minsetsize']->value;?>
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
<td><?php echo $_smarty_tpl->tpl_vars['LN_poster_name']->value;?>
:</td>
<td><input type="text" id="poster" name="poster" size="10" value="<?php echo $_smarty_tpl->tpl_vars['poster']->value;?>
"/></td>
<td></td>
<td>
<select name="flag" class="search" id="flag">
    <option <?php if ($_smarty_tpl->tpl_vars['flag']->value=='') {?> selected="selected" <?php }?> value=""><?php echo $_smarty_tpl->tpl_vars['LN_browse_allsets']->value;?>
</option>
    <option <?php if ($_smarty_tpl->tpl_vars['flag']->value=='interesting') {?> selected="selected" <?php }?> value="interesting"><?php echo $_smarty_tpl->tpl_vars['LN_browse_interesting']->value;?>
</option>
    <option <?php if ($_smarty_tpl->tpl_vars['flag']->value=='read') {?> selected="selected" <?php }?> value="read"><?php echo $_smarty_tpl->tpl_vars['LN_browse_downloaded']->value;?>
</option>
<?php if ($_smarty_tpl->tpl_vars['show_makenzb']->value!=0) {?>
<option <?php if ($_smarty_tpl->tpl_vars['flag']->value=='nzb') {?> selected="selected" <?php }?> value="nzb"><?php echo $_smarty_tpl->tpl_vars['LN_browse_nzb']->value;?>
</option>
<?php }?>
<option <?php if ($_smarty_tpl->tpl_vars['flag']->value=='kill') {?> selected="selected" <?php }?> value="kill"><?php echo $_smarty_tpl->tpl_vars['LN_browse_killed']->value;?>
</option>
</select>&nbsp;
</td>
<td><?php echo $_smarty_tpl->tpl_vars['LN_rating']->value;?>
:</td>
<td><input type="text" id="minrating" name="minrating" size="6" value="<?php echo $_smarty_tpl->tpl_vars['minrating']->value;?>
"/></td> 
<td><div id="setrating" style="width:100px;"></div></td>
<td><input type="text" id="maxrating" name="maxrating" size="6" value="<?php echo $_smarty_tpl->tpl_vars['maxrating']->value;?>
"/></td>
<td colspan="3"></td>
<td><input type="button" value="<?php echo $_smarty_tpl->tpl_vars['LN_reset']->value;?>
" class="submitsmall" onclick='clear_form("searchform");do_select_subcat();'/></td>
</tr>
</table>
</div>
</form>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php $_smarty_tpl->_capture_stack[0][] = array('default', "rss_link", null); ob_start(); ?>
<div id="rss"><table class="rss"><tr><td class="rssleft"><a href="rss.php" id="rss_id" class="rss">RSS</a></td><td class="rssright">2.0</td></tr></table> </div>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php echo $_smarty_tpl->tpl_vars['rss_link']->value;?>

<?php echo $_smarty_tpl->tpl_vars['searchform']->value;?>


<form method="post" id="setform">
<div id="basketdiv" class="down3"></div>


<div>
<input type="hidden" name="usersettype" id="usersettype" value="<?php echo $_smarty_tpl->tpl_vars['USERSETTYPE']->value;?>
"/>
<input type="hidden" name="offset" id="offset" value="<?php echo $_smarty_tpl->tpl_vars['offset']->value;?>
"/>
<input type="hidden" name="spotid" id="spotid" value="<?php echo $_smarty_tpl->tpl_vars['spotid']->value;?>
"/>
<input type="hidden" name="cat_id" id="cat_id" value="<?php echo $_smarty_tpl->tpl_vars['catid']->value;?>
"/>
<input type="hidden" name="dlname" id="dlname" value=""/>
<input type="hidden" name="whichbutton" value="" id="whichbutton"/>
<input type="hidden" name="previewBinID" value="" id="previewBinID"/>
<input type="hidden" name="previewGroupID" value="" id="previewGroupID"/>
<input type="hidden" name="lastdivid" id="lastdivid" value=""/>
<input type="hidden" name="curScrollVal" id="curScrollVal" value=""/>
<input type="hidden" name="type" id="type" value="spots"/>
</div>
</form>

<div class="innerwaitingdiv" id="waitingdiv">
<div class="waitingimg centered"></div>
<p><br/></p>
<h3 class="centered"><?php echo $_smarty_tpl->tpl_vars['LN_loading']->value;?>
</h3>
</div>

<div id="setsdiv" class="hidden">
</div>
<script type="text/javascript">$(document).ready(function() {init_slider(<?php echo $_smarty_tpl->tpl_vars['minsetsizelimit']->value;?>
, <?php echo $_smarty_tpl->tpl_vars['maxsetsizelimit']->value;?>
, "#setsize", "#minsetsize", "#maxsetsize");init_slider(<?php echo $_smarty_tpl->tpl_vars['minratinglimit']->value;?>
, <?php echo $_smarty_tpl->tpl_vars['maxratinglimit']->value;?>
, "#setrating", "#minrating", "#maxrating");init_slider(<?php echo $_smarty_tpl->tpl_vars['minagelimit']->value;?>
, <?php echo $_smarty_tpl->tpl_vars['maxagelimit']->value;?>
, "#setage", "#minage", "#maxage");<?php if (($_smarty_tpl->tpl_vars['categoryID']->value=='')&&($_smarty_tpl->tpl_vars['_saved_search']->value!='')) {?>update_search_names('<?php echo strtr($_smarty_tpl->tpl_vars['_saved_search']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
');update_spot_searches('<?php echo strtr($_smarty_tpl->tpl_vars['_saved_search']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
');<?php } else { ?>load_sets( {'offset':'0'<?php if ($_smarty_tpl->tpl_vars['spotid']->value=='') {?>, 'setid':''<?php }?><?php if ($_smarty_tpl->tpl_vars['categoryID']->value!=='') {?>, 'next':'<?php echo $_smarty_tpl->tpl_vars['categoryID']->value;?>
'<?php }?>});<?php }?>set_scroll_handler('#contentout', load_sets);update_basket_display();});</script>

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