<?php /* Smarty version Smarty-3.1.7, created on 2017-08-28 16:34:29
         compiled from "/var/www/html/bs4/templates/backend/shortcut_list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:193193435559a3d5951c21d4-66137132%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6837fc42e21ab1d396d08dbc1e1e358b934e87c8' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/shortcut_list.tpl',
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
  'nocache_hash' => '193193435559a3d5951c21d4-66137132',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'bsname' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_59a3d5952d49c',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_59a3d5952d49c')) {function content_59a3d5952d49c($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/Storage/var/www/html/bs4/libs/Smarty/libs/plugins/modifier.replace.php';
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
	
<link href="css/customize/shortcut.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="../scripts/loader.class.js"></script>
<script type="text/javascript" src="../plugin/tag/scripts/loader_shortcut_list.js"></script>
<script type="text/javascript">
$(document).ready(function() { 
	//clear search field & change search text color 
    $("#q").focus(function() { 
        $("#q").css('color','#333333'); 
        var sv = $("#q").val(); //get current value of search field 
        if (sv == 'Search') { 
            $("#q").val(''); 
        } 
    }); 

    //post form on keydown or onclick, get results 
    $("#q").bind('keyup click', function() { 
        $.post("book.php?type=search_top10", //post 
            $("#search").serialize(),  
                function(data){ 
                    //show results if more than 2 characters 
                    if (data != 'hide') { 
                        $("#books_table").html(data); 
                    } 
            }); 
    }); 
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
				<div class="title">
					<h3><?php echo @LANG_SHORTCUT;?>
 - <?php echo @LANG_SHORTCUT_LIST_TITLE;?>
</h3>	
				</div>
					<div class="other">						
						<div class="button float-right">
							<a href="shortcut.php?type=add" class="btn ui-state-default"><span class="ui-icon ui-icon-circle-plus"></span><?php echo @LANG_SHORTCUT_BTN_ADD;?>
</a>
						</div>
						<div class="clearfix"></div>
					</div>				
				<div class="hastable">
					<table cellspacing="0">
						<thead>
							<tr>
								<td><?php echo @LANG_SHORTCUT_LIST_COL_IMG;?>
</td>
								<td><?php echo @LANG_SHORTCUT_LIST_COL_DESCRIPTION;?>
</td>
								<td><?php echo @LANG_CONST_MANAGEMENT;?>
</td>
							</tr>
						</thead>
						<tbody id="books_table">
						<?php  $_smarty_tpl->tpl_vars["val"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["val"]->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['myloop']['iteration']=0;
foreach ($_from as $_smarty_tpl->tpl_vars["val"]->key => $_smarty_tpl->tpl_vars["val"]->value){
$_smarty_tpl->tpl_vars["val"]->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['myloop']['iteration']++;
?>   
							<tr<?php if (!(1 & $_smarty_tpl->getVariable('smarty')->value['foreach']['myloop']['iteration'])){?> class="alt"<?php }?>>
								<td>
									<?php echo $_smarty_tpl->tpl_vars['val']->value['img_html'];?>

								</td>
								<td>
									<?php echo $_smarty_tpl->tpl_vars['val']->value['ts_description'];?>

								</td>
								<td>
									<?php if (MemberSystemFuncMapping::isEnable('edit')){?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<?php echo @LANG_BUTTON_EDIT;?>
" href="shortcut.php?type=edit&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['ts_id'];?>
&page=<?php echo $_GET['page'];?>
">
									<span class="ui-icon ui-icon-wrench"></span>
									</a>
									<?php }?>
									<?php if (MemberSystemFuncMapping::isEnable('delete')){?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<?php echo @LANG_BUTTON_DELETE;?>
" href="shortcut.php?type=delete&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['ts_id'];?>
&page=<?php echo $_GET['page'];?>
">
										<span class="ui-icon ui-icon-circle-close"></span>
									</a>
									<?php }?>
									<?php if ($_smarty_tpl->tpl_vars['val']->value['ts_status']==1){?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<?php echo @LANG_BUTTON_INACTIVE;?>
" href="shortcut.php?type=disable&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['ts_id'];?>
&page=<?php echo $_GET['page'];?>
">
										<span class="ui-icon ui-icon-pause"></span>
									</a>
									<?php }else{ ?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<?php echo @LANG_BUTTON_ACTIVE;?>
" href="shortcut.php?type=enable&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['ts_id'];?>
&page=<?php echo $_GET['page'];?>
">
										<span class="ui-icon ui-icon-play"></span>
									</a>
									<?php }?>
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