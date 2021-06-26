<div id="topMenu">
	<a class="MenuItem" href="<{$smarty.const.WEB_URL}>/user/<{$smarty.session.acc}>/" target="_blank"><{$smarty.const.LANG_TOPMENU_USERLIST}></a>
	<a class="MenuItem" href="<{$smarty.const.WEB_URL}>/user/expired/" target="_blank"><{$smarty.const.LANG_TOPMENU_EXPIREDLIST}></a>
	<{if $smarty.const.ENABLE_GIANTVIEW && ($smarty.const.GIANTVIEW_CHAT || $smarty.const.GIANTVIEW_SYSTEM) }>
	<{if $smarty.const.GiantviewChat && $smarty.const.BSGiantviewChat && $smarty.const.GIANTVIEW_CHAT}>
	<a id="Giantview_Chat" class="MenuItemRight" href="javascript:;"><{$smarty.const.LANG_TOPMENU_CHAT}></a>
	<{/if}>
	<{if $smarty.const.GiantviewSystem && $smarty.const.BSGiantviewSystem && $smarty.const.GIANTVIEW_SYSTEM}>
	<a id="Giantview_Login" class="MenuItemRight" href="javascript:;"><{$smarty.const.LANG_TOPMENU_LOGIN_GIANTVIEW}></a>
	<{/if}>
	<{/if}>
</div>