<?php /* Smarty version Smarty-3.1.7, created on 2016-09-07 16:28:01
         compiled from "/var/www/html/bs4/templates/backend/category_edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:106995247757cfcf914c7104-48436119%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '76f9f69c4754a4de62c54689580fce54fd1dbe26' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/category_edit.tpl',
      1 => 1471795381,
      2 => 'file',
    ),
    '3b120c05e76ffd4582aa50ef424756c469763a8a' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/base.tpl',
      1 => 1471795381,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '106995247757cfcf914c7104-48436119',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'bsname' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_57cfcf915baaf',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57cfcf915baaf')) {function content_57cfcf915baaf($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/Storage/var/www/html/bs4/libs/Smarty/libs/plugins/modifier.replace.php';
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
	
<link href="css/customize/category.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="js/validation/category.js"></script>

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
					<div class="portlet-header ui-widget-header"><?php echo @LANG_CATE_EDIT_TITLE;?>
</div>
					<div class="portlet-content">
						<form action="category.php?type=<?php if ($_GET['type']=='add'){?>do_add<?php }else{ ?>do_update<?php }?>&page=<?php echo $_GET['page'];?>
" method="post" enctype="multipart/form-data" class="forms" name="form" >
							<ul>
								<li>
									<label  class="desc">
										*<?php echo @LANG_CATE_EDIT_CATENAME;?>

									</label>
									<div>
										<input type="text" tabindex="1" maxlength="255" class="field text small" id="c_name" name="c_name" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['c_name'];?>
" />
									</div>
								</li>
                <li>
                  <label  class="desc">
                    *<?php echo @LANG_CATE_EDIT_BELONG;?>

                  </label>
                  <div>
                    <select name="c_parent_id">
                      <option value="0"><?php echo @LANG_CONST_NONE;?>
</option>
                      <?php  $_smarty_tpl->tpl_vars["val"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["val"]->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['parent_data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["val"]->key => $_smarty_tpl->tpl_vars["val"]->value){
$_smarty_tpl->tpl_vars["val"]->_loop = true;
?>
                      <option value="<?php echo $_smarty_tpl->tpl_vars['val']->value['c_id'];?>
"<?php if ($_smarty_tpl->tpl_vars['val']->value['c_id']==$_GET['pid']||$_smarty_tpl->tpl_vars['val']->value['c_id']==$_smarty_tpl->tpl_vars['data']->value['c_parent_id']){?> selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['val']->value['c_name'];?>
</option>
                      <?php } ?>
                    </select>
                  </div>
                </li>
								<li>
									<label  class="desc">
										<?php echo @LANG_CATE_EDIT_DESC;?>

									</label>
									<div>
										<textarea tabindex="2" cols="50" rows="5" class="field textarea small" name="c_description" ><?php echo $_smarty_tpl->tpl_vars['data']->value['c_description'];?>
</textarea>
									</div>
								</li>
                <li>
                  <label  class="desc">
                    <?php echo @LANG_CATE_EDIT_ORDER;?>

                  </label>
                  <div>
                    <input type="text" tabindex="1" maxlength="5" class="field text small" id="c_order" name="c_order" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['c_order'];?>
" />
                  </div>
                </li>
                 <li class="buttons">
                  <input type="submit" value="<?php echo @LANG_BUTTON_SAVE;?>
" class="submit" />
                  <input type="button" value="<?php echo @LANG_BUTTON_CANCEL;?>
" onclick="javascript:history.go(-1);"/>
                </li>
							</ul>
							<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['c_id'];?>
" />
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