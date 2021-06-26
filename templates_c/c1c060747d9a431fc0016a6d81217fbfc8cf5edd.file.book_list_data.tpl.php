<?php /* Smarty version Smarty-3.1.7, created on 2017-11-16 23:39:09
         compiled from "/var/www/html/bs4/templates/backend/book_list_data.tpl" */ ?>
<?php /*%%SmartyHeaderCode:104103425157c4ec274d09f8-30787960%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c1c060747d9a431fc0016a6d81217fbfc8cf5edd' => 
    array (
      0 => '/var/www/html/bs4/templates/backend/book_list_data.tpl',
      1 => 1510846735,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '104103425157c4ec274d09f8-30787960',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_57c4ec277d145',
  'variables' => 
  array (
    'data' => 0,
    'val' => 0,
    'host_base' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57c4ec277d145')) {function content_57c4ec277d145($_smarty_tpl) {?><?php  $_smarty_tpl->tpl_vars["val"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["val"]->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['myloop']['iteration']=0;
foreach ($_from as $_smarty_tpl->tpl_vars["val"]->key => $_smarty_tpl->tpl_vars["val"]->value){
$_smarty_tpl->tpl_vars["val"]->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['myloop']['iteration']++;
?>   
							<tr<?php if (!(1 & $_smarty_tpl->getVariable('smarty')->value['foreach']['myloop']['iteration'])){?> class="alt"<?php }?>>
								<td>
									<?php echo $_smarty_tpl->tpl_vars['val']->value['b_id'];?>
<?php if ($_smarty_tpl->tpl_vars['val']->value['b_top']==1){?><br /><span class="new">[NEW]</span><?php }?>
								</td>
								<td>
									<img src="<?php echo $_smarty_tpl->tpl_vars['host_base']->value;?>
/uploadfiles/s_<?php echo $_smarty_tpl->tpl_vars['val']->value['filename'];?>
" />
								</td>
								<td>
<?php if ($_smarty_tpl->tpl_vars['val']->value['b_status']=='1'){?>
	 <?php echo $_smarty_tpl->tpl_vars['val']->value['b_name'];?>

<?php }else{ ?>
	<span class="off"><?php echo $_smarty_tpl->tpl_vars['val']->value['b_name'];?>
</span>
<?php }?>
<br /><br />
<?php if ($_smarty_tpl->tpl_vars['val']->value['writer_data']!=''||$_smarty_tpl->tpl_vars['val']->value['cowriter_data']!=''){?>
作者: <?php echo $_smarty_tpl->tpl_vars['val']->value['writer_data'];?>
<br />
共同作者: <?php echo $_smarty_tpl->tpl_vars['val']->value['cowriter_data'];?>
<br />
<br /><br />
<?php }?>

KEY: <?php echo $_smarty_tpl->tpl_vars['val']->value['b_key'];?>
<br />
EcocatID: <?php echo $_smarty_tpl->tpl_vars['val']->value['ecocat_id'];?>

								</td>
                <td>
                  <?php echo $_smarty_tpl->tpl_vars['val']->value['b_order'];?>

                </td>
<!--
                <td>
                  <?php if ($_smarty_tpl->tpl_vars['val']->value['b_top']==1){?>新書<?php }?>
                </td>

								<td>
									<?php if ($_smarty_tpl->tpl_vars['val']->value['b_status']=='on'){?>上架中<?php }else{ ?>下架中<?php }?>
								</td>
-->
                <td>
                  <?php echo $_smarty_tpl->tpl_vars['val']->value['b_views_webbook'];?>

                </td>
                <td>
                  <?php echo $_smarty_tpl->tpl_vars['val']->value['b_views_ibook'];?>

                </td>
								<td>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<?php echo @LANG_BTNHINT_EDIT;?>
" href="book.php?type=edit&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['b_id'];?>
&page=<?php echo $_GET['page'];?>
">
									<span class="ui-icon ui-icon-wrench"></span>
									</a>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<?php echo @LANG_BTNHINT_DELETE;?>
" href="book.php?type=delete&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['b_id'];?>
&page=<?php echo $_GET['page'];?>
">
										<span class="ui-icon ui-icon-circle-close"></span>
									</a>
                  <?php if (@MEMBER){?>
                  <a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<?php echo @LANG_BTNHINT_READINGTIME;?>
" href="book_readingtime.php?id=<?php echo $_smarty_tpl->tpl_vars['val']->value['b_id'];?>
&page=<?php echo $_GET['page'];?>
">
                    <span class="ui-icon ui-icon-clock"></span>
                  </a>
									<?php }?>
									<?php if (@MEMBER&&@CONFIG_MYBOOKSHELF){?>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<?php echo @LANG_BTNHINT_MYBOOKSHELF;?>
" href="book.php?type=users_bookshelf&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['b_id'];?>
&page=<?php echo $_GET['page'];?>
">
										<span class="ui-icon ui-icon-note"></span>
									</a>
									<?php }?>

								</td>
							</tr>
						<?php } ?>
<?php }} ?>