<{foreach from=$data item="val" name=myloop}>   
	<tr<{if $smarty.foreach.myloop.iteration is even}> class="alt"<{/if}>>
		<td>
			<{$val.type}>
		</td>
		<td>
			<a href="sys_<{$val.type}>.php?id=<{$val.id}>">
				<{$val.name}>
			</a>
			 | <{$val.key}>
		</td>
		<td>
			<{$val.description}>
		</td>
		<td>
			<{$val.createdate}>
		</td>
		<td>
			<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<{$smarty.const.LANG_BUTTON_EDIT}>" href="sys_allexam.php?type=edit&id=<{$val.id}>&page=<{$smarty.get.page}>&q=<{$q_str}>">
				<{$smarty.const.LANG_BUTTON_EDIT}>
			</a>
			<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<{$smarty.const.LANG_BUTTON_DELETE}>" href="sys_allexam.php?type=delete&id=<{$val.id}>&page=<{$smarty.get.page}>">
				<{$smarty.const.LANG_BUTTON_DELETE}>
			</a>
		</td>
	</tr>
<{/foreach}>