<?php /* Smarty version Smarty-3.1.7, created on 2016-09-21 02:35:42
         compiled from "/var/www/html/bs4/templates/backend/setup.tpl" */ ?>
<?php /*%%SmartyHeaderCode:150891388257e1817e93b290-66795290%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '06ef0bf6508e84ef1c171b9c4304db278f455662' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/setup.tpl',
      1 => 1471795382,
      2 => 'file',
    ),
    '3b120c05e76ffd4582aa50ef424756c469763a8a' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/base.tpl',
      1 => 1471795381,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '150891388257e1817e93b290-66795290',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'bsname' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_57e1817ea8da0',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57e1817ea8da0')) {function content_57e1817ea8da0($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/Storage/var/www/html/bs4/libs/Smarty/libs/plugins/modifier.replace.php';
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
	
<link href="css/customize/setup.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="js/validation/setup.js"></script>

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
				<div class="clearfix"></div>
				<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all form-container">
					<form action="setup.php?type=do_update" method="post" enctype="multipart/form-data" class="forms" name="form" >
					<div class="portlet-header ui-widget-header"><?php echo @LANG_SETUP;?>
</div>
					<div class="portlet-content">	
						<div class="title title-spacing">
							<h2><?php echo @LANG_SETUP_TITLE_SETUP;?>
</h2>					
						</div>
						<ul>
							<li>
								<label><?php echo @LANG_SETUP_ONLIST;?>
: </label>
								<label><input type="checkbox" name="bs_list_status" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['bs_list_status']==1){?>checked="checked"<?php }?>/></label>
							</li>
							<li>
            		<label><?php echo @LANG_SETUP_BOOKBUTTON;?>
: </label>
              	<label><input type="checkbox" name="is_webbook" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['is_webbook']==1){?>checked="checked"<?php }?>/> <?php echo @LANG_SETUP_WEBBOOK;?>
</label>
                <label><input type="checkbox" name="is_ibook" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['is_ibook']==1){?>checked="checked"<?php }?>/> <?php echo @LANG_SETUP_IBOOK;?>
</label>
              </li>

							<?php if (@ENABLE_GIANTVIEW&&(@GiantviewSystem||@GiantviewChat)){?>
							<li>
								<label><?php echo @LANG_SETUP_GIANTVIEW_CHAT;?>
: </label>
								<?php if (@GiantviewChat){?>
								<label><input type="checkbox" name="enable_giantview_chat" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['enable_giantview_chat']==1){?>checked="checked"<?php }?>/> <?php echo @LANG_SETUP_GIANTVIEW_CHAT;?>
</label>
								<?php }?>
								<?php if (@GiantviewSystem){?>
								<label><input type="checkbox" name="enable_giantview_system" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['enable_giantview_system']==1){?>checked="checked"<?php }?>/> <?php echo @LANG_SETUP_GIANTVIEW_SYSTEM;?>
</label>
								<?php }?>
							</li>
							<?php }?>

							<?php if ($_smarty_tpl->tpl_vars['data']->value['is_member']!=1){?>
							<li>
								<label>All Book: </label>
								<input type="checkbox" name="is_allbook" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['is_allbook']!=0){?>checked="checked"<?php }?>/>
							</li>
							<li>
								<label>New Book: </label>
								<input type="checkbox" name="is_newbook" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['is_newbook']!=0){?>checked="checked"<?php }?>/>
							</li>
							<?php }?>
						</ul>
						</ul>					
						<div class="title title-spacing">
							<h2><?php echo @LANG_SETUP_TITLE_INFO;?>
</h2>							
						</div>
						<ul>
							<li>
								<label  class="desc">
								<?php echo @LANG_SETUP_BOOKSHELFTITLE;?>
: 
								</label>
								<div>
									<input type="text"maxlength="255" class="field text small" id="bs_title" name="bs_title" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['bs_title'];?>
" />
								</div>
							</li>
              <li>
                <label  class="desc">
                <?php echo @LANG_SETUP_HEADERLINK;?>

                </label>
                <div>
									<input type="text"maxlength="255" class="field text large" id="bs_header_link" name="bs_header_link" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['bs_header_link'];?>
" />
                </div>
              </li>
							<li>
								<label  class="desc">
								<?php echo @LANG_SETUP_HEADERIMG;?>
: 
								</label>
								<div>
									<input type="button" class="field" name="bs_remove_file" value="Delete" id="bs_remove_file" />
									<input type="file" class="field" name="bs_header_file" value="" id="bs_header_img" />
									<input type="hidden" name="bs_header" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['bs_header'];?>
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
									<input type="text" class="field text medium" name="bs_header_height" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['bs_header_height'];?>
" /> px (ex:214px)
								</div>
							</li>
							<li>
								<label  class="desc">
								<?php echo @LANG_SETUP_FOOTERIMG;?>
: 
								</label>
								<div>
									<input type="button" class="field" name="bs_remove_footerfile" value="Delete" id="bs_remove_footerfile">
									<input type="file" class="field" name="bs_footer_file" value="" id="bs_footer_img" />
									<input type="hidden" name="bs_footer" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['bs_footer'];?>
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
									<input type="text" class="field text medium" name="bs_footer_height" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['bs_footer_height'];?>
" /> px
								</div>
							</li>
							<li>
								<label class="desc">
								<?php echo @LANG_SETUP_FOOTER_TEXT;?>
: 
								</label>
								<div>
									<textarea tabindex="2" cols="50" rows="5" class="field textarea medium" name="bs_footer_content" ><?php echo $_smarty_tpl->tpl_vars['data']->value['bs_footer_content'];?>
</textarea>
								</div>
							</li>
							<li class="buttons">
								<input type="submit" value="<?php echo @LANG_BUTTON_SAVE;?>
" class="submit" onclick="return check();" />
							</li>
						</ul>
						<input type="hidden" name="mid" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['is_member'];?>
" />
					</div>
					</form>
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