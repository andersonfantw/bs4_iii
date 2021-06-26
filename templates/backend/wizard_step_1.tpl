<{extends file="backend/base.tpl"}>
<{block name="head"}>
<link href="css/customize/wizard.css" rel="stylesheet" media="all" />
<script language="javascript">
function check(){
	if(!$("#grade_number").val()){
		alert('請填寫有幾個年級')
		return false;
	}
	return true;
}
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
					<div class="portlet-header ui-widget-header">初始化設定精靈 Step 1 :</div>
					<div class="portlet-content">
						提醒您，當群組管理中未設定帳號時，才能使用初始化設定精靈喔！
					 <{if $WIZARD!='off'}>
						<form action="wizard.php?step=2" method="post" enctype="multipart/form-data" class="forms" name="form" >
							<ul>
								<li>
									<label  class="desc">
										*請問有幾個年級
									</label>
									<div>
										<input type="text" tabindex="1" maxlength="255" class="field text small" id="grade_number" name="grade_number" />
									</div>
								</li>
                 <li class="buttons">
				
                  <input type="submit" value="下一步" class="submit" onclick="return check();" />
                  <input type="button" value="取消" onclick="javascript:location.href='wizard.php';"/>
				  
                </li>
							</ul>
						</form><{/if}>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
