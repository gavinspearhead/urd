<?php /* Smarty version Smarty-3.1.14, created on 2013-08-27 22:33:40
         compiled from "/var/www/html/urd/branches/devel/html/smarty/templates/default/head.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14045186465200207572d615-19043056%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c5244ff0afdd87afd19d67af1acd7c456d23e64b' => 
    array (
      0 => '/var/www/html/urd/branches/devel/html/smarty/templates/default/head.tpl',
      1 => 1377635602,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14045186465200207572d615-19043056',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_520020758a6eb9_91203689',
  'variables' => 
  array (
    'title' => 0,
    'allow_robots' => 0,
    'CSSDIR' => 0,
    'stylesheet' => 0,
    'rssurl' => 0,
    'JSDIR' => 0,
    'LN_version' => 0,
    'VERSION' => 0,
    'menu' => 0,
    'menuitem' => 0,
    'itemlist' => 0,
    'first' => 0,
    'extra' => 0,
    'menuitems' => 0,
    'category' => 0,
    'add_class' => 0,
    'mainmenulink' => 0,
    'mainmenuname' => 0,
    'mainmenumessage' => 0,
    'LN_login_jserror' => 0,
    'LN_search' => 0,
    'challenge' => 0,
    'urdd_online' => 0,
    'offline_message' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_520020758a6eb9_91203689')) {function content_520020758a6eb9_91203689($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/var/www/html/urd/branches/devel/functions/libs/smarty/libs/plugins/modifier.replace.php';
?><!DOCTYPE html>
<html>
<head>
<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
<?php if (!$_smarty_tpl->tpl_vars['allow_robots']->value) {?>
<meta name="robots" content="noindex, nofollow"/>
<?php }?>

<link id="basic_css" rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['CSSDIR']->value;?>
/_basic.css" type="text/css"/>
<link id="urd_css" rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['CSSDIR']->value;?>
/<?php if ($_smarty_tpl->tpl_vars['stylesheet']->value!='') {?><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['stylesheet']->value,".css",'');?>
/<?php echo $_smarty_tpl->tpl_vars['stylesheet']->value;?>
.css<?php } else { ?>light/light.css<?php }?>" type="text/css"/>
<link id="jquery_css" rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['CSSDIR']->value;?>
/<?php if ($_smarty_tpl->tpl_vars['stylesheet']->value!='') {?><?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['stylesheet']->value,".css",'');?>
<?php } else { ?>/light<?php }?>/jquery-ui.css" type="text/css"/>
<!--[if IE]>
<link rel="stylesheet" id="iehacks_css" href="<?php echo $_smarty_tpl->tpl_vars['CSSDIR']->value;?>
/_iehacks.css" type="text/css"/>
<![endif]--> 
<link id="icon" rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
<?php if (isset($_smarty_tpl->tpl_vars['rssurl']->value)&&$_smarty_tpl->tpl_vars['rssurl']->value!='') {?>
<link rel="alternate" type="application/rss+xml" href="<?php echo $_smarty_tpl->tpl_vars['rssurl']->value;?>
" title="URD" /> 
<?php }?>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['JSDIR']->value;?>
/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['JSDIR']->value;?>
/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['JSDIR']->value;?>
/js.js"></script>
</head>
<?php echo smarty_function_urd_flush(array(),$_smarty_tpl);?>

<body onload="javascript:init();">
<div class="Message hidden" id="message_bar" onclick="javascript:hide_message('message_bar', 0);"></div>
<div id="scrollmenuright" class="buttonlike white" onclick="scroll_menu_right(event);">&gt;</div>
<div id="scrollmenuleft" class="buttonlike white" onclick="scroll_menu_left(event);">&lt;</div>
<div id="menu">
    <div id="pulldown_menu">
        <div id="pulldown" class="pulldown"> 
            <ul>
            <li class="smalllogo"><div id="smalllogo" class="buttonlike" onclick="javascript:jump('index.php');">&nbsp;</div>
            <div class="downm8"> 
            <ul><li class="plain pulldown_last_item"><div class="down3 centered2"><?php echo $_smarty_tpl->tpl_vars['LN_version']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['VERSION']->value;?>
</div></li></ul>
            </div>
            </li>
            <li class="smallstatus"><div id="smallstatus">&nbsp;</div></li>
            <li class="normal" onmouseover="javascript:load_activity_status();" id="status_item">
                <div id="status_msg" class="nooverflow"></div>
                <div class="downm8">
                <ul id="activity_status">
                    <li class="activity plain pulldown_last_item"></li>
                </ul>
                </div>
            </li>

<?php  $_smarty_tpl->tpl_vars['menuitem'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menuitem']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['menu']->value->get_items(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['menuitem']->key => $_smarty_tpl->tpl_vars['menuitem']->value) {
$_smarty_tpl->tpl_vars['menuitem']->_loop = true;
?>
<?php $_smarty_tpl->tpl_vars['itemlist'] = new Smarty_variable($_smarty_tpl->tpl_vars['menuitem']->value->get_items(), null, 0);?>
<?php $_smarty_tpl->tpl_vars['first'] = new Smarty_variable($_smarty_tpl->tpl_vars['itemlist']->value[0], null, 0);?>
<?php if ($_smarty_tpl->tpl_vars['menuitem']->value->get_category()=='') {?><?php $_smarty_tpl->tpl_vars['category'] = new Smarty_variable('plain', null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['category'] = new Smarty_variable($_smarty_tpl->tpl_vars['menuitem']->value->get_category(), null, 0);?><?php }?>
<?php if ($_smarty_tpl->tpl_vars['first']->value->get_link_type()=='command') {?><?php $_smarty_tpl->tpl_vars['extra'] = new Smarty_variable("commando", null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['extra'] = new Smarty_variable('', null, 0);?><?php }?>
<li class="normal <?php echo $_smarty_tpl->tpl_vars['extra']->value;?>
">
    <?php if ($_smarty_tpl->tpl_vars['menuitem']->value->get_count()>1) {?>
    <div <?php if ($_smarty_tpl->tpl_vars['first']->value->get_link_type()=='jump') {?> onclick="javascript:jump('<?php echo $_smarty_tpl->tpl_vars['first']->value->get_url();?>
');" class="nooverflow down3 buttonlike"<?php } else { ?> class="nooverflow down3" <?php }?>><?php echo $_smarty_tpl->tpl_vars['menuitem']->value->get_name();?>
</div>
        <ul>
		    <?php  $_smarty_tpl->tpl_vars['menuitems'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menuitems']->_loop = false;
 $_smarty_tpl->tpl_vars['link'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['menuitem']->value->get_items(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['menuitems']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['menuitems']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['menuitems']->key => $_smarty_tpl->tpl_vars['menuitems']->value) {
$_smarty_tpl->tpl_vars['menuitems']->_loop = true;
 $_smarty_tpl->tpl_vars['link']->value = $_smarty_tpl->tpl_vars['menuitems']->key;
 $_smarty_tpl->tpl_vars['menuitems']->iteration++;
 $_smarty_tpl->tpl_vars['menuitems']->last = $_smarty_tpl->tpl_vars['menuitems']->iteration === $_smarty_tpl->tpl_vars['menuitems']->total;
?>
                <?php $_smarty_tpl->tpl_vars['mainmenuname'] = new Smarty_variable($_smarty_tpl->tpl_vars['menuitems']->value->get_name(), null, 0);?>
                <?php $_smarty_tpl->tpl_vars['mainmenulink'] = new Smarty_variable($_smarty_tpl->tpl_vars['menuitems']->value->get_url(), null, 0);?>
                <?php $_smarty_tpl->tpl_vars['mainmenulinktype'] = new Smarty_variable($_smarty_tpl->tpl_vars['menuitems']->value->get_link_type(), null, 0);?>
                <?php $_smarty_tpl->tpl_vars['mainmenumessage'] = new Smarty_variable($_smarty_tpl->tpl_vars['menuitems']->value->get_message(), null, 0);?>
                <?php if ($_smarty_tpl->tpl_vars['menuitems']->last) {?><?php $_smarty_tpl->tpl_vars['add_class'] = new Smarty_variable("pulldown_last_item", null, 0);?><?php } else { ?><?php $_smarty_tpl->tpl_vars['add_class'] = new Smarty_variable('', null, 0);?><?php }?>
                <li 
                 <?php if ($_smarty_tpl->tpl_vars['menuitems']->value->get_link_type()=='jump') {?> class="buttonlike <?php echo $_smarty_tpl->tpl_vars['category']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['add_class']->value;?>
" onclick="javascript:jump('<?php echo $_smarty_tpl->tpl_vars['mainmenulink']->value;?>
');" 
                 <?php } elseif ($_smarty_tpl->tpl_vars['menuitems']->value->get_link_type()=='jumpext') {?> class="buttonlike <?php echo $_smarty_tpl->tpl_vars['category']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['add_class']->value;?>
" onclick="javascript:jump('<?php echo $_smarty_tpl->tpl_vars['mainmenulink']->value;?>
', true);" 
                 <?php } elseif ($_smarty_tpl->tpl_vars['menuitems']->value->get_link_type()=='command') {?> class="buttonlike <?php echo $_smarty_tpl->tpl_vars['category']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['add_class']->value;?>
" onclick="javascript:do_command('<?php echo $_smarty_tpl->tpl_vars['mainmenulink']->value;?>
', '<?php echo $_smarty_tpl->tpl_vars['mainmenuname']->value;?>
', '<?php echo $_smarty_tpl->tpl_vars['mainmenumessage']->value;?>
');"
                 <?php } else { ?> class="<?php echo $_smarty_tpl->tpl_vars['category']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['add_class']->value;?>
"  
                 <?php }?>>
                    <div class="nooverflow down3"><?php echo $_smarty_tpl->tpl_vars['mainmenuname']->value;?>
</div>
                </li>
			<?php } ?>
		</ul>
    <?php } else { ?>
    <div <?php if ($_smarty_tpl->tpl_vars['first']->value->get_link_type()=='jump') {?>onclick="javascript:jump('<?php echo $_smarty_tpl->tpl_vars['first']->value->get_url();?>
');" class="down3 nooverflow buttonlike" 
    <?php } elseif ($_smarty_tpl->tpl_vars['first']->value->get_link_type()=='command') {?> class="buttonlike down3 nooverflow <?php echo $_smarty_tpl->tpl_vars['category']->value;?>
" onclick="javascript:do_command('<?php echo $_smarty_tpl->tpl_vars['first']->value->get_url();?>
', '<?php echo strtr($_smarty_tpl->tpl_vars['first']->value->get_name(), array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
', '<?php echo strtr($_smarty_tpl->tpl_vars['first']->value->get_message(), array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
');"
    <?php } else { ?>class="down3 nooverflow" <?php }?>><?php echo $_smarty_tpl->tpl_vars['first']->value->get_name();?>
</div>
    <?php }?>
        </li>
<?php } ?>
            <li class="hidden normal" id="disk_li">
                <div id="status_disk" class="down3 centered"></div>   
            </li>
            </ul>
        </div>
    </div>
</div>


<div><div id="smallhelp" class="hidden"></div></div>

<div id="helpwrapper" class="hidden">
<div id="helptext" class="helptext">
<div id="helpheader"></div>
<div id="helpbody"></div>
</div>
</div>

<div id="overlay_back">
<div id="overlay_content"></div>
</div>

<div id="overlay_back2">
<div id="overlay_content2"></div>
</div>
<div id="topcontent" onmouseup="javascript:set_selected();">
<div id="quickmenu" class="quickmenuoff"></div>
<div id="quickwindow" class="quickwindowoff"></div>
<div id="contentout" onmouseover="javascript:CloseQuickMenu();">
<div id="content" class="down3">
<noscript><div class="centered" id="nojs"><?php echo $_smarty_tpl->tpl_vars['LN_login_jserror']->value;?>
</div></noscript>
<input type="hidden" id="search_str" value="&lt;<?php echo $_smarty_tpl->tpl_vars['LN_search']->value;?>
&gt;"/>
<input type="hidden" id="challenge" value="<?php echo $_smarty_tpl->tpl_vars['challenge']->value;?>
"/>
<input type="hidden" id="cssdir" value="<?php echo $_smarty_tpl->tpl_vars['CSSDIR']->value;?>
"/>
<input type="hidden" name="urdd_status" id="urdd_status" value="<?php echo $_smarty_tpl->tpl_vars['urdd_online']->value;?>
"/>
<input type="hidden" name="urdd_message" id="urdd_message" value="<?php echo $_smarty_tpl->tpl_vars['offline_message']->value;?>
"/>
<?php }} ?>