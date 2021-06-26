<?php /* Smarty version Smarty-3.1.7, created on 2016-08-26 09:47:49
         compiled from "/var/www/html/bs4/view/startup/startup.html" */ ?>
<?php /*%%SmartyHeaderCode:111271329757bf9fc5ed38f9-49724360%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b5e7e4b3d18b232c15e1d5dfcff0b2ec983312fa' => 
    array (
      0 => '/var/www/html/bs4/view/startup/startup.html',
      1 => 1471795654,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '111271329757bf9fc5ed38f9-49724360',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'header_html' => 0,
    'activedate' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_57bf9fc61420a',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57bf9fc61420a')) {function content_57bf9fc61420a($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/var/www/html/bs4/libs/Smarty/libs/plugins/modifier.replace.php';
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="zh-Hant-TW">
<head>
<title><?php echo @TITLE;?>
</title>
<meta name="viewport" content="width=device-width" />
<script src="<?php echo @WEB_URL;?>
/scripts/jquery-1.11.2.min.js" type="text/javascript"></script>
<script src="<?php echo @WEB_URL;?>
/scripts/loader.class.js" type="text/javascript"></script>
<script src="<?php echo @WEB_URL;?>
/scripts/loader.js" type="text/javascript"></script>

<link type="text/css" href="<?php echo @WEB_URL;?>
/css/style.css" rel="stylesheet" />
<link type="text/css" href="<?php echo @WEB_URL;?>
/css/startup.css" rel="stylesheet" />
<script type="text/javascript">
var bs_id=0;
var uid=0;
var web_url = '<?php echo @WEB_URL;?>
';
</script>
</head>
<body>
	<?php echo $_smarty_tpl->tpl_vars['header_html']->value;?>


  <div id="body">

<div id="container">
	<div id="button">
		<div class="lang">
			<select></select>
		</div>
	</div>
	<div id="msg">
		Active ID: <?php echo @wonderbox_id;?>

		<br />
		<?php echo smarty_modifier_replace(@LANG_MESSAGE_STARTUP_MSG1,'@date@',$_smarty_tpl->tpl_vars['activedate']->value);?>

		<form method="post" action="">
		<input type="submit" value="<?php echo @LANG_BUTTON_ACTIVE;?>
" />
		<input type="hidden" name="cmd" value="enabled" />
		</form>
		<?php echo @LANG_MESSAGE_STARTUP_MSG2;?>

	</div>
	<div id="info">
		<?php echo @LANG_MESSAGE_COMPANY_INFO;?>

	</div>
</div>

  </div>
</body>
</html>
<?php }} ?>