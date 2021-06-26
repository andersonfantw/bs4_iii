<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<script type="text/javascript" src="js/validation/sys_synonyms.js"></script>
<script language="javascript">

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
				<div class="clearfix"></div>
				<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all form-container">
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_SYNONYMS_EDIT_TITLE}></div>
					<div class="portlet-content">
						<form action="sys_synonyms.php?type=<{if $smarty.get.type=='add'}>do_add<{else}>do_update<{/if}>" method="post" enctype="multipart/form-data" class="forms" name="form" >
							<ul>
								<li>
									<label  class="desc">
										*<{$smarty.const.LANG_SYNONYMS_EDIT_NAME}>
									</label>
									<div>
										<input type="text"maxlength="255" class="field text small" id="name" name="name" value="<{$data.fts_name}>" />
									</div>
								</li>
								<li>
									<label class="desc">
										*<{$smarty.const.LANG_SYNONYMS_EDIT_CONTENT}>
									</label>
									<div>
										<textarea id="content" name="content" width="100%" rows="3"><{$data.fts_content}></textarea>
									</div>
								</li>
								<li>
									<label class="desc">
										<{$smarty.const.LANG_SYNONYMS_EDIT_STATUS}>
									</label>
									<div>
										<input type="radio" id="status_1" name="status" value="1" checked="checked" /><label for="status_1">互相</label>
										<input type="radio" id="status_2" name="status" value="2" /><label for="status_1">雙向</label>
										<input type="radio" id="status_3" name="status" value="3" /><label for="status_1">單向</label>
									</div>
								</li>
								<li class="buttons">
									<input type="submit" value="<{$smarty.const.LANG_BUTTON_SAVE}>" class="submit" />
									<input type="button" value="<{$smarty.const.LANG_BUTTON_CANCEL}>" onclick="javascript:location.href='sys_account.php?type=search&q=<{$q_str}>'"/>
								</li>
							</ul>
							<input type="hidden" name="id" value="<{$data.u_id}>" />
							<input type="hidden" name="q" value="<{$q_str}>" />
						</form>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>

