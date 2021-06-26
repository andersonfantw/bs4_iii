<{extends file="backend/base.tpl"}>
<{block name="head"}>
<link href="css/customize/activecode.css" rel="stylesheet" media="all" />
<script type="text/javascript">
$(function() {
      $(".delete").click(function(event) {
          return confirm('<{$smarty.const.LANG_WARNING_DELETE_CONFIRM}>');
      }); 
});
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
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_ACTIVECODE_EDIT_TITLE}></div>
					<div class="portlet-content">
						<form action="activecode.php?type=do_add&page=<{$smarty.get.page}>" method="post" enctype="multipart/form-data" class="forms" name="form" >
							<ul>
								<li>
									<label class="desc">Active Code</label>
									<div>
										<{$activecdoe}>
										<input type="hidden" id="activecode" name="activecode" value="<{$activecdoe}>" />
									</div>
								</li>
								<li>
									<label class="desc">Term</label>
									<div>
										<select name="term">
											<option value="1">一個月</option>
											<option value="3">三個月</option>
											<option value="6">六個月</option>
											<option value="12">一年</option>
										</select>
									</div>
								</li>
								<li>
									<label class="desc">User Name</label>
									<div>
										<input type="text" name="username">
									</div>
								</li>
								<li>
									<label class="desc">plan</label>
									<div class="group_panel">
									<{foreach from=$data item="val" name=myloop}>
										<span><input type="checkbox" id="g_<{$val.g_id}>" name="g_id[]" value="<{$val.g_id}>"><label for="g_<{$val.g_id}>"><{$val.g_name}></label></span>
									<{/foreach}>
									</div>
								</li>
								<li class="buttons">
									<input type="submit" value="<{$smarty.const.LANG_BUTTON_SAVE}>" class="submit" />
									<input type="button" value="<{$smarty.const.LANG_BUTTON_CANCEL}>" onclick="javascript:history.go(-1);"/>
								</li>
						</ul>
						</form>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
