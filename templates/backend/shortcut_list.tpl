<{extends file="backend/base.tpl"}>
<{block name="head"}>
<link href="css/customize/shortcut.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="../scripts/loader.class.js"></script>
<script type="text/javascript" src="../plugin/tag/scripts/loader_shortcut_list.js"></script>
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
					<h3><{$smarty.const.LANG_SHORTCUT}> - <{$smarty.const.LANG_SHORTCUT_LIST_TITLE}></h3>	
				</div>
					<div class="other">						
						<div class="button float-right">
							<a href="shortcut.php?type=add" class="btn ui-state-default"><span class="ui-icon ui-icon-circle-plus"></span><{$smarty.const.LANG_SHORTCUT_BTN_ADD}></a>
						</div>
						<div class="clearfix"></div>
					</div>				
				<div class="hastable">
					<table cellspacing="0">
						<thead>
							<tr>
								<td><{$smarty.const.LANG_SHORTCUT_LIST_COL_IMG}></td>
								<td><{$smarty.const.LANG_SHORTCUT_LIST_COL_DESCRIPTION}></td>
								<td><{$smarty.const.LANG_CONST_MANAGEMENT}></td>
							</tr>
						</thead>
						<tbody id="books_table">
						<{foreach from=$data item="val" name=myloop}>   
							<tr<{if $smarty.foreach.myloop.iteration is even}> class="alt"<{/if}>>
								<td>
									<{$val.img_html}>
								</td>
								<td>
									<{$val.ts_description}>
								</td>
								<td>
									<{if MemberSystemFuncMapping::isEnable('edit')}>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<{$smarty.const.LANG_BUTTON_EDIT}>" href="shortcut.php?type=edit&id=<{$val.ts_id}>&page=<{$smarty.get.page}>">
									<span class="ui-icon ui-icon-wrench"></span>
									</a>
									<{/if}>
									<{if MemberSystemFuncMapping::isEnable('delete')}>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<{$smarty.const.LANG_BUTTON_DELETE}>" href="shortcut.php?type=delete&id=<{$val.ts_id}>&page=<{$smarty.get.page}>">
										<span class="ui-icon ui-icon-circle-close"></span>
									</a>
									<{/if}>
									<{if $val.ts_status==1}>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<{$smarty.const.LANG_BUTTON_INACTIVE}>" href="shortcut.php?type=disable&id=<{$val.ts_id}>&page=<{$smarty.get.page}>">
										<span class="ui-icon ui-icon-pause"></span>
									</a>
									<{else}>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip" title="<{$smarty.const.LANG_BUTTON_ACTIVE}>" href="shortcut.php?type=enable&id=<{$val.ts_id}>&page=<{$smarty.get.page}>">
										<span class="ui-icon ui-icon-play"></span>
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
