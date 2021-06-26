<{extends file="backend/base.tpl"}>
<{block name="head"}>
<script src="<{$smarty.const.WEB_URL}>/scripts/loader.class.js" type="text/javascript"></script>
<script src="<{$smarty.const.WEB_URL}>/plugin/dataimport/scripts/loader.js" type="text/javascript"></script>
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
					<h3><{$smarty.const.LANG_CONVERT}> - <{$smarty.get.cn}></h3>		
				</div>
					<div class="other">						
						<div class="button float-right">
&nbsp;
						</div>
						<div class="clearfix"></div>
					</div>				
				<div class="hastable" id="body">
<!--
					<div id="format"></div>
					<div id="dropbox">
						<input type="file" />
						<div id="upload"></div>
					</div>
					<div id="ecocat_upload_container"></div>
<script type="text/javascript">
// Parse URL Queries Method
$(document).ready(function(){
	$(function() {
		$("#dropbox input, #multiple").html5Uploader({
			name: "foo",
			postUrl: "EcocatConnector.php?cmd=ConvertProcess"	
		});
	});
});
</script>
-->
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
