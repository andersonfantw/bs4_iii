<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_queue.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="../scripts/loader.class.js"></script>
<script type="text/javascript" src="../plugin/uploadqueue/scripts/loader_queue_edit.js"></script>
<script>
var lnettoken='<{$lnettoken}>';
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
					<h3><{$smarty.const.LANG_BOOKSHELFS}></h3>		
				</div>
				<div class="hastable">
					<div id="tags">
					</div>
					<div id="multiupload">
					</div>
				<div>
			</div>
<{/block}>