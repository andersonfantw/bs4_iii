<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_class.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="js/validation/sys_class.js"></script>
<script type="text/javascript" src="../scripts/loader.class.js"></script>
<script type="text/javascript" src="../plugin/meeting/scripts/loader_webadmin.js"></script>
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
			<h3><{$smarty.const.LANG_VCUBE}></h3>		
		</div>
		<br />
		<br />
		<div class="hastable">
		</div>
		<div class="clearfix"></div>
	</div>
	<div class="clearfix"></div>
<{/block}>
