<{extends file="backend/base.tpl"}>
<{block name="head"}>
<link href="css/customize/category.css" rel="stylesheet" media="all" />
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
					<h3><{$smarty.const.LANG_CATE}></h3>		
				</div>
					<div class="other">						
						<div class="button float-right">
							<{if $smarty.get.pid!=0}><a href="category.php"  class="btn ui-state-default"><span class="ui-icon ui-icon-arrowreturnthick-1-w"></span><{$smarty.const.LANG_CATE_BTN_RETURN}></a><{/if}>
							<a href="category.php?type=add&pid=<{$smarty.get.pid}>"  class="btn ui-state-default"><span class="ui-icon ui-icon-circle-plus"></span><{$smarty.const.LANG_CATE_BTN_ADD}></a>
							<{if LicenseManager::chkAuth($smarty.const.MEMBER_SYSTEM,MemberSystemEnum::Import) || LicenseManager::chkAuth($smarty.const.MEMBER_SYSTEM,MemberSystemEnum::Regist)}>
								<{if LicenseManager::chkAuth($smarty.const.BACKEND_IMPORT_MODE,ImportManagerModeEnum::CATEGORY)}>
								<{if $smarty.const.ENABLE_IMPOERT}><a href="import.php"  class="btn ui-state-default"><span class="ui-icon ui-icon-arrowreturnthick-1-s"></span><{$smarty.const.LANG_CATE_BTN_IMPORT}></a><{/if}>
								<{if $smarty.const.ENABLE_EXPOERT}><a href="import.php?cmd=export"  class="btn ui-state-default"><span class="ui-icon ui-icon-arrowreturnthick-1-n"></span><{$smarty.const.LANG_CATE_BTN_EXPORT}></a><{/if}>
								<{/if}>
							<{/if}>
						</div>
						<div class="clearfix"></div>
					</div>				
				<div class="hastable">
					<table cellspacing="0">
						<thead>
							<tr>
								<td>No</td>
								<td><{if $smarty.get.pid!=0}><{$smarty.const.LANG_CATE_LIST_COL_SUBNAME}><{else}><{$smarty.const.LANG_CATE_LIST_COL_MAINNAME}><{/if}></td>
								<td><{$smarty.const.LANG_CATE_LIST_COL_DESC}></td>
								<td><{$smarty.const.LANG_CATE_LIST_COL_ORDER}></td>
                <td><{if $smarty.get.pid!=0}><{$smarty.const.LANG_CATE_LIST_COL_BELONG}><{else}><{$smarty.const.LANG_CATE_LIST_COL_TOGO_SUBCATE}><{/if}></td>
								<td><{$smarty.const.LANG_CONST_MANAGEMENT}></td>
							</tr>
						</thead>
						<tbody>
						<{foreach from=$data item="val" name=myloop}>   
							<tr<{if $smarty.foreach.myloop.iteration is even}> class="alt"<{/if}>>
								<td><{$val.c_id}></td>
								<td>
									<{$val.c_name}><br />
									<{$val.c_key}>
								</td>
                <td>
                  <{$val.c_description}><br />
                  <a href="<{$smarty.const.HttpDomain}><{$smarty.const.WEB_URL}>/<{common::getcookie('acc')}>/<{common::getcookie('bs')}>/bs/#/?cid=<{$val.c_id}>" target="_new">
										<{$smarty.const.HttpDomain}><{$smarty.const.WEB_URL}>/<{common::getcookie('acc')}>/<{common::getcookie('bs')}>/bs/#/?cid=<{$val.c_id}>
									</a>
                </td>
                <td>
                  <{$val.c_order}>
                </td>
                <td>
                  <{if $val.c_parent_id==0}>
                    <a href="category.php?pid=<{$val.c_id}>"><{$smarty.const.LANG_CATE_LIST_COL_TOGO_SUBCATE}></a>
                  <{else}>
                    <{$val.c_parent_name}>
                  <{/if}>
                </td>
								<td>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<{$smarty.const.LANG_BTNHINT_EDIT}>" href="category.php?type=edit&id=<{$val.c_id}>&page=<{$smarty.get.page}>">
									<span class="ui-icon ui-icon-wrench"></span>
									</a>
									<{if $smarty.get.pid>0}>
										<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<{$smarty.const.LANG_BTNHINT_DELETE}>" href="category.php?type=delete&id=<{$val.c_id}>&pid=<{$smarty.get.pid}>">
											<span class="ui-icon ui-icon-circle-close"></span>
										</a>
									<{else}>
										<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<{$smarty.const.LANG_BTNHINT_DELETE}>" href="category.php?type=delete&id=<{$val.c_id}>&page=<{$smarty.get.page}>">
											<span class="ui-icon ui-icon-circle-close"></span>
										</a>
									<{/if}>
									<{if $val.c_parent_id>0}>
										<a class="btn_no_text btn ui-state-default ui-corner-all tooltip upload" title="<{$smarty.const.LANG_BTNHINT_UPLOAD}>" href="ecocatconvert.php?id=<{$val.c_id}>&pid=<{$smarty.get.pid}>&cn=<{$val.c_name}>">
										<span class="ui-icon ui-icon-arrowreturnthick-1-n"></span>
										</a>
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
