<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_index.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="../scripts/loader.class.js"></script>
<script type="text/javascript" src="../plugin/tag/scripts/loader_sys_tag.js"></script>
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
					<h3><{$smarty.const.LANG_TAG_LIST_TITLE}></h3>		
				</div>
				<div class="other">
					<div class="button float-right">
						<a href="sys_tagimport.php?m=1" class="btn ui-state-default"><span class="ui-icon ui-icon-circle-plus"></span><{$smarty.const.LANG_TAG_LIST_BTN_IMPORT}></a>
						<a href="sys_tagimport.php?m=2" class="btn ui-state-default"><span class="ui-icon ui-icon-circle-plus"></span><{$smarty.const.LANG_DIC_LIST_BTN_IMPORT}></a>
						<a href="sys_tag.php?type=exporttag" class="btn ui-state-default"><span class="ui-icon ui-icon-circle-plus"></span><{$smarty.const.LANG_TAG_LIST_BTN_EXPORT}></a>
						<a href="sys_tag.php?type=exportdic" class="btn ui-state-default"><span class="ui-icon ui-icon-circle-plus"></span><{$smarty.const.LANG_DIC_LIST_BTN_EXPORT}></a>
					</div>
					<div class="clearfix"></div>
				</div>	
				<br /><br />
				<div id="tag"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
