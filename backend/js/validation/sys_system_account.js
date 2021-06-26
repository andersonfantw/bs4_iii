$(document).ready(function(){
	$('input.submit').click(function(){
		if($('#su_password').val()!=''){
		    //if($('#su_password').val()==''){alert('請輸入系統管理者密碼!');return false;}
		    if($('#su_password2').val()==''){alert(LANG_WARMING_SYSADMIN_ENTER_CONFIRMPASSWORD);return false;}
		    if($('#su_password').val()!=$('#su_password2').val()){alert(LANG_WARMING_SYSADMIN_CONFIRMPASSWORD_NOT_MATCH);return false;}
		}else{
		    alert(LANG_WARMING_SYSADMIN_PASSWORD_NOT_CHANGE);
		}
	});
});