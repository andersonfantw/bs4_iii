<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_system_account.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="js/validation/sys_system_account.js"></script>
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
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_ADMIN_EDIT_TITLE}></div>
					<div class="portlet-content">
						<form action="sys_system_account.php?type=do_update" method="post" enctype="multipart/form-data" class="forms" name="form" >
							<ul>
								<li>
									<label  class="desc">
										<{$smarty.const.LANG_ADMIN_EDIT_ACCOUNT}>
									</label>
									<div>
										<{$data.su_name}>
									</div>
								</li>
								<li>
									<label class="desc">
										<{$smarty.const.LANG_ADMIN_EDIT_PASSWORD}>
									</label>
									<div>
										<input type="password" class="field text medium" id="su_password" name="su_password" value="" />
									</div>
								</li>
								<li>
								  <label class="desc">
									<{$smarty.const.LANG_ADMIN_EDIT_PASSWORDCONFIRM}>
								  </label>
								  <div>
									<input type="password" class="field text medium" id="su_password2" name="su_password2" value="" />
								  </div>
								</li>
								<li class="buttons">
									<input type="submit" value="<{$smarty.const.LANG_BUTTON_SAVE}>" class="submit" />
									<input type="button" value="<{$smarty.const.LANG_BUTTON_CANCEL}>" onclick="javascript:history.go(-1);"/>
								</li>
							</ul>
							<input type="hidden" name="id" value="<{$data.su_id}>" />
							<input type="hidden" name="su_name" value="<{$data.su_name}>" />
						</form>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
