<?php /* Smarty version Smarty-3.1.7, created on 2019-11-22 23:28:28
         compiled from "/var/www/html/bs4/templates/backend/sys_bookshelf_user_list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:76692973459e6c385caa2a1-37920808%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0dc4599b07908f2a6135f4be769fb3feea567e1c' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/sys_bookshelf_user_list.tpl',
      1 => 1471795383,
      2 => 'file',
    ),
    '9084960af51179b5136906150f6316f62d06a157' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/sys_base.tpl',
      1 => 1574386487,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '76692973459e6c385caa2a1-37920808',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_59e6c385e706e',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_59e6c385e706e')) {function content_59e6c385e706e($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
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
	
<link href="css/customize/bookshelf_user.css" rel="stylesheet" media="all" />
<script type="text/javascript">
$(function() {
      $(".delete").click(function(event) {
          return confirm('<?php echo @LANG_WARNING_DELETE_CONFIRM;?>
');
      }); 
});
</script>

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
				<div class="title">
					<h3><?php echo @LANG_GROUP;?>
 - <?php echo @LANG_GROUP_USERLIST_TITLE;?>
(<?php echo $_smarty_tpl->tpl_vars['group_data']->value['g_name'];?>
)</h3>		
				</div>
				<div>
						<form action="sys_bookshelf_user.php?type=search&gid=<?php echo $_GET['gid'];?>
" method="post" class="forms" name="form" id="search">
							<ul>
								<li>
									<label  class="desc">
										<?php echo @LANG_GROUP_USERLIST_SEARCH;?>

									</label>
									<div>
										<input type="text"maxlength="255" class="field text small" id="q" name="q" value="<?php echo $_GET['q'];?>
" /><input type="submit" value="<?php echo @LANG_BUTTON_SEARCH;?>
" class="submit" />
										<input type="hidden" name="type" value="search" />
									</div>
								</li>
							</ul>
						</form>
				</div>
					<div class="other">						
						<div class="button float-right">
							<?php if ($_GET['gid']){?><a href="sys_group.php"  class="btn ui-state-default"><span class="ui-icon ui-icon-arrowreturnthick-1-w"></span><?php echo @LANG_GROUP_BTN_RETURN;?>
</a><?php }?>
							<?php if (LicenseManager::chkAuth(@MEMBER_SYSTEM,MemberSystemEnum::Import)||LicenseManager::chkAuth(@MEMBER_SYSTEM,MemberSystemEnum::Regist)){?>
								<?php if (LicenseManager::chkAuth(@BACKEND_IMPORT_MODE,ImportManagerModeEnum::GROUP)){?>
								<?php if (@ENABLE_IMPOERT){?><a href="sys_import.php?cmd=import_user" class="btn ui-state-default"><span class="ui-icon ui-icon-arrowreturnthick-1-s"></span><?php echo @LANG_GROUP_USERLIST_BTN_IMPORT;?>
</a><?php }?>
								<?php if (@ENABLE_EXPOERT){?><a href="sys_import.php?cmd=export_user"  class="btn ui-state-default"><span class="ui-icon ui-icon-arrowreturnthick-1-n"></span><?php echo @LANG_GROUP_USERLIST_BTN_EXPORT;?>
</a><?php }?>
								<?php }?>
							<?php }?>
						</div>
						<div class="clearfix"></div>
					</div>
				<div class="hastable">
					<table cellspacing="0">
						<thead>
							<tr>								
								<td><?php echo @LANG_GROUP_USERLIST_COL_USERNAME;?>
</td>
								<td><?php echo @LANG_GROUP_USERLIST_COL_ACCOUNT;?>
</td>
								<td><?php echo @LANG_GROUP_USERLIST_COL_LASLOGIN;?>
</td>
								<td><?php echo @LANG_CONST_MANAGEMENT;?>
</td>
							</tr>
						</thead>
						<tbody>
						<?php  $_smarty_tpl->tpl_vars["val"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["val"]->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['myloop']['iteration']=0;
foreach ($_from as $_smarty_tpl->tpl_vars["val"]->key => $_smarty_tpl->tpl_vars["val"]->value){
$_smarty_tpl->tpl_vars["val"]->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['myloop']['iteration']++;
?>   
							<tr<?php if (!(1 & $_smarty_tpl->getVariable('smarty')->value['foreach']['myloop']['iteration'])){?> class="alt"<?php }?>>
								<td>
									<?php echo $_smarty_tpl->tpl_vars['val']->value['bu_cname'];?>

								</td>
								<td>
									<?php echo $_smarty_tpl->tpl_vars['val']->value['bu_name'];?>

								</td>
								<td>
									<?php echo $_smarty_tpl->tpl_vars['val']->value['last_login'];?>

								</td>
								<td>
									<?php if (MemberSystemFuncMapping::isEnable('delete')){?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<?php echo @LANG_BTNHINT_DELETE;?>
" href="sys_bookshelf_user.php?type=delete&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['bu_id'];?>
&gid=<?php echo $_GET['gid'];?>
&page=<?php echo $_GET['page'];?>
">
										<span class="ui-icon ui-icon-circle-close"></span>
									</a>
									<?php }?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip chart" title="<?php echo @LANG_BTNHINT_CHART;?>
" href="sys_learninghistory.php?id=<?php echo $_smarty_tpl->tpl_vars['val']->value['key'];?>
">
										<span class="ui-icon ui-icon-image"></span>
									</a>
								</td>
							</tr>
						<?php } ?>	
						</tbody>
					</table>
					<?php if ($_smarty_tpl->tpl_vars['pagebar']->value){?>
					<div id="pagebar"><?php echo $_smarty_tpl->tpl_vars['pagebar']->value->showPageBar();?>
</div>
					<?php }?>
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