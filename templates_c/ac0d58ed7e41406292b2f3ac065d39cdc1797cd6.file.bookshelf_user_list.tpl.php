<?php /* Smarty version Smarty-3.1.7, created on 2020-01-14 11:22:26
         compiled from "/var/www/html/bs4/templates/backend/bookshelf_user_list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:99524536757e1e0a7f00cd3-90761270%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ac0d58ed7e41406292b2f3ac065d39cdc1797cd6' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/bookshelf_user_list.tpl',
      1 => 1471795381,
      2 => 'file',
    ),
    '3b120c05e76ffd4582aa50ef424756c469763a8a' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/base.tpl',
      1 => 1577207486,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '99524536757e1e0a7f00cd3-90761270',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_57e1e0a818b02',
  'variables' => 
  array (
    'bsname' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57e1e0a818b02')) {function content_57e1e0a818b02($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/Storage/var/www/html/bs4/libs/Smarty/libs/plugins/modifier.replace.php';
if (!is_callable('smarty_modifier_date_format')) include '/Storage/var/www/html/bs4/libs/Smarty/libs/plugins/modifier.date_format.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=10" />
	<!--[if IE]>
	<script src="js/html5.js"></script>
	<![endif]-->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo @LANG_TITLE;?>
</title>
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
		var bs_id = <?php echo common::getcookie('bs');?>
;
		var uid = <?php echo common::getcookie('adminid');?>
;
		var web_url = '<?php echo @WEB_URL;?>
';
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
			<a href="../index.php" target="bookshelf" class="logo float-left" title="<?php echo @LANG_TITLE;?>
"><?php echo @LANG_TITLE;?>
 | <?php if ($_smarty_tpl->tpl_vars['bsname']->value){?><?php echo $_smarty_tpl->tpl_vars['bsname']->value;?>
<?php }else{ ?><?php echo common::getcookie('bsname');?>
<?php }?></a>
		</div>
		<ul id="navigation" class="sf-navbar">			
      <li>
        <a href="/<?php echo smarty_modifier_replace($_SESSION['adminacc'],@LDAP_DOMAIN_PREFIX,'');?>
/<?php echo common::getcookie('bs');?>
/" target="_web"><?php echo @LANG_CONST_SHOW_WEBSITE;?>
</a>
      </li>
      <!-- deliver bookshelf -->
      <!--
      <li>
        <a href="../?type=publishinghouse" target="_web"><?php echo @LANG_CONST_SHOW_DELIVER;?>
</a>
      </li>
      -->
			<?php if (@MEMBER=="1"){?>
      <li>
        <a href="group.php"><?php echo @LANG_GROUP;?>
</a>
      </li>
			<?php }?>
			<li>
				<a href="category.php"><?php echo @LANG_CATE;?>
</a>
				<ul>
					<li>
						<a href="category.php"><?php echo @LANG_CATE_LIST;?>
</a>
					</li>
					<li>
						<a href="category.php?type=add"><?php echo @LANG_CATE_BTN_ADD;?>
</a>
					</li>									
				</ul>
			</li>
			<li>
				<a href="book.php"><?php echo @LANG_BOOKS;?>
</a>
				<ul>
					<li>
						<a href="book.php"><?php echo @LANG_BOOKS_LIST;?>
</a>
					</li>
					<li>
						<a href="book.php?type=add"><?php echo @LANG_BOOKS_BTN_ADD;?>
</a>
					</li>
				</ul>
			</li>
<!--
			<li>
				<a href="shortcut.php"><?php echo @LANG_SHORTCUT;?>
</a>
				<ul>
					<li>
						<a href="shortcut.php"><?php echo @LANG_SHORTCUT_LIST;?>
</a>
					</li>
					<li>
						<a href="shortcut.php?type=add"><?php echo @LANG_SHORTCUT_BTN_ADD;?>
</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="class.php"><?php echo @LANG_CLASS;?>
</a>
				<ul>
					<li>
						<a href="class.php"><?php echo @LANG_CLASS;?>
</a>
					</li>
					<li>
						<a href="seminar.php"><?php echo @LANG_SEMINAR;?>
</a>
					</li>
					<li>
						<a href="allexam.php"><?php echo @LANG_ALLEXAM;?>
</a>
					</li>
				</ul>
			</li>
-->

			<?php if (@MEMBER&&LicenseManager::chkAuth(@MEMBER_SYSTEM,MemberSystemEnum::Regist)){?>
      <li>
        <a href="activecode.php"><?php echo @LANG_ACTIVECODE;?>
</a>
      </li>
			<?php }?>
      <li>
        <a href="setup.php"><?php echo @LANG_SETUP;?>
</a>
      </li>

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
						<form action="bookshelf_user.php?type=search&gid=<?php echo $_GET['gid'];?>
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
							<?php if ($_GET['gid']){?><a href="group.php"  class="btn ui-state-default"><span class="ui-icon ui-icon-arrowreturnthick-1-w"></span><?php echo @LANG_GROUP_BTN_RETURN;?>
</a><?php }?>
							<?php if (LicenseManager::chkAuth(@MEMBER_MODE,MemberModeEnum::INDIVIDUAL)&&MemberSystemFuncMapping::isEnable('add')){?>
								<a href="bookshelf_user.php?type=add&gid=<?php echo $_GET['gid'];?>
"  class="btn ui-state-default"><span class="ui-icon ui-icon-circle-plus"></span><?php echo @LANG_GROUP_BTN_ADD;?>
</a>
								<?php if (LicenseManager::chkAuth(@MEMBER_SYSTEM,MemberSystemEnum::Import)||LicenseManager::chkAuth(@MEMBER_SYSTEM,MemberSystemEnum::Regist)){?>
									<?php if (LicenseManager::chkAuth(@BACKEND_IMPORT_MODE,ImportManagerModeEnum::USER)){?>
									<?php if (@ENABLE_IMPOERT){?><a href="import.php?cmd=import_user"  class="btn ui-state-default"><span class="ui-icon ui-icon-arrowreturnthick-1-s"></span><?php echo @LANG_GROUP_USERLIST_BTN_IMPORT;?>
</a><?php }?>
									<?php if (@ENABLE_EXPOERT){?><a href="import.php?cmd=export_user"  class="btn ui-state-default"><span class="ui-icon ui-icon-arrowreturnthick-1-n"></span><?php echo @LANG_GROUP_USERLIST_BTN_EXPORT;?>
</a><?php }?>
									<?php }?>
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
								<td><?php echo @LANG_GROUP_USERLIST_COL_DATA;?>
</td>
								<td><?php echo @LANG_GROUP_USERLIST_COL_LASLOGIN;?>
</td>
								<?php if (LicenseManager::chkAuth(@MEMBER_MODE,MemberModeEnum::INDIVIDUAL)){?>
								<td><?php echo @LANG_CONST_MANAGEMENT;?>
</td>
								<?php }?>
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
									birthday: <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['val']->value['bu_birth']);?>
<br />
									Email: <?php echo $_smarty_tpl->tpl_vars['val']->value['bu_email'];?>
<br />
									Career: <?php echo $_smarty_tpl->tpl_vars['val']->value['careername'];?>
<br />
									Receive Mail: <?php echo $_smarty_tpl->tpl_vars['val']->value['bu_receive_mail'];?>

								</td>
								<td>
									<?php echo $_smarty_tpl->tpl_vars['val']->value['last_login'];?>

								</td>
								<?php if (LicenseManager::chkAuth(@MEMBER_MODE,MemberModeEnum::INDIVIDUAL)){?>
								<td>
									<?php if (MemberSystemFuncMapping::isEnable('edit')){?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<?php echo @LANG_BTNHINT_EDIT;?>
" href="bookshelf_user.php?type=edit&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['bu_id'];?>
&gid=<?php echo $_GET['gid'];?>
&page=<?php echo $_GET['page'];?>
">
									<span class="ui-icon ui-icon-wrench"></span>
									</a>
									<?php }?>
									<?php if (MemberSystemFuncMapping::isEnable('delete')){?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<?php echo @LANG_BTNHINT_DELETE;?>
" href="bookshelf_user.php?type=delete&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['bu_id'];?>
&gid=<?php echo $_GET['gid'];?>
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
								<?php }?>
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