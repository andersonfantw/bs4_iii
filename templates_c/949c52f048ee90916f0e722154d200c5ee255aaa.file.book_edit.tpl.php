<?php /* Smarty version Smarty-3.1.7, created on 2021-03-19 15:01:21
         compiled from "/var/www/html/bs4/templates/backend/book_edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:122402491457cfcf70575ea2-06193492%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '949c52f048ee90916f0e722154d200c5ee255aaa' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/book_edit.tpl',
      1 => 1529378489,
      2 => 'file',
    ),
    '3b120c05e76ffd4582aa50ef424756c469763a8a' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/base.tpl',
      1 => 1577207486,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '122402491457cfcf70575ea2-06193492',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_57cfcf7077493',
  'variables' => 
  array (
    'bsname' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57cfcf7077493')) {function content_57cfcf7077493($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/Storage/var/www/html/bs4/libs/Smarty/libs/plugins/modifier.replace.php';
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
	
<script>
_enable_cowriter=<?php echo @FUNCTION_WRITER;?>
;
_enable_links=<?php echo @FUNCTION_LINK;?>
;
_enable_imglinks=<?php echo @FUNCTION_IMGLINK;?>
;
//_enable_cowriter=false;
//_enable_links=false;
//_enable_imglinks=false;
var bid=<?php echo $_GET['id'];?>
;
</script>
<link href="css/customize/book.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="js/validation/book.js"></script>

<script type="text/javascript" src="../scripts/loader.class.js"></script>
<script type="text/javascript" src="../plugin/tag/scripts/loader_book_edit.js"></script>

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
				<div><img id="iconorgpic" /></div>
				<div class="clearfix"></div>
				<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all form-container">
					<div class="portlet-header ui-widget-header"><?php echo @LANG_BOOKS_EDIT_TITLE;?>
</div>
					<div class="portlet-content">
						<form action="book.php?type=<?php if ($_GET['type']=='add'){?>do_add<?php }else{ ?>do_update<?php }?>&page=<?php echo $_GET['page'];?>
" method="post" enctype="multipart/form-data" class="forms" name="form" >
							<ul>
								<li>
									<label  class="desc">
										*<?php echo @LANG_BOOKS_EDIT_BOOKNAME;?>

									</label>
									<div>
										<input type="text"maxlength="255" class="field text small" id="b_name" name="b_name" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['b_name'];?>
" />
									</div>
								</li>
								<li>
									<label  class="desc">
										*<?php echo @LANG_BOOKS_EDIT_BOOKKEY;?>

									</label>
									<div>
										<?php echo $_smarty_tpl->tpl_vars['data']->value['b_key'];?>

										<input type="hidden" id="b_key" name="b_key" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['b_key'];?>
" />
									</div>
								</li>
								<li>
									<label  class="desc">
										*<?php echo @LANG_BOOKS_EDIT_COVER;?>

									</label>
									<div>
										<input type="file" class="field" name="img" value="" id="img" />
										<input type="hidden" name="file_id" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['file_id'];?>
" id="file_id" />
									</div>
									<div>
									<img src="<?php echo $_smarty_tpl->tpl_vars['cover_image']->value;?>
" />
									<input type="hidden" name="cover_image" value="<?php echo $_smarty_tpl->tpl_vars['cover_image']->value;?>
" id="cover_image" />
								</div>
								</li>
								<li>
									<label  class="desc">
										<?php echo @LANG_BOOKS_EDIT_WEBBOOKLINK;?>

									</label>
									<div>
										<input type="text" class="field text medium" name="webbook_link" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['webbook_link'];?>
" <?php if ($_smarty_tpl->tpl_vars['data']->value['is_webbook']==0&&$_GET['type']!='add'){?>readonly  style="background:#ccc" alt=""<?php }?> />
										<input type="checkbox" name="webbook_show" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['webbook_show']==1){?>checked="checked"<?php }?> <?php if ($_smarty_tpl->tpl_vars['data']->value['is_webbook']==0&&$_GET['type']!='add'){?>readonly style="background:#ccc" alt=""<?php }?> /> show
									</div>
								</li>
								<li>
									<label class="desc">
										<?php echo @LANG_BOOKS_EDIT_IBOOKLINK;?>

									</label>
									<div>
										<?php echo @LANG_BOOKS_EDIT_IBOOKLINK_DESC;?>
<br /><br />
										<input type="text" class="field text medium" name="ibook_link" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['ibook_link'];?>
" <?php if ($_smarty_tpl->tpl_vars['data']->value['is_ibook']==0&&$_GET['type']!='add'){?>readonly  style="background:#ccc" alt=""<?php }?> />
										<input type="checkbox" name="ibook_show" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['ibook_show']==1){?>checked="checked"<?php }?> <?php if ($_smarty_tpl->tpl_vars['data']->value['is_ibook']==0&&$_GET['type']!='add'){?>onclick="return false" style="background:#ccc" alt=""<?php }?> /> show
									</div>
								</li>
								<li>
									<label  class="desc">
										*<?php echo @LANG_BOOKS_EDIT_SHOWLINK;?>

									</label>
									<div>
										<?php  $_smarty_tpl->tpl_vars["val"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["val"]->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['category']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["val"]->key => $_smarty_tpl->tpl_vars["val"]->value){
$_smarty_tpl->tpl_vars["val"]->_loop = true;
?>
                      <?php if ($_smarty_tpl->tpl_vars['val']->value['sub_category']){?><div style="margin-bottom:5px;"><?php echo $_smarty_tpl->tpl_vars['val']->value['c_name'];?>
<br />
                      <?php  $_smarty_tpl->tpl_vars["subval"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["subval"]->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['val']->value['sub_category']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["subval"]->key => $_smarty_tpl->tpl_vars["subval"]->value){
$_smarty_tpl->tpl_vars["subval"]->_loop = true;
?>
											<span style="padding-left:5px;"><input type="checkbox" id="c_<?php echo $_smarty_tpl->tpl_vars['subval']->value['c_id'];?>
" name="c_id[]" value="<?php echo $_smarty_tpl->tpl_vars['subval']->value['c_id'];?>
"<?php if ($_smarty_tpl->tpl_vars['subval']->value['checked']){?>checked="checked"<?php }?>><label for="c_<?php echo $_smarty_tpl->tpl_vars['subval']->value['c_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['subval']->value['c_name'];?>
</label></span>
                      <?php } ?>
                      </div>
                      <?php }?>
										<?php } ?>
									</div>
								</li>
								<li>
									<label class="desc">
										<?php echo @LANG_BOOKS_EDIT_SETTAG;?>

									</label>
									<div id="tag"></div>
								</li>
								<li>
									<label class="desc">
										<?php echo @LANG_BOOKS_EDIT_DESC;?>

									</label>
									<div>
										<textarea tabindex="2" cols="50" rows="5" class="field textarea medium" name="b_description" ><?php echo $_smarty_tpl->tpl_vars['data']->value['b_description'];?>
</textarea>
									</div>
								</li>
                 <li>
                  <label  class="desc">
                    <?php echo @LANG_BOOKS_EDIT_ORDER;?>

                  </label>
                  <div>
                    <input type="text" maxlength="5" class="field text small" id="b_order" name="b_order" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['b_order'];?>
" />
                  </div>
                </li>
<!--
<?php if (@MEMBER==0){?>
                <li>
                  <label  class="desc">
                    <?php echo @LANG_BOOKS_EDIT_NEWBOOK;?>

                  </label>
                  <div>
                    <label><input type="radio" name="b_top" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['b_top']=='1'){?>checked="checked"<?php }?>/> <?php echo @LANG_CONST_YES;?>
</label>
                    <label><input type="radio" name="b_top" value="0" <?php if ($_smarty_tpl->tpl_vars['data']->value['b_top']!='1'){?>checked="checked"<?php }?> /> <?php echo @LANG_CONST_NO;?>
</label>
                  </div>
                </li>
<?php }?>
-->
								<li>
									<label  class="desc">
										<?php echo @LANG_BOOKS_EDIT_ISVISIBLE;?>

									</label>
									<div>
										<label><input type="radio" name="b_status" value="1" <?php if ($_smarty_tpl->tpl_vars['data']->value['b_status']!='0'){?>checked="checked"<?php }?>/> <?php echo @LANG_CONST_VISIBLE;?>
</label> 
										<label><input type="radio" name="b_status" value="0" <?php if ($_smarty_tpl->tpl_vars['data']->value['b_status']=='0'){?>checked="checked"<?php }?> /> <?php echo @LANG_CONST_INVISIBLE;?>
</label> 
									</div>
								</li>
								<li class="buttons">

									<input type="submit" value="<?php echo @LANG_BUTTON_SAVE;?>
" class="submit" />
									<input type="button" value="<?php echo @LANG_BUTTON_CANCEL;?>
" onclick="javascript:history.go(-1);"/>
								</li>
							</ul>
							<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['b_id'];?>
" />
							<input type="hidden" name="icons_data" class="icons_data" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['icons_data'];?>
" />
						</form>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<input type="hidden" class="writer_data" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['writer_data'];?>
" />
<input type="hidden" class="cowriters_data" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['cowriter_data'];?>
" />
<input type="hidden" class="l_data" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['link_data'];?>
" />

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