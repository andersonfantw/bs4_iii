<?php /* Smarty version Smarty-3.1.7, created on 2020-09-24 04:45:15
         compiled from "/var/www/html/bs4/templates/backend/sys_tagevolve_edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:77580160759fd43db09f943-27062419%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5b2469e9a7cc3c7fd10d4369e4d18fc6b201432e' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/sys_tagevolve_edit.tpl',
      1 => 1574387571,
      2 => 'file',
    ),
    '9084960af51179b5136906150f6316f62d06a157' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/sys_base.tpl',
      1 => 1599899037,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '77580160759fd43db09f943-27062419',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_59fd43db186e3',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_59fd43db186e3')) {function content_59fd43db186e3($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo @LANG_TITLE;?>
</title>
	<link href="css/sys_style.css" rel="stylesheet" media="all" />
	<link href="" rel="stylesheet" title="style" media="all" />
	<link href="/css/jquery-ui-1.11.4.custom.min.css" rel="stylesheet" title="style" media="all">
  <script type="text/javascript" src="../scripts/jquery-2.0.3.min.js"></script>
  <script type="text/javascript" src="../scripts/jquery-ui-1.11.4.custom.min.js"></script>
	<script type="text/javascript" src="../scripts/loader.class.js"></script>
	<script type="text/javascript" src="../scripts/loader_empty.js"></script>

	<script type="text/javascript" src="js/superfish.js"></script>

	<script type="text/javascript" src="js/tablesorter.js"></script>
	<script type="text/javascript" src="js/tablesorter-pager.js"></script>
	<script type="text/javascript" src="js/cookie.js"></script>
	<script type="text/javascript" src="js/custom.js"></script>
	<script type="text/javascript" src="js/lang.js"></script>
	<script type="text/javascript" src="../scripts/jquery.canvasjs.min.js"></script>
	<script type="text/javascript" src="../languages/backend/<?php echo common::getcookie('currentlang');?>
.js"></script>
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
		var web_url = '<?php echo @WEB_URL;?>
';
		var uid = 0;
		var bs_id = 0;
	</script>
	
<link href="css/customize/sys_tagevolve.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="../scripts/loader.class.js"></script>
<script type="text/javascript" src="/plugin/tag/scripts/loader_tagevolve.js"></script>
<script type="text/javascript" src="js/validation/sys_tagevolve.js"></script>

</head>
<body>
	<div id="header">
		<div id="top-menu">
			<select id="lang" onchange="selectlanguage(this)"></select>
			<a href="index.php?op=logout" title="<?php echo @LANG_BUTTON_LOGOUT;?>
"><?php echo @LANG_BUTTON_LOGOUT;?>
</a>
		</div>
		<div id="sitename">
			<a href="sys_index.php" class="logo float-left" title="<?php echo @LANG_TITLE;?>
 | <?php echo @LANG_TITLE_DEALER;?>
"><?php echo @LANG_TITLE;?>
 | <?php echo @LANG_TITLE_DEALER;?>
</a>			
		</div>
		
		<ul id="navigation" class="sf-navbar">
			<?php if (!LicenseManager::chkAuth(@MEMBER_SYSTEM,MemberSystemEnum::NAS_LDAP)){?>
			<li>
				<a href="sys_system_account.php"><?php echo @LANG_ADMIN;?>
</a>
			</li>
			<li>
				<a href="sys_account.php"><?php echo @LANG_SYSACCOUNT;?>
</a>
			</li>
			<?php }?>
			<?php if (LicenseManager::chkAuth(@MEMBER_MODE,MemberModeEnum::CENTRALIZE)||LicenseManager::chkAuth(@MEMBER_MODE,MemberModeEnum::CENTRALIZE_ASSIGN)){?>
			<li>
				<a href="sys_group.php"><?php echo @LANG_GROUP;?>
</a>
			</li>
			<?php }?>
			<li>
				<a href="sys_bookshelf.php"><?php echo @LANG_BOOKSHELFS;?>
</a>
			</li>
			<li>
				<a href="sys_tag.php"><?php echo @LANG_TAG_SYSTEMTAG_AND_CONVERTING;?>
</a>
				<ul>
					<li>
						<a href="sys_tag.php"><?php echo @LANG_TAG_SET_SYSTEM_TAG;?>
</a>
					</li>
					<li>
						<a href="sys_tagevolve.php"><?php echo @LANG_TAGEVOLVE;?>
</a>
					</li>
					<li>
						<a target="tagmap" href="../plugin/tag/tagmap.html"><?php echo @LANG_TAGMAP;?>
</a>
					</li>
					<li>
						<a href="sys_queue.php"><?php echo @LANG_UPLOADQUEUE;?>
</a>
					</li>
					<li>
						<a href="sys_queue_err.php"><?php echo @LANG_UPLOADQUEUE_ERRLIST;?>
</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="sys_synonyms.php"><?php echo @LANG_FULLTEXT;?>
</a>
				<ul>
					<li>
						<a href="sys_synonyms.php"><?php echo @LANG_FULLTEXT_SYNONYMS;?>
</a>
					</li>
<!--
					<li>
						<a href="sys_blacklist.php"><?php echo @LANG_FULLTEXT_BLACKLIST;?>
</a>
					</li>
					<li>
						<a href="sys_fulltextchart.php"><?php echo @LANG_FULLTEXT_CHART;?>
</a>
					</li>
-->
				</ul>
			</li>
<!--
			<li>
				<?php if (@VCubeAPIBase==''&&@VCubeAPIBase==''){?>
					<a href="sys_allexam.php"><?php echo @LANG_CLASS;?>
</a>
				<?php }elseif(@VCubeAPIBase!=''||@ZoomAPIBase!=''||@ZoomMeetingID==true){?>
					<a href="sys_class.php"><?php echo @LANG_CLASS;?>
</a>
				<?php }else{ ?>
					<a href="sys_seminar.php"><?php echo @LANG_CLASS;?>
</a>
				<?php }?>
				<ul>
					<?php if (@VCubeSeminarAPIBase!=''||@ZoomAPIBase!=''||@ZoomMeetingID=='1'){?>
					<li>
						<a href="sys_class.php"><?php echo @LANG_VCUBE;?>
</a>
					</li>
					<?php }?>
					<?php if (@VCubeSeminarAPIBase!=''){?>
					<li>
						<a href="sys_seminar.php"><?php echo @LANG_SEMINAR;?>
</a>
					</li>
					<?php }?>
					<li>
						<a href="sys_allexam.php"><?php echo @LANG_ALLEXAM;?>
</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="sys_querychart.php"><?php echo @LANG_QUERYCHART;?>
</a>
				<ul>
					<li>
						<a href="sys_querychart.php"><?php echo @LANG_QUERYCHART;?>
</a>
					</li>
					<li>
						<a href="sys_piwik.php"><?php echo @LANG_ANALYZE;?>
</a>
					</li>
				</ul>
			</li>
-->
			<?php if (@REFLECTION_GAME){?>
			<li>
				<a href="sys_game.php"><?php echo @LANG_REFLECTIONGAME;?>
</a>
			</li>
			<?php }?>
			<li>
				<a href="sys_config.php"><?php echo @LANG_SETUP;?>
</a>
			</li>
			<?php if (common::getcookie('sysuser')=='admin'){?>
			<li>
				<a href="sys_testcase.php"><?php echo @LANG_TESTCASE;?>
</a>
			</li>
			<?php }?>
			<?php if (@ENABLE_SHARE){?>
			<li>
				<a href="sys_bookshelf_share.php"><?php echo @LANG_SHARE;?>
</a>
			</li>
			<?php }?>
		</ul>		
	</div>	
  <div id="page-wrapper" class="fixed">
    <div id="main-wrapper">
    
			<div id="main-content">
				<?php if ($_smarty_tpl->tpl_vars['status_code']->value!=''){?>
				<script>setTimeout(function(){jQuery('#status_bar').fadeOut('slow');}, 2000);</script>
				<div class="response-msg ui-corner-all <?php echo $_smarty_tpl->tpl_vars['status_code']->value;?>
" id="status_bar">
				  <?php echo $_smarty_tpl->tpl_vars['status_desc']->value;?>

				</div>
				<?php }?>
				<div class="clearfix"></div>
				<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all form-container">
					<div class="portlet-header ui-widget-header"><?php echo @LANG_TAGEVOLVE_EDIT_TITLE;?>
</div>
					<div class="portlet-content">
						<form action="sys_tagevolve.php?type=<?php if ($_GET['type']=='add'){?>do_add<?php }else{ ?>do_update<?php }?>&page=<?php echo $_GET['page'];?>
" method="post" enctype="multipart/form-data" class="forms" name="form" >
							<ul>
								<li>
									<label class="desc">
										*<?php echo @LANG_TAGEVOLVE_EDIT_TYPE;?>

									</label>
									<div>
										<input type="radio" value="1" class="te_type" name="te_type" checked="checked" /> 分開
										<input type="radio" value="2" class="te_type" name="te_type" /> 合併
										<input type="radio" value="0" class="te_type" name="te_type" /> 更名
									</div>
								</li>
								<li>
									<label class="desc">
										<?php echo @LANG_TAGEVOLVE_EDIT_UNIT;?>

									</label>
									<div>
										<input type="radio" value="pi" id="tag_pi" name="tag" checked="checked" /> 執行單位
										<input type="radio" value="pcu" id="tag_pcu" name="tag" /> 承辦科別
									</div>
								</li>
								<li>
									<label class="desc">
										<?php echo @LANG_TAGEVOLVE_EDIT_YEAR;?>

									</label>
									<div>
										<select id="year" name="year"></select>
									</div>
								</li>
								<li>
									<label class="desc">
										<?php echo @LANG_TAGEVOLVE_EDIT_OLDTAG;?>

									</label>
									<div>
										<select id="oldtag" name="oldtag[]"></select>
									</div>
								</li>
								<li>
									<label class="desc">
										<?php echo @LANG_TAGEVOLVE_EDIT_NEWTAG;?>

									</label>
									<div>
										<select id="newtag" name="newtag[]" multiple></select>
									</div>
								</li>
								<li class="buttons">
									<input type="submit" value="<?php echo @LANG_BUTTON_SAVE;?>
" class="submit" />
									<input type="button" value="<?php echo @LANG_BUTTON_CANCEL;?>
" onclick="javascript:history.go(-1);"/>
								</li>
							</ul>
						</form>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>

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
<?php }} ?>