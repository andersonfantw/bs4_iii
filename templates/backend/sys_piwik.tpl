<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_analyze.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="js/validation/sys_analyze.js"></script>
<{/block}>
<{block name="content"}>
			<div id="main-content">
				<{if $status_code !=''}>
				<script>setTimeout(function(){jQuery('#status_bar').fadeOut('slow');}, 2000);</script>
				<div class="response-msg ui-corner-all <{$status_code}>" id="status_bar">
				  <{$status_desc}>
				</div>
				<{/if}>
				<div class="clearfix"></div>
        <div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all form-container">
				  <a href="https://itunes.apple.com/us/app/piwik-mobile-2/id737216887?mt=8" title="Piwik Access Url: <{$smarty.const.HttpExternalIPPort}>/plugin/piwik/, Username: viewer, Password:"><img src="<{$smarty.const.WEB_URL}>/images/appstore.png"></a>
				  <a href="https://play.google.com/store/apps/details?id=org.piwik.mobile2" title="Piwik Access Url: <{$smarty.const.HttpExternalIPPort}>/plugin/piwik/, Username: viewer, Password:"><img src="<{$smarty.const.WEB_URL}>/images/googleplay.png"></a>
				  <br /><br />
					<iframe src="/plugin/piwik/index.php?module=Widgetize&action=iframe&moduleToWidgetize=Dashboard&actionToWidgetize=index&idSite=1&period=week&date=yesterday&token_auth=eab804d6a540b4a8bda29fb3bdeba7f3" frameborder="0" marginheight="0" marginwidth="0" width="1000" height="1400"></iframe>
        </div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
