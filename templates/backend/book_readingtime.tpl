<{extends file="backend/base.tpl"}>
<{block name="head"}>
<link href="css/customize/reading_time.css" rel="stylesheet" media="all" />
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
					<h3><{$smarty.const.LANG_READINGTIME}></h3>		
				</div>
					<div class="other">						

						<div class="button float-right">
							<div><a href="book.php?page=<{$smarty.get.page}>" class="btn ui-state-default"><span class="ui-icon ui-icon-arrowreturnthick-1-w"></span><{$smarty.const.LANG_READINGTIME_BTN_RETURN}></a></div>
                                                        <div class="circle red"></div><div class="desc"><{$smarty.const.LANG_READINGTIME_CONST_OVER0}></div>
                                                        <div class="circle orange"></div><div class="desc"><{$smarty.const.LANG_READINGTIME_CONST_OVER25}></div>
                                                        <div class="circle green"></div><div class="desc"><{$smarty.const.LANG_READINGTIME_CONST_OVER50}></div>
							<div class="circle blue"></div><div class="desc"><{$smarty.const.LANG_READINGTIME_CONST_OVER75}></div>
							&nbsp;
						</div>
						<div class="clearfix"></div>
					</div>				
				<div class="hastable">
					<table cellspacing="0">
						<thead>
							<tr>
								<td><{$smarty.const.LANG_READINGTIME_LIST_USERNAME}></td>
								<td><{$smarty.const.LANG_READINGTIME_LIST_TIMERANGE}></td>
							</tr>
						</thead>
						<tbody>
						<{foreach from=$data item="val" name=myloop}>   
							<tr<{if $smarty.foreach.myloop.iteration is even}> class="alt"<{/if}>>
								<td>
									<{if $val.bu_id>0}>
									<{$val.bu_cname}>
									<{else}>
										<{$smarty.const.LANG_READINGTIME_CONST_NOLOGIN}>
									<{/if}>
								</td>
								<td>
									<{if $val.sec/$max[0].sec*100 > 75}>
										<div class="circle blue"></div>
									<{elseif $val.sec/$max[0].sec*100 > 50}>
										<div class="circle green"></div>
									<{elseif $val.sec/$max[0].sec*100 > 25}>
										<div class="circle orange"></div>
									<{else}>
										<div class="circle red"></div>
									<{/if}>
									<{($val.sec/60)|string_format:"%d"}>min
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
