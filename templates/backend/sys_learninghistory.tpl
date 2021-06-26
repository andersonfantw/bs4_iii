<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_index.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="js/validation/sys_learninghistory.js"></script>
<script type="text/javascript" src="../scripts/loader.class.js"></script>
<script type="text/javascript" src="../plugin/chart/scripts/loader_learninghistory.js"></script>
<script type="text/javascript" src="../plugin/tag/scripts/loader_learninghistory.js"></script>
<{/block}>
<{block name="content"}>
<div id="chart-panel">
	<p>
	<{if $smarty.get.id==''}>
		ALL
	<{else}>
		<{$name}>
	<{/if}>
	&nbsp;
	Subject:
	<span id="subject"></span>
	</p>
</div>
<{/block}>
