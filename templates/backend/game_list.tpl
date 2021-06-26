<{extends file="backend/base.tpl"}>
<{block name="head"}>
<link href="css/customize/reflectiongame.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="js/validation/game.js"></script>
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
						</div>
						<div class="clearfix"></div>
					</div>				
				<div class="hastable">
					<table cellspacing="0">
						<thead>
							<tr>
								<td><{$smarty.const.LANG_REFLECTIONGAME_LIST_COL_COMMON}></td>
								<td><{$smarty.const.LANG_CONST_MANAGEMENT}></td>
							</tr>
						</thead>
						<tbody>
						<{foreach from=$data item="val" name=myloop}>  
							<tr>
	              <td>
	              	<span><{$val.bu_cname}></span><br />
	                <{$val.grc_common}>
	              </td>
	              <td>
	              	<select class="grc_mark" name="grc_mark_<{$val.bu_name}>">
	              		<option value="<{$val.bs_id}>,<{$val.b_id}>,<{$val.bu_id}>,-1" <{if $val.grc_mark=='-1'}>selected<{/if}>><{$smarty.const.LANG_REFLECTIONGAME_EDIT_COL_WRITEAGAIN}></option>
	              		<option value="<{$val.bs_id}>,<{$val.b_id}>,<{$val.bu_id}>,0" <{if $val.grc_mark=='0'}>selected<{/if}>>0</option>
	              		<option value="<{$val.bs_id}>,<{$val.b_id}>,<{$val.bu_id}>,1" <{if $val.grc_mark=='1'}>selected<{/if}>>1</option>
	              		<option value="<{$val.bs_id}>,<{$val.b_id}>,<{$val.bu_id}>,2" <{if $val.grc_mark=='2'}>selected<{/if}>>2</option>
	              		<option value="<{$val.bs_id}>,<{$val.b_id}>,<{$val.bu_id}>,3" <{if $val.grc_mark=='3'}>selected<{/if}>>3</option>
	              		
	              	</select>
<!--
	                <input class="mark_great" name="grc_mark_<{$val.bu_name}>" type="radio" value="<{$val.bs_id}>,<{$val.b_id}>,<{$val.bu_id}>" <{if $val.grc_mark=='1'}>checked<{/if}> /><{$smarty.const.LANG_REFLECTIONGAME_EDIT_COL_GREAT}> <br />
	                <input class="mark_writeagain" name="grc_mark_<{$val.bu_name}>" type="radio" value="<{$val.bs_id}>,<{$val.b_id}>,<{$val.bu_id}>" <{if $val.grc_mark=='-1'}>checked<{/if}> /><{$smarty.const.LANG_REFLECTIONGAME_EDIT_COL_WRITEAGAIN}>
-->
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
