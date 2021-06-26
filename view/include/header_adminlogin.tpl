<div id="topMenu">
	<{if $smarty.const.CONFIG_BACKEND }>
	<a class="MenuItem" href="<{$smarty.const.WEB_URL}>/backend/bookshelf_index.php?bs=<{$smarty.session.site_bsid}>" target="_blank"><{$smarty.const.LANG_TOPMENU_BACKEND}></a>
	<{/if}>
	<{if $smarty.const.CONFIG_CONVERT }>
	<a class="MenuItem" href="javascript:;" id="userconvert"><{$smarty.const.LANG_TOPMENU_ASSISTANT}></a>
	<{/if}>
<{*
	<a class="MenuItem" href="javascript:;"><{$smarty.const.LANG_TOPMENU_TRANSCRIPT}></a>
	<a class="MenuItem" href="http://lnet.cyberhood.net/cloudbook/" target="_blank"><{$smarty.const.LANG_TOPMENU_TEACHING_MATERIAL}></a>
*}>
	<{if $smarty.const.ENABLE_GIANTVIEW && ($smarty.const.GIANTVIEW_CHAT || $smarty.const.GIANTVIEW_SYSTEM) }>
	<{if $smarty.const.GiantviewChat && $smarty.const.BSGiantviewChat && $smarty.const.GIANTVIEW_CHAT}>
	<a id="Giantview_Chat" class="MenuItemRight" href="javascript:;"><{$smarty.const.LANG_TOPMENU_CHAT}></a>
	<{/if}>
	<{if $smarty.const.GiantviewSystem && $smarty.const.BSGiantviewSystem && $smarty.const.GIANTVIEW_SYSTEM}>
	<a id="Giantview_Login" class="MenuItemRight" href="javascript:;"><{$smarty.const.LANG_TOPMENU_LOGIN_GIANTVIEW}></a>
	<{/if}>
	<{/if}>
</div>