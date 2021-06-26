<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_tagrelate.css" rel="stylesheet" media="all" />
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
					<h3><{$smarty.const.LANG_TAGEVOLVE}> - <{$smarty.const.LANG_TAGEVOLVE_LIST_TITLE}></h3>
				</div>
<!--
				<div>
						<form action="sys_tagevolve.php?type=search" method="post" class="forms" name="form" id="search">
							<ul>
								<li>
									<label  class="desc">
										<{$smarty.const.LANG_TAGEVOLVE_LIST_SEARCH}>
									</label>
									<div>
										<input type="text"maxlength="255" class="field text small" id="q" name="q" value="<{$smarty.get.q}>" /><input type="submit" value="<{$smarty.const.LANG_BUTTON_SEARCH}>" class="submit" />
										<input type="hidden" name="type" value="search" />
									</div>
								</li>
							</ul>
						</form>
				</div>
-->
					<div class="other">
						<div class="button float-right">
							<{if LicenseManager::chkAuth($smarty.const.MEMBER_MODE,MemberModeEnum::CENTRALIZE) && MemberSystemFuncMapping::isEnable('add')}>
								<a href="sys_tagevolve.php?type=add" class="btn ui-state-default"><span class="ui-icon ui-icon-circle-plus"></span><{$smarty.const.LANG_TAGEVOLVE_LIST_BTN_ADD}></a>
							<{/if}>
							<br />
						</div>
						<div class="clearfix"></div>
					</div>
				<div class="hastable">
					<table cellspacing="0">
						<thead>
							<tr>
								<td><{$smarty.const.LANG_TAGEVOLVE_LIST_COL_TYPE}></td>
								<td><{$smarty.const.LANG_TAGEVOLVE_LIST_COL_OLDTAG}></td>
								<td><{$smarty.const.LANG_TAGEVOLVE_LIST_COL_NEWTAG}></td>
								<td><{$smarty.const.LANG_TAGEVOLVE_LIST_COL_YEAR}></td>
								<td><{$smarty.const.LANG_CONST_MANAGEMENT}></td>
							</tr>
						</thead>
						<tbody>
						<{foreach from=$data item="val" name=myloop}>
							<tr<{if $smarty.foreach.myloop.iteration is even}> class="alt"<{/if}>>
								<td>
									<{$arr[$val.type]}>
								</td>
                <td>
                   <{$val.old}>
                </td>
                <td>
                   <{$val.new}>
                </td>
                <td>
                   <{$val.year}>
                </td>
								<td>
									<{if MemberSystemFuncMapping::isEnable('delete')}>
										<{if $val.edit}>
										<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<{$smarty.const.LANG_BUTTON_DELETE}>" href="sys_tagevolve.php?type=delete&key=<{$val.key}>&page=<{$smarty.get.page}>">
											<span class="ui-icon ui-icon-circle-close"></span>
										</a>
										<{/if}>
									<{/if}>
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
