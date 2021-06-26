<{extends file="backend/base.tpl"}>
<{block name="head"}>
<link href="css/customize/group.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="js/validation/group.js"></script>
<script language="javascript">
/*function check(){
	var alert_str = '';
	if(!$("#g_name").val()){
		alert_str += '<{$smarty.const.LANG_WARNING_GROUPNAME_CANT_NOT_BE_NULL}>\n';		
	}

  if(!$("#g_account").val()){
    alert_str += '<{$smarty.const.LANG_WARNING_GROUPACCOUNTNAME_CANT_NOT_BE_NULL}>\n';
  }

<{if $smarty.get.type=='add'}>
  if(!$("#g_password").val()){
    alert_str += '<{$smarty.const.LANG_WARNING_GROUPPASSWORD_CANT_NOT_BE_NULL}>\n';
  }
	
  if(!$("#g_password2").val()){
    alert_str += '<{$smarty.const.LANG_WARNING_GROUPPASSWORD_CONFIRM_CANT_NOT_BE_NULL}>\n';
  }
<{/if}>
  if($("#g_password").val()!=$("#g_password2").val()){
    alert_str += '<{$smarty.const.LANG_WARNING_GROUPPASSWORD_NOT_MATCH}>\n';
  }
	if(alert_str!=''){
		alert(alert_str);
		return false;
	}	
	return true;
}*/
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
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_GROUP_EDIT_TITLE}></div>
					<div class="portlet-content">
						<form action="group.php?type=<{if $smarty.get.type=='add'}>do_add<{else}>do_update<{/if}>&page=<{$smarty.get.page}>" method="post" enctype="multipart/form-data" class="forms" name="form" >
							<ul>
								<li>
									<label  class="desc">
										*<{$smarty.const.LANG_GROUP_EDIT_GROUPNAME}>
									</label>
									<div>
										<{if LicenseManager::chkAuth($smarty.const.MEMBER_MODE,MemberModeEnum::INDIVIDUAL)}>
											<input type="text" maxlength="255" class="field text small" id="g_name" name="g_name" value="<{$data.g_name}>" />
										<{else}>
											<{$data.g_name}>
											<input type="hidden" id="g_name" name="g_name" value="<{$data.g_name}>" />
										<{/if}>
									</div>
								</li>
								<li>
									<label  class="desc">
										<{$smarty.const.LANG_GROUP_EDIT_CATE}>
									</label>
									<div>
										<{foreach from=$category item="val" name=myloop}>
                      <{if $val.sub_category}><div style="margin-bottom:5px;"><{$val.c_name}><br />
                      <{foreach from=$val.sub_category item="subval"}>
											<span style="padding-left:5px;"><input type="checkbox" id="c_<{$subval.c_id}>" name="c_id[]" value="<{$subval.c_id}>"<{if $subval.checked}>checked="checked"<{/if}>><label for="c_<{$subval.c_id}>"><{$subval.c_name}></label></span>
                      <{/foreach}>
                      </div>
                      <{/if}>
										<{/foreach}>
									</div>
								</li>
								<li class="buttons">
									<input type="submit" value="<{$smarty.const.LANG_BUTTON_SAVE}>" class="submit" />
									<input type="button" value="<{$smarty.const.LANG_BUTTON_CANCEL}>" onclick="javascript:history.go(-1);"/>
								</li>
							</ul>
							<input type="hidden" name="id" value="<{$data.g_id}>" />
							<input type="hidden" name="key" value="<{$smarty.get.id}>" />
						</form>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
