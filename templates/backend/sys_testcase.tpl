<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_index.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="../scripts/loader.class.js"></script>
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
					<h3><{$smarty.const.LANG_TESTCASE}></h3>		
				</div>
				<br />
				<br />
				<div class="hastable">
					<input type="button" value="Test DB" onclick="window.open('ttii/sys_tdb.php')" /><br />
					<input type="button" value="<{$smarty.const.LANG_TESTCASE_UPLOADQUEUE}>" onclick="window.open('ttii/sys_tuploadqueue.php')" /><br />
					<input type="button" value="Tag Document" onclick="window.open('ttii/sys_ttagdocument.php')" /><br />
					<input type="button" value="Test case 20160909" onclick="window.open('ttii/sys_treport160909.php')" /><br />
					<input type="button" value="Test case 20160919" onclick="window.open('ttii/sys_treport160919.php')" /><br />
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
