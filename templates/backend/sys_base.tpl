<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><{$smarty.const.LANG_TITLE}></title>
	<link href="css/sys_style.css" rel="stylesheet" media="all" />
	<link href="" rel="stylesheet" title="style" media="all" />
	<link href="/css/jquery-ui-1.11.4.custom.min.css" rel="stylesheet" title="style" media="all">
  <script type="text/javascript" src="../scripts/jquery-2.0.3.min.js"></script>
  <script type="text/javascript" src="../scripts/jquery-ui-1.11.4.custom.min.js"></script>
	<script type="text/javascript" src="../scripts/loader.class.js"></script>
	<script type="text/javascript" src="../scripts/loader_empty.js"></script>

	<script type="text/javascript" src="js/superfish.js"></script>
<{*
	<script type="text/javascript" src="js/tooltip.js"></script>
*}>
	<script type="text/javascript" src="js/tablesorter.js"></script>
	<script type="text/javascript" src="js/tablesorter-pager.js"></script>
	<script type="text/javascript" src="js/cookie.js"></script>
	<script type="text/javascript" src="js/custom.js"></script>
	<script type="text/javascript" src="js/lang.js"></script>
	<script type="text/javascript" src="../scripts/jquery.canvasjs.min.js"></script>
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
		var web_url = '<{$smarty.const.WEB_URL}>';
		var uid = 0;
		var bs_id = 0;
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
			<a href="sys_index.php" class="logo float-left" title="<{$smarty.const.LANG_TITLE}> | <{$smarty.const.LANG_TITLE_DEALER}>"><{$smarty.const.LANG_TITLE}> | <{$smarty.const.LANG_TITLE_DEALER}></a>			
		</div>
		
		<ul id="navigation" class="sf-navbar">
			<{if !LicenseManager::chkAuth($smarty.const.MEMBER_SYSTEM,MemberSystemEnum::NAS_LDAP)}>
			<li>
				<a href="sys_system_account.php"><{$smarty.const.LANG_ADMIN}></a>
			</li>
			<li>
				<a href="sys_account.php"><{$smarty.const.LANG_SYSACCOUNT}></a>
			</li>
			<{/if}>
			<{if LicenseManager::chkAuth($smarty.const.MEMBER_MODE,MemberModeEnum::CENTRALIZE) || LicenseManager::chkAuth($smarty.const.MEMBER_MODE,MemberModeEnum::CENTRALIZE_ASSIGN)}>
			<li>
				<a href="sys_group.php"><{$smarty.const.LANG_GROUP}></a>
			</li>
			<{/if}>
			<li>
				<a href="sys_bookshelf.php"><{$smarty.const.LANG_BOOKSHELFS}></a>
			</li>
			<li>
				<a href="sys_tag.php"><{$smarty.const.LANG_TAG_SYSTEMTAG_AND_CONVERTING}></a>
				<ul>
					<li>
						<a href="sys_tag.php"><{$smarty.const.LANG_TAG_SET_SYSTEM_TAG}></a>
					</li>
					<li>
						<a href="sys_tagevolve.php"><{$smarty.const.LANG_TAGEVOLVE}></a>
					</li>
					<li>
						<a target="tagmap" href="../plugin/tag/tagmap.html"><{$smarty.const.LANG_TAGMAP}></a>
					</li>
					<li>
						<a href="sys_queue.php"><{$smarty.const.LANG_UPLOADQUEUE}></a>
					</li>
					<li>
						<a href="sys_queue_err.php"><{$smarty.const.LANG_UPLOADQUEUE_ERRLIST}></a>
					</li>
				</ul>
			</li>
			<li>
				<a href="sys_synonyms.php"><{$smarty.const.LANG_FULLTEXT}></a>
				<ul>
					<li>
						<a href="sys_synonyms.php"><{$smarty.const.LANG_FULLTEXT_SYNONYMS}></a>
					</li>
<!--
					<li>
						<a href="sys_blacklist.php"><{$smarty.const.LANG_FULLTEXT_BLACKLIST}></a>
					</li>
					<li>
						<a href="sys_fulltextchart.php"><{$smarty.const.LANG_FULLTEXT_CHART}></a>
					</li>
-->
				</ul>
			</li>
<!--
			<li>
				<{if $smarty.const.VCubeAPIBase=='' && $smarty.const.VCubeAPIBase=='' }>
					<a href="sys_allexam.php"><{$smarty.const.LANG_CLASS}></a>
				<{elseif $smarty.const.VCubeAPIBase!='' || $smarty.const.ZoomAPIBase!='' || $smarty.const.ZoomMeetingID==true}>
					<a href="sys_class.php"><{$smarty.const.LANG_CLASS}></a>
				<{else}>
					<a href="sys_seminar.php"><{$smarty.const.LANG_CLASS}></a>
				<{/if}>
				<ul>
					<{if $smarty.const.VCubeSeminarAPIBase!='' || $smarty.const.ZoomAPIBase!='' || $smarty.const.ZoomMeetingID=='1'}>
					<li>
						<a href="sys_class.php"><{$smarty.const.LANG_VCUBE}></a>
					</li>
					<{/if}>
					<{if $smarty.const.VCubeSeminarAPIBase!='' }>
					<li>
						<a href="sys_seminar.php"><{$smarty.const.LANG_SEMINAR}></a>
					</li>
					<{/if}>
					<li>
						<a href="sys_allexam.php"><{$smarty.const.LANG_ALLEXAM}></a>
					</li>
				</ul>
			</li>
			<li>
				<a href="sys_querychart.php"><{$smarty.const.LANG_QUERYCHART}></a>
				<ul>
					<li>
						<a href="sys_querychart.php"><{$smarty.const.LANG_QUERYCHART}></a>
					</li>
					<li>
						<a href="sys_piwik.php"><{$smarty.const.LANG_ANALYZE}></a>
					</li>
				</ul>
			</li>
-->
			<{if $smarty.const.REFLECTION_GAME }>
			<li>
				<a href="sys_game.php"><{$smarty.const.LANG_REFLECTIONGAME}></a>
			</li>
			<{/if}>
			<li>
				<a href="sys_config.php"><{$smarty.const.LANG_SETUP}></a>
			</li>
			<{if common::getcookie('sysuser')=='admin'}>
			<li>
				<a href="sys_testcase.php"><{$smarty.const.LANG_TESTCASE}></a>
			</li>
			<{/if}>
			<{if $smarty.const.ENABLE_SHARE}>
			<li>
				<a href="sys_bookshelf_share.php"><{$smarty.const.LANG_SHARE}></a>
			</li>
			<{/if}>
		</ul>		
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
