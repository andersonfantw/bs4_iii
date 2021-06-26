$(document).ready(function(){
	$('input.submit').click(function(){
	    if($('#u_cname').val()==''){alert(LANG_WARMING_ADMIN_ENTER_USERNAME);return false;}
		if($('#u_password').val()!=''){
		    //if($('#u_password').val()==''){alert('請輸入書櫃管理者密碼!');return false;}
		    if($('#u_password2').val()==''){alert(LANG_WARMING_ADMIN_PASSWORDCONFIRM);return false;}
		    if($('#u_password').val()!=$('#u_password2').val()){alert(LANG_WARMING_ADMIN_NOT_MATCH);return false;}
		}else{
		    alert(LANG_WARMING_ADMIN_PASSWORD_NOT_CHANGE);
		}
	});
});