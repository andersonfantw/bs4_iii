<?php /* Smarty version Smarty-3.1.7, created on 2016-10-24 08:02:47
         compiled from "/var/www/html/bs4/templates/backend/sys_account_list_data.tpl" */ ?>
<?php /*%%SmartyHeaderCode:620829268580d4fa7da8091-23296485%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'eb02a3b753a7de400ffa9e07f88aec2c163b84a3' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/sys_account_list_data.tpl',
      1 => 1471795382,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '620829268580d4fa7da8091-23296485',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
    'val' => 0,
    'q_str' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_580d4fa7e28fa',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_580d4fa7e28fa')) {function content_580d4fa7e28fa($_smarty_tpl) {?>						<?php  $_smarty_tpl->tpl_vars["val"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["val"]->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['myloop']['iteration']=0;
foreach ($_from as $_smarty_tpl->tpl_vars["val"]->key => $_smarty_tpl->tpl_vars["val"]->value){
$_smarty_tpl->tpl_vars["val"]->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['myloop']['iteration']++;
?>   
							<tr<?php if (!(1 & $_smarty_tpl->getVariable('smarty')->value['foreach']['myloop']['iteration'])){?> class="alt"<?php }?>>
								<td>
									<?php echo $_smarty_tpl->tpl_vars['val']->value['u_cname'];?>

								</td>
								<td>
									<?php echo $_smarty_tpl->tpl_vars['val']->value['u_name'];?>

								</td>
								<td>
									<?php if (MemberSystemFuncMapping::isEnable('edit')){?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<?php echo @LANG_BUTTON_EDIT;?>
" href="sys_account.php?type=edit&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['u_id'];?>
&q=<?php echo $_smarty_tpl->tpl_vars['q_str']->value;?>
">
									<?php echo @LANG_BUTTON_EDIT;?>

									</a>
									<?php }?>
									<?php if (MemberSystemFuncMapping::isEnable('delete')){?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<?php echo @LANG_BUTTON_DELETE;?>
" href="sys_account.php?type=delete&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['u_id'];?>
&page=<?php echo $_GET['page'];?>
">
									<?php echo @LANG_BUTTON_DELETE;?>

									</a>
									<?php }?>
								</td>
							</tr>
						<?php } ?>	<?php }} ?>