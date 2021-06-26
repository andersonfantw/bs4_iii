$(document).ready(function(){
	$('#bookshelf option').eq(0).attr('selected','selected');
	$('input.submit').click(function(){
		    if($('#bss_ip').val()==''){alert(LANG_WARMING_SHARE_SET_ENTER_IP);return false;}
		    if($('#bsss_account').val()==''){alert(LANG_WARMING_SHARE_SET_ENTER_ACCOUNT);return false;}
		    if($('#bsss_password').val()==''){alert(LANG_WARMING_SHARE_SET_ENTER_PASSWORD);return false;}
	});
});