<{extends file="backend/base.tpl"}>
<{block name="head"}>
<link href="css/customize/wizard.css" rel="stylesheet" media="all" />
<script language="javascript">
$(document).ready(function(e){

  $("#form1").submit(function(){
    if(confirm('確定要開始新增?')){
      var isFormValid = true;

      $("#form1 input:text").each(function(){
        if ($.trim($(this).val()).length == 0){
            isFormValid = false;
        }
      });

      if (!isFormValid) alert("所有欄位皆須填寫");

      return isFormValid;
    }else{
      return false;
    }
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
					<div class="portlet-header ui-widget-header">初始化設定精靈 Step 2 :</div>
					<div class="portlet-content">
						<form action="wizard.php?step=3" method="post" enctype="multipart/form-data" class="forms" name="form" id="form1">
							<ul>
                <{section name=grade start=0 loop=$grade_number step=1}>
								<li>
									<label class="desc">
										*請問<{$smarty.section.grade.index + 1}>年級有幾個班?
									</label>
									<div>
										<input type="text" tabindex="1" maxlength="255" class="field text small" id="class_number" name="class_number[]" />
									</div>
								</li>
                <{/section}>
                 <li class="buttons">
                  <input type="submit" value="新增" class="submit" />
                  <input type="button" value="取消" onclick="javascript:location.href='wizard.php';"/>
                </li>
							</ul>
						</form>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
