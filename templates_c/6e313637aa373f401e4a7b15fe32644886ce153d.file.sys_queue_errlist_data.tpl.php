<?php /* Smarty version Smarty-3.1.7, created on 2019-01-21 14:42:07
         compiled from "/var/www/html/bs4/templates/backend/sys_queue_errlist_data.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6727002205b600a55092588-35795166%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6e313637aa373f401e4a7b15fe32644886ce153d' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/sys_queue_errlist_data.tpl',
      1 => 1548052922,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6727002205b600a55092588-35795166',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_5b600a5513517',
  'variables' => 
  array (
    'data' => 0,
    'val' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5b600a5513517')) {function content_5b600a5513517($_smarty_tpl) {?><?php  $_smarty_tpl->tpl_vars["val"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["val"]->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['myloop']['iteration']=0;
foreach ($_from as $_smarty_tpl->tpl_vars["val"]->key => $_smarty_tpl->tpl_vars["val"]->value){
$_smarty_tpl->tpl_vars["val"]->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['myloop']['iteration']++;
?>   
	<tr<?php if (!(1 & $_smarty_tpl->getVariable('smarty')->value['foreach']['myloop']['iteration'])){?> class="alt"<?php }?>>
		<td>
			<?php echo $_smarty_tpl->tpl_vars['val']->value['createdate'];?>

		</td>
		<td>
			<?php echo $_smarty_tpl->tpl_vars['val']->value['q_id'];?>

		</td>
		<td>
			<?php if ($_smarty_tpl->tpl_vars['val']->value['q_retry']==3&&$_smarty_tpl->tpl_vars['val']->value['status']==0){?>
				<span class="red">
				嚐試三次轉檔失敗<br />
			<?php }elseif($_smarty_tpl->tpl_vars['val']->value['q_retry']>0&&$_smarty_tpl->tpl_vars['val']->value['q_retry']<3){?>
				<span class="gray">
				轉檔失敗，重新轉檔中<br />
			<?php }elseif($_smarty_tpl->tpl_vars['val']->value['status']==-1){?>
				系統錯誤(可能是匯入lock)<br />
			<?php }elseif($_smarty_tpl->tpl_vars['val']->value['status']==-2){?>
				檔案遺失<br />
			<?php }elseif($_smarty_tpl->tpl_vars['val']->value['status']==100){?>
				匯入失敗<br />
			<?php }?>
			<?php echo $_smarty_tpl->tpl_vars['val']->value['q_name'];?>

			</span>
		</td>
		<td>
			<?php echo $_smarty_tpl->tpl_vars['val']->value['q_retry'];?>

		</td>
		<td>
			<?php echo $_smarty_tpl->tpl_vars['val']->value['data'];?>

		</td>
		<td>
				<?php if (($_smarty_tpl->tpl_vars['val']->value['q_retry']==3||$_smarty_tpl->tpl_vars['val']->value['status']==0||$_smarty_tpl->tpl_vars['val']->value['status']==-1||$_smarty_tpl->tpl_vars['val']->value['status']<=100)&&$_smarty_tpl->tpl_vars['val']->value['isdelete']==0){?>
			<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<?php echo @LANG_BUTTON_DELETE;?>
" href="sys_queue_err.php?type=delete&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['q_id'];?>
&page=<?php echo $_GET['page'];?>
">
				<?php echo @LANG_BUTTON_DELETE;?>

			</a>
			<?php }?>
		</td>
	</tr>
<?php } ?>
<?php }} ?>