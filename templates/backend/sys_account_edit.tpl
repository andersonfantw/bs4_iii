<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<script type="text/javascript" src="js/validation/sys_account.js"></script>
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
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_SYSACCOUNT_EDIT_TITLE}></div>
					<div class="portlet-content">
						<form action="sys_account.php?type=<{if $smarty.get.type=='add'}>do_add<{else}>do_update<{/if}>" method="post" enctype="multipart/form-data" class="forms" name="form" >
							<ul>
								<li>
									<label  class="desc">
										*<{$smarty.const.LANG_SYSACCOUNT_EDIT_NAME}>
									</label>
									<div>
										<input type="text"maxlength="255" class="field text small" id="u_cname" name="u_cname" value="<{$data.u_cname}>" />
									</div>
								</li>
								<li>
									<label class="desc">
										*<{$smarty.const.LANG_SYSACCOUNT_EDIT_ACCOUNT}>
									</label>
									<div>
										<{if $smarty.get.type=='add'}>
											<input type="text" class="field text medium" id="u_name" name="u_name" value="<{$data.u_name}>"/>
										<{else}>
											<{$data.u_name}>
											<input type="hidden" name="u_name" value="<{$data.u_name}>"/>
										<{/if}>
									</div>
								</li>
								<li>
									<label class="desc">
										<{$smarty.const.LANG_SYSACCOUNT_EDIT_PASSWORD}>
									</label>
									<div>
										<input type="password" class="field text medium" id="u_password" name="u_password" value="" />
									</div>
								</li>
				                <li>
				                  <label class="desc">
										<{$smarty.const.LANG_SYSACCOUNT_EDIT_PASSWORDCONFIRM}>
				                  </label>
				                  <div>
				                    <input type="password" class="field text medium" id="u_password2" name="u_password2" value="" />
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
