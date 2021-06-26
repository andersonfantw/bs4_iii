<{extends file="backend/base.tpl"}>
<{block name="head"}>
<script type="text/javascript" src="js/validation/book_users_bookshelf.js"></script>
<link href="css/customize/book_users_bookshelf.css" rel="stylesheet" media="all" />
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
					<div class="portlet-header ui-widget-header"><{$data.b_name}> | <{$smarty.const.LANG_MY}></div>
					<div class="portlet-content">
						<form action="book.php?type=do_update_users_bookshelf&page=<{$smarty.get.page}>" method="post" enctype="multipart/form-data" class="forms" name="form" >
							<ul>
								<li>
									<div>
										<{foreach from=$bookshelf_user item="val" name=myloop}>
                      <{if $val.users}><div style="margin-bottom:5px;"><{$val.g_name}><br />
                      <{foreach from=$val.users item="subval"}>
											<span style="padding-left:5px;"><input type="checkbox" id="c_<{$subval.bu_id}>" name="bu_id[]" value="<{$subval.bu_id}>"<{if $subval.checked}>checked="checked"<{/if}>><label for="c_<{$subval.bu_id}>"><{$subval.bu_cname}></label></span>
                      <{/foreach}>
                      </div>
                      <{/if}>
										<{/foreach}>
									</div>
								</li>

								<li class="buttons">
									<input type="submit" value="<{$smarty.const.LANG_BUTTON_SAVE}>" class="submit" onclick="return check();" />
									<input type="button" value="<{$smarty.const.LANG_BUTTON_CANCEL}>" onclick="javascript:history.go(-1);"/>
								</li>
							</ul>
							<input type="hidden" name="id" value="<{$data.b_id}>" />
						</form>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
