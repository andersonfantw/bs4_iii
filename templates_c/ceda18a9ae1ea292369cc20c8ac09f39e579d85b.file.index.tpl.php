<?php /* Smarty version Smarty-3.1.7, created on 2016-10-17 22:32:47
         compiled from "/var/www/html/bs4/templates/backend/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:140906063457cfcf64cfbd36-60749786%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ceda18a9ae1ea292369cc20c8ac09f39e579d85b' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/index.tpl',
      1 => 1471795382,
      2 => 'file',
    ),
    '8f272fb253c5da00c5e8781f1de12ec0e142485c' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/user_index_base.tpl',
      1 => 1474386796,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '140906063457cfcf64cfbd36-60749786',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_57cfcf64e6a1f',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57cfcf64e6a1f')) {function content_57cfcf64e6a1f($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo @LANG_TITLE;?>
</title>
	<link href="css/style.css" rel="stylesheet" media="all" />
        <script type="text/javascript" src="../scripts/jquery-1.11.2.min.js"></script>
        <script type="text/javascript" src="../scripts/jquery-ui-1.11.4.custom.min.js"></script>
	<script type="text/javascript" src="js/superfish.js"></script>
	<script type="text/javascript" src="js/tooltip.js"></script>
	<script type="text/javascript" src="js/tablesorter.js"></script>
	<script type="text/javascript" src="js/tablesorter-pager.js"></script>
	<script type="text/javascript" src="js/cookie.js"></script>
	<script type="text/javascript" src="js/custom.js"></script>
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
	
<link href="css/customize/index.css" rel="stylesheet" media="all" />

</head>
<body>
	<div id="header">
		<div id="top-menu">
			<a href="index.php?op=logout" title="<?php echo @LANG_BUTTON_LOGOUT;?>
"><?php echo @LANG_BUTTON_LOGOUT;?>
</a>
		</div>
		<div id="sitename">
			<a href="index.php" class="logo float-left" title="<?php echo @LANG_TITLE;?>
"><?php echo @LANG_TITLE;?>
</a>			
		</div>
<!--
		<?php if (@MEMBER_SYSTEM=='self'){?>
		<ul id="navigation" class="sf-navbar">
			<li>
				<a href="account.php"><?php echo @LANG_ACCOUNT;?>
</a>
			</li>
		</ul>
		<?php }?>
-->
	</div>	
  <div id="page-wrapper" class="fixed">
    <div id="main-wrapper">
    
    <div class="hastable">
					<br />
					<table cellspacing="0">
						<thead>
							<tr>								
								<td><?php echo @LANG_INDEX_LIST_COL_SEQ;?>
</td>							
								<td><?php echo @LANG_INDEX_LIST_COL_BOOKSHEKFNAME;?>
</td>
								<td><?php echo @LANG_CONST_MEMBER;?>
 / <?php echo @LANG_CONST_PUBLIC;?>
</td>
							</tr>
						</thead>
						<tbody>
						<?php  $_smarty_tpl->tpl_vars["val"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["val"]->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["val"]->key => $_smarty_tpl->tpl_vars["val"]->value){
$_smarty_tpl->tpl_vars["val"]->_loop = true;
?>   
							<tr>
								<td>
									<?php echo $_smarty_tpl->tpl_vars['val']->value['bs_id'];?>

								</td>
								<td>
								<?php if ($_smarty_tpl->tpl_vars['val']->value['bs_status']==1){?>
									<a href="bookshelf_index.php?bs=<?php echo $_smarty_tpl->tpl_vars['val']->value['bs_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['val']->value['bs_name'];?>
</a>
								<?php }else{ ?>
									<span class="del"><?php echo $_smarty_tpl->tpl_vars['val']->value['bs_name'];?>
(..)</span>
								<?php }?>
								</td>
								<td>
									<?php if ($_smarty_tpl->tpl_vars['val']->value['is_member']==1){?>
										<?php echo @LANG_CONST_MEMBER;?>

									<?php }else{ ?>
										<?php echo @LANG_CONST_PUBLIC;?>

									<?php }?>
								</td>
							</tr>
						<?php } ?>	
						</tbody>
					</table>
				</div>

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