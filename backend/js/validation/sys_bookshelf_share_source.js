$(document).ready(function(){
	$('input.submit').click(function(){
		    if($('#bsss_name').val()==''){alert(LANG_WARMING_SHARE_GET_ENTER_SOURCENAME);return false;}
		    if($('#bsss_source').val()==''){alert(LANG_WARMING_SHARE_GET_ENTER_SOURCEURL);return false;}
		    if($('#bsss_account').val()==''){alert(LANG_WARMING_SHARE_GET_ENTER_SOURCEACCOUNT);return false;}
		    if($('#bsss_password').val()==''){alert(LANG_WARMING_SHARE_GET_ENTER_SOURCEPASSWORD);return false;}
	});
});

