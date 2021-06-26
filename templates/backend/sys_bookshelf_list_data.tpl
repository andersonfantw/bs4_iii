<{foreach from=$data item="val" name=myloop}>   
							<tr<{if $smarty.foreach.myloop.iteration is even}> class="alt"<{/if}>>
								<td>
<!--
									<a href="index.php?op=sso&bs=<{$val.bs_id}>" target="_blank" title="<{$smarty.const.LANG_BTNHINT_EDIT}>"><{$val.bs_name}></a>
-->
<{if $val.bs_status==1}>
	<{$val.bs_name}>
<{else}>
	<span class="off"><{$val.bs_name}></span>
<{/if}>
								</td>
								<td>
									<{if $val.is_member==1}><{$smarty.const.LANG_CONST_MEMBER}><{else}><{$smarty.const.LANG_CONST_PUBLIC}><{/if}>
								</td>
								<td>
									<{$val.u_cname}>
								</td>
								<{if LicenseManager::chkAuth($smarty.const.MEMBER_MODE,MemberModeEnum::CENTRALIZE_ASSIGN)}>
								<td>
									<{if ($val.is_member==1) }>
									<a href="sys_bookshelf.php?type=group&id=<{$val.bs_id}>&page=<{$smarty.get.page}>&bookshelf_q=<{$q_str}>"><{$smarty.const.LANG_BOOKSHELFS_LIST_COL_GROUPSETTINGTEXT}></a>
									<{/if}>
								</td>
								<{/if}>
								<td>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<{$smarty.const.LANG_BUTTON_EDIT}>" href="sys_bookshelf.php?type=edit&id=<{$val.bs_id}>&u_name=<{$val.u_cname}>&page=<{$smarty.get.page}>&bookshelf_q=<{$q_str}>">
									<{$smarty.const.LANG_BUTTON_EDIT}>
									</a>
<!--
									<{if $val.bs_status==1}>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<{$smarty.const.LANG_BUTTON_INACTIVE}>" href="sys_bookshelf.php?type=disable&id=<{$val.bs_id}>&page=<{$smarty.get.page}>">
									<{$smarty.const.LANG_BUTTON_INACTIVE}>
									</a>
									<{else}>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<{$smarty.const.LANG_BUTTON_ACTIVE}>" href="sys_bookshelf.php?type=enable&id=<{$val.bs_id}>&page=<{$smarty.get.page}>">
									<{$smarty.const.LANG_BUTTON_ACTIVE}>
									</a>
									<{/if}>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<{$smarty.const.LANG_BUTTON_DELETE}>" href="sys_bookshelf.php?type=delete&id=<{$val.bs_id}>&page=<{$smarty.get.page}>">
										<{$smarty.const.LANG_BUTTON_DELETE}>
									</a>
-->
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<{$smarty.const.LANG_BUTTON_MANAGMENT}>"  href="index.php?op=sso&bs=<{$val.bs_id}>&acc=<{$val.u_name|replace:$smarty.const.LDAP_DOMAIN_PREFIX:''}>" target="webadmin">
										<{$smarty.const.LANG_BUTTON_MANAGMENT}>
									</a>
<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<{$smarty.const.LANG_CONST_SHOW_WEBSITE}>"  href="sys_bookshelf.php?type=do_gowebsite&uid=<{$val.u_id}>&acc=<{$val.u_name}>&name=<{$val.u_cname}>&bsid=<{$val.bs_id}>" target="_blank">
										<{$smarty.const.LANG_CONST_SHOW_WEBSITE}>
									</a>
								</td>
							</tr>
						<{/foreach}>	
