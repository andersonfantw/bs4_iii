<?php /* Smarty version Smarty-3.1.7, created on 2017-11-02 11:29:45
         compiled from "/var/www/html/bs4/view/desktop/desktop.html" */ ?>
<?php /*%%SmartyHeaderCode:195910598757ce491e4e1de2-09393200%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '51ffbad067ef292982dd24b8119cd6d9df7cd898' => 
    array (
      0 => '/var/www/html/bs4/view/desktop/desktop.html',
      1 => 1509593334,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '195910598757ce491e4e1de2-09393200',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_57ce491e62bab',
  'variables' => 
  array (
    'uid' => 0,
    'bsid' => 0,
    'header_html' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57ce491e62bab')) {function content_57ce491e62bab($_smarty_tpl) {?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="zh-Hant-TW" <?php if ((@DESKTOP_CACHE&&browser::detect('ua_type')!='modile')||(@MOBILE_CACHE&&browser::detect('ua_type')=='modile')){?>manifest="/desktop.appcache"<?php }?>>
<head>
<title><?php echo @TITLE;?>
</title>
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />

<link type="text/css" href="<?php echo @WEB_URL;?>
/images/desktop/style.css" rel="stylesheet" />
<link type="text/css" href="<?php echo @WEB_URL;?>
/hosts/config.css" rel="stylesheet" />
<link type="text/css" href="<?php echo @WEB_URL;?>
/hosts/<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['bsid']->value;?>
/config.css" rel="stylesheet" />
<script src="<?php echo @WEB_URL;?>
/scripts/jquery-1.11.2.min.js" type="text/javascript"></script>
<?php if (@ENABLE_GIANTVIEW){?>
<script id="webChatApi" src="<?php echo @GiantviewURL;?>
/js/web-chat-api.js" type="text/javascript"></script>
<?php }?>
<script src="<?php echo @WEB_URL;?>
/desktop.init.js" type="text/javascript"></script>
<script src="<?php echo @WEB_URL;?>
/scripts/loader.class.js" type="text/javascript"></script>
<script src="<?php echo @WEB_URL;?>
/scripts/loader.js" type="text/javascript"></script>

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
		<?php echo $_smarty_tpl->tpl_vars['header_html']->value;?>

    <div id="body" class="mode_bookshelf">
        <div id="login" class="popup">
					<h1>登入</h1>
					<h2>Login</h2>
					<h3>
						<span>帳號</span> : <input type="text" /><br />
						<span>密碼</span> : <input type="password" />
						<input type="hidden" />
					</h3>
					<div class="button"><div>確認送出</div></div>
        </div>
        <div id="book_content">
            <div class="booktitle"><span></span><div class="close"></div></div>
            <div class="bookimg"><img src="" /></div>
            <div class="book-content">
             <div class="bookcontent"></div>
             <div id="slidebox-common"></div>
            </div>
            <div class="button">
            	<div class="ibook"><a>Download</a></div>
            	<div class="webbook"><a>Open</a></div>
            	<?php if (@ENABLE_GIANTVIEW&&@GIANTVIEW_CHAT&&$_SESSION['adminid']){?>
            		<div class="send"><a>Send</a></div>
            	<?php }?>
            </div>
            <input type="hidden" />
        </div>
        <div id="dialogue_bg"></div>
        
        <div id="header">
        	<div class="banner"></div>
        </div>

				<div id="content-left">
			        	<div id="menu" class="init"><img src="<?php echo @WEB_URL;?>
/images/desktop/add_item.png" /></div>
				</div>
				<div id="button">
					<div class="lang">
						<span>語系</span>
						<select></select>
					</div>
					<div id="buttonlogin">
						<div class="btnlogin">
							<div></div>
							<span>登入</span>
						</div>
						<div class="btnlogout">
<?php if (@CONFIG_MYBOOKSHELF&&@ENABLE_MYBOOKSHELF){?>
							<div id="buttonbs" class="open">
								<div class="btnopenbs">
									<div></div>
									<span>公開的書櫃</span>
								</div>
								<div class="btnmybs">
									<div></div>
									<span>我的書櫃</span>
								</div>
							</div>
<?php }?>
		          <div class="transcript" onclick="window.open('transcript/')">
		            <div></div>
		            <span>成績單</span>
		          </div>
		          <div class="curriculum" onclick="window.open('curriculum/')">
		            <div></div>
		            <span>課表</span>
		          </div>

							<?php if (@CLOUDCONVERT_STATUS=='1'){?>

							<?php }?>
							<div class="logout">
								<div></div>
								<span>登出</span>
							</div>
						</div>
					</div>
					<div class="search">
						<input type="text" />
					</div>
					<div class="result_info">
						查詢結果共<span>0</span>筆資料
					</div>
				</div>

        <div id="bookshelf">
					<ul class="top"></ul>
					<ul class="mid"></ul>
					<ul class="mid"></ul>
					<ul class="mid"></ul>
				</div>

        <div id="searchbook">
            <ul></ul>
        </div>

        <div id="footer">
        	<?php echo @FOOTER_TEXT;?>

<!--
            台北東京影像資訊有限公司 TaipeiTokyo Image & Information Co., Ltd. <br />
            地址：新北市三重區重新路五段609巷16號2F之9 &nbsp;&nbsp; 電話：(02)2999-1815<br />
            Copyright c 2012 台北東京影像資訊有限公司. All Rights Reserved.
-->
        </div>
    </div>

</body>

<ul id="TemplateFrame">
    <li>
        <ul class="cover">
            <li><h3></h3><div><div></div><img src="" style="z-index:1000" /><span></span></div><input type="hidden" /></li>
        </ul>
        <ul class="btn">
            <li class="webbook"><a>Open</a></li>
            <li>|</li>
            <li class="ibook"><a>Downlad</a></li>
        </ul>
    </li>
    <li>
        <div class="bookimg"><img src="" /></div>
        <div class="booktitle"></div>
        <div class="bookcontent"></div>
        <div class="button"><div>Read</div></div>
        <input type="hidden" />
    </li>
</ul>
</html>
<?php }} ?>