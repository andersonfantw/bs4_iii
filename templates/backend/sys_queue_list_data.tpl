<{foreach from=$data item="val" name=myloop}>   
	<tr<{if $smarty.foreach.myloop.iteration is even}> class="alt"<{/if}>>
		<td>
			<{$val.createdate}>
		</td>
		<td>
			<{$val.q_id}>
		</td>
		<td>
			<{if $val.q_retry==3 && $val.status==0}>
				<span class="red">
				嚐試三次轉檔失敗<br />
			<{elseif $val.q_retry>0 and $val.q_retry<3}>
				<span class="gray">
				轉檔失敗，重新轉檔中<br />
			<{elseif $val.status==-1}>
				系統錯誤(可能是匯入lock)<br />
			<{elseif $val.status==-2}>
				檔案遺失<br />
			<{elseif $val.status==100}>
				匯入失敗<br />
			<{/if}>
			<{$val.q_name}>
			</span>
		</td>
		<td>
			<{$val.q_retry}>
		</td>
		<td>
			<{$val.data}>
		</td>
		<td>
				<{if $val.q_retry==3 || $val.status==0 || $val.status==-1 || $val.status<=100}>
			<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<{$smarty.const.LANG_BUTTON_DELETE}>" href="sys_queue.php?type=delete&id=<{$val.q_id}>&page=<{$smarty.get.page}>">
				<{$smarty.const.LANG_BUTTON_DELETE}>
			</a>
			<{/if}>
		</td>
	</tr>
<{/foreach}>