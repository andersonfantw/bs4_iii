<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=10" />
	<!--[if IE]>
	<script src="js/html5.js"></script>
	<![endif]-->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><{$smarty.const.LANG_TITLE}></title>
	<link href="css/style.css" rel="stylesheet" media="all" />
	<link href="/css/jquery-ui-1.11.4.custom.min.css" rel="stylesheet" title="style" media="all">
  <script type="text/javascript" src="../scripts/jquery-1.11.2.min.js"></script>
  <script type="text/javascript" src="../scripts/jquery-ui-1.11.4.custom.min.js"></script>
	<script type="text/javascript" src="../scripts/loader.class.js"></script>
	<script type="text/javascript" src="../scripts/loader_empty.js"></script>
	<script type="text/javascript" src="js/superfish.js"></script>
	<script type="text/javascript" src="js/tooltip.js"></script>
	<script type="text/javascript" src="js/tablesorter.js"></script>
	<script type="text/javascript" src="js/tablesorter-pager.js"></script>
	<script type="text/javascript" src="js/cookie.js"></script>
	<script type="text/javascript" src="js/custom.js"></script>
	<script type="text/javascript" src="js/lang.js"></script>
	<script type="text/javascript" src="../languages/backend/<{common::getcookie('currentlang')}>.js"></script>
	<!--[if IE 6]>
	<link href="css/ie6.css" rel="stylesheet" media="all" />
	
	<script src="js/pngfix.js"></script>
	<script>
	  /* EXAMPLE */
	  DD_belatedPNG.fix('.logo, .other ul#dashboard-buttons li a');

	</script>
	<![endif]-->
	<!--[if IE 7]>
	<link href="css/ie7.css" rel="stylesheet" media="all" />
	<![endif]-->
	<script>
		var bs_id = <{common::getcookie('bs')}>;
		var uid = <{common::getcookie('adminid')}>;
		var web_url = '<{$smarty.const.WEB_URL}>';
	</script>
	<{block name="head"}><{/block}>
</head>
<body>
	<div id="header">
		<div id="top-menu">
			<select id="lang" onchange="selectlanguage(this)"></select>
			<a href="index.php?op=logout" title="<{$smarty.const.LANG_BUTTON_LOGOUT}>"><{$smarty.const.LANG_BUTTON_LOGOUT}></a>
		</div>
		<div id="sitename">
			<a href="../index.php" target="bookshelf" class="logo float-left" title="<{$smarty.const.LANG_TITLE}>"><{$smarty.const.LANG_TITLE}> | <{if $bsname}><{$bsname}><{else}><{common::getcookie('bsname')}><{/if}></a>
		</div>
		<ul id="navigation" class="sf-navbar">			
      <li>
        <a href="/<{$smarty.session.adminacc|replace:$smarty.const.LDAP_DOMAIN_PREFIX:''}>/<{common::getcookie('bs')}>/" target="_web"><{$smarty.const.LANG_CONST_SHOW_WEBSITE}></a>
      </li>
      <!-- deliver bookshelf -->
      <!--
      <li>
        <a href="../?type=publishinghouse" target="_web"><{$smarty.const.LANG_CONST_SHOW_DELIVER}></a>
      </li>
      -->
			<{if $smarty.const.MEMBER=="1"}>
      <li>
        <a href="group.php"><{$smarty.const.LANG_GROUP}></a>
      </li>
			<{/if}>
			<li>
				<a href="category.php"><{$smarty.const.LANG_CATE}></a>
				<ul>
					<li>
						<a href="category.php"><{$smarty.const.LANG_CATE_LIST}></a>
					</li>
					<li>
						<a href="category.php?type=add"><{$smarty.const.LANG_CATE_BTN_ADD}></a>
					</li>									
				</ul>
			</li>
			<li>
				<a href="book.php"><{$smarty.const.LANG_BOOKS}></a>
				<ul>
					<li>
						<a href="book.php"><{$smarty.const.LANG_BOOKS_LIST}></a>
					</li>
					<li>
						<a href="book.php?type=add"><{$smarty.const.LANG_BOOKS_BTN_ADD}></a>
					</li>
				</ul>
			</li>
<!--
			<li>
				<a href="shortcut.php"><{$smarty.const.LANG_SHORTCUT}></a>
				<ul>
					<li>
						<a href="shortcut.php"><{$smarty.const.LANG_SHORTCUT_LIST}></a>
					</li>
					<li>
						<a href="shortcut.php?type=add"><{$smarty.const.LANG_SHORTCUT_BTN_ADD}></a>
					</li>
				</ul>
			</li>
			<li>
				<a href="class.php"><{$smarty.const.LANG_CLASS}></a>
				<ul>
					<li>
						<a href="class.php"><{$smarty.const.LANG_CLASS}></a>
					</li>
					<li>
						<a href="seminar.php"><{$smarty.const.LANG_SEMINAR}></a>
					</li>
					<li>
						<a href="allexam.php"><{$smarty.const.LANG_ALLEXAM}></a>
					</li>
				</ul>
			</li>
-->
<{*
      <{if $smarty.const.CONNECT_ECOCAT}>
      <li>
        <a href="ecocat.php"><{$smarty.const.LANG_UPDATE}></a>
      </li>
      <{/if}>
*}>
			<{if $smarty.const.MEMBER && LicenseManager::chkAuth($smarty.const.MEMBER_SYSTEM,MemberSystemEnum::Regist)}>
      <li>
        <a href="activecode.php"><{$smarty.const.LANG_ACTIVECODE}></a>
      </li>
			<{/if}>
      <li>
        <a href="setup.php"><{$smarty.const.LANG_SETUP}></a>
      </li>
<{*
      <li>
        <a href="wizard.php">初始化設定精靈</a>
      </li>
			<li>
				<a href="http://cloudbook.cyberhood.net/cloudbook/licensebuy.php?service_id=<{$smarty.const.wonderbox_id}>" target="buylicense">License</a>
			</li>
*}>
		</ul>
	</div>	
  <div id="page-wrapper" class="fixed">
    <div id="main-wrapper">
    <{block name="content"}><{/block}>
    </div>
    <{*include file="backend/sidebar.tpl"*}>
  </div>
  <div class="clearfix"></div>
  <{*
  <div id="footer">
		<div id="menu">
			<a href="#" title="Home">Home</a>
			<a href="#" title="Administration">Administration</a>
			<a href="#" title="Settings">Settings</a>
			<a href="#" title="Contact">Contact</a>
		</div>
	</div>*}>
	<!-- 錯誤訊息 Start -->
  <div id="dialog" title="系統訊息">
    <p></p>
  </div>
  <!-- 錯誤訊息 End -->
</body>
</html>
