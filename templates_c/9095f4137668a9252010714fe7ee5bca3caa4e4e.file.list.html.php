<?php /* Smarty version Smarty-3.1.7, created on 2016-10-21 02:35:39
         compiled from "/var/www/html/bs4/view/list/list.html" */ ?>
<?php /*%%SmartyHeaderCode:163788812457be676197a164-72336750%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9095f4137668a9252010714fe7ee5bca3caa4e4e' => 
    array (
      0 => '/var/www/html/bs4/view/list/list.html',
      1 => 1476916896,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '163788812457be676197a164-72336750',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_57be6761cf7ab',
  'variables' => 
  array (
    'mode' => 0,
    'uid' => 0,
    'buid' => 0,
    'data' => 0,
    'val' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57be6761cf7ab')) {function content_57be6761cf7ab($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/Storage/var/www/html/bs4/libs/Smarty/libs/plugins/modifier.replace.php';
?><html lang="zh-Hant-TW" <?php if ((@DESKTOP_CACHE&&browser::detect('ua_type')!='modile')||(@MOBILE_CACHE&&browser::detect('ua_type')=='modile')){?>manifest="/desktop.appcache"<?php }?>>
<head>
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
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