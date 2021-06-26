<{extends file="backend/user_index_base.tpl"}>
<{block name="head"}>
<link href="css/customize/index.css" rel="stylesheet" media="all" />
<{/block}>
<{block name="content"}>
    <div class="hastable">
					<br />
					<table cellspacing="0">
						<thead>
							<tr>								
								<td><{$smarty.const.LANG_INDEX_LIST_COL_SEQ}></td>							
								<td><{$smarty.const.LANG_INDEX_LIST_COL_BOOKSHEKFNAME}></td>
								<td><{$smarty.const.LANG_CONST_MEMBER}> / <{$smarty.const.LANG_CONST_PUBLIC}></td>
							</tr>
						</thead>
						<tbody>
						<{foreach from=$data item="val" name=myloop}>   
							<tr>
								<td>
									<{$val.bs_id}>
								</td>
								<td>
								<{if $val.bs_status==1}>
									<a href="bookshelf_index.php?bs=<{$val.bs_id}>"><{$val.bs_name}></a>
								<{else}>
									<span class="del"><{$val.bs_name}>(..)</span>
								<{/if}>
								</td>
								<td>
									<{if $val.is_member==1}>
										<{$smarty.const.LANG_CONST_MEMBER}>
									<{else}>
										<{$smarty.const.LANG_CONST_PUBLIC}>
									<{/if}>
								</td>
							</tr>
						<{/foreach}>	
						</tbody>
					</table>
				</div>
<{/block}>
