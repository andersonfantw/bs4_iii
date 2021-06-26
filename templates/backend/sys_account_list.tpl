<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_account.css" rel="stylesheet" media="all" />
<script type="text/javascript">
$(function() {
	//clear search field & change search text color 
    $("#q").focus(function() { 
        $("#q").css('color','#333333'); 
        var sv = $("#q").val(); //get current value of search field 
        if (sv == 'Search') { 
            $("#q").val(''); 
        } 
    }); 

    //post form on keydown or onclick, get results 
    $("#q").bind('keyup click', function() { 
        $.post("sys_account.php?type=search_instant", //post 
            $("#search").serialize(),  
                function(data){ 
                    //show results if more than 2 characters 
                    if (data != 'hide') { 
                        $("#sys_account_table").html(data); 
                    } 
            }); 
    }); 

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
					<h3><{$smarty.const.LANG_SYSACCOUNT_LIST_TITLE}></h3>		
				</div>
				<div>
				<form action="sys_account.php?type=search" method="post" class="forms" name="form" id="search">
					<ul>
						<li>
							<label  class="desc">
								<{$smarty.const.LANG_SYSACCOUNT_LIST_SEARCH}>
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
							<a href="sys_account.php?type=add"  class="btn ui-state-default"><span class="ui-icon ui-icon-circle-plus"></span><{$smarty.const.LANG_SYSACCOUNT_LIST_BTN_ADD}></a>
							<{if LicenseManager::chkAuth($smarty.const.MEMBER_SYSTEM,MemberSystemEnum::Import) || LicenseManager::chkAuth($smarty.const.MEMBER_SYSTEM,MemberSystemEnum::Regist)}>
								<{if LicenseManager::chkAuth($smarty.const.BACKEND_IMPORT_MODE,ImportManagerModeEnum::MANAGER)}>
								<{if $smarty.const.ENABLE_IMPOERT}><a href="sys_import.php" class="btn ui-state-default"><span class="ui-icon ui-icon-arrowreturnthick-1-s"></span><{$smarty.const.LANG_SYSACCOUNT_LIST_BTN_IMPORT}></a><{/if}>
								<{if $smarty.const.ENABLE_EXPOERT}><a href="sys_import.php?cmd=export" class="btn ui-state-default"><span class="ui-icon ui-icon-arrowreturnthick-1-n"></span><{$smarty.const.LANG_SYSACCOUNT_LIST_BTN_EXPORT}></a><{/if}>
								<{/if}>
							<{/if}>
						</div>
						<div class="clearfix"></div>
					</div>	
				<div class="hastable">
					<table cellspacing="0">
						<thead>
							<tr>								
								<td><{$smarty.const.LANG_SYSACCOUNT_LIST_COL_NAME}></td>
								<td><{$smarty.const.LANG_SYSACCOUNT_LIST_COL_ACCOUNT}></td>
								<td><{$smarty.const.LANG_CONST_MANAGEMENT}></td>
							</tr>
						</thead>
						<tbody id="sys_account_table">
						<{$sys_account_list_data_html}>
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
