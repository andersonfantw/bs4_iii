<?php /* Smarty version Smarty-3.1.7, created on 2016-08-30 10:15:05
         compiled from "/var/www/html/bs4/view/include/header_adminlogin.tpl" */ ?>
<?php /*%%SmartyHeaderCode:33402230057c4ec29392708-52959094%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b9934227a4f4c9670759d5f5b957a02010ad6b3a' => 
    array (
      0 => '/var/www/html/bs4/view/include/header_adminlogin.tpl',
      1 => 1471795653,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '33402230057c4ec29392708-52959094',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_57c4ec2ab6291',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57c4ec2ab6291')) {function content_57c4ec2ab6291($_smarty_tpl) {?><div id="topMenu">
	<?php if (@CONFIG_BACKEND){?>
	<a class="MenuItem" href="<?php echo @WEB_URL;?>
/backend/bookshelf_index.php?bs=<?php echo $_SESSION['site_bsid'];?>
" target="_blank"><?php echo @LANG_TOPMENU_BACKEND;?>
</a>
	<?php }?>
	<?php if (@CONFIG_CONVERT){?>
	<a class="MenuItem" href="javascript:;" id="userconvert"><?php echo @LANG_TOPMENU_ASSISTANT;?>
</a>
	<?php }?>

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