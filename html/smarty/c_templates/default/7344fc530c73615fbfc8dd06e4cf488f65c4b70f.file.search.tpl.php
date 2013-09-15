<?php /* Smarty version Smarty-3.1.14, created on 2013-09-06 00:24:33
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/search.tpl" */ ?>
<?php /*%%SmartyHeaderCode:177125046952056255a83ad0-42394892%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7344fc530c73615fbfc8dd06e4cf488f65c4b70f' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/search.tpl',
      1 => 1378218514,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '177125046952056255a83ad0-42394892',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_520562560f11e6_44606916',
  'variables' => 
  array (
    'title' => 0,
    'show_spots' => 0,
    'spot_subcats' => 0,
    'k1' => 0,
    'LN_spots_subcategories' => 0,
    'item' => 0,
    'LN_reset' => 0,
    'k2' => 0,
    'si' => 0,
    'item2' => 0,
    'cnt' => 0,
    'k3' => 0,
    'LN_menuspotssearch' => 0,
    'LN_browse_searchsets' => 0,
    'LN_spots_allcategories' => 0,
    'spots_total_articles' => 0,
    'spot_categories' => 0,
    'subcatdivs' => 0,
    'LN_browse_allsets' => 0,
    'LN_browse_interesting' => 0,
    'LN_browse_downloaded' => 0,
    'show_makenzb' => 0,
    'LN_browse_nzb' => 0,
    'LN_browse_killed' => 0,
    'LN_search' => 0,
    'LN_setsize' => 0,
    'spotminsetsize' => 0,
    'spotmaxsetsize' => 0,
    'LN_age' => 0,
    'spotminagelimit' => 0,
    'spotmaxagelimit' => 0,
    'LN_rating' => 0,
    'spotminratinglimit' => 0,
    'spotmaxratinglimit' => 0,
    'show_groups' => 0,
    'LN_menugroupsearch' => 0,
    'LN_browse_allgroups' => 0,
    'groups_total_articles' => 0,
    'subscribedgroups' => 0,
    'LN_category' => 0,
    'groupminsetsizelimit' => 0,
    'groupmaxsetsizelimit' => 0,
    'groupminagelimit' => 0,
    'groupmaxagelimit' => 0,
    'groupminratinglimit' => 0,
    'groupmaxratinglimit' => 0,
    'LN_complete' => 0,
    'groupmincompletelimit' => 0,
    'groupmaxcompletelimit' => 0,
    'show_rss' => 0,
    'LN_menursssearch' => 0,
    'LN_feeds_allgroups' => 0,
    'rss_total_articles' => 0,
    'subscribedfeeds' => 0,
    'rssminsetsizelimit' => 0,
    'rssmaxsetsizelimit' => 0,
    'rssminagelimit' => 0,
    'rssmaxagelimit' => 0,
    'rssminratinglimit' => 0,
    'rssmaxratinglimit' => 0,
    'spotminsetsizelimit' => 0,
    'spotmaxsetsizelimit' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_520562560f11e6_44606916')) {function content_520562560f11e6_44606916($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate ("head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('title'=>$_smarty_tpl->tpl_vars['title']->value), 0);?>



<div id="textcontent">
<div class="urdlogo2 floatright noborder buttonlike" onclick="javascript:jump('index.php');"></div>

<?php if ($_smarty_tpl->tpl_vars['show_spots']->value==1) {?> 
<?php $_smarty_tpl->_capture_stack[0][] = array('default', "subcatdivs", null); ob_start(); ?>
<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['k1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['spot_subcats']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['k1']->value = $_smarty_tpl->tpl_vars['item']->key;
?>

<div id="subcat_selector_<?php echo $_smarty_tpl->tpl_vars['k1']->value;?>
" class="subcat_selector hidden">
<div class="closebutton buttonlike noborder fixedright down5" onclick="javascript:close_subcat_selector();" ></div>
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
<tr><td onclick="javascript:fold_adv_search('subcat_button_<?php echo $_smarty_tpl->tpl_vars['k1']->value;?>
<?php echo $_smarty_tpl->tpl_vars['k2']->value;?>
', 'subcat_items_<?php echo $_smarty_tpl->tpl_vars['k1']->value;?>
<?php echo $_smarty_tpl->tpl_vars['k2']->value;?>
');" class="subcat_head">
<div id="subcat_button_<?php echo $_smarty_tpl->tpl_vars['k1']->value;?>
<?php echo $_smarty_tpl->tpl_vars['k2']->value;?>
" class="floatleft iconsize dynimgplus buttonlike"></div>&nbsp;
<?php echo $_smarty_tpl->tpl_vars['si']->value['name'];?>

</td></tr>
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
<?php if ($_smarty_tpl->tpl_vars['cnt']->value==3) {?></tr><tr>
<?php $_smarty_tpl->tpl_vars['cnt'] = new Smarty_variable("0", null, 0);?>
<?php }?>
<td class="subcat">
<?php echo smarty_function_urd_checkbox(array('value'=>"0",'name'=>"subcat_".((string) $_smarty_tpl->tpl_vars['k1']->value)."_".((string) $_smarty_tpl->tpl_vars['k2']->value)."_".((string) $_smarty_tpl->tpl_vars['k3']->value),'id'=>"subcat_".((string) $_smarty_tpl->tpl_vars['k1']->value)."_".((string) $_smarty_tpl->tpl_vars['k2']->value)."_".((string) $_smarty_tpl->tpl_vars['k3']->value),'data'=>((string) $_smarty_tpl->tpl_vars['item2']->value),'tristate'=>"1"),$_smarty_tpl);?>
 
</td>
<?php $_smarty_tpl->tpl_vars['cnt'] = new Smarty_variable(((string) $_smarty_tpl->tpl_vars['cnt']->value+1), null, 0);?>
<?php }?>
<?php } ?>
</tr>            
</table>
<?php } ?>
</div>
<div id="save_subcat_<?php echo $_smarty_tpl->tpl_vars['k1']->value;?>
" class="save_subcat">
</div>

</div>
<?php } ?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>


<h3 class="title"><?php echo $_smarty_tpl->tpl_vars['LN_menuspotssearch']->value;?>
</h3>
<form id="searchform3" action="spots.php" method="post">
<table class="search">
<tr><td>
	<?php echo $_smarty_tpl->tpl_vars['LN_browse_searchsets']->value;?>
:&nbsp;
    </td>
    <td>
	<select name="categoryID" class="search" id="select_catid" onchange='javascript:do_select_subcat();'>
    <option value=""><?php echo $_smarty_tpl->tpl_vars['LN_spots_allcategories']->value;?>
 (<?php echo $_smarty_tpl->tpl_vars['spots_total_articles']->value;?>
)</option>
    <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['spot_categories']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
		<option value="<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
">
            <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
 (<?php echo $_smarty_tpl->tpl_vars['item']->value['article_count'];?>
)
        </option>
	<?php } ?>
	</select>&nbsp;
</td>
<td>

    <?php echo $_smarty_tpl->tpl_vars['subcatdivs']->value;?>

    <input type="button" id="subcatbutton" class="submitsmall invisible" value="<?php echo $_smarty_tpl->tpl_vars['LN_spots_subcategories']->value;?>
" onclick="javascript:show_subcat_selector();" />&nbsp;
</td>
<td>
<select name="flag" class="search" id="flag">
    <option selected="selected" value=""><?php echo $_smarty_tpl->tpl_vars['LN_browse_allsets']->value;?>
</option>
    <option value="interesting"><?php echo $_smarty_tpl->tpl_vars['LN_browse_interesting']->value;?>
</option>
    <option value="read"><?php echo $_smarty_tpl->tpl_vars['LN_browse_downloaded']->value;?>
</option>
<?php if ($_smarty_tpl->tpl_vars['show_makenzb']->value!=0) {?>
<option value="nzb"><?php echo $_smarty_tpl->tpl_vars['LN_browse_nzb']->value;?>
</option>
<?php }?>
<option value="kill"><?php echo $_smarty_tpl->tpl_vars['LN_browse_killed']->value;?>
</option>
</select>&nbsp;
</td>
<td>
<input type="text" id="search" name="search" size="30" class="search" value="&lt;<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
&gt;" 
onfocus="if (this.value=='&lt;<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
&gt;') this.value='';" onkeypress="javascript:submit_enter(event, load_sets, { 'offset':'0', 'setid':'' } );"/>&nbsp;
</td>
</tr>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_setsize']->value;?>
:</td>
<td><input type="text" id="spotminsetsize" name="minsetsize"  size="6" value="<?php echo $_smarty_tpl->tpl_vars['spotminsetsize']->value;?>
"/></td> 
<td><div id="spotsetsize" style="width:100px;"></div></td>
<td><input type="text" id="spotmaxsetsize" name="maxsetsize" size="6" value="<?php echo $_smarty_tpl->tpl_vars['spotmaxsetsize']->value;?>
"/></td>
</tr>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_age']->value;?>
:</td>
<td><input type="text" id="spotminage" name="minage" size="6" value="<?php echo $_smarty_tpl->tpl_vars['spotminagelimit']->value;?>
"/></td> 
<td><div id="spotsetage" style="width:100px;"></div></td>
<td><input type="text" id="spotmaxage" name="maxage" size="6" value="<?php echo $_smarty_tpl->tpl_vars['spotmaxagelimit']->value;?>
"/></td>
</tr>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_rating']->value;?>
:</td>
<td><input type="text" id="spotminrating" name="minrating" size="6" value="<?php echo $_smarty_tpl->tpl_vars['spotminratinglimit']->value;?>
"/></td> 
<td><div id="spotrating" style="width:100px;"></div></td>
<td><input type="text" id="spotmaxrating" name="maxrating" size="6" value="<?php echo $_smarty_tpl->tpl_vars['spotmaxratinglimit']->value;?>
"/></td>
</tr>

<tr>
<td>
<input type="submit" value="<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
" class="submitsmall" onclick='javascript:do_submit("searchform3");'/>
&nbsp;&nbsp;
<input type="button" value="<?php echo $_smarty_tpl->tpl_vars['LN_reset']->value;?>
" class="submitsmall" onclick='javascript:clear_form("searchform3");do_select_subcat();'/>
</td>
</tr>
</table>
</form>

<p></p>

<p>&nbsp;</p>
<?php }?>


<?php if ($_smarty_tpl->tpl_vars['show_groups']->value!=0) {?> 
<h3 class="title"><?php echo $_smarty_tpl->tpl_vars['LN_menugroupsearch']->value;?>
</h3>
<form id="searchform1" action="browse.php" method="get">
<table class="search">
<tr><td>
	<?php echo $_smarty_tpl->tpl_vars['LN_browse_searchsets']->value;?>
:
    </td>
    <td colspan="2">
	<select name="groupID" class="search" id="select_groupid" >
    <option value=""><?php echo $_smarty_tpl->tpl_vars['LN_browse_allgroups']->value;?>
 (<?php echo $_smarty_tpl->tpl_vars['groups_total_articles']->value;?>
)</option>
    <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['subscribedgroups']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
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
		<option value="<?php echo $_smarty_tpl->tpl_vars['item']->value['type'];?>
_<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
"><?php if ($_smarty_tpl->tpl_vars['item']->value['type']=='category') {?><?php echo $_smarty_tpl->tpl_vars['LN_category']->value;?>
: <?php }?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['shortname'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
 <?php if ($_smarty_tpl->tpl_vars['item']->value['type']=='group') {?>(<?php echo $_smarty_tpl->tpl_vars['item']->value['article_count'];?>
)<?php }?>
</option>
	<?php } ?>
	</select>
    </td>

    <td>
    <select name="flag" class="search">
		<option selected="selected" value=""><?php echo $_smarty_tpl->tpl_vars['LN_browse_allsets']->value;?>
</option>
		<option value="interesting"><?php echo $_smarty_tpl->tpl_vars['LN_browse_interesting']->value;?>
</option>
		<option value="read"><?php echo $_smarty_tpl->tpl_vars['LN_browse_downloaded']->value;?>
</option>
		<option value="nzb"><?php echo $_smarty_tpl->tpl_vars['LN_browse_nzb']->value;?>
</option>
		<option value="kill"><?php echo $_smarty_tpl->tpl_vars['LN_browse_killed']->value;?>
</option>
	</select>
    </td>
    <td>
	<input type="text" name="search" size="30" class="search" value="&lt;<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
&gt;" onfocus="if (this.value=='&lt;<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
&gt;') this.value='';" onkeypress="javascript:submit_enter(event,do_submit, 'searchform2');"/>
    </td>

</tr>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_setsize']->value;?>
:</td>
<td><input type="text" id="groupminsetsize" name="minsetsize" size="6" value="<?php echo $_smarty_tpl->tpl_vars['groupminsetsizelimit']->value;?>
"/></td> 
<td><div id="groupsetsize" style="width:100px;"></div></td>
<td><input type="text" id="groupmaxsetsize" name="maxsetsize" size="6" value="<?php echo $_smarty_tpl->tpl_vars['groupmaxsetsizelimit']->value;?>
"/></td>
</tr>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_age']->value;?>
:</td>
<td><input type="text" id="groupminage" name="minage" size="6" value="<?php echo $_smarty_tpl->tpl_vars['groupminagelimit']->value;?>
"/></td> 
<td><div id="groupsetage" style="width:100px;"></div></td>
<td><input type="text" id="groupmaxage" name="maxage" size="6" value="<?php echo $_smarty_tpl->tpl_vars['groupmaxagelimit']->value;?>
"/></td>
</tr>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_rating']->value;?>
:</td>
<td><input type="text" id="groupminrating" name="minrating" size="6" value="<?php echo $_smarty_tpl->tpl_vars['groupminratinglimit']->value;?>
"/></td> 
<td><div id="groupsetrating" style="width:100px;"></div></td>
<td><input type="text" id="groupmaxrating" name="maxrating" size="6" value="<?php echo $_smarty_tpl->tpl_vars['groupmaxratinglimit']->value;?>
"/></td>
</tr>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_complete']->value;?>
:</td>
<td><input type="text" id="groupmincomplete" name="mincomplete" size="6" value="<?php echo $_smarty_tpl->tpl_vars['groupmincompletelimit']->value;?>
"/></td> 
<td><div id="groupsetcomplete" style="width:100px;"></div></td>
<td><input type="text" id="groupmaxcomplete" name="maxcomplete" size="6" value="<?php echo $_smarty_tpl->tpl_vars['groupmaxcompletelimit']->value;?>
"/></td>
</tr>

<tr>
<td>
<input type="submit" value="<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
" class="submitsmall" onclick='javascript: do_submit("searchform1");'/>
&nbsp;&nbsp;
<input type="button" value="<?php echo $_smarty_tpl->tpl_vars['LN_reset']->value;?>
" class="submitsmall" onclick='javascript:clear_form("searchform1");'/>
&nbsp;&nbsp;
</td></tr>
</table>
</form>
<p></p>

<p>&nbsp;</p>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['show_rss']->value!=0) {?> 
<h3 class="title"><?php echo $_smarty_tpl->tpl_vars['LN_menursssearch']->value;?>
</h3>
<form id="searchform2" action="rsssets.php" method="post">
<table class="search">
<tr><td>
	<?php echo $_smarty_tpl->tpl_vars['LN_browse_searchsets']->value;?>
:
    </td>
    <td>
    <select name="feed_id" class="search" id="select_feedid">
    <option value=""><?php echo $_smarty_tpl->tpl_vars['LN_feeds_allgroups']->value;?>
 (<?php echo $_smarty_tpl->tpl_vars['rss_total_articles']->value;?>
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
		<option value="<?php echo $_smarty_tpl->tpl_vars['item']->value['type'];?>
_<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
"><?php if ($_smarty_tpl->tpl_vars['item']->value['type']=='category') {?><?php echo $_smarty_tpl->tpl_vars['LN_category']->value;?>
: <?php }?><?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['item']->value['name'], ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
 (<?php echo $_smarty_tpl->tpl_vars['item']->value['article_count'];?>
)</option>
    <?php } ?>
	</select>
    </td>
    <td>
    <select name="flag" class="search">
		<option selected="selected" value=""><?php echo $_smarty_tpl->tpl_vars['LN_browse_allsets']->value;?>
</option>
		<option value="interesting"><?php echo $_smarty_tpl->tpl_vars['LN_browse_interesting']->value;?>
</option>
		<option value="read"><?php echo $_smarty_tpl->tpl_vars['LN_browse_downloaded']->value;?>
</option>
		<option value="nzb"><?php echo $_smarty_tpl->tpl_vars['LN_browse_nzb']->value;?>
</option>
		<option value="kill"><?php echo $_smarty_tpl->tpl_vars['LN_browse_killed']->value;?>
</option>
	</select>
    </td>
    <td>

	<input type="text" name="search" size="30" class="search" value="&lt;<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
&gt;" onfocus="if (this.value=='&lt;<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
&gt;') this.value='';" onkeypress="javascript:submit_enter(event,do_submit, 'searchform2');"/>
	<input type="hidden" value="" name="maxage"/>
    </td>
    </tr>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_setsize']->value;?>
:</td>
<td><input type="text" id="rssminsetsize" size="6" name="minsetsize" value="<?php echo $_smarty_tpl->tpl_vars['rssminsetsizelimit']->value;?>
"/></td> 
<td><div id="rsssetsize" style="width:100px;"></div></td>
<td><input type="text" id="rssmaxsetsize" size="6" name="maxsetsize" value="<?php echo $_smarty_tpl->tpl_vars['rssmaxsetsizelimit']->value;?>
"/></td>
</tr>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_age']->value;?>
:</td>
<td><input type="text" id="rssminage" name="minage" size="6" value="<?php echo $_smarty_tpl->tpl_vars['rssminagelimit']->value;?>
"/></td> 
<td><div id="rsssetage" style="width:100px;"></div></td>
<td><input type="text" id="rssmaxage" name="maxage" size="6" value="<?php echo $_smarty_tpl->tpl_vars['rssmaxagelimit']->value;?>
"/></td>
</tr>
<tr>
<td><?php echo $_smarty_tpl->tpl_vars['LN_rating']->value;?>
:</td>
<td><input type="text" id="rssminrating" name="minrating" size="6" value="<?php echo $_smarty_tpl->tpl_vars['rssminratinglimit']->value;?>
"/></td> 
<td><div id="rsssetrating" style="width:100px;"></a></div></td>
<td><input type="text" id="rssmaxrating" name="maxrating" size="6" value="<?php echo $_smarty_tpl->tpl_vars['rssmaxratinglimit']->value;?>
"/></td>
<td>

<tr>
<td>
<input type="submit" value="<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
" class="submitsmall" onclick='javascript:do_submit("searchform2");'/>
&nbsp;&nbsp;
<input type="button" value="<?php echo $_smarty_tpl->tpl_vars['LN_reset']->value;?>
" class="submitsmall" onclick='javascript:clear_form("searchform2");'/>
</td>
</tr>
</table>
</form>

<p>&nbsp;</p>

<?php }?>
</div>

<script type="text/javascript">

$(document).ready(function() {
<?php if ($_smarty_tpl->tpl_vars['show_spots']->value==1) {?> 
       init_slider(<?php echo $_smarty_tpl->tpl_vars['spotminsetsizelimit']->value;?>
, <?php echo $_smarty_tpl->tpl_vars['spotmaxsetsizelimit']->value;?>
, "#spotsetsize", "#spotminsetsize", "#spotmaxsetsize");
       init_slider(<?php echo $_smarty_tpl->tpl_vars['spotminagelimit']->value;?>
, <?php echo $_smarty_tpl->tpl_vars['spotmaxagelimit']->value;?>
, "#spotsetage", "#spotminage", "#spotmaxage");
       init_slider(<?php echo $_smarty_tpl->tpl_vars['spotminratinglimit']->value;?>
, <?php echo $_smarty_tpl->tpl_vars['spotmaxratinglimit']->value;?>
, "#spotrating", "#spotminrating", "#spotmaxrating");
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['show_rss']->value==1) {?> 
       init_slider(<?php echo $_smarty_tpl->tpl_vars['rssminsetsizelimit']->value;?>
, <?php echo $_smarty_tpl->tpl_vars['rssmaxsetsizelimit']->value;?>
, "#rsssetsize", "#rssminsetsize", "#rssmaxsetsize");
       init_slider(<?php echo $_smarty_tpl->tpl_vars['rssminagelimit']->value;?>
, <?php echo $_smarty_tpl->tpl_vars['rssmaxagelimit']->value;?>
, "#rsssetage", "#rssminage", "#rssmaxage");
       init_slider(<?php echo $_smarty_tpl->tpl_vars['rssminratinglimit']->value;?>
, <?php echo $_smarty_tpl->tpl_vars['rssmaxratinglimit']->value;?>
, "#rsssetrating", "#rssminrating", "#rssmaxrating");
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['show_groups']->value==1) {?> 
       init_slider(<?php echo $_smarty_tpl->tpl_vars['groupminsetsizelimit']->value;?>
, <?php echo $_smarty_tpl->tpl_vars['groupmaxsetsizelimit']->value;?>
, "#groupsetsize", "#groupminsetsize", "#groupmaxsetsize");
       init_slider(<?php echo $_smarty_tpl->tpl_vars['groupminagelimit']->value;?>
, <?php echo $_smarty_tpl->tpl_vars['groupmaxagelimit']->value;?>
, "#groupsetage", "#groupminage", "#groupmaxage");
       init_slider(<?php echo $_smarty_tpl->tpl_vars['groupminratinglimit']->value;?>
, <?php echo $_smarty_tpl->tpl_vars['groupmaxratinglimit']->value;?>
, "#groupsetrating", "#groupminrating", "#groupmaxrating");
       init_slider(<?php echo $_smarty_tpl->tpl_vars['groupmincompletelimit']->value;?>
, <?php echo $_smarty_tpl->tpl_vars['groupmaxcompletelimit']->value;?>
, "#groupsetcomplete", "#groupmincomplete", "#groupmaxcomplete");
<?php }?>
});

</script>

<?php echo $_smarty_tpl->getSubTemplate ("foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>