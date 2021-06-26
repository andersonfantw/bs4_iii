<?php /* Smarty version Smarty-3.1.7, created on 2017-07-11 08:04:58
         compiled from "/var/www/html/bs4/templates/backend/sys_bookshelf_edit_account_data.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4693175775964162aea6181-31377672%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bab7ea7b4111bc2dac40f1c027cdc218d6b87b71' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/sys_bookshelf_edit_account_data.tpl',
      1 => 1471795383,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4693175775964162aea6181-31377672',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'account_data' => 0,
    'val' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5964162af0816',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5964162af0816')) {function content_5964162af0816($_smarty_tpl) {?><?php  $_smarty_tpl->tpl_vars["val"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["val"]->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['account_data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["val"]->key => $_smarty_tpl->tpl_vars["val"]->value){
$_smarty_tpl->tpl_vars["val"]->_loop = true;
?>
	<option value="<?php echo $_smarty_tpl->tpl_vars['val']->value['u_id'];?>
" <?php if ($_smarty_tpl->tpl_vars['val']->value['u_id']==$_GET['uid']){?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['val']->value['u_cname'];?>
</option>
<?php } ?><?php }} ?>