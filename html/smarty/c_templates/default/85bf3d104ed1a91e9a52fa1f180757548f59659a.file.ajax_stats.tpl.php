<?php /* Smarty version Smarty-3.1.14, created on 2013-08-06 00:03:56
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_stats.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20054571675200214c77e269-77612645%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '85bf3d104ed1a91e9a52fa1f180757548f59659a' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/ajax_stats.tpl',
      1 => 1370035842,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20054571675200214c77e269-77612645',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'type' => 0,
    'period' => 0,
    'subtypes' => 0,
    'subtype' => 0,
    'width' => 0,
    'year' => 0,
    'thisyear' => 0,
    'thismonth' => 0,
    'endcnt' => 0,
    'cnt' => 0,
    'source' => 0,
    'years' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5200214c8ebac0_66277890',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5200214c8ebac0_66277890')) {function content_5200214c8ebac0_66277890($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.date_format.php';
?>

<?php $_smarty_tpl->tpl_vars['thisyear'] = new Smarty_variable(smarty_modifier_date_format(time(),"Y"), null, 0);?>
<?php $_smarty_tpl->tpl_vars['thismonth'] = new Smarty_variable(smarty_modifier_date_format(time(),"m"), null, 0);?>

<?php if ($_smarty_tpl->tpl_vars['type']->value=='activity') {?>
    <?php if ($_smarty_tpl->tpl_vars['period']->value=='years') {?>
        
        <div id="template_overview" >
        <?php  $_smarty_tpl->tpl_vars['subtype'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['subtype']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['subtypes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['subtype']->key => $_smarty_tpl->tpl_vars['subtype']->value) {
$_smarty_tpl->tpl_vars['subtype']->_loop = true;
?>
            <img src="creategraph.php?period=years&amp;subtype=<?php echo $_smarty_tpl->tpl_vars['subtype']->value;?>
&amp;source=size&amp;type=activity&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
            <img src="creategraph.php?period=years&amp;subtype=<?php echo $_smarty_tpl->tpl_vars['subtype']->value;?>
&amp;source=count&amp;type=activity&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
            <br/>
        <?php } ?>
        </div> 
    <?php } elseif ($_smarty_tpl->tpl_vars['period']->value=='months') {?>
        <div id="template_<?php echo $_smarty_tpl->tpl_vars['year']->value;?>
">
        <?php  $_smarty_tpl->tpl_vars['subtype'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['subtype']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['subtypes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['subtype']->key => $_smarty_tpl->tpl_vars['subtype']->value) {
$_smarty_tpl->tpl_vars['subtype']->_loop = true;
?>
            <img class="buttonlike" src="creategraph.php?period=months&amp;year=<?php echo $_smarty_tpl->tpl_vars['year']->value;?>
&amp;subtype=<?php echo $_smarty_tpl->tpl_vars['subtype']->value;?>
&amp;source=size&amp;type=activity&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" 
                    alt="" onclick="javascript:select_tab_stats(<?php echo $_smarty_tpl->tpl_vars['year']->value;?>
, 'activity','<?php echo $_smarty_tpl->tpl_vars['year']->value;?>
', 'days', 'size', '<?php echo $_smarty_tpl->tpl_vars['subtype']->value;?>
');"/>
            <img class="buttonlike" src="creategraph.php?period=months&amp;year=<?php echo $_smarty_tpl->tpl_vars['year']->value;?>
&amp;subtype=<?php echo $_smarty_tpl->tpl_vars['subtype']->value;?>
&amp;source=count&amp;type=activity&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
"
                    alt="" onclick="javascript:select_tab_stats(<?php echo $_smarty_tpl->tpl_vars['year']->value;?>
, 'activity','<?php echo $_smarty_tpl->tpl_vars['year']->value;?>
', 'days', 'count', '<?php echo $_smarty_tpl->tpl_vars['subtype']->value;?>
');"/>
            <br/>
        <?php } ?>
        </div>
    <?php } elseif ($_smarty_tpl->tpl_vars['period']->value=='days') {?>
        <div id="template_<?php echo $_smarty_tpl->tpl_vars['year']->value;?>
">
        <?php if ($_smarty_tpl->tpl_vars['year']->value==$_smarty_tpl->tpl_vars['thisyear']->value) {?><?php $_smarty_tpl->tpl_vars['endcnt'] = new Smarty_variable($_smarty_tpl->tpl_vars['thismonth']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['endcnt'] = new Smarty_variable(12, null, 0);?><?php }?>
        <?php $_smarty_tpl->tpl_vars['cnt'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['cnt']->step = 1;$_smarty_tpl->tpl_vars['cnt']->total = (int) ceil(($_smarty_tpl->tpl_vars['cnt']->step > 0 ? $_smarty_tpl->tpl_vars['endcnt']->value+1 - (1) : 1-($_smarty_tpl->tpl_vars['endcnt']->value)+1)/abs($_smarty_tpl->tpl_vars['cnt']->step));
if ($_smarty_tpl->tpl_vars['cnt']->total > 0) {
for ($_smarty_tpl->tpl_vars['cnt']->value = 1, $_smarty_tpl->tpl_vars['cnt']->iteration = 1;$_smarty_tpl->tpl_vars['cnt']->iteration <= $_smarty_tpl->tpl_vars['cnt']->total;$_smarty_tpl->tpl_vars['cnt']->value += $_smarty_tpl->tpl_vars['cnt']->step, $_smarty_tpl->tpl_vars['cnt']->iteration++) {
$_smarty_tpl->tpl_vars['cnt']->first = $_smarty_tpl->tpl_vars['cnt']->iteration == 1;$_smarty_tpl->tpl_vars['cnt']->last = $_smarty_tpl->tpl_vars['cnt']->iteration == $_smarty_tpl->tpl_vars['cnt']->total;?>
        <img src="creategraph.php?period=days&amp;year=<?php echo $_smarty_tpl->tpl_vars['year']->value;?>
&amp;month=<?php echo $_smarty_tpl->tpl_vars['cnt']->value;?>
&amp;subtype=<?php echo $_smarty_tpl->tpl_vars['subtype']->value;?>
&amp;source=<?php echo $_smarty_tpl->tpl_vars['source']->value;?>
&amp;type=activity&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
        <?php if (!(1 & $_smarty_tpl->tpl_vars['cnt']->value)) {?><br/><?php }?>
        <?php }} ?>
        <?php if ((1 & $_smarty_tpl->tpl_vars['endcnt']->value)) {?><img src="creategraph.php?period=blank&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/><?php }?>
        </div>
    <?php }?>
<?php } elseif ($_smarty_tpl->tpl_vars['type']->value=='spots_details') {?>
<table>
<tr><td valign="top">
    <img src="creategraph.php?type=spots_details&amp;period=month&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_details&amp;period=dow&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=1&amp;subcat=b&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=1&amp;subcat=d&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=1&amp;subcat=a&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=1&amp;subcat=c&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=1&amp;subcat=z&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=3&amp;subcat=a&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=3&amp;subcat=b&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=2&amp;subcat=b&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=2&amp;subcat=c&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
</td>
<td valign="top">
    <img src="creategraph.php?type=spots_details&amp;period=week&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_details&amp;period=hour&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=0&amp;subcat=a&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=0&amp;subcat=c&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=0&amp;subcat=b&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=0&amp;subcat=z&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=0&amp;subcat=d&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
    <br/>
    <img src="creategraph.php?type=spots_subcat&amp;cat=2&amp;subcat=a&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
</td>
</tr>
</table>


<?php } elseif ($_smarty_tpl->tpl_vars['type']->value=='supply') {?>
    <?php if ($_smarty_tpl->tpl_vars['period']->value=='month') {?>
        <?php $_smarty_tpl->tpl_vars['cnt'] = new Smarty_variable(0, null, 0);?>
        <?php  $_smarty_tpl->tpl_vars['year'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['year']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['years']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['year']->key => $_smarty_tpl->tpl_vars['year']->value) {
$_smarty_tpl->tpl_vars['year']->_loop = true;
?>
            <img class="buttonlike" src="creategraph.php?type=supply&amp;period=month&year=<?php echo $_smarty_tpl->tpl_vars['year']->value;?>
&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt="" onclick="javascript:select_tab_stats('supply', 'supply', '<?php echo $_smarty_tpl->tpl_vars['year']->value;?>
', 'day');"/>
            <?php $_smarty_tpl->tpl_vars['cnt'] = new Smarty_variable($_smarty_tpl->tpl_vars['cnt']->value+1, null, 0);?>
            <?php if (!(1 & $_smarty_tpl->tpl_vars['cnt']->value)) {?><br/><?php }?>
        <?php } ?>
        <?php if ((1 & $_smarty_tpl->tpl_vars['cnt']->value)) {?><img src="creategraph.php?period=blank&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/><?php }?>

<?php } elseif ($_smarty_tpl->tpl_vars['period']->value=='day') {?>
    <?php if ($_smarty_tpl->tpl_vars['year']->value==$_smarty_tpl->tpl_vars['thisyear']->value) {?><?php $_smarty_tpl->tpl_vars['endcnt'] = new Smarty_variable($_smarty_tpl->tpl_vars['thismonth']->value, null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['endcnt'] = new Smarty_variable(12, null, 0);?><?php }?>
    <?php $_smarty_tpl->tpl_vars['cnt'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['cnt']->step = 1;$_smarty_tpl->tpl_vars['cnt']->total = (int) ceil(($_smarty_tpl->tpl_vars['cnt']->step > 0 ? $_smarty_tpl->tpl_vars['endcnt']->value+1 - (1) : 1-($_smarty_tpl->tpl_vars['endcnt']->value)+1)/abs($_smarty_tpl->tpl_vars['cnt']->step));
if ($_smarty_tpl->tpl_vars['cnt']->total > 0) {
for ($_smarty_tpl->tpl_vars['cnt']->value = 1, $_smarty_tpl->tpl_vars['cnt']->iteration = 1;$_smarty_tpl->tpl_vars['cnt']->iteration <= $_smarty_tpl->tpl_vars['cnt']->total;$_smarty_tpl->tpl_vars['cnt']->value += $_smarty_tpl->tpl_vars['cnt']->step, $_smarty_tpl->tpl_vars['cnt']->iteration++) {
$_smarty_tpl->tpl_vars['cnt']->first = $_smarty_tpl->tpl_vars['cnt']->iteration == 1;$_smarty_tpl->tpl_vars['cnt']->last = $_smarty_tpl->tpl_vars['cnt']->iteration == $_smarty_tpl->tpl_vars['cnt']->total;?>
        <img src="creategraph.php?type=supply&amp;period=day&year=<?php echo $_smarty_tpl->tpl_vars['year']->value;?>
&month=<?php echo $_smarty_tpl->tpl_vars['cnt']->value;?>
&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/>
        <?php if (!(1 & $_smarty_tpl->tpl_vars['cnt']->value)) {?><br/><?php }?>
    <?php }} ?>
    <?php if ((1 & $_smarty_tpl->tpl_vars['endcnt']->value)) {?><img src="creategraph.php?type=blank&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt=""/><?php }?>
    <?php } else { ?>
        <img class="buttonlike" src="creategraph.php?type=spots_details&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt="" onclick="javascript:select_tab_stats('supply', 'spots_details');"/>
        <img class="buttonlike" src="creategraph.php?type=supply&amp;period=year&amp;width=<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" alt="" onclick="javascript:select_tab_stats('supply', 'supply', null, 'month');"/><br/>
    <?php }?>
<?php }?>
<?php }} ?>