<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_system_account.css" rel="stylesheet" media="all" />
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
					<h3><{$smarty.const.LANG_ADMIN_LIST_TITLE}></h3>		
				</div>
				<div class="hastable">
					<table cellspacing="0">
						<thead>
							<tr>								
								<td><{$smarty.const.LANG_ADMIN_LIST_ACCOUNT}></td>
								<td><{$smarty.const.LANG_ADMIN_LIST_SETPASSWORD}></td>
							</tr>
						</thead>
						<tbody>
						<{foreach from=$data item="val" name=myloop}>   
							<tr<{if $smarty.foreach.myloop.iteration is even}> class="alt"<{/if}>>
								<td>
									<{$val.su_name}>
								</td>
								<td>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<{$smarty.const.LANG_ADMIN_LIST_SETPASSWORDTEXT}>" href="sys_system_account.php?type=edit&id=<{$val.su_id}>">
									<{$smarty.const.LANG_ADMIN_LIST_SETPASSWORDTEXT}>
									</a>
								</td>
							</tr>
						<{/foreach}>	
						</tbody>
					</table>
					<{if $pagebar}>
					<div id="pagebar"><{$pagebar->showPageBar()}></div>
					<{/if}>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
