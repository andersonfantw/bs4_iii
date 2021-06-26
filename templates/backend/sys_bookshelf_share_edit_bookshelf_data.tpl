<{foreach from=$bookshelf_data item="val" name=myloop}>
	<option value="<{$val.bs_id}>" <{if $val.bs_id==$data.bs_id}>selected<{/if}>><{$val.bs_name}></option>
<{/foreach}>