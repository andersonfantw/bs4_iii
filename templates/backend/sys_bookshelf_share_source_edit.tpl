<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_bookshelf_share.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="js/validation/sys_bookshelf_share_source.js"></script>
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
					<form action="sys_bookshelf_share.php?type=<{if $smarty.get.type=='source_add'}>source_do_add<{else}>source_do_update<{/if}>&page=<{$smarty.get.page}>" method="post" enctype="multipart/form-data" class="forms" name="form" >
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_SHARE_GET_EDIT_TITLE}></div>
					<div class="portlet-content">
						<ul>
							<li>
								<label  class="desc">
								<{$smarty.const.LANG_SHARE_GET_EDIT_SOURCENAME}>: 
								</label>
								<div>
									<input type="text"maxlength="255" class="field text small" id="bsss_name" name="bsss_name" value="<{$data.bsss_name}>" />
								</div>
							</li>
							<li>
								<label  class="desc">
								<{$smarty.const.LANG_SHARE_GET_EDIT_SOURCEURL}>: 
								</label>
								<div>
									<input type="text"maxlength="255" class="field text small" id="bsss_source" name="bsss_source" value="<{$data.bsss_source}>" />
								</div>
							</li>
							<li>
								<label  class="desc">
								<{$smarty.const.LANG_SHARE_GET_EDIT_ACCOUNT}>: 
								</label>
								<div>
									<input type="text"maxlength="255" class="field text small" id="bsss_account" name="bsss_account" value="<{$data.bsss_account}>" />
								</div>
							</li>
							<li>
								<label  class="desc">
								<{$smarty.const.LANG_SHARE_GET_EDIT_PASSWORD}>: 
								</label>
								<div>
									<input type="text"maxlength="255" class="field text small" id="bsss_password" name="bsss_password" value="<{$data.bsss_password}>" />
								</div>
							</li>
							<li class="buttons">
								<input type="submit" value="<{$smarty.const.LANG_BUTTON_SAVE}>" class="submit" onclick="return check();" />
								<input type="button" value="<{$smarty.const.LANG_BUTTON_CANCEL}>" onclick="javascript:history.go(-1)"/>
							</li>
						</ul>
						<input type="hidden" name="id" value="<{$data.bsss_id}>" />
					</div>
					</form>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
