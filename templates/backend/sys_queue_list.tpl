<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_queue.css" rel="stylesheet" media="all" />
<{if $smarty.get.type==''}>
<script type="text/javascript" src="../scripts/loader.class.js"></script>
<script type="text/javascript" src="../plugin/uploadqueue/scripts/loader_queue_list.js"></script>
<{/if}>
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
        $.post("allexam.php?type=search_instant", //post 
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
var lnettoken='<{$lnettoken}>';
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
					<h3><{$smarty.const.LANG_UPLOADQUEUE}></h3>		
				</div>
				<div class="other">
					<div class="button float-right">
					<{if $smarty.get.type==''}>
						<a href="sys_queue.php?type=add" class="btn ui-state-default"><span class="ui-icon ui-icon-circle-plus"></span><{$smarty.const.LANG_UPLOADQUEUE_LIST_BTN_ADD}></a>
					<{/if}>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="hastable">
					<div id="progressing">
						<h3>Converting</h3> <span></span>
						<div></div>
					</div>
					<table cellspacing="0">
						<thead>
							<tr>
								<td><{$smarty.const.LANG_UPLOADQUEUE_LIST_COL_CREATEDATE}></td>
								<td><{$smarty.const.LANG_UPLOADQUEUE_LIST_COL_ID}></td>
								<td><{$smarty.const.LANG_UPLOADQUEUE_LIST_COL_NAME}></td>
								<td><{$smarty.const.LANG_UPLOADQUEUE_LIST_COL_RETRY}></td>
								<td><{$smarty.const.LANG_UPLOADQUEUE_LIST_COL_DATA}></td>
								<td><{$smarty.const.LANG_CONST_MANAGEMENT}></td>
							</tr>
						</thead>
						<tbody id="queue_table">
						<{$sys_queue_list_data_html}>
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