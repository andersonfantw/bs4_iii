<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_synonyms.css" rel="stylesheet" media="all" />
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
					<h3><{$smarty.const.LANG_SYNONYMS_LIST_TITLE}></h3>		
				</div>
				<div class="other">						
						<div class="button float-right">
							<a href="sys_synonyms.php?type=add"  class="btn ui-state-default"><span class="ui-icon ui-icon-circle-plus"></span><{$smarty.const.LANG_FULLTEXT_SYNONYMS_BTN_ADD}></a>
						</div>
						<div class="clearfix"></div>
					</div>	
				<div class="hastable">
					<table cellspacing="0">
						<thead>
							<tr>								
								<td><{$smarty.const.LANG_SYNONYMS_LIST_COL_NAME}></td>
								<td><{$smarty.const.LANG_SYNONYMS_LIST_COL_CONTENT}></td>
								<td><{$smarty.const.LANG_SYNONYMS_LIST_COL_STATUS}></td>
								<td><{$smarty.const.LANG_CONST_MANAGEMENT}></td>
							</tr>
						</thead>
						<tbody id="sys_account_table">
						<{foreach from=$data item="val" name=myloop}>   
							<tr<{if $smarty.foreach.myloop.iteration is even}> class="alt"<{/if}>>
								<td>
									<{$val.fts_name}>
								</td>
								<td>
									<{$val.fts_content}>
								</td>
								<td>
									<{if $val.fts_status==1}>
										互相
									<{elseif $val.fts_status==2}>
										雙向
									<{elseif $val.fts_status==3}>
										單向
									<{else}>
										設定有誤，請重新設定
									<{/if}>
								</td>
								<td>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<{$smarty.const.LANG_BTNHINT_DELETE}>" href="sys_synonyms.php?type=delete&id=<{$val.fts_id}>&page=<{$smarty.get.page}>">
										<span class="ui-icon ui-icon-circle-close"></span>
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

