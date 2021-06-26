<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_bookshelf.css" rel="stylesheet" media="all" />
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
        $.post("sys_bookshelf.php?type=search_instant", //post 
            $("#search").serialize(),  
                function(data){ 
                    //show results if more than 2 characters 
                    if (data != 'hide') { 
                        $("#bookshelfs_table").html(data); 
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
					<h3><{$smarty.const.LANG_BOOKSHELFS}></h3>		
				</div>
				<div>
						<form action="sys_bookshelf.php?type=search" method="post" class="forms" name="form" id="search">
							<ul>
								<li>
									<label  class="desc">
										<{$smarty.const.LANG_BOOKSHELFS_LIST_SEARCH}>
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
							<a href="sys_bookshelf.php?type=add"  class="btn ui-state-default"><span class="ui-icon ui-icon-circle-plus"></span><{$smarty.const.LANG_BOOKSHELFS_LIST_BTN_ADD}></a>
						</div>
						<div class="clearfix"></div>
					</div>				
				<div class="hastable">
					<table cellspacing="0">
						<thead>
							<tr>
								<td><{$smarty.const.LANG_BOOKSHELFS_LIST_COL_BSNAME}></td>
								<td><{$smarty.const.LANG_BOOKSHELFS_LIST_COL_AUTH}></td>
								<td><{$smarty.const.LANG_BOOKSHELFS_LIST_COL_ADMIN}></td>
								<{if LicenseManager::chkAuth($smarty.const.MEMBER_MODE,MemberModeEnum::CENTRALIZE_ASSIGN)}>
								<td><{$smarty.const.LANG_BOOKSHELFS_LIST_COL_GROUPSETTING}></td>
								<{/if}>
								<td><{$smarty.const.LANG_CONST_MANAGEMENT}></td>
							</tr>
						</thead>
						<tbody id="bookshelfs_table">
						<{$bookshelf_list_data_html}>
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
