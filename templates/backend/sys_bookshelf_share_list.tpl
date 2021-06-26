<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_bookshelf_share.css" rel="stylesheet" media="all" />
<script type="text/javascript">
$(function() {
      $(".delete").click(function(event) {
          return confirm('<{$smarty.const.LANG_WARNING_DELETE_CONFIRM}>');
      }); 
      $(".delete2").click(function(event) {
          return confirm('<{$smarty.const.LANG_WARNING_DELETE_CONFIRM}>');
      });
});
</script>
<{/block}>
<{block name="content"}>
			<div id="main-content">
				<{if $status_code !=''}>
				<script>setTimeout(function(){jQuery('#status_bar').fadeOut('slow');}, 2000);</script>
				<div class="response-msg ui-corner-all <{$status_code}>" id="status_bar">
				  <{$status_desc}>
				</div>
				<{/if}>
				<div class="title">
					<h3><{$smarty.const.LANG_SHARE_GETTITLE}></h3>		
				</div>
					<div class="other">						
						<div class="button float-right">
							<a href="sys_bookshelf_share.php?type=source_add"  class="btn ui-state-default"><span class="ui-icon ui-icon-circle-plus"></span><{$smarty.const.LANG_SHARE_GET_BTN_ADD}></a>
						</div>
						<div class="clearfix"></div>
					</div>				
				<div class="hastable">
					<table cellspacing="0">
						<thead>
							<tr>
								<td><{$smarty.const.LANG_SHARE_GET_LIST_SOURCE}></td>
								<td><{$smarty.const.LANG_SHARE_GET_LIST_BOOKNUMBER}></td>
								<td><{$smarty.const.LANG_SHARE_GET_LIST_LAST_CONNECT_TIME}></td>
								<td><{$smarty.const.LANG_SHARE_GET_LIST_CONNECT_STATUS}></td>
								<td><{$smarty.const.LANG_CONST_MANAGEMENT}></td>
							</tr>
						</thead>
						<tbody id="books_table">
						<{foreach from=$data_share_source item="val" name=myloop}>   
							<tr<{if $smarty.foreach.myloop.iteration is even}> class="alt"<{/if}>>
								<td>
									<{$val.bsss_name}></a>
								</td>
								<td>
									<{$val.books_count}>
								</td>
								<td>
									<{$val.bsss_last_time}>
								</td>
								<td>
									<{if $val.bsss_status==200}><{$smarty.const.LANG_MESSAGE_UPDATE_SUCCESS}><{else}><a href="javascript:alert('
									<{if $val.bsss_status==403}><{$smarty.const.LANG_ERROR_NO_AUTH}>
									<{elseif $val.bsss_status==404}><{$smarty.const.LANG_ERROR_MISSING_SOURCE}>
									<{elseif $val.bsss_status==501}><{$smarty.const.LANG_ERROR_NO_DATA}>
									<{elseif $val.bsss_status==503}><{$smarty.const.LANG_ERROR_CONNECTION_FAIL}>
									<{elseif $val.bsss_status==504}><{$smarty.const.LANG_ERROR_CONNECTION_TIMEOUT}>
									<{/if}>
									');"><{$smarty.const.LANG_ERROR_UPDATE_FAIL}></a><{/if}>
								</td>
								<td>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<{$smarty.const.LANG_BUTTON_DELETE}>" href="sys_bookshelf_share.php?type=source_delete&id=<{$val.bsss_id}>&page=<{$smarty.get.page}>">
										<{$smarty.const.LANG_BUTTON_DELETE}>
									</a>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<{$smarty.const.LANG_BUTTON_CONNECT}>" href="sys_bookshelf_share.php?type=connection&id=<{$val.bsss_id}>&page=<{$smarty.get.page}>">
										<{$smarty.const.LANG_BUTTON_CONNECT}>
									</a>
								</td>
							</tr>
						<{/foreach}>	
						</tbody>
					</table>
				</div>
				<div class="title">
					<h3><{$smarty.const.LANG_SHARE_SHARETITLE}></h3>		
				</div>
					<div class="other">						
						<div class="button float-right">
							<a href="sys_bookshelf_share.php?type=add"  class="btn ui-state-default"><span class="ui-icon ui-icon-circle-plus"></span><{$smarty.const.LANG_SHARE_SET_BTN_ADD}></a>
						</div>
						<div class="clearfix"></div>
					</div>				
				<div class="hastable">
					<table cellspacing="0">
						<thead>
							<tr>
								<td><{$smarty.const.LANG_SHARE_SET_LIST_BOOKSHELFNAME}></td>
								<td><{$smarty.const.LANG_SHARE_SET_LIST_IP}></td>
								<td><{$smarty.const.LANG_SHARE_SET_LIST_ACCOUNT}></td>
								<td><{$smarty.const.LANG_SHARE_SET_LIST_PASSWORD}></td>
								<td><{$smarty.const.LANG_SHARE_SET_LIST_BOOKNUMBER}></td>
								<td><{$smarty.const.LANG_SHARE_SET_LIST_URL}></td>
								<td><{$smarty.const.LANG_CONST_MANAGEMENT}></td>
							</tr>
						</thead>
						<tbody id="books_table">
						<{foreach from=$data_share item="val" name=myloop}>   
							<tr<{if $smarty.foreach.myloop.iteration is even}> class="alt"<{/if}>>
								<td>
									<{$val.bs_name}>
								</td>
								<td>
									<{$val.bss_ip}>
								</td>
								<td>
									<{$val.bss_account}>
								</td>
								<td>
									<{$val.bss_password}>
								</td>
								<td>
									<{$val.books_count}>
								</td>
								<td>
									<{$smarty.const.HttpDomain}><{$smarty.const.DATA_SOURCE_PATH}>datasource.php?bs=<{$val.bs_id}>
								</td>
								<td>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete2" title="<{$smarty.const.LANG_BUTTON_DELETE}>" href="sys_bookshelf_share.php?type=delete&id=<{$val.bss_id}>&page=<{$smarty.get.page}>">
										<{$smarty.const.LANG_BUTTON_DELETE}>
									</a>
								</td>
							</tr>
						<{/foreach}>	
						</tbody>
					</table>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
