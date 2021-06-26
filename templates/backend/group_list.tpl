<{extends file="backend/base.tpl"}>
<{block name="head"}>
<link href="css/customize/group.css" rel="stylesheet" media="all" />
<script type="text/javascript">
$(function() {
      $(".delete").click(function(event) {
          return confirm('<{$smarty.const.LANG_WARNING_DELETE_CONFIRM}>');
      });
});
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
					<h3><{$smarty.const.LANG_GROUP}> - <{$smarty.const.LANG_GROUP_LIST_TITLE}></h3>
				</div>
				<div>
						<form action="group.php?type=search" method="post" class="forms" name="form" id="search">
							<ul>
								<li>
									<label  class="desc">
										<{$smarty.const.LANG_GROUP_LIST_SEARCH}>
									</label>
									<div>
										<input type="text"maxlength="255" class="field text small" id="q" name="q" value="<{$smarty.get.q}>" /><input type="submit" value="<{$smarty.const.LANG_BUTTON_SEARCH}>" class="submit" />
										<input type="hidden" name="type" value="search" />
									</div>
								</li>
							</ul>
						</form>
				</div>
					<div class="other">
						<div class="button float-right">
							<{if LicenseManager::chkAuth($smarty.const.MEMBER_MODE,MemberModeEnum::INDIVIDUAL) && MemberSystemFuncMapping::isEnable('add')}>
								<a href="group.php?type=add" class="btn ui-state-default"><span class="ui-icon ui-icon-circle-plus"></span><{$smarty.const.LANG_GROUP_LIST_BTN_ADD}></a>
								<{if LicenseManager::chkAuth($smarty.const.MEMBER_SYSTEM,MemberSystemEnum::Import) || LicenseManager::chkAuth($smarty.const.MEMBER_SYSTEM,MemberSystemEnum::Regist)}>
									<{if LicenseManager::chkAuth($smarty.const.BACKEND_IMPORT_MODE,ImportManagerModeEnum::GROUP)}>
									<{if $smarty.const.ENABLE_IMPOERT}><a href="import.php?cmd=import_group" class="btn ui-state-default"><span class="ui-icon ui-icon-arrowreturnthick-1-s"></span><{$smarty.const.LANG_GROUP_LIST_BTN_IMPORT}></a><{/if}>
									<{if $smarty.const.ENABLE_EXPOERT}><a href="import.php?cmd=export_group"  class="btn ui-state-default"><span class="ui-icon ui-icon-arrowreturnthick-1-n"></span><{$smarty.const.LANG_GROUP_LIST_BTN_EXPORT}></a><{/if}>
									<{/if}>
								<{/if}>
							<{/if}>
							<br />
						</div>
						<div class="clearfix"></div>
					</div>
				<div class="hastable">
					<table cellspacing="0">
						<thead>
							<tr>
								<td><{$smarty.const.LANG_GROUP_LIST_COL_GROUPNAME}></td>
								<{if LicenseManager::chkAuth($smarty.const.MEMBER_MODE,MemberModeEnum::INDIVIDUAL)}>
									<td><{$smarty.const.LANG_GROUP_LIST_COL_NUM}></td>
								<{else}>
									<td><{$smarty.const.LANG_GROUP_LIST_COL_USERS}></td>
								<{/if}>
								<td><{$smarty.const.LANG_CONST_MANAGEMENT}></td>
							</tr>
						</thead>
						<tbody>
						<{foreach from=$data item="val" name=myloop}>
							<tr<{if $smarty.foreach.myloop.iteration is even}> class="alt"<{/if}>>
								<td>
									<{$val.g_name}>
								</td>
                <td style="text-align:center;">
		  <{if LicenseManager::chkAuth($smarty.const.MEMBER_MODE,MemberModeEnum::INDIVIDUAL)}>
                    <span style="text-decoration:underline;margin-bottom:3px;"><a href="bookshelf_user.php?gid=<{$val.g_id}>"><{$val.bu_total}></span>
                  <{else}>
                    <span style="text-decoration:underline;margin-bottom:3px;"><a href="bookshelf_user.php?gid=<{$val.g_id}>"><{$smarty.const.LANG_GROUP_LIST_COL_USERLIST}></span>
                  <{/if}>
		  <{if LicenseManager::chkAuth($smarty.const.MEMBER_MODE,MemberModeEnum::INDIVIDUAL)}>
                  <br /><br />
                  (<a href="bookshelf_user.php?type=add&gid=<{$val.g_id}>"><span style="text-decoration:underline"><{$smarty.const.LANG_GROUP_LIST_COL_CREATEUSER}></span></a>)</a>
                  <{/if}>
                </td>
								<td>
									<{if MemberSystemFuncMapping::isEnable('edit')}>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<{$smarty.const.LANG_BUTTON_EDIT}>" href="group.php?type=edit&id=<{$val.g_id}>&gn=<{$val.g_name|base64_encode}>&page=<{$smarty.get.page}>">
									<span class="ui-icon ui-icon-wrench"></span>
									</a>
									<{/if}>
									<{if MemberSystemFuncMapping::isEnable('delete')}>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<{$smarty.const.LANG_BUTTON_DELETE}>" href="group.php?type=delete&id=<{$val.g_id}>&page=<{$smarty.get.page}>">
										<span class="ui-icon ui-icon-circle-close"></span>
									</a>
									<{/if}>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip chart" title="<{$smarty.const.LANG_BTNHINT_CHART}>" href="learninghistory.php?id=<{$val.key}>">
										<span class="ui-icon ui-icon-image"></span>
									</a>
								</td>
							</tr>
						<{/foreach}>
						</tbody>
					</table>
					<{if $pagebar}>
					<div id="pagebar"><{$pagebar->showPageBar()}></div>
					<{/if}>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
