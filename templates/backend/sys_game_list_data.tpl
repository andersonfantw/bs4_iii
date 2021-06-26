<{foreach from=$data item="val" name=myloop}>   
	<tr<{if $smarty.foreach.myloop.iteration is even}> class="alt"<{/if}>>
		<td>
			<{$val.gr_name}>
		</td>
                <td>
                        <{$val.gr_amount}>
                </td>
                <td>
                        <{$val.gr_set}>
                </td>
		<td>
			<{$val.gr_classes}>
		</td>
		<td>
			<{$val.gr_reflect}>
		</td>
		<td>
			<{$val.gr_mark}>
		</td>
		<td>
			<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<{$smarty.const.LANG_BUTTON_DELETE}>" href="sys_game.php?type=delete&id=<{$val.gr_id}>&page=<{$smarty.get.page}>">
				<{$smarty.const.LANG_BUTTON_DELETE}>
			</a>
                        <a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<{$smarty.const.LANG_BUTTON_SETUP}>" href="sys_game.php?type=edit&id=<{$val.gr_id}>&page=<{$smarty.get.page}>">
                                <{$smarty.const.LANG_BUTTON_SETUP}>
                        </a>

		</td>
	</tr>
<{/foreach}>
