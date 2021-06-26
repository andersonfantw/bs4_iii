<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><{$smarty.const.LANG_TITLE}></title>
	<link href="css/style.css" rel="stylesheet" media="all" />
        <script type="text/javascript" src="../scripts/jquery-1.11.2.min.js"></script>
        <script type="text/javascript" src="../scripts/jquery-ui-1.11.4.custom.min.js"></script>
	<script type="text/javascript" src="js/superfish.js"></script>
	<script type="text/javascript" src="js/tooltip.js"></script>
	<script type="text/javascript" src="js/tablesorter.js"></script>
	<script type="text/javascript" src="js/tablesorter-pager.js"></script>
	<script type="text/javascript" src="js/cookie.js"></script>
	<script type="text/javascript" src="js/custom.js"></script>
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
	<{block name="head"}><{/block}>
</head>
<body>
	<div id="header">
		<div id="top-menu">
			<a href="index.php?op=logout" title="<{$smarty.const.LANG_BUTTON_LOGOUT}>"><{$smarty.const.LANG_BUTTON_LOGOUT}></a>
		</div>
		<div id="sitename">
			<a href="index.php" class="logo float-left" title="<{$smarty.const.LANG_TITLE}>"><{$smarty.const.LANG_TITLE}></a>			
		</div>
<!--
		<{if $smarty.const.MEMBER_SYSTEM=='self'}>
		<ul id="navigation" class="sf-navbar">
			<li>
				<a href="account.php"><{$smarty.const.LANG_ACCOUNT}></a>
			</li>
		</ul>
		<{/if}>
-->
	</div>	
  <div id="page-wrapper" class="fixed">
    <div id="main-wrapper">
    <{block name="content"}><{/block}>
    </div>
  </div>
  <div class="clearfix"></div>
	<!-- 錯誤訊息 Start -->
            <div id="dialog" title="系統訊息">
	            <p></p>
	        </div>
            <!-- 錯誤訊息 End -->

</body>
</html>
