<?php /* Smarty version Smarty-3.1.7, created on 2018-11-23 14:15:53
         compiled from "/var/www/html/bs4/templates/backend/sys_allexam_list_data.tpl" */ ?>
<?php /*%%SmartyHeaderCode:862795775bf79b1945d788-16068848%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ad3fb4f946babf3498747d47d77a616a071dcd0c' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/sys_allexam_list_data.tpl',
      1 => 1471795383,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '862795775bf79b1945d788-16068848',
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
  'unifunc' => 'content_5bf79b19608ad',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bf79b19608ad')) {function content_5bf79b19608ad($_smarty_tpl) {?><?php  $_smarty_tpl->tpl_vars["val"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["val"]->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['myloop']['iteration']=0;
foreach ($_from as $_smarty_tpl->tpl_vars["val"]->key => $_smarty_tpl->tpl_vars["val"]->value){
$_smarty_tpl->tpl_vars["val"]->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['myloop']['iteration']++;
?>   
	<tr<?php if (!(1 & $_smarty_tpl->getVariable('smarty')->value['foreach']['myloop']['iteration'])){?> class="alt"<?php }?>>
		<td>
			<?php echo $_smarty_tpl->tpl_vars['val']->value['type'];?>

		</td>
		<td>
			<a href="sys_<?php echo $_smarty_tpl->tpl_vars['val']->value['type'];?>
.php?id=<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
">
				<?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>

			</a>
			 | <?php echo $_smarty_tpl->tpl_vars['val']->value['key'];?>

		</td>
		<td>
			<?php echo $_smarty_tpl->tpl_vars['val']->value['description'];?>

		</td>
		<td>
			<?php echo $_smarty_tpl->tpl_vars['val']->value['createdate'];?>

		</td>
		<td>
			<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<?php echo @LANG_BUTTON_EDIT;?>
" href="sys_allexam.php?type=edit&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
&page=<?php echo $_GET['page'];?>
&q=<?php echo $_smarty_tpl->tpl_vars['q_str']->value;?>
">
				<?php echo @LANG_BUTTON_EDIT;?>

			</a>
			<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<?php echo @LANG_BUTTON_DELETE;?>
" href="sys_allexam.php?type=delete&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
&page=<?php echo $_GET['page'];?>
">
				<?php echo @LANG_BUTTON_DELETE;?>

			</a>
		</td>
	</tr>
<?php } ?><?php }} ?>