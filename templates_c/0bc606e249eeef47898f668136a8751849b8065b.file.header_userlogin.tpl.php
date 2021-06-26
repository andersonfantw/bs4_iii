<?php /* Smarty version Smarty-3.1.7, created on 2016-09-07 01:58:26
         compiled from "/var/www/html/bs4/view/include/header_userlogin.tpl" */ ?>
<?php /*%%SmartyHeaderCode:85758029257cf03c2ecbf46-45565530%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0bc606e249eeef47898f668136a8751849b8065b' => 
    array (
      0 => '/var/www/html/bs4/view/include/header_userlogin.tpl',
      1 => 1471795653,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '85758029257cf03c2ecbf46-45565530',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_57cf03c302112',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57cf03c302112')) {function content_57cf03c302112($_smarty_tpl) {?><div id="topMenu">
	<a class="MenuItem" href="<?php echo @WEB_URL;?>
/user/<?php echo $_SESSION['acc'];?>
/" target="_blank"><?php echo @LANG_TOPMENU_USERLIST;?>
</a>
	<a class="MenuItem" href="<?php echo @WEB_URL;?>
/user/expired/" target="_blank"><?php echo @LANG_TOPMENU_EXPIREDLIST;?>
</a>
	<?php if (@ENABLE_GIANTVIEW&&(@GIANTVIEW_CHAT||@GIANTVIEW_SYSTEM)){?>
	<?php if (@GiantviewChat&&@BSGiantviewChat&&@GIANTVIEW_CHAT){?>
	<a id="Giantview_Chat" class="MenuItemRight" href="javascript:;"><?php echo @LANG_TOPMENU_CHAT;?>
</a>
	<?php }?>
	<?php if (@GiantviewSystem&&@BSGiantviewSystem&&@GIANTVIEW_SYSTEM){?>
	<a id="Giantview_Login" class="MenuItemRight" href="javascript:;"><?php echo @LANG_TOPMENU_LOGIN_GIANTVIEW;?>
</a>
	<?php }?>
	<?php }?>
</div><?php }} ?>