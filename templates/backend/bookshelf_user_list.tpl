<{extends file="backend/base.tpl"}>
<{block name="head"}>
<link href="css/customize/bookshelf_user.css" rel="stylesheet" media="all" />
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
					<h3><{$smarty.const.LANG_GROUP}> - <{$smarty.const.LANG_GROUP_USERLIST_TITLE}>(<{$group_data.g_name}>)</h3>		
				</div>
				<div>
						<form action="bookshelf_user.php?type=search&gid=<{$smarty.get.gid}>" method="post" class="forms" name="form" id="search">
							<ul>
								<li>
									<label  class="desc">
										<{$smarty.const.LANG_GROUP_USERLIST_SEARCH}>
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
							<{if $smarty.get.gid}><a href="group.php"  class="btn ui-state-default"><span class="ui-icon ui-icon-arrowreturnthick-1-w"></span><{$smarty.const.LANG_GROUP_BTN_RETURN}></a><{/if}>
							<{if LicenseManager::chkAuth($smarty.const.MEMBER_MODE,MemberModeEnum::INDIVIDUAL) && MemberSystemFuncMapping::isEnable('add')}>
								<a href="bookshelf_user.php?type=add&gid=<{$smarty.get.gid}>"  class="btn ui-state-default"><span class="ui-icon ui-icon-circle-plus"></span><{$smarty.const.LANG_GROUP_BTN_ADD}></a>
								<{if LicenseManager::chkAuth($smarty.const.MEMBER_SYSTEM,MemberSystemEnum::Import) || LicenseManager::chkAuth($smarty.const.MEMBER_SYSTEM,MemberSystemEnum::Regist)}>
									<{if LicenseManager::chkAuth($smarty.const.BACKEND_IMPORT_MODE,ImportManagerModeEnum::USER)}>
									<{if $smarty.const.ENABLE_IMPOERT}><a href="import.php?cmd=import_user"  class="btn ui-state-default"><span class="ui-icon ui-icon-arrowreturnthick-1-s"></span><{$smarty.const.LANG_GROUP_USERLIST_BTN_IMPORT}></a><{/if}>
									<{if $smarty.const.ENABLE_EXPOERT}><a href="import.php?cmd=export_user"  class="btn ui-state-default"><span class="ui-icon ui-icon-arrowreturnthick-1-n"></span><{$smarty.const.LANG_GROUP_USERLIST_BTN_EXPORT}></a><{/if}>
									<{/if}>
								<{/if}>
							<{/if}>
						</div>
						<div class="clearfix"></div>
					</div>
				<div class="hastable">
					<table cellspacing="0">
						<thead>
							<tr>								
								<td><{$smarty.const.LANG_GROUP_USERLIST_COL_USERNAME}></td>
								<td><{$smarty.const.LANG_GROUP_USERLIST_COL_ACCOUNT}></td>
								<td><{$smarty.const.LANG_GROUP_USERLIST_COL_DATA}></td>
								<td><{$smarty.const.LANG_GROUP_USERLIST_COL_LASLOGIN}></td>
								<{if LicenseManager::chkAuth($smarty.const.MEMBER_MODE,MemberModeEnum::INDIVIDUAL)}>
								<td><{$smarty.const.LANG_CONST_MANAGEMENT}></td>
								<{/if}>
							</tr>
						</thead>
						<tbody>
						<{foreach from=$data item="val" name=myloop}>   
							<tr<{if $smarty.foreach.myloop.iteration is even}> class="alt"<{/if}>>
								<td>
									<{$val.bu_cname}>
								</td>
								<td>
									<{$val.bu_name}>
								</td>
								<td>
									birthday: <{$val.bu_birth|date_format}><br />
									Email: <{$val.bu_email}><br />
									Career: <{$val.careername}><br />
									Receive Mail: <{$val.bu_receive_mail}>
								</td>
								<td>
									<{$val.last_login}>
								</td>
								<{if LicenseManager::chkAuth($smarty.const.MEMBER_MODE,MemberModeEnum::INDIVIDUAL)}>
								<td>
									<{if MemberSystemFuncMapping::isEnable('edit')}>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<{$smarty.const.LANG_BTNHINT_EDIT}>" href="bookshelf_user.php?type=edit&id=<{$val.bu_id}>&gid=<{$smarty.get.gid}>&page=<{$smarty.get.page}>">
									<span class="ui-icon ui-icon-wrench"></span>
									</a>
									<{/if}>
									<{if MemberSystemFuncMapping::isEnable('delete')}>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<{$smarty.const.LANG_BTNHINT_DELETE}>" href="bookshelf_user.php?type=delete&id=<{$val.bu_id}>&gid=<{$smarty.get.gid}>&page=<{$smarty.get.page}>">
										<span class="ui-icon ui-icon-circle-close"></span>
									</a>
									<{/if}>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip chart" title="<{$smarty.const.LANG_BTNHINT_CHART}>" href="learninghistory.php?id=<{$val.key}>">
										<span class="ui-icon ui-icon-image"></span>
									</a>
								</td>
								<{/if}>
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
