$(document).ready(function(){
	var _name = $('#ts_name').val();
	$('#ts_name').keyup(function(){
		if(_name!=$('#ts_name').val()){
			$('#preview').val('* '+LANG_SHORTCUT_BTN_PREVIEW);
		}
	});
	$('#preview').click(function(e){
		if(!$("#ts_name").val()){
			alert(LANG_WARMING_SHORTCUTNAME_CAN_NOT_BE_NULL);
			$("#ts_name").focus();
			e.stopImmediatePropagation();
			return false;
		}else{
			$('#fid').val('');
			str = $('#ts_name').val();
			TagAPIHandler.getImageTicket(str,function(data){
				$('#ticket').val(data.ticket);
				if(data.info['has_zh'] && !data.info['has_en']){
					img_html = '<img src="'+web_url+'/plugin/tag/api/api.php?cmd=getShortcutImage&t='+data.ticket+'" />';
					$('#shortcutimg').html(img_html);
				}else{
					TagAPIHandler.getShortcutHtml(data.ticket,function(data){
						$('#shortcutimg').html(data.html);
					});
				}
			});
			$('#preview').val(LANG_SHORTCUT_BTN_PREVIEW);
		}
	});
	$('form').submit(function(e){
		if(!$("#ts_name").val()){
			alert(LANG_WARMING_SHORTCUTNAME_CAN_NOT_BE_NULL);
			$("#ts_name").focus();
			return false;
		}
		if($('#shortcutimg > div').length){
			if($('#preview').val()!=LANG_SHORTCUT_BTN_PREVIEW){
				alert(LANG_WARMING_SHORTCUTNAME_CHOOSE_STYLE);
				$("#ts_name").focus();
				return false;
			}
		}else{
			if(!$('#shortcutimg img').attr('src') || $('#preview').val()!=LANG_SHORTCUT_BTN_PREVIEW){
				alert(LANG_WARMING_SHORTCUTNAME_CHOOSE_STYLE);
				$("#ts_name").focus();
				return false;
			}
		}
	});
});