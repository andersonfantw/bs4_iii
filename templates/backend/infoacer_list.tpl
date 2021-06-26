<{extends file="backend/base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_infoacer.css" rel="stylesheet" media="all" />
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
        $.post("infoacer.php?type=search_instant", //post 
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
					<h3><{$smarty.const.LANG_INFOACER}></h3>		
				</div>
				<div>
						<form action="infoacer.php?type=search" method="post" class="forms" name="form" id="search">
							<ul>
								<li>
									<label  class="desc">
										<{$smarty.const.LANG_INFOACER_LIST_SEARCH}>
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
							<a href="allexam.php" class="btn ui-state-default"><span class="ui-icon ui-icon-circle-plus"></span><{$smarty.const.LANG_INFOACER_LIST_BTN_BACK}></a>
						</div>
						<div class="clearfix"></div>
					</div>				
				<div class="hastable">
					<table cellspacing="0">
						<thead>
							<tr>
								<td><{$smarty.const.LANG_INFOACER_LIST_COL_USERNAME}></td>
								<td><{$smarty.const.LANG_INFOACER_LIST_COL_USERCNAME}></td>
								<td><{$smarty.const.LANG_INFOACER_LIST_COL_CORRECT}></td>
								<td><{$smarty.const.LANG_INFOACER_LIST_COL_SCORE}></td>
								<{foreach from=$data_quiz item="val" name=myloop}>
									<td><{$val.seq_reportid}></td>
								<{/foreach}>
								<td><{$smarty.const.LANG_CONST_MANAGEMENT}></td>
							</tr>
						</thead>
						<tbody id="bookshelfs_table">
						<{foreach from=$data item="val" name=myloop}>   
							<tr<{if $smarty.foreach.myloop.iteration is even}> class="alt"<{/if}>>
								<td>
									<{$val.bu_name}>
								</td>
								<td>
									<{$val.bu_cname}>
								</td>
								<td>
									<{$val.seu_correct}>
								</td>
								<td>
									<{$val.seu_percent}>
								</td>
								<{foreach from=$data_key item="v" name=myloop}>
								<td>
										<{if $data_exercise[$val.bu_id][$v]['see_result']=='0'}>
											<span class="wrong"><{$data_exercise[$val.bu_id][$v]['see_answers']}></span>
										<{else}>
											<span class="right"><{$data_exercise[$val.bu_id][$v]['see_answers']}></span>
										<{/if}>
								</td>
								<{/foreach}>
								<td>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<{$smarty.const.LANG_BUTTON_DELETE}>" href="infoacer.php?type=delete&id=<{$val.key}>&page=<{$smarty.get.page}>">
										<{$smarty.const.LANG_BUTTON_DELETE}>
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
