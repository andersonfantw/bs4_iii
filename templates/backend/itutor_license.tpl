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
					<h3><{$smarty.const.LANG_ITUTOR}> - License expired!</h3>		
				</div>
				<div class="hastable">
				<br />
				Check <a href="http://cloudbook.cyberhood.net/cloudbook/licensebuy.php?service_id=<{$smarty.const.wonderbox_id}>" target="_blank">here</a> to get more infomation.
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
