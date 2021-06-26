						<{foreach from=$data item="val" name=myloop}>   
							<tr<{if $smarty.foreach.myloop.iteration is even}> class="alt"<{/if}>>
								<td>
									<{$val.u_cname}>
								</td>
								<td>
									<{$val.u_name}>
								</td>
								<td>
									<{if MemberSystemFuncMapping::isEnable('edit')}>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<{$smarty.const.LANG_BUTTON_EDIT}>" href="sys_account.php?type=edit&id=<{$val.u_id}>&q=<{$q_str}>">
									<{$smarty.const.LANG_BUTTON_EDIT}>
									</a>
									<{/if}>
									<{if MemberSystemFuncMapping::isEnable('delete')}>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<{$smarty.const.LANG_BUTTON_DELETE}>" href="sys_account.php?type=delete&id=<{$val.u_id}>&page=<{$smarty.get.page}>">
									<{$smarty.const.LANG_BUTTON_DELETE}>
									</a>
									<{/if}>
								</td>
							</tr>
						<{/foreach}>	