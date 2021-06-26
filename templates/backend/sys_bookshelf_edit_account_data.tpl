<{foreach from=$account_data item="val" name=myloop}>
	<option value="<{$val.u_id}>" <{if $val.u_id==$smarty.get.uid}>selected<{/if}>><{$val.u_cname}></option>
<{/foreach}>