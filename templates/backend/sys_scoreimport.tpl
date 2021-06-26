<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<script src="<{$smarty.const.WEB_URL}>/scripts/loader.class.js" type="text/javascript"></script>
<script src="<{$smarty.const.WEB_URL}>/plugin/scoreimport/scripts/loader.js" type="text/javascript"></script>
<script type="text/javascript">
var mode = <{$mode}>;
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
					<h3><{$smarty.const.LANG_SCOREIMPORT}></h3>
				</div>
					<div class="other">						
						<div class="button float-right">
&nbsp;
						</div>
						<div class="clearfix"></div>
					</div>				
				<div class="hastable" id="body">
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
