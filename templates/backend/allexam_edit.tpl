<{extends file="backend/base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_allexam.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="js/validation/sys_allexam.js"></script>
<script type="text/javascript" src="../scripts/loader.class.js"></script>
<script type="text/javascript" src="../plugin/tag/scripts/loader_sys_allexam.js"></script>
<script type="text/javascript" src="../plugin/tag/scripts/loader_tagquiz.js"></script>
<{/block}>
<{block name="content"}>
			<div id="main-content">
				<{if $status_code !=''}>
				<script>setTimeout(function(){jQuery('#status_bar').fadeOut('slow');}, 2000);</script>
				<div class="response-msg ui-corner-all <{$status_code}>" id="status_bar">
				  <{$status_desc}>
				</div>
				<{/if}>
				<div class="clearfix"></div>
				<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all form-container">
					<form action="allexam.php?type=<{if $smarty.get.type=='add'}>do_add<{else}>do_update<{/if}>&page=<{$smarty.get.page}>" method="post" enctype="multipart/form-data" class="forms" name="form" >
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_ALLEXAM_EDIT_TITLE}></div>
					<div class="portlet-content">
						<ul>
							<li>
								<label class="desc">
								<{$smarty.const.LANG_ALLEXAM_EDIT_NAME}>: 
								</label>
								<div>
									<{$data.name}>
								</div>
							</li>
							<li>
								<label class="desc">
								<{$smarty.const.LANG_ALLEXAM_EDIT_DATE}>: 
								</label>
								<div>
									<{$data.createdate}>
								</div>
							</li>
							<li>
								<label class="desc">
								<{$smarty.const.LANG_ALLEXAM_EDIT_TAGS}>:
								</label>
								<div id="tags">
								</div>
							</li>
							<li>
								<label class="desc">
								<{$smarty.const.LANG_ALLEXAM_EDIT_TAGQUIZ}>:
								</label>
								<div id="quiztags">
								</div>
							</li>
							<li class="buttons">
								<input type="submit" value="<{$smarty.const.LANG_BUTTON_SAVE}>" class="submit" />
								<input type="button" value="<{$smarty.const.LANG_BUTTON_CANCEL}>" onclick="javascript:location.href='allexam.php?q=<{$q_str}>'"/>
							</li>
						</ul>
						<input type="hidden" name="id" value="<{$data.bs_id}>" />
						<input type="hidden" name="q" value="<{$q_str}>" />
					</div>
					</form>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
