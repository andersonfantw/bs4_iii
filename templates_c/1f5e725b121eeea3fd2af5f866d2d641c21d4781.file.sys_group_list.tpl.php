<?php /* Smarty version Smarty-3.1.7, created on 2016-08-22 08:30:00
         compiled from "/var/www/html/bs3/templates/backend/sys_group_list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:101191413557ba478807aa78-52665148%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1f5e725b121eeea3fd2af5f866d2d641c21d4781' => 
    array (
      0 => '/var/www/html/bs3/templates/backend/sys_group_list.tpl',
      1 => 1471795384,
      2 => 'file',
    ),
    '054486c5816ec69ff96e36b2037ceb9b8605f590' => 
    array (
      0 => '/var/www/html/bs3/templates/backend/sys_base.tpl',
      1 => 1471795383,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '101191413557ba478807aa78-52665148',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_57ba47884d6ae',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57ba47884d6ae')) {function content_57ba47884d6ae($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo @LANG_TITLE;?>
</title>
	<link href="css/sys_style.css" rel="stylesheet" media="all" />
	<link href="" rel="stylesheet" title="style" media="all" />
	<link href="/css/jquery-ui-1.11.4.custom.min.css" rel="stylesheet" title="style" media="all">
  <script type="text/javascript" src="../scripts/jquery-1.11.2.min.js"></script>
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
	
<link href="css/customize/group.css" rel="stylesheet" media="all" />
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
				<a href="sys_tag.php"><?php echo @LANG_TAG_SET_SYSTEM_TAG;?>
</a>
				<ul>
					<li>
						<a href="sys_tag.php"><?php echo @LANG_TAG_SET_SYSTEM_TAG;?>
</a>
					</li>
					<li>
						<a target="tagmap" href="../plugin/tag/tagmap.html"><?php echo @LANG_TAGMAP;?>
</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="sys_class.php"><?php echo @LANG_CLASS;?>
</a>
				<ul>
					<li>
						<a href="sys_class.php"><?php echo @LANG_VCUBE;?>
</a>
					</li>
					<li>
						<a href="sys_seminar.php"><?php echo @LANG_SEMINAR;?>
</a>
					</li>
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
<!--
			<li>
				<a href="sys_bookshelf_share.php"><?php echo @LANG_SHARE;?>
</a>
			</li>
-->
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
 - <?php echo @LANG_GROUP_LIST_TITLE;?>
</h3>
				</div>
				<div>
						<form action="sys_group.php?type=search" method="post" class="forms" name="form" id="search">
							<ul>
								<li>
									<label  class="desc">
										<?php echo @LANG_GROUP_LIST_SEARCH;?>

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
							<?php if (LicenseManager::chkAuth(@MEMBER_MODE,MemberModeEnum::CENTRALIZE)&&MemberSystemFuncMapping::isEnable('add')){?>
								<a href="sys_group.php?type=add" class="btn ui-state-default"><span class="ui-icon ui-icon-circle-plus"></span><?php echo @LANG_GROUP_LIST_BTN_ADD;?>
</a>
								<?php if (LicenseManager::chkAuth(@MEMBER_SYSTEM,MemberSystemEnum::Import)||LicenseManager::chkAuth(@MEMBER_SYSTEM,MemberSystemEnum::Regist)){?>
									<?php if (LicenseManager::chkAuth(@BACKEND_IMPORT_MODE,ImportManagerModeEnum::GROUP)){?>
									<?php if (@ENABLE_IMPOERT){?><a href="sys_import.php?cmd=import_group" class="btn ui-state-default"><span class="ui-icon ui-icon-arrowreturnthick-1-s"></span><?php echo @LANG_GROUP_LIST_BTN_IMPORT;?>
</a><?php }?>
									<?php if (@ENABLE_EXPOERT){?><a href="sys_import.php?cmd=export_group"  class="btn ui-state-default"><span class="ui-icon ui-icon-arrowreturnthick-1-n"></span><?php echo @LANG_GROUP_LIST_BTN_EXPORT;?>
</a><?php }?>
									<?php }?>
								<?php }?>
							<?php }?>
							<br />
						</div>
						<div class="clearfix"></div>
					</div>
				<div class="hastable">
					<table cellspacing="0">
						<thead>
							<tr>
								<td><?php echo @LANG_GROUP_LIST_COL_GROUPNAME;?>
</td>
								<td><?php echo @LANG_GROUP_LIST_COL_USERS;?>
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
									<?php echo $_smarty_tpl->tpl_vars['val']->value['g_name'];?>

								</td>
                <td style="text-align:center;">
                   <span style="text-decoration:underline;margin-bottom:3px;"><a href="sys_bookshelf_user.php?gid=<?php echo $_smarty_tpl->tpl_vars['val']->value['g_id'];?>
"><?php echo @LANG_GROUP_LIST_COL_USERLIST;?>
</span>
                </td>
								<td>
									<?php if (MemberSystemFuncMapping::isEnable('edit')){?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<?php echo @LANG_BUTTON_EDIT;?>
" href="sys_group.php?type=edit&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['g_id'];?>
&gn=<?php echo base64_encode($_smarty_tpl->tpl_vars['val']->value['g_name']);?>
&page=<?php echo $_GET['page'];?>
">
									<span class="ui-icon ui-icon-wrench"></span>
									</a>
									<?php }?>
									<?php if (MemberSystemFuncMapping::isEnable('delete')){?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<?php echo @LANG_BUTTON_DELETE;?>
" href="sys_group.php?type=delete&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['g_id'];?>
&page=<?php echo $_GET['page'];?>
">
										<span class="ui-icon ui-icon-circle-close"></span>
									</a>
									<?php }?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip chart" title="<?php echo @LANG_BTNHINT_CHART;?>
" href="learninghistory.php?id=<?php echo $_smarty_tpl->tpl_vars['val']->value['key'];?>
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