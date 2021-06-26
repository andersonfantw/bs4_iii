<{extends file="backend/base.tpl"}>
<{block name="head"}>
<link href="css/customize/book.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="js/validation/book_list.js"></script>
<script type="text/javascript" src="../scripts/loader.class.js"></script>
<script type="text/javascript" src="../plugin/tag/scripts/loader_book_list.js"></script>
<script type="text/javascript">
$(document).ready(function() { 
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
        $.post("book.php?type=search_top10", //post 
            $("#search").serialize(),  
                function(data){ 
                    //show results if more than 2 characters 
                    if (data != 'hide') { 
                        $("#books_table").html(data); 
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
					<h3><{$smarty.const.LANG_BOOKS}></h3>		
				</div>
				<div>
						<form action="book.php?type=search" method="post" class="forms" name="form" id="search">
							<ul>
								<li>
									<label  class="desc">
										<{$smarty.const.LANG_BOOKS_LIST_SEARCH}>
									</label>
									<div>
										<div id="tag"></div>
										<input type="text"maxlength="255" class="field text small" id="q" name="q" value="<{$smarty.get.q}>" /><input type="submit" value="<{$smarty.const.LANG_BUTTON_SEARCH}>" class="submit" />
										<input type="hidden" name="type" value="search" />
									</div>
								</li>
							</ul>
						</form>
				</div>
					<div class="other">						
						<div class="button float-right">
							<a href="book.php?type=add"  class="btn ui-state-default"><span class="ui-icon ui-icon-circle-plus"></span><{$smarty.const.LANG_BOOKS_BTN_ADD}></a>
							<{if LicenseManager::chkAuth($smarty.const.MEMBER_SYSTEM,MemberSystemEnum::Import) || LicenseManager::chkAuth($smarty.const.MEMBER_SYSTEM,MemberSystemEnum::Regist)}>
								<{if LicenseManager::chkAuth($smarty.const.BACKEND_IMPORT_MODE,ImportManagerModeEnum::BOOK)}>
								<{if $smarty.const.ENABLE_IMPOERT}><a href="import.php" class="btn ui-state-default"><span class="ui-icon ui-icon-arrowreturnthick-1-s"></span><{$smarty.const.LANG_BOOKS_BTN_IMPORT}></a><{/if}>
								<{if $smarty.const.ENABLE_EXPOERT}><a href="import.php?cmd=export" class="btn ui-state-default"><span class="ui-icon ui-icon-arrowreturnthick-1-n"></span><{$smarty.const.LANG_BOOKS_BTN_EXPORT}></a><{/if}>
								<{/if}>
							<{/if}>
						</div>
						<div class="clearfix"></div>
					</div>				
				<div class="hastable">
					<table cellspacing="0">
						<thead>
							<tr>
								<td><{$smarty.const.LANG_BOOKS_LIST_COL_SEQ}></td>
								<td><{$smarty.const.LANG_BOOKS_LIST_COL_COVERIMG}></td>
								<td><{$smarty.const.LANG_BOOKS_LIST_COL_BOOKNAME}></td>
								<td><{$smarty.const.LANG_BOOKS_LIST_COL_ORDER}></td>
<!--
								<td>新書</td>
								<td>上下架</td>
-->
								<td><{$smarty.const.LANG_BOOKS_LIST_COL_WEBBOOK_CLICK}></td>
								<td><{$smarty.const.LANG_BOOKS_LIST_COL_IBOOK_CLICK}></td>
								<td><{$smarty.const.LANG_CONST_MANAGEMENT}></td>
							</tr>
						</thead>
						<tbody id="books_table">
						<{$book_list_data_html}>
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
