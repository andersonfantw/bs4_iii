<?php /* Smarty version Smarty-3.1.7, created on 2020-12-11 16:54:50
         compiled from "/var/www/html/bs4/templates/backend/sys_config.tpl" */ ?>
<?php /*%%SmartyHeaderCode:85827400657bf9b1054f117-40856758%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '34a2e520d9f8935712ebc347a07fb9f5b6bf5738' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/sys_config.tpl',
      1 => 1535703789,
      2 => 'file',
    ),
    '9084960af51179b5136906150f6316f62d06a157' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/sys_base.tpl',
      1 => 1599899037,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '85827400657bf9b1054f117-40856758',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_57bf9b10dd3a6',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57bf9b10dd3a6')) {function content_57bf9b10dd3a6($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
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
	
<link href="css/customize/sys_config.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="js/validation/sys_config.js"></script>
<script type="text/javascript" src="../scripts/loader.class.js"></script>
<script type="text/javascript" src="../plugin/tag/scripts/loader_sys_config.js"></script>

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
					<div class="portlet-header ui-widget-header"><?php echo @LANG_CONFIG;?>
</div>
					<div class="portlet-content">	
						<div id="tabs">
							<ul>
								<?php if (common::getcookie('sysuser')=='admin'){?>
								<li><a href="#tabs1"><?php echo @LANG_CONFIG_SYS_SETUP;?>
</a></li>
								<?php }?>
								<li><a href="#tabs2"><?php echo @LANG_CONFIG_BS_SETUP;?>
</a></li>
								<?php if (common::getcookie('sysuser')=='admin'){?>
								<li><a href="#tabs3"><?php echo @LANG_CONFIG_MEMBER_SETUP;?>
</a></li>
								<li><a href="#tabs4"><?php echo @LANG_CONFIG_IMPORT_SETUP;?>
</a></li>
								<li><a href="#tabs5"><?php echo @LANG_CONFIG_TAG_SETUP;?>
</a></li>
								<li><a href="#tabs6"><?php echo @LANG_CONFIG_PLUGIN_SETUP;?>
</a></li>
								<?php }?>
								<li><a href="#tabs7"><?php echo @LANG_CONFIG_API_SETUP;?>
</a></li>
							</ul>
							<form action="sys_config.php?type=do_update" method="post" enctype="multipart/form-data" class="forms" name="form2" >
							<?php if (common::getcookie('sysuser')=='admin'){?>
							<div id="tabs1">
								<li>
									<label class="desc">
									System status:
									</label>
									<div>
										<?php if ($_smarty_tpl->tpl_vars['sysinfo']->value['mode']==3){?>
											mode: BUY<br />
										<?php }elseif($_smarty_tpl->tpl_vars['sysinfo']->value['mode']==2){?>
											mode: RENT, expired date:<?php echo $_smarty_tpl->tpl_vars['sysinfo']->value['date'];?>
<br />
										<?php }elseif($_smarty_tpl->tpl_vars['sysinfo']->value['mode']==1){?>
											mode: TRIAL, active date:<?php echo $_smarty_tpl->tpl_vars['sysinfo']->value['date'];?>
<br />
												<input type="button" name="active" value="開通 / Active" onclick="document.location.href='sys_config.php?type=do_active'" />
										<?php }?>

										<?php if ($_smarty_tpl->tpl_vars['sysinfo']->value['active']){?>
											<input type="button" name="disable" value="停用 / Disable" onclick="document.location.href='sys_config.php?type=do_disable'" />
										<?php }else{ ?>
											<input type="button" name="enable" value="啟用 / Enable" onclick="document.location.href='sys_config.php?type=do_enable'" />
										<?php }?>
									</div>
								</li>
								<li>
									<label class="desc">
									電子書分散式保護機制 Distributed system:
									</label>
									<div>
										<input type="radio" name="distributed" value="0" <?php if ($_smarty_tpl->tpl_vars['data']->value['distributed']==0){?>checked<?php }?> /> 停用 Disable
										<input type="radio" name="distributed" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['distributed']==1){?>checked<?php }?> /> 啟用 Enable
									</div>
								</li>
								<li>
									<label class="desc">
									最大書櫃數目: 
									</label>
									<div>
										<input type="text" class="field text tiny" name="bs_number" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['bs_number'];?>
" />
									</div>
								</li>
								<li>
									<label class="desc">
									書櫃快取 Application Cache: 
									</label>
									<div>
										<p>
											啟用快取功能Application Cache會將檔案暫存在使用者端。在啟用狀態若主機系統有更新，則需要設定新的版號，更新後，使用者才會看到更新版。請注意!設定新的版本，所有的裝置會重新再cache一次所有檔案。
										</p>
									</div>
									<div>
										快取版本 System version(目前版本:<?php echo $_smarty_tpl->tpl_vars['data']->value['applicationcacheversion'];?>
) : 
										<input type="text" class="field text small" name="application_cache_version" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['applicationcacheversion'];?>
" /><br />

										<input type="checkbox" name="desktop_cache" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['desktopcache']==1){?>checked<?php }?> /> 啟用桌上型電腦快取 Enable desktop computer cache<br />
										<input type="checkbox" name="mobile_cache" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['mobilecache']==1){?>checked<?php }?> /> 啟用APP(Android/iOS)快取 Enable APP(Android/iOS) cache<br />
									</div>
								</li>
								<li>
									<label class="desc">
									System config:
									</label>
									<div>
										<input type="checkbox" name="configbackend" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['configbackend']==1){?>checked<?php }?> /> 啟用前台的"後台"快捷 Enable Backend quick link at front site<br />
										<input type="checkbox" name="configfrontconvert" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['configfrontconvert']==1){?>checked<?php }?> /> 啟用前台轉書 Enable convert at front site<br />
										<input type="checkbox" name="configmybookshelf" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['configmybookshelf']==1){?>checked<?php }?> /> 啟用我的書櫃 Enable My Bookshelf<br />
										<input type="checkbox" name="configecocat" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['configecocat']==1){?>checked<?php }?> /> 啟用EcocatCMS Enable EcocatCMS<br />
										<input type="checkbox" name="configshare" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['configshare']==1){?>checked<?php }?> /> 啟用書櫃分享 Enable Share Method<br />
										<input type="checkbox" name="configdebugmode" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['configdebugmode']==1){?>checked<?php }?> /> 啟用除錯模式 Enable Debuge Mode<br />
										<input type="checkbox" name="configi519" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['configi519']==1){?>checked<?php }?> /> 啟用i519金流 Enable i519<br />
										<input type="checkbox" name="configloginmode" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['configloginmode']==1){?>checked<?php }?> /> 啟用登入模式 Enable Login Mode<br />
									</div>
								</li>
								<li>
									<label class="desc">
									Convert:
									</label>
									<div>
										<input type="checkbox" name="convertmode[]" title="EcocatCMS" value="848" <?php if ($_smarty_tpl->tpl_vars['data']->value['convertmode_EcocatCMS']==1){?>checked<?php }?> <?php if (!@CONNECT_ECOCAT){?>disabled="disabled"<?php }?> /> Ecocat轉書 Ecocat Convert<br />
										<input type="checkbox" name="convertmode[]" title="EcocatZIP" value="2051" <?php if ($_smarty_tpl->tpl_vars['data']->value['convertmode_EcocatZIP']==1){?>checked<?php }?> /> Ecocat, LBM ZIP上架書櫃 Ecocat, LBM ZIP upload to bookshelf<br />
										<input type="checkbox" name="convertmode[]" title="ItutorZIP" value="1028" <?php if ($_smarty_tpl->tpl_vars['data']->value['convertmode_ItutorZIP']==1){?>checked<?php }?> /> Itutor ZIP上架書櫃 Itutor ZIP upload to bookshelf<br />
										<input type="checkbox" name="convertmode[]" title="FlipbuilderZIP" value="4096" <?php if ($_smarty_tpl->tpl_vars['data']->value['convertmode_FlipbuilderZIP']==1){?>checked<?php }?> /> Flipbuilder ZIP上架書櫃 Flipbuilder ZIP upload to bookshelf<br />
										<input type="checkbox" name="convertmode[]" title="MCGZIP" value="8192" <?php if ($_smarty_tpl->tpl_vars['data']->value['convertmode_MCGZIP']==1){?>checked<?php }?> /> MCG ZIP上架書櫃 MCG ZIP upload to bookshelf<br />
									</div>
								</li>
								<li>
									<label class="desc">
									Default Language:
									</label>
									<div>
										<input type="radio" name="defaultlang" value="zh-tw" <?php if ($_smarty_tpl->tpl_vars['data']->value['defaultlang']=='zh-tw'){?>checked<?php }?> /> zh-tw
										<input type="radio" name="defaultlang" value="zh-cn" <?php if ($_smarty_tpl->tpl_vars['data']->value['defaultlang']=='zh-cn'){?>checked<?php }?> /> zh-cn
										<input type="radio" name="defaultlang" value="en" <?php if ($_smarty_tpl->tpl_vars['data']->value['defaultlang']=='en'){?>checked<?php }?> /> en
										<input type="radio" name="defaultlang" value="jp" <?php if ($_smarty_tpl->tpl_vars['data']->value['defaultlang']=='jp'){?>checked<?php }?> /> jp
									</div>
								</li>
								<li>
									<label class="desc">
									System log:
									</label>
									<div>
										<input type="button" value="書櫃系統更新 Upgrade" onclick="window.open('<?php echo @WEB_URL;?>
/backend/sys_logviewer.php?ln=upgrade')" />
										<input type="button" value="資料庫備份 DBBackup" onclick="window.open('<?php echo @WEB_URL;?>
/backend/sys_logviewer.php?ln=dbbackup')" />
										<input type="button" value="排程轉書 Queue" onclick="window.open('<?php echo @WEB_URL;?>
/backend/sys_logviewer.php?ln=uploadqueue')" />
										<input type="button" value="轉書引擎 Ecocat" onclick="window.open('<?php echo @WEB_URL;?>
/backend/sys_logviewer.php?ln=ecocat')" />
										<input type="button" value="PHP錯誤記錄 PHP_ERRORS" onclick="window.open('<?php echo @WEB_URL;?>
/backend/sys_logviewer.php?ln=phperrors')" />
									</div>
								</li>
								<li>
									<div class="title title-spacing">
									</div>
									<label class="desc">
									<?php echo @LANG_SETUP_GOOGLECODE;?>
: 
									</label>
									<div>
										<textarea tabindex="2" cols="50" rows="5" class="field textarea medium" name="google_code" ><?php echo $_smarty_tpl->tpl_vars['dataw']->value['google_code'];?>
</textarea>
									</div>
								</li>
								<li>
									<label class="desc">
									附屬工具: 
									</label>
									<div>
										<input type="checkbox" name="b_writer" value="1" <?php if ($_smarty_tpl->tpl_vars['dataw']->value['b_writer']==1){?>checked<?php }?>/> 作者、共同作者
										<input type="checkbox" name="b_link" value="1" <?php if ($_smarty_tpl->tpl_vars['dataw']->value['b_link']==1){?>checked<?php }?>/> 連結
										<input type="checkbox" name="b_imglink" value="1" <?php if ($_smarty_tpl->tpl_vars['dataw']->value['b_imglink']==1){?>checked<?php }?>/> 圖片連結
									</div>
								</li>
							</div>
							<?php }?>
							<div id="tabs2">
								<li>
									<label  class="desc">
									<?php echo @LANG_SETUP_BOOKSHELFTITLE;?>
: 
									</label>
									<div>
										<input type="text"maxlength="255" class="field text small" id="bs_title" name="bs_title" value="<?php echo $_smarty_tpl->tpl_vars['dataw']->value['bs_title'];?>
" />
									</div>
								</li>
	              <li>
	                <label  class="desc">
	                <?php echo @LANG_SETUP_HEADERLINK;?>

	                </label>
	                <div>
	                        <input type="text"maxlength="255" class="field text large" id="bs_header_link" name="bs_header_link" value="<?php echo $_smarty_tpl->tpl_vars['dataw']->value['bs_header_link'];?>
" />
	                </div>
	              </li>
								<li>
									<label  class="desc">
									<?php echo @LANG_SETUP_HEADERIMG;?>
: 
									</label>
									<div>
	                  <input type="button" class="field" name="bs_remove_file" value="Delete" i="" id="bs_remove_file">
										<input type="file" class="field" name="bs_header_file" value="" id="bs_header_img" />
										<input type="hidden" name="bs_header" value="<?php echo $_smarty_tpl->tpl_vars['dataw']->value['bs_header'];?>
" id="bs_header" />
										<input type="hidden" name="del_bs_header" value="" id="del_bs_header" />
									</div>
									<div>
										<img src="<?php echo $_smarty_tpl->tpl_vars['path_header_image']->value;?>
" />
										<input type="hidden" name="header_image" value="<?php echo $_smarty_tpl->tpl_vars['header_image']->value;?>
" id="header_image" />
									</div>
								</li>
								<li>
									<label  class="desc">
									<?php echo @LANG_SETUP_HEADER_HEIGHT;?>
: 
									</label>
									<div>
										<input type="text" class="field text tiny" name="bs_header_height" value="<?php echo $_smarty_tpl->tpl_vars['dataw']->value['bs_header_height'];?>
" /> px (214px)
									</div>
								</li>
								<li>
									<label  class="desc">
									<?php echo @LANG_SETUP_FOOTERIMG;?>
: 
									</label>
									<div>
	                  <input type="button" class="field" name="bs_remove_footerfile" value="Delete" i="" id="bs_remove_footerfile">
										<input type="file" class="field" name="bs_footer_file" value="" id="bs_footer_img" />
										<input type="hidden" name="bs_footer" value="<?php echo $_smarty_tpl->tpl_vars['dataw']->value['bs_footer'];?>
" id="bs_footer" />
										<input type="hidden" name="del_bs_footer" value="" id="del_bs_footer" />
									</div>
									<div>
										<img src="<?php echo $_smarty_tpl->tpl_vars['path_footer_image']->value;?>
" />
										<input type="hidden" name="footer_image" value="<?php echo $_smarty_tpl->tpl_vars['footer_image']->value;?>
" id="footer_image" />
									</div>
								</li>
								<li>
									<label  class="desc">
									<?php echo @LANG_SETUP_FOOTER_HEIGHT;?>
: 
									</label>
									<div>
										<input type="text" class="field text tiny" name="bs_footer_height" value="<?php echo $_smarty_tpl->tpl_vars['dataw']->value['bs_footer_height'];?>
" /> px
									</div>
								</li>
								<li>
									<label class="desc">
									<?php echo @LANG_SETUP_FOOTER_TEXT;?>
: 
									</label>
									<div>
										<textarea tabindex="2" cols="50" rows="5" class="field full medium" name="bs_footer_content" ><?php echo $_smarty_tpl->tpl_vars['dataw']->value['bs_footer_content'];?>
</textarea>
									</div>
								</li>
							</div>
							<?php if (common::getcookie('sysuser')=='admin'){?>
							<div id="tabs3">
								<li>
									<label class="desc">
									會員模式 Member Mode:
									</label>
									<div>
										<p>模式在系統使用後就不可變更 Don't change setting after system online.</p>
									</div>
									<div>
										<input type="radio" name="membermode" value="4" <?php if ($_smarty_tpl->tpl_vars['data']->value['membermode']==4){?>checked<?php }?> <?php if (@MEMBER_SYSTEM==4){?>disabled="disabled"<?php }?> /> 獨立帳號 Individual
										<input type="radio" name="membermode" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['membermode']==1){?>checked<?php }?> /> 共同帳號 Centralizs									
									</div>
								</li>
								<li>
									<label class="desc">
									會員系統 Member System:
									</label>
									<div>
										<p>模式在系統使用後就不可變更 Don't change setting after system online.</p>
									</div>
									<div>
										<input type="radio" name="membersystem" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['membersystem']==1||$_smarty_tpl->tpl_vars['data']->value['membersystem']==2){?>checked<?php }?> /> 書櫃帳號 DB account
										<input type="radio" name="membersystem" value="4" <?php if ($_smarty_tpl->tpl_vars['data']->value['membersystem']==4){?>checked<?php }?> /> NAS LDAP帳號 NAS / LDAP account
										<input type="radio" name="membersystem" value="16" <?php if ($_smarty_tpl->tpl_vars['data']->value['membersystem']==16){?>checked<?php }?> /> 使用者註冊 Regist account		
									</div>
									<div id="NAS" <?php if ($_smarty_tpl->tpl_vars['data']->value['membersystem']==4){?>style="display:block"<?php }?>>
										<p>
											user from:
											<input type="radio" name="ldapdomaintype" value="0" <?php if ($_smarty_tpl->tpl_vars['data']->value['ldapdomaintype']==0){?>checked<?php }?> /> NAS User
											<input type="radio" name="ldapdomaintype" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['ldapdomaintype']==1){?>checked<?php }?> /> LDAP User<br />
											LDAP Prefix: <input type="text" id="ldapprefix" name="ldapprefix" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['ldapprefix'];?>
" />
										</p>
									</div>
								</li>
							</div>
							<div id="tabs4">
								<li>
									<label class="desc">
									啟用匯入/出 Enable Import/Export:
									</label>
									<div>
										<input type="checkbox" name="import" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['import']==1){?>checked<?php }?> /> Enable Import
										<input type="checkbox" name="export" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['export']==1){?>checked<?php }?> /> Enable Export
									</div>
								</li>
								<li>
									<label class="desc">
									匯入/出功能 Enable Import/Export function:
									</label>
									<div>
										<p>匯入/匯出功能僅在[會員系統]為[書櫃會員]時啟用.</p>
									</div>
									<div>
										<input type="checkbox" name="importmode[]" value="16" <?php if ($_smarty_tpl->tpl_vars['data']->value['importmode_manager']){?>checked<?php }?> /> 管理員帳號 Manager account
										<input type="checkbox" name="importmode[]" value="3" <?php if ($_smarty_tpl->tpl_vars['data']->value['importmode_user']){?>checked<?php }?> /> 使用者帳號 user account
										<input type="checkbox" name="importmode[]" value="12" <?php if ($_smarty_tpl->tpl_vars['data']->value['importmode_book']){?>checked<?php }?> /> 電子書 books/groups
									</div>
								</li>
							</div>
							<div id="tabs5">
								<li>
									<label class="desc">
									系統標籤 system tag:
									</label>
									<div>
										<p>標籤會套用在所有的內容上(年度 學年 學期). Tag on all contents. ex:Year, School Year, Term</p>
									</div>
									<div id="systemtag">
									</div>
								</li>
								<li>
									<label class="desc">
									必設考卷標籤 Required exam tags:
									</label>
									<div>
										<p>設定為必填，在設定時以下拉選單選擇 Use dropdown list while set to require</p>
									</div>
									<div id="infoacer_pid">
									</div>
								</li>
							</ul>
						</div>
						<div id="tabs6">
							<li>
								<label class="desc">
								Giantview:
								</label>
								<div>
									<input type="checkbox" name="giantview" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['giantview']==1){?>checked<?php }?> /> 啟用學習互動 Enable GiantView<br />
									<input type="checkbox" name="giantviewsystem" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['giantviewsystem']==1){?>checked<?php }?> /> 啟用進入教室 Enable Classroom<br />
									<input type="checkbox" name="giantviewchat" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['giantviewchat']==1){?>checked<?php }?> /> 啟用聊天 Enable Chat<br />
									Giantview URL: <input type="text" id="GiantviewURL" name="GiantviewURL" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['GiantviewURL'];?>
" />
									<p>
									請設定Giantview 區域網路IP。Please set giantview private ip.<br />
									Bookshelf local ip:<?php echo $_SERVER['SERVER_ADDR'];?>
<br />
									Default Giantview port:20028
									</p>
								</div>
							</li>
							<li>
								<label class="desc">
								VCube Setting:
								</label>
								<div>
									<span>API Version:</span>
										<input type="radio" name="VCubeVersion" value="v5" <?php if ($_smarty_tpl->tpl_vars['data']->value['VCubeVersion']=='v5'){?>checked<?php }?> /> V5
										<input type="radio" name="VCubeVersion" value="v4" <?php if ($_smarty_tpl->tpl_vars['data']->value['VCubeVersion']=='v4'){?>checked<?php }?> /> V4<br />
									<span>API Base:</span> <input type="text" id="VCubeAPIBase" class="APIBase" name="VCubeAPIBase" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['VCubeAPIBase'];?>
" /><br />
									<span>ID:</span> <input type="text" id="VCubeID" class="ID" name="VCubeID" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['VCubeID'];?>
" /><br />
									<span>PWD:</span> <input type="text" id="VCubePWD" class="PWD" name="VCubePWD" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['VCubePWD'];?>
" /><br />
									<span>Notice Mail:</span> <input type="text" id="VCubeNoticeMail" class="NoticeMail" name="VCubeNoticeMail" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['VCubeNoticeMail'];?>
" />
								</div>
							</li>
							<li>
								<label class="desc">
								VCube Seminar Setting:
								</label>
								<div>
									<span>API Base:</span> <input type="text" id="VCubeSeminarAPIBase" class="APIBase" name="VCubeSeminarAPIBase" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['VCubeSeminarAPIBase'];?>
" /><br />
									<span>ID:</span> <input type="text" id="VCubeSeminarID" class="ID" name="VCubeSeminarID" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['VCubeSeminarID'];?>
" /><br />
									<span>PWD:</span> <input type="text" id="VCubeSeminarPWD" class="PWD" name="VCubeSeminarPWD" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['VCubeSeminarPWD'];?>
" /><br />
									<span>Notice Mail:</span> <input type="text" id="VCubeSeminarNoticeMail" class="NoticeMail" name="VCubeSeminarNoticeMail" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['VCubeSeminarNoticeMail'];?>
" />
								</div>
							</li>
							<li>
								<label class="desc">
								Zoom Setting:
								</label>
								<div>
									<span>Meeting ID</span> 
										<input type="radio" name="ZoomMeetingID" value="0" <?php if ($_smarty_tpl->tpl_vars['data']->value['ZoomMeetingID']=='0'){?>checked<?php }?> /> Disable
										<input type="radio" name="ZoomMeetingID" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['ZoomMeetingID']=='1'){?>checked<?php }?> /> Enable<br />
									<span>API Base:</span> <input type="text" id="ZoomAPIBase" class="APIBase" name="ZoomAPIBase" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['ZoomAPIBase'];?>
" /><br />
									<span>ID:</span> <input type="text" id="ZoomID" class="ID" name="ZoomID" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['ZoomID'];?>
" /><br />
									<span>KEY:</span> <input type="text" id="ZoomKey" class="PWD" name="ZoomKey" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['ZoomKey'];?>
" /><br />
									<span>SECRET:</span> <input type="text" id="ZoomSecret" class="PWD" name="ZoomSecret" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['ZoomSecret'];?>
" /><br />
								</div>
							</li>
						</div>
						<?php }?>
						<div id="tabs7">
							<li>
								<label class="desc">
								轉書排程 Upload Queue:
								</label>
								<div>
									<p>
										批次轉檔錯誤時，通知的電子郵件為
										System will notice you when convert ebook fail!
									</p>
									<span>Contact 1:</span> <input type="text" class="field text medium" name="UploadQueueErrorApplyTo1" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['UploadQueueErrorApplyTo1'];?>
" /><br /><br />
									<span>Contact 2:</span> <input type="text" class="field text medium" name="UploadQueueErrorApplyTo2" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['UploadQueueErrorApplyTo2'];?>
" /><br /><br />
								</div>
								<?php if (common::getcookie('sysuser')=='admin'){?>
								<div>
									<p>
										啟用分類設定，分類以傳入分類id(cid)的方式設定，若未傳入cid則自動以年為主分類，月為次分類。停用分類設定，必須設定傳入的主/次分類參數名稱。<br />
										Enable CID, post cid when upload a file. systen will set year as main-cate, month as sub-cate when cid is empty. Disable CID, must post both parent cate and child cate.
									</p>
									<span>API ID:</span> <input type="text" class="field text" name="UploadQueueID" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['UploadQueueID'];?>
" /><br />
									<span>API PWD:</span> <input type="text" class="field text" name="UploadQueuePWD" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['UploadQueuePWD'];?>
" /><br /><br />

									<input type="checkbox" name="UploadQueueSetBSID" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['UploadQueueSetBSID']==1){?>checked<?php }?> /> 啟用書櫃設定 Enable bsid param, 設定停用書櫃設定，電子書會放入預設的書櫃。<br />
									<span>預設 default:</span> <input type="text" class="field text tiny" name="UploadQueueDefaultBSID" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['UploadQueueDefaultBSID'];?>
" /><br />
									<input type="checkbox" name="UploadQueueSetCID" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['UploadQueueSetCID']==1){?>checked<?php }?> /> 啟用分類設定 Enable cid param<br /><br />
									<div id="UploadQueueCate" <?php if ($_smarty_tpl->tpl_vars['data']->value['UploadQueueSetCID']!=1){?>style="display:block"<?php }?>>
										以下的分類會自動設定為必填，並設定為書的標籤。參數名稱的格式為 key: 參數名稱 + 'Key'; val: 參數名<br />
										Categories below are required, and will be default tag. Parameters format are Key: param name + 'Key'; val: param name<br /><br />
										<span>Parent Cate:</span> <input type="text" class="field text" name="UploadQueueParentCate" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['UploadQueueParentCate'];?>
" /><br />
										<span>Child Cate:&nbsp;&nbsp;</span> <input type="text" class="field text" name="UploadQueueChildCate" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['UploadQueueChildCate'];?>
" /><br />
										<span>Tag settings:</span><br /><br />
										格式 / format: 逗號分隔的大小寫英文字，*開頭為必填。 Upper and lower case phrase, start with * is required.<br />
										<div>
											<textarea tabindex="2" cols="50" rows="5" class="field full medium" name="UploadQueueTagSettings" ><?php echo $_smarty_tpl->tpl_vars['data']->value['UploadQueueTagSettings'];?>
</textarea>
										</div>
									<div><br />
									<span>Tag root:</span> <input type="text" class="field text" name="UploadQueueTagRoot" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['UploadQueueTagRoot'];?>
" /><br />
								</div>
								<?php }?>
							</li>
							<?php if (common::getcookie('sysuser')=='admin'){?>
							<li>
								<label class="desc">
								APP官網連結 Website URL:
								</label>
								<div>
									<span>Website link:</span>	<input type="text" class="field text medium" name="APPWebsiteURL" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['APPWebsiteURL'];?>
" />
								</div>
							</li>
							<?php }?>
						</div>
						<div class="buttons">
							<input type="submit" value="<?php echo @LANG_BUTTON_SAVE;?>
" class="submit" onclick="return check();" />
						</div>
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