<{extends file="backend/user_index_base.tpl"}>
<{block name="head"}>
<script type="text/javascript" src="js/validation/account.js"></script>
<link href="css/customize/account.css" rel="stylesheet" media="all" />
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
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_ACCOUNT_TITLE}></div>
					<div class="portlet-content">
						<form action="account.php?type=<{if $smarty.get.type=='add'}>do_add<{else}>do_update<{/if}>" method="post" enctype="multipart/form-data" class="forms" name="form" >
							<ul>
								<li>
									<label  class="desc">
										<{$smarty.const.LANG_ACCOUNT_ACCOUNT}>
									</label>
									<div>
										<{$account}>
									</div>
								</li>
								<li>
									<label  class="desc">
										<{$smarty.const.LANG_ACOUNT_PASSWORD}>
									</label>
									<div>
										<input type="password" tabindex="1" maxlength="255" class="field text small" name="u_password" value="" />
									</div>
								</li>
								<li>
									<label  class="desc">
										<{$smarty.const.LANG_ACOUNT_PASSWORDCONFIRM}>
									</label>
									<div>
										<input type="password" tabindex="1" maxlength="255" class="field text small" name="u_password2" value="" />
									</div>
								</li>
								<li class="buttons">
									<input type="submit" value="<{$smarty.const.LANG_BUTTON_SAVE}>" class="submit" />
								</li>
							</ul>
						</form>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>