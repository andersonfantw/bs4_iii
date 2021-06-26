<?php /* Smarty version Smarty-3.1.7, created on 2017-11-08 20:49:22
         compiled from "/var/www/html/bs4/view/page/logout.html" */ ?>
<?php /*%%SmartyHeaderCode:50645828659cafe7a4212c8-07010971%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'db7a9ad2d4a075b1ff1922119609f38e5c867979' => 
    array (
      0 => '/var/www/html/bs4/view/page/logout.html',
      1 => 1510145359,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '50645828659cafe7a4212c8-07010971',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_59cafe7a44f13',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_59cafe7a44f13')) {function content_59cafe7a44f13($_smarty_tpl) {?>﻿<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="zh-Hant-TW">
<head>
<title><?php echo @TITLE;?>
</title>
<meta name="viewport" content="width=device-width" />
<style>
html{
	height: 100%;
}
body{
  padding: 0px;
  margin: 0px;
  background: url(../images/ebooksearch_bg.png);
	background-repeat: no-repeat;
  background-attachment: fixed;
  background-position: center;
  background-size: cover;
}
#body .content{
	background-color: #fff;
  text-align: center;
  margin: 200px auto;
  width: 500px;
  border: solid 5px #666;
}
</style>
</head>
<body>
  <div id="body" class="mode_bookshelf">
		<div class="content">
			<br /><br /><br /><br /><br /><br /><br /><br />
			您的帳號已經登出，請重新登入。<br />
			若是您一直被登出，請您清除cookies後，再重新登入。
			<br /><br /><br /><br /><br /><br /><br /><br />
		</div>
  </div>
</body>
</html>
<?php }} ?>