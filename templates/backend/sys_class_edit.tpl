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
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_VCUBE_EDIT_TITLE}></div>
					<div class="portlet-content">
						<form action="sys_account.php?type=<{if $smarty.get.type=='add'}>do_add<{else}>do_update<{/if}>" method="post" enctype="multipart/form-data" class="forms" name="form" >
							<ul>
								<li>
									<label  class="desc">
										*<{$smarty.const.LANG_VCUBE_EDIT_ROOM}>
									</label>
									<div>
										<select id="vmc_roomid" name="vmc_roomid">
										</select>
									</div>
								</li>
								<li>
									<label class="desc">
										*<{$smarty.const.LANG_VCUBE_EDIT_NAME}>
									</label>
									<div>
										<input type="text" class="field text medium" id="vmc_name" name="vmc_name" value="<{$data.vmc_name}>"/>
									</div>
								</li>
								<li>
									<label class="desc">
										*<{$smarty.const.LANG_VCUBE_EDIT_TIME}>
									</label>
									<div>
										<input type="text" class="field text medium" id="vmc_start" name="vmc_start" value="<{$data.vmc_start}>"/>
										~
										<input type="text" class="field text medium" id="vmc_end" name="vmc_end" value="<{$data.vmc_end}>"/>
									</div>
								</li>
								<li>
									<label class="desc">
										*<{$smarty.const.LANG_VCUBE_EDIT_GROUP}>
									</label>
									<div>
										
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
