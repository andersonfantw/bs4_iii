<{extends file="backend/base.tpl"}>
<{block name="head"}>
<link href="css/customize/itutor_edit.css" rel="stylesheet" media="all" />
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
					<h3><{$smarty.const.LANG_ITUTOR}></h3>	
				</div>
					<div class="other">						
						<div class="button float-right">
							<a href="itutor.php"  class="btn ui-state-default"><span class="ui-icon ui-icon-arrowreturnthick-1-w"></span><{$smarty.const.LANG_ITUTOR_BTN_RETURN}></a>
						</div>
						<div class="clearfix"></div>
					</div>				
				<div class="hastable">
					<table cellspacing="0">
						<thead>
							<tr>
								<td class="fixLeft"><{$smarty.const.LANG_ITUTOR_EDIT_USERNAME}></td>	
								<td><{$smarty.const.LANG_ITUTOR_EDIT_USERID}></td>
								<td><{$smarty.const.LANG_ITUTOR_EDIT_DATE}></td>
								<td><{$smarty.const.LANG_ITUTOR_EDIT_TOTALTIME}></td>
								<td><{$smarty.const.LANG_ITUTOR_EDIT_TAKENSLIDE}></td>
								<td><{$smarty.const.LANG_ITUTOR_EDIT_TAKENINTERACTION}></td>
								<{foreach from=$data_key item="val" name=myloop}>
									<td><{$val}></td>
								<{/foreach}>	
								<td><{$smarty.const.LANG_ITUTOR_EDIT_CORRECT}></td>
								<td><{$smarty.const.LANG_ITUTOR_EDIT_PERCENT}></td>
								<td><{$smarty.const.LANG_ITUTOR_EDIT_RESULT}></td>
							</tr>
						</thead>
						<tbody>
						<{foreach from=$data item="val" name=myloop}>   
							<tr<{if $smarty.foreach.myloop.iteration is even}> class="alt"<{/if}>>
								<td class="fixLeft"><{($val.bu_cname=='')?'not set':$val.bu_cname}> (<{($val.bu_name=='')?'not set':$val.bu_name}>)</td>
								<td><{$val.i_userid}></td>
								<td>
									<{$val.i_date}>
								</td>
								<td>
									<{$val.i_totaltime|date_format:"%M:%S"}>
								</td>
								<td>
									<{$val.i_takenslide}>
								</td>
								<td>
									<{$val.i_takeninteraction}>
								</td>

                <{foreach from=$data_key item="v" name=myloop}>
                <td>
                		<{if $data_exercise[$val.i_id][$v]['e_result']==0}>
											<span class="wrong"><{$data_exercise[$val.i_id][$v]['e_answers']}></span>
										<{else}>
											<span class="right"><{$data_exercise[$val.i_id][$v]['e_answers']}></span>
										<{/if}>
								</td>
                <{/foreach}>
	
								<td>
									<{$val.i_correct}> / <{$val.i_totalinteraction}>
								</td>
								<td>
									<{$val.i_percent}>
								</td>
								<td>
									<{$val.i_result}>
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
			<script>
				$(document).ready(function(){
				$('.hastable table').css('width',($('.hastable table thead td').length-9)*150+600);
				});
			</script>
<{/block}>
