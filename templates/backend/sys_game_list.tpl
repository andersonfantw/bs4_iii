<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_reflectiongame.css" rel="stylesheet" media="all" />
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
					<h3><{$smarty.const.LANG_REFLECTIONGAME_LIST_TITLE}></h3>		
				</div>
					<div class="other">						
						<div class="button float-right">
							<a href="sys_game.php?type=add"  class="btn ui-state-default"><span class="ui-icon ui-icon-circle-plus"></span><{$smarty.const.LANG_REFLECTIONGAME_LIST_BTN_ADD}></a>
						</div>
						<div class="clearfix"></div>
					</div>				
				<div class="hastable">
					<table cellspacing="0">
						<thead>
							<tr>
								<td><{$smarty.const.LANG_REFLECTIONGAME_LIST_COL_NAME}></td>
                                                                <td><{$smarty.const.LANG_REFLECTIONGAME_LIST_COL_SET}></td>
                                                                <td><{$smarty.const.LANG_REFLECTIONGAME_LIST_COL_AMOUNT}></td>
								<td><{$smarty.const.LANG_REFLECTIONGAME_LIST_COL_CLASSES}></td>
								<td><{$smarty.const.LANG_REFLECTIONGAME_LIST_COL_REFLECT}></td>
								<td><{$smarty.const.LANG_REFLECTIONGAME_LIST_COL_MARK}></td>
								<td><{$smarty.const.LANG_CONST_MANAGEMENT}></td>
							</tr>
						</thead>
						<tbody id="bookshelfs_table">
						<{$game_list_data_html}>
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
