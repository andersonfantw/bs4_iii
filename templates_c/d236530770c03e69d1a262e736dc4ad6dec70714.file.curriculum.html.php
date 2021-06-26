<?php /* Smarty version Smarty-3.1.7, created on 2016-09-07 17:36:17
         compiled from "/var/www/html/bs4/view/page/curriculum.html" */ ?>
<?php /*%%SmartyHeaderCode:197315050057cfdf91a992c2-56090445%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd236530770c03e69d1a262e736dc4ad6dec70714' => 
    array (
      0 => '/var/www/html/bs4/view/page/curriculum.html',
      1 => 1471795653,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '197315050057cfdf91a992c2-56090445',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'bsid' => 0,
    'uid' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_57cfdf91aeb39',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57cfdf91aeb39')) {function content_57cfdf91aeb39($_smarty_tpl) {?><html>
	<head>
		<title></title>
		<script src="<?php echo @WEB_URL;?>
/scripts/jquery-1.11.2.min.js" type="text/javascript"></script>
		<script src="<?php echo @WEB_URL;?>
/scripts/loader.class.js" type="text/javascript"></script>
		<script src="<?php echo @WEB_URL;?>
/scripts/loader_curriculum.js" type="text/javascript"></script>
		<script src="<?php echo @WEB_URL;?>
/plugin/meeting/scripts/loader_user.js" type="text/javascript"></script>
		<link type="text/css" href="<?php echo @WEB_URL;?>
/images/desktop/style.css" rel="stylesheet" />
<script type="text/javascript">
var bs_id = <?php echo $_smarty_tpl->tpl_vars['bsid']->value;?>
;
var uid = <?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
;
var web_url = '<?php echo @WEB_URL;?>
';
</script>
	</head>
	<body>
		<div id="main-content">
			<h1>School Timetable</h1>
			<div id="app">
				<a target="_blank" id="icon_vcube5">Download</a>
			</div>
			<div id="curriculum"></div>
		</div>
	</body>
</html><?php }} ?>