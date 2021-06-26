<?php /* Smarty version Smarty-3.1.7, created on 2016-08-25 11:34:58
         compiled from "/var/www/html/bs4/view/startup/expired.html" */ ?>
<?php /*%%SmartyHeaderCode:64942391357be6762012252-31034901%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a13703149348c62a93ee9bc651ff7ec73d212cb6' => 
    array (
      0 => '/var/www/html/bs4/view/startup/expired.html',
      1 => 1471795654,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '64942391357be6762012252-31034901',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'header_html' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_57be676214515',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57be676214515')) {function content_57be676214515($_smarty_tpl) {?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
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
		<?php echo @LANG_MESSAGE_EXPIRED_MSG;?>

	</div>
	<div id="info">
		<?php echo @LANG_MESSAGE_COMPANY_INFO;?>

	</div>
</div>

  </div>
</body>
</html>
<?php }} ?>