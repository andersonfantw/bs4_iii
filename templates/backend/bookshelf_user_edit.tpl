<{extends file="backend/base.tpl"}>
<{block name="head"}>
<link href="css/customize/bookshelf_user.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="js/validation/bookshelf_user.js"></script>
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
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_GROUP_USEREDIT_TITLE}></div>
					<div class="portlet-content">
						<form action="bookshelf_user.php?type=<{if $smarty.get.type=='add'}>do_add<{else}>do_update<{/if}>&page=<{$smarty.get.page}>" method="post" enctype="multipart/form-data" class="forms" name="form" >
							<ul>
								<li>
									<label  class="desc">
										*<{$smarty.const.LANG_GROUP_USEREDIT_USERNAME}>
									</label>
									<div>
										<input type="text"maxlength="255" class="field text small" id="bu_cname" name="bu_cname" value="<{$data.bu_cname}>" />
									</div>
								</li>
								<li>
									<label class="desc">
										*<{$smarty.const.LANG_GROUP_USEREDIT_ACCOUNT}>
									</label>
									<div>
										<{if $smarty.get.type=='add'}>
										<input type="text" class="field text medium" id="bu_name" name="bu_name" value="<{$data.bu_name}>"/>
										<{else}>
										<{$data.bu_name}>
										<input type="hidden" class="field text medium" id="bu_name" name="bu_name" value="<{$data.bu_name}>"/>										
										<{/if}>
									</div>
								</li>
								<li>
									<label class="desc">
										<{$smarty.const.LANG_GROUP_USEREDIT_PASSWORD}>
									</label>
									<div>
										<input type="password" class="field text medium" id="bu_password" name="bu_password" value="" />
									</div>
								</li>
                <li>
                  <label class="desc">
                    <{$smarty.const.LANG_GROUP_USEREDIT_PASSWORDCONFIRM}>
                  </label>
                  <div>
                    <input type="password" class="field text medium" id="bu_password2" name="bu_password2" value="" />
                  </div>
                </li>
								
								<li class="buttons">
									<input type="submit" value="<{$smarty.const.LANG_BUTTON_SAVE}>" class="submit" />
									<input type="button" value="<{$smarty.const.LANG_BUTTON_CANCEL}>" onclick="javascript:history.go(-1);"/>
								</li>
							</ul>
							<{if $smarty.get.type=='edit'}>
							<input type="hidden" name="id" value="<{$data.bu_id}>" />
							<{/if}>
							<input type="hidden" name="gid" value="<{$smarty.get.gid}>" />
						</form>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
