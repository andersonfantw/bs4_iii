<{extends file="backend/base.tpl"}>
<{block name="head"}>
<link href="css/customize/itutor.css" rel="stylesheet" media="all" />
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
					<h3><{$smarty.const.LANG_ITUTOR}></h3>		
				</div>
					<div class="other">						
						<div class="button float-right">							&nbsp;
						</div>
						<div class="clearfix"></div>
					</div>				
				<div class="hastable">
					<table cellspacing="0">
						<thead>
							<tr>
								<td><{$smarty.const.LANG_ITUTOR_LIST_TESTNAME}></td>
								<td><{$smarty.const.LANG_ITUTOR_LIST_SLIDECOUNT}></td>
								<td><{$smarty.const.LANG_ITUTOR_LIST_TOTALINTERACTION}></td>
								<td><{$smarty.const.LANG_ITUTOR_LIST_PORTIONS}></td>
								<td><{$smarty.const.LANG_CONST_MANAGEMENT}></td>
							</tr>
						</thead>
						<tbody>
						<{foreach from=$data item="val" name=myloop}>   
							<tr<{if $smarty.foreach.myloop.iteration is even}> class="alt"<{/if}>>
								<td>
									<{$val.i_name}>
								</td>
								<td>
									<{$val.i_slidecount}>
								</td>
								<td>
									<{$val.i_totalinteraction}>
								</td>
								<td>
									<{$val.num}>
								</td>
								<td>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<{$smarty.const.LANG_BTNHINT_EDIT}>" href="itutor.php?type=edit&id=<{$val.id}>&page=<{$smarty.get.page}>">
									<span class="ui-icon ui-icon-wrench"></span>
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
