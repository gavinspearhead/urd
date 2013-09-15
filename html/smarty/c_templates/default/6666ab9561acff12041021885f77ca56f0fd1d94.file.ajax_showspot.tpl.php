<?php /* Smarty version Smarty-3.1.14, created on 2013-09-15 10:32:17
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_showspot.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1798525850520021bbbe72e5-75829816%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6666ab9561acff12041021885f77ca56f0fd1d94' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_showspot.tpl',
      1 => 1379233933,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1798525850520021bbbe72e5-75829816',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_520021bbe967e6_34078322',
  'variables' => 
  array (
    'title' => 0,
    'show_image' => 0,
    'image' => 0,
    'image_from_db' => 0,
    'spotid' => 0,
    'image_file' => 0,
    'LN_browse_subject' => 0,
    'LN_size' => 0,
    'filesize' => 0,
    'LN_browse_age' => 0,
    'age' => 0,
    'timestamp' => 0,
    'LN_showsetinfo_postedby' => 0,
    'poster' => 0,
    'spotter_id' => 0,
    'whitelisted' => 0,
    'subcata' => 0,
    'k' => 0,
    'cat' => 0,
    'val' => 0,
    'subcatd' => 0,
    'subcatb' => 0,
    'subcatc' => 0,
    'display' => 0,
    'vals' => 0,
    'looped' => 0,
    'tag' => 0,
    'LN_spots_tag' => 0,
    'url' => 0,
    'LN_feeds_url' => 0,
    'LN_bin_image' => 0,
    'LN_category' => 0,
    'category_id' => 0,
    'category' => 0,
    'subcat' => 0,
    'LN_spot_subcategory' => 0,
    'LN_spam_reports' => 0,
    'spam_reports' => 0,
    'extsetoverview' => 0,
    'description' => 0,
    'comments' => 0,
    'comment' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_520021bbe967e6_34078322')) {function content_520021bbe967e6_34078322($_smarty_tpl) {?>

<div class="closebutton buttonlike noborder fixedright down5" id="close_button"></div>
<div class="set_title centered"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['title']->value, ENT_QUOTES, 'UTF-8', true);?>
</div>
<div class="sets_inner" onmouseup="javascript:start_quickmenu('setdetails', '', 0, event);" id="td_sets">
<?php if ($_smarty_tpl->tpl_vars['show_image']->value&&$_smarty_tpl->tpl_vars['image']->value!=''&&$_smarty_tpl->tpl_vars['image_from_db']->value==0) {?>
<div class="spot_thumbnail noborder buttonlike"><img src="<?php echo $_smarty_tpl->tpl_vars['image']->value;?>
" class="max100x100" alt="" onclick="javascript:jump('<?php echo strtr($_smarty_tpl->tpl_vars['image']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', true);"/> </div>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['show_image']->value&&$_smarty_tpl->tpl_vars['image_from_db']->value==1) {?>
<div class="spot_thumbnail noborder buttonlike"><img src="show_image.php?spotid=<?php echo $_smarty_tpl->tpl_vars['spotid']->value;?>
" class="max100x100" alt="" onclick="javascript:jump('show_image.php?spotid=<?php echo $_smarty_tpl->tpl_vars['spotid']->value;?>
', true);"/></div>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['show_image']->value&&$_smarty_tpl->tpl_vars['image_file']->value!='') {?>
<div class="spot_thumbnail noborder buttonlike"><img src="getfile.php?raw=1&amp;file=<?php echo $_smarty_tpl->tpl_vars['image_file']->value;?>
" class="max100x100" alt="" onclick="javascript:show_spot_image('getfile.php?file=<?php echo $_smarty_tpl->tpl_vars['image_file']->value;?>
&amp;raw=1', true);"/></div>
<?php }?>
<table class="set_details">
<tr class="comment"><td class="nowrap bold"><?php echo $_smarty_tpl->tpl_vars['LN_browse_subject']->value;?>
:</td><td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['title']->value, ENT_QUOTES, 'UTF-8', true);?>
</td></tr>
<tr class="comment"><td class="nowrap bold"><?php echo $_smarty_tpl->tpl_vars['LN_size']->value;?>
:</td><td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['filesize']->value, ENT_QUOTES, 'UTF-8', true);?>
</td></tr>
<tr class="comment"><td class="nowrap bold"><?php echo $_smarty_tpl->tpl_vars['LN_browse_age']->value;?>
:</td><td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['age']->value, ENT_QUOTES, 'UTF-8', true);?>
 (<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['timestamp']->value, ENT_QUOTES, 'UTF-8', true);?>
)</td></tr>
<tr class="comment"><td class="nowrap bold"><?php echo $_smarty_tpl->tpl_vars['LN_showsetinfo_postedby']->value;?>
:</td><td class="buttonlike" onclick="javascript: load_sets({ 'poster':'<?php echo strtr($_smarty_tpl->tpl_vars['poster']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
' });"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['poster']->value, ENT_QUOTES, 'UTF-8', true);?>
 (<?php echo $_smarty_tpl->tpl_vars['spotter_id']->value;?>
)<?php if ($_smarty_tpl->tpl_vars['whitelisted']->value) {?>&nbsp;<div <?php echo smarty_function_urd_popup(array('type'=>"small",'text'=>((string) $_smarty_tpl->tpl_vars['LN_browse_userwhitelisted']->value)),$_smarty_tpl);?>
 class="highlight_whitelist inline center width15">W</div><?php }?></td></tr>
<?php  $_smarty_tpl->tpl_vars['cat'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cat']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['subcata']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cat']->key => $_smarty_tpl->tpl_vars['cat']->value) {
$_smarty_tpl->tpl_vars['cat']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['cat']->key;
?><tr class="comment"><td class="nowrap bold"><?php echo $_smarty_tpl->tpl_vars['k']->value;?>
:</td>
<td>
<?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['cat']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['val']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['val']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
 $_smarty_tpl->tpl_vars['val']->iteration++;
 $_smarty_tpl->tpl_vars['val']->last = $_smarty_tpl->tpl_vars['val']->iteration === $_smarty_tpl->tpl_vars['val']->total;
?>
<span  class="buttonlike" onclick="javascript:load_sets({ 'spot_cat':'<?php echo strtr($_smarty_tpl->tpl_vars['val']->value[1], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', 'subcat':'subcat_<?php echo strtr($_smarty_tpl->tpl_vars['val']->value[1], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
_<?php echo strtr($_smarty_tpl->tpl_vars['val']->value[2], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
_<?php echo strtr($_smarty_tpl->tpl_vars['val']->value[3], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
' });"><?php echo $_smarty_tpl->tpl_vars['val']->value[0];?>
</span>
<?php if (!$_smarty_tpl->tpl_vars['val']->last) {?>;<?php }?> 
<?php } ?>
</td></tr>
<?php  $_smarty_tpl->tpl_vars['cat'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cat']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['subcatd']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cat']->key => $_smarty_tpl->tpl_vars['cat']->value) {
$_smarty_tpl->tpl_vars['cat']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['cat']->key;
?><tr class="comment"><td class="nowrap bold buttonlike"><?php echo $_smarty_tpl->tpl_vars['k']->value;?>
:</td><td> 
<?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['cat']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['val']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['val']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
 $_smarty_tpl->tpl_vars['val']->iteration++;
 $_smarty_tpl->tpl_vars['val']->last = $_smarty_tpl->tpl_vars['val']->iteration === $_smarty_tpl->tpl_vars['val']->total;
?><span  class="buttonlike" onclick="javascript: load_sets({ 'spot_cat':'<?php echo strtr($_smarty_tpl->tpl_vars['val']->value[1], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', 'subcat':'subcat_<?php echo strtr($_smarty_tpl->tpl_vars['val']->value[1], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
_<?php echo strtr($_smarty_tpl->tpl_vars['val']->value[2], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
_<?php echo strtr($_smarty_tpl->tpl_vars['val']->value[3], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
' });"><?php echo $_smarty_tpl->tpl_vars['val']->value[0];?>
</span><?php if (!$_smarty_tpl->tpl_vars['val']->last) {?>;<?php }?> <?php } ?></td></tr>
<?php } ?>
<?php  $_smarty_tpl->tpl_vars['cat'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cat']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['subcatb']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cat']->key => $_smarty_tpl->tpl_vars['cat']->value) {
$_smarty_tpl->tpl_vars['cat']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['cat']->key;
?><tr class="comment"><td class="nowrap bold buttonlike"><?php echo $_smarty_tpl->tpl_vars['k']->value;?>
:</td><td>
<?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['cat']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['val']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['val']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
 $_smarty_tpl->tpl_vars['val']->iteration++;
 $_smarty_tpl->tpl_vars['val']->last = $_smarty_tpl->tpl_vars['val']->iteration === $_smarty_tpl->tpl_vars['val']->total;
?><span  class="buttonlike" onclick="javascript: load_sets({ 'spot_cat':'<?php echo strtr($_smarty_tpl->tpl_vars['val']->value[1], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', 'subcat':'subcat_<?php echo strtr($_smarty_tpl->tpl_vars['val']->value[1], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
_<?php echo strtr($_smarty_tpl->tpl_vars['val']->value[2], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
_<?php echo strtr($_smarty_tpl->tpl_vars['val']->value[3], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
' });"><?php echo $_smarty_tpl->tpl_vars['val']->value[0];?>
</span><?php if (!$_smarty_tpl->tpl_vars['val']->last) {?>;<?php }?> <?php } ?> </td></tr>
<?php } ?>
<?php  $_smarty_tpl->tpl_vars['cat'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cat']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['subcatc']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cat']->key => $_smarty_tpl->tpl_vars['cat']->value) {
$_smarty_tpl->tpl_vars['cat']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['cat']->key;
?><tr class="comment"><td class="nowrap bold buttonlike"><?php echo $_smarty_tpl->tpl_vars['k']->value;?>
:</td><td>
<?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['cat']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['val']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['val']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
 $_smarty_tpl->tpl_vars['val']->iteration++;
 $_smarty_tpl->tpl_vars['val']->last = $_smarty_tpl->tpl_vars['val']->iteration === $_smarty_tpl->tpl_vars['val']->total;
?><span  class="buttonlike" onclick="javascript: load_sets({ 'spot_cat':'<?php echo strtr($_smarty_tpl->tpl_vars['val']->value[1], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', 'subcat':'subcat_<?php echo strtr($_smarty_tpl->tpl_vars['val']->value[1], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
_<?php echo strtr($_smarty_tpl->tpl_vars['val']->value[2], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
_<?php echo strtr($_smarty_tpl->tpl_vars['val']->value[3], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
' });"><?php echo $_smarty_tpl->tpl_vars['val']->value[0];?>
</span><?php if (!$_smarty_tpl->tpl_vars['val']->last) {?>;<?php }?> <?php } ?></td></tr>
<?php } ?>
<?php } ?>

<?php $_smarty_tpl->_capture_stack[0][] = array('default', 'extsetoverview', null); ob_start(); ?>
	<?php $_smarty_tpl->tpl_vars['looped'] = new Smarty_variable(0, null, 0);?>
	<?php  $_smarty_tpl->tpl_vars['vals'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['vals']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['display']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['vals']->key => $_smarty_tpl->tpl_vars['vals']->value) {
$_smarty_tpl->tpl_vars['vals']->_loop = true;
?>
	<?php if ($_smarty_tpl->tpl_vars['vals']->value['value']!="0"&&$_smarty_tpl->tpl_vars['vals']->value['value']!=''&&$_smarty_tpl->tpl_vars['vals']->value['value']!="name") {?>
		<?php $_smarty_tpl->tpl_vars['looped'] = new Smarty_variable(((string) $_smarty_tpl->tpl_vars['looped']->value+1), null, 0);?>
		<tr class="vtop small comment"><td class="nowrap bold"><?php echo $_smarty_tpl->tpl_vars['vals']->value['name'];?>
:</td><td>
		<?php if ($_smarty_tpl->tpl_vars['vals']->value['display']=='text') {?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['vals']->value['value'], ENT_QUOTES, 'UTF-8', true);?>
<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['vals']->value['display']=='url') {?><span class="buttonlike" onclick="javascript:jump('<?php echo strtr($_smarty_tpl->tpl_vars['vals']->value['value'], array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
',1);"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['vals']->value['value'], ENT_QUOTES, 'UTF-8', true);?>
</span><?php }?>
		<?php if ($_smarty_tpl->tpl_vars['vals']->value['display']=='number') {?><b><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['vals']->value['value'], ENT_QUOTES, 'UTF-8', true);?>
</b><?php }?>
		<?php if ($_smarty_tpl->tpl_vars['vals']->value['display']=='checkbox') {?><?php if ($_smarty_tpl->tpl_vars['vals']->value['value']==1) {?>Yes<?php } else { ?>No<?php }?><?php }?>
		</td></tr>
	<?php }?>
	<?php } ?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>

<?php if ($_smarty_tpl->tpl_vars['tag']->value!='') {?>
<tr class="comment"><td class="nowrap bold"><?php echo $_smarty_tpl->tpl_vars['LN_spots_tag']->value;?>
:</td><td class="buttonlike" onclick="javascript: load_sets({ 'search':'<?php echo strtr($_smarty_tpl->tpl_vars['tag']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
' });"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['tag']->value, ENT_QUOTES, 'UTF-8', true);?>
</td></tr>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['url']->value!='') {?>
<tr class="comment"><td class="nowrap bold"><?php echo $_smarty_tpl->tpl_vars['LN_feeds_url']->value;?>
:</td><td><span class="buttonlike" onclick="javascript:jump('<?php echo strtr($_smarty_tpl->tpl_vars['url']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
',1);"><?php echo $_smarty_tpl->tpl_vars['url']->value;?>
</span></td></tr>
<?php }?>
<?php if ($_smarty_tpl->tpl_vars['image']->value!='') {?>
<tr class="comment"><td class="nowrap bold"><?php echo $_smarty_tpl->tpl_vars['LN_bin_image']->value;?>
:</td><td><span class="buttonlike" onclick="javascript:jump('<?php echo strtr($_smarty_tpl->tpl_vars['image']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
',1);"><?php echo $_smarty_tpl->tpl_vars['image']->value;?>
</span></td></tr>
<?php }?>
<tr class="comment"><td class="nowrap bold"><?php echo $_smarty_tpl->tpl_vars['LN_category']->value;?>
:</td><td class="buttonlike" onclick="javascript: load_sets({ 'spot_cat':'<?php echo strtr($_smarty_tpl->tpl_vars['category_id']->value, array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
' });"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['category']->value, ENT_QUOTES, 'UTF-8', true);?>
</td></tr>
<?php if ($_smarty_tpl->tpl_vars['subcat']->value!=0) {?> <tr class="comment"><td class="nowrap bold"><?php echo $_smarty_tpl->tpl_vars['LN_spot_subcategory']->value;?>
:</td><td><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['subcat']->value, ENT_QUOTES, 'UTF-8', true);?>
</td></tr> <?php }?>
<tr class="comment"><td class="nowrap bold"><?php echo $_smarty_tpl->tpl_vars['LN_spam_reports']->value;?>
:</td><td>
<?php if ($_smarty_tpl->tpl_vars['spam_reports']->value>0) {?><div class="highlight_spam inline center width15"><?php echo $_smarty_tpl->tpl_vars['spam_reports']->value;?>
</div>
<?php } else { ?>0<?php }?>
</td></tr>
<?php if ($_smarty_tpl->tpl_vars['looped']->value>0) {?>
<tr><td colspan="2">&nbsp;</td></tr>
<?php echo $_smarty_tpl->tpl_vars['extsetoverview']->value;?>

<?php }?>

<tr class="comment"><td colspan="2"><br/></td></tr>
<tr class="comment"><td colspan="2"><?php echo $_smarty_tpl->tpl_vars['description']->value;?>
</td></tr>
<tr class="comment"><td colspan="2"><br/></td></tr>

<?php  $_smarty_tpl->tpl_vars['comment'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['comment']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['comments']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['comment']->key => $_smarty_tpl->tpl_vars['comment']->value) {
$_smarty_tpl->tpl_vars['comment']->_loop = true;
?>
<tr class="comment_poster"><td colspan="2"><?php echo $_smarty_tpl->tpl_vars['LN_showsetinfo_postedby']->value;?>
: <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['comment']->value['from'], ENT_QUOTES, 'UTF-8', true);?>
 (<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['comment']->value['userid'], ENT_QUOTES, 'UTF-8', true);?>
) <div class="floatright"> @ <?php echo $_smarty_tpl->tpl_vars['comment']->value['stamp'];?>
</div></td></tr>
<tr class="comment"><td colspan="2"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['comment']->value['comment'], ENT_QUOTES, 'UTF-8', true);?>
</td></tr>
<tr class="comment"><td colspan="2"><br/></td></tr>
<?php } ?>

</table>
</div>
<?php }} ?>