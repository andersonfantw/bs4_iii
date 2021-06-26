<{extends file="backend/base.tpl"}>
<{block name="head"}>
<link href="css/customize/ecocat.css" rel="stylesheet" media="all" />
<script language="javascript">
$(document).ready(function(e){
  //setInterval('checkProcess()',300);
  $('#btn').click(update);
});

var checkProcess = function(){
	if($('#processing').val()>0){
		$('#loading').show();
	}else{
		$('#loading').hide();
	}
}

function doUpdate(){
	if($('input:checkbox[name=update_arr][checked=checked]').length==($('#processindex').val()-1)){
		$('#processing').val(-1);
		$('#loading').addClass('hide');
	}
	if($('#processing').val()==0){
		target = $('input:checkbox[name=update_arr][checked=checked]').eq($('#processindex').val()).val();
    var update_url;
    if ( target =='ecocat'){
      update_url = './ecocat_update.php';
    }else{
      update_url = './ecocat_update.php?type=share_bs&bsss_id='+target;
    }
    $.ajax({
      url: update_url,
      error: function(xhr) {
        alert('<{$smarty.const.LANG_ERROR_AJAX_REQUEST_ERROR}>');
      },
      success: function(response) {
        if(response){
          /*alert(target);*/
          $('#'+target+'_status').css('color','red');
          $('#'+target+'_status').html('<{$smarty.const.LANG_ERROR_UPDATE_FAIL}>: '+response);

        }else{
          /*alert('書籍更新成功');*/
          $('#'+target+'_status').css('color','green');
          $('#'+target+'_status').html('<{$smarty.const.LANG_MESSAGE_UPDATE_SUCCESS}>');
        }
      },
      beforeSend :function(){
      	$('#processing').val(1);
      },
      complete: function(){
      	$('#processindex').val(parseInt($('#processindex').val())+1);
      	$('#processing').val(0);
      }
    }); 
	}
}

function update(e) {
  if(confirm('<{$smarty.const.LANG_WARNING_ECOCATUPDATE_CONFIRM}>'))
  {
		$('#processing').val(0);
		$('#processindex').val(0);
  	$('input:checkbox[name=update_arr]').each(function(){
  		target = $(this).val();
  		$('#'+target+'_status').html('');
  	});
  	$('input:checkbox[name=update_arr][checked=checked]').each(function(){
  		target = $(this).val();
	    $('#'+target+'_status').css('color','black');
	    $('#'+target+'_status').html('<{$smarty.const.LANG_MESSAGE_UPDATING}>');
  	});
  	$('#loading').removeClass('hide');
  	if($('input:checkbox[name=update_arr][checked=checked]').length>0){
  		$('#loading').removeClass('hide');
    	setInterval('doUpdate()',500);
    }
  }
}
</script>
<style>
.hide{
	display: none;
}
</style>
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
				
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_UPDATE_TITLE}></div>
					<div class="hastable">
            <table border="1">
              <thead>
              <tr>
                <td><{$smarty.const.LANG_UPDATE_COL_SOURCE}></td>
                <td><{$smarty.const.LANG_UPDATE_COL_UPDATESTATUS}></td>
              </tr>
              </thead>
              <tbody>
              	<{if $smarty.const.CONNECT_ECOCAT==1 }>
                <tr>
                  <td><label><input type="checkbox" name="update_arr" value="ecocat" checked="true" /> Ecocat</label></td>
                  <td id="ecocat_status"></td>
                </tr>
                <{/if}>
                <{foreach from=$data item="val" name=myloop}>
                <tr>
                  <td><label>
			<{if $val.bsss_status=='200'}>
			<input type="checkbox" name="update_arr" value="<{$val.bsss_id}>" /> <{$val.bsss_name}>
			<{else}>
			<input type="checkbox" disabled />
				<{$val.bsss_name}> (Connection error! Please contact Webadmin)
			<{/if}>
		  </label></td>
                  <td id="<{$val.bsss_id}>_status"></td>
                </tr>
                <{/foreach}>
              </tbody>
            </table>
			<input type="button" id="btn" value="<{$smarty.const.LANG_BUTTON_UPDATE}>" />
			<input type="hidden" id="processing" value="0" />
			<input type="hidden" id="processindex" value="0" />
			<span id="loading" style="color:red;" class="hide"><{$smarty.const.LANG_MESSAGE_UPDATING}><img src="./images/ajax-loader.gif" /></span>          
					</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
