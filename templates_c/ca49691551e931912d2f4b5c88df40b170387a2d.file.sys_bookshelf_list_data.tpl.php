<?php /* Smarty version Smarty-3.1.7, created on 2019-12-26 14:11:30
         compiled from "/var/www/html/bs4/templates/backend/sys_bookshelf_list_data.tpl" */ ?>
<?php /*%%SmartyHeaderCode:91693286957c4ec1ecd19b4-08967585%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ca49691551e931912d2f4b5c88df40b170387a2d' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/sys_bookshelf_list_data.tpl',
      1 => 1577340686,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '91693286957c4ec1ecd19b4-08967585',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_57c4ec1f8d2f3',
  'variables' => 
  array (
    'data' => 0,
    'val' => 0,
    'q_str' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57c4ec1f8d2f3')) {function content_57c4ec1f8d2f3($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/Storage/var/www/html/bs4/libs/Smarty/libs/plugins/modifier.replace.php';
?><?php  $_smarty_tpl->tpl_vars["val"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["val"]->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['myloop']['iteration']=0;
foreach ($_from as $_smarty_tpl->tpl_vars["val"]->key => $_smarty_tpl->tpl_vars["val"]->value){
$_smarty_tpl->tpl_vars["val"]->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['myloop']['iteration']++;
?>   
							<tr<?php if (!(1 & $_smarty_tpl->getVariable('smarty')->value['foreach']['myloop']['iteration'])){?> class="alt"<?php }?>>
								<td>
<!--
									<a href="index.php?op=sso&bs=<?php echo $_smarty_tpl->tpl_vars['val']->value['bs_id'];?>
" target="_blank" title="<?php echo @LANG_BTNHINT_EDIT;?>
"><?php echo $_smarty_tpl->tpl_vars['val']->value['bs_name'];?>
</a>
-->
<?php if ($_smarty_tpl->tpl_vars['val']->value['bs_status']==1){?>
	<?php echo $_smarty_tpl->tpl_vars['val']->value['bs_name'];?>

<?php }else{ ?>
	<span class="off"><?php echo $_smarty_tpl->tpl_vars['val']->value['bs_name'];?>
</span>
<?php }?>
								</td>
								<td>
									<?php if ($_smarty_tpl->tpl_vars['val']->value['is_member']==1){?><?php echo @LANG_CONST_MEMBER;?>
<?php }else{ ?><?php echo @LANG_CONST_PUBLIC;?>
<?php }?>
								</td>
								<td>
									<?php echo $_smarty_tpl->tpl_vars['val']->value['u_cname'];?>

								</td>
								<?php if (LicenseManager::chkAuth(@MEMBER_MODE,MemberModeEnum::CENTRALIZE_ASSIGN)){?>
								<td>
									<?php if (($_smarty_tpl->tpl_vars['val']->value['is_member']==1)){?>
									<a href="sys_bookshelf.php?type=group&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['bs_id'];?>
&page=<?php echo $_GET['page'];?>
&bookshelf_q=<?php echo $_smarty_tpl->tpl_vars['q_str']->value;?>
"><?php echo @LANG_BOOKSHELFS_LIST_COL_GROUPSETTINGTEXT;?>
</a>
									<?php }?>
								</td>
								<?php }?>
								<td>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<?php echo @LANG_BUTTON_EDIT;?>
" href="sys_bookshelf.php?type=edit&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['bs_id'];?>
&u_name=<?php echo $_smarty_tpl->tpl_vars['val']->value['u_cname'];?>
&page=<?php echo $_GET['page'];?>
&bookshelf_q=<?php echo $_smarty_tpl->tpl_vars['q_str']->value;?>
">
									<?php echo @LANG_BUTTON_EDIT;?>

									</a>
<!--
									<?php if ($_smarty_tpl->tpl_vars['val']->value['bs_status']==1){?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<?php echo @LANG_BUTTON_INACTIVE;?>
" href="sys_bookshelf.php?type=disable&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['bs_id'];?>
&page=<?php echo $_GET['page'];?>
">
									<?php echo @LANG_BUTTON_INACTIVE;?>

									</a>
									<?php }else{ ?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<?php echo @LANG_BUTTON_ACTIVE;?>
" href="sys_bookshelf.php?type=enable&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['bs_id'];?>
&page=<?php echo $_GET['page'];?>
">
									<?php echo @LANG_BUTTON_ACTIVE;?>

									</a>
									<?php }?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<?php echo @LANG_BUTTON_DELETE;?>
" href="sys_bookshelf.php?type=delete&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['bs_id'];?>
&page=<?php echo $_GET['page'];?>
">
										<?php echo @LANG_BUTTON_DELETE;?>

									</a>
-->
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<?php echo @LANG_BUTTON_MANAGMENT;?>
"  href="index.php?op=sso&bs=<?php echo $_smarty_tpl->tpl_vars['val']->value['bs_id'];?>
&acc=<?php echo smarty_modifier_replace($_smarty_tpl->tpl_vars['val']->value['u_name'],@LDAP_DOMAIN_PREFIX,'');?>
" target="webadmin">
										<?php echo @LANG_BUTTON_MANAGMENT;?>

									</a>
<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<?php echo @LANG_CONST_SHOW_WEBSITE;?>
"  href="sys_bookshelf.php?type=do_gowebsite&uid=<?php echo $_smarty_tpl->tpl_vars['val']->value['u_id'];?>
&acc=<?php echo $_smarty_tpl->tpl_vars['val']->value['u_name'];?>
&name=<?php echo $_smarty_tpl->tpl_vars['val']->value['u_cname'];?>
&bsid=<?php echo $_smarty_tpl->tpl_vars['val']->value['bs_id'];?>
" target="_blank">
										<?php echo @LANG_CONST_SHOW_WEBSITE;?>

									</a>
								</td>
							</tr>
						<?php } ?>	
<?php }} ?>