<?php /* Smarty version Smarty-3.1.7, created on 2020-09-16 05:16:23
         compiled from "/var/www/html/bs4/view/search/logout.html" */ ?>
<?php /*%%SmartyHeaderCode:3286398075f5b2426701da8-10329491%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '801458f9ded5ef0f61e621ced1383c312f681ba0' => 
    array (
      0 => '/var/www/html/bs4/view/search/logout.html',
      1 => 1600204555,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3286398075f5b2426701da8-10329491',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5f5b2426758ac',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5f5b2426758ac')) {function content_5f5b2426758ac($_smarty_tpl) {?><!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title><?php echo @TITLE;?>
</title>
		<meta http-equiv="cache-control" content="max-age=0" />
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
		<meta http-equiv="pragma" content="no-cache" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@100;300;400;500;700;900&amp;display=swap">
    <link rel="stylesheet" href="<?php echo @WEB_URL;?>
/plugin/search/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo @WEB_URL;?>
/plugin/search/css/Google-Style-Login.css">
    <link rel="stylesheet" href="<?php echo @WEB_URL;?>
/plugin/search/css/common.css">
    <link rel="stylesheet" href="<?php echo @WEB_URL;?>
/plugin/search/css/logout.css">
<script type="text/javascript">
var bs_id = 0;
var uid = 0;
var web_url = '<?php echo @WEB_URL;?>
';
</script>
</head>

<body class="logout">
    <div class="login-card"><img src="/plugin/search/images/avatar_2x.png" class="profile-img-card">
        <p class="profile-name-card"> </p>
        <form class="form-signin">
        	<span class="reauth-email"> </span>
          <div class="checkbox">
          	<span>您的帳號已經登出，請重新登入</span>
          </div>
          <button class="btn btn-primary btn-block btn-lg btn-signin" type="submit">離 &nbsp; &nbsp;開</button>
				</form>
    </div>
</body>
</html>
<?php }} ?>