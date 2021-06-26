<?php /* Smarty version Smarty-3.1.7, created on 2016-08-22 00:18:25
         compiled from "/var/www/html/bs3/view/list/list.html" */ ?>
<?php /*%%SmartyHeaderCode:132141906657b9d451029c60-71108210%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '563707dd0ed14ede994cb80d315a7b902268f944' => 
    array (
      0 => '/var/www/html/bs3/view/list/list.html',
      1 => 1471795653,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '132141906657b9d451029c60-71108210',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'mode' => 0,
    'uid' => 0,
    'buid' => 0,
    'data' => 0,
    'val' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_57b9d4516e835',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57b9d4516e835')) {function content_57b9d4516e835($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/var/www/html/bs3/libs/Smarty/libs/plugins/modifier.replace.php';
?><html manifest="/desktop.appcache">
<head>
<link href="<?php echo @WEB_URL;?>
/css/style.css" rel="stylesheet" media="all">
<script src="<?php echo @WEB_URL;?>
/scripts/jquery-1.11.2.min.js" type="text/javascript"></script>
<script src="<?php echo @WEB_URL;?>
/desktop.init.js" type="text/javascript"></script>
<script src="<?php echo @WEB_URL;?>
/scripts/loader.class.js" type="text/javascript"></script>
<script src="<?php echo @WEB_URL;?>
/scripts/loader_<?php echo $_smarty_tpl->tpl_vars['mode']->value;?>
.js" type="text/javascript"></script>
<script type="text/javascript">
var bs_id = 0;
var uid = <?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
;
var buid = <?php echo $_smarty_tpl->tpl_vars['buid']->value;?>
;
var web_url = '<?php echo @WEB_URL;?>
';
</script>
</head>
<body>
<center>
<div id="main-content">
<div id="header" class="list">
	<div id="button">
		<div class="wonderboxid">ID: <?php echo @wonderbox_id;?>
</div>
		<?php if (!$_POST['token']){?>
			<div id="buttonlogin">
				<div class="btnlogin">
					<div></div>
					<span>µn¤J</span>
				</div>
				<div class="btnlogout">
					<div class="listlogout">
						<div></div>
						<span>µn¥X</span>
					</div>
				</div>
			</div>
		<?php }?>
	</div>
	<div class="right"><img src="<?php echo @WEB_URL;?>
/images/head.png" /></div>
</div>
<div id="notice"></div>
<div id="bslist"></div>
<!--
<?php  $_smarty_tpl->tpl_vars["val"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["val"]->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["val"]->key => $_smarty_tpl->tpl_vars["val"]->value){
$_smarty_tpl->tpl_vars["val"]->_loop = true;
?>
<div class="frame<?php if (($_smarty_tpl->tpl_vars['val']->value['bs_header_image']=='')||($_smarty_tpl->tpl_vars['val']->value['bs_header_height']==0)){?> nopic<?php }?>">
		<div class="top"><a name="bs<?php echo $_smarty_tpl->tpl_vars['val']->value['bs_id'];?>
" href="<?php echo @WEB_URL;?>
/<?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['val']->value['u_name'],@LDAP_DOMAIN_PREFIX,'');?>
/<?php echo $_smarty_tpl->tpl_vars['val']->value['bs_id'];?>
/" target="bookshelf"><h2><?php echo $_smarty_tpl->tpl_vars['val']->value['bs_name'];?>
 | <?php echo $_smarty_tpl->tpl_vars['val']->value['u_cname'];?>
</h2></a></div>
		<div class="mid">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td class="borderleft"></td>
					<td><a name="bs<?php echo $_smarty_tpl->tpl_vars['val']->value['bs_id'];?>
" href="<?php echo @WEB_URL;?>
/<?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['val']->value['u_name'],@LDAP_DOMAIN_PREFIX,'');?>
/<?php echo $_smarty_tpl->tpl_vars['val']->value['bs_id'];?>
/" target="bookshelf"><img src="/hosts/<?php echo $_smarty_tpl->tpl_vars['val']->value['u_id'];?>
/<?php echo $_smarty_tpl->tpl_vars['val']->value['bs_id'];?>
/<?php echo $_smarty_tpl->tpl_vars['val']->value['bs_header_image'];?>
" border="0" /></a></td>
					<td class="borderright"></td>
				</tr>
			</table>
		</div>
		<div class="bottom"></div>
		<div class="info"></div>
	</div>
	<?php } ?>
-->
</div>
</center>
</body>
</html>
<?php }} ?>