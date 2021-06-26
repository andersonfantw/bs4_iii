$(document).ready(function(){
    $('#main-content .forms ul li input[type=text]').attr('maxlength','20');
    $('#main-content .forms ul li div').append('<div style="color:#f00;"></div>');
    $('#main-content .buttons .submit').click(function(){
        var valid = true;
    
        //清空前一次的訊息
        $('#main-content .forms ul li div div').text('');
        //驗證是否輸入
        /*
        str = $('#main-content .forms ul li input').eq(0).val();
        if(str==''){
            $('#main-content .forms ul li div div').eq(0).text(LANG_WARMING_ENTER_ACCOUNT);
            valid = false;
        }else{
            if(!/[a-zA-Z][0-9a-zA-Z]{2,19}/.test(str)){
                $('#main-content .forms ul li div div').eq(0).text(LANG_WARMING_ACCOUNT_WRONG_FORMAT);
                valid = false;
            }
        }*/

        str = $('#main-content .forms ul li input').eq(0).val();
        if(str==''){
            $('#main-content .forms ul li div div').eq(1).text(LANG_WARMING_ENTER_PASSWORD);
            valid = false;
        }else{
            if(!/[0-9a-zA-Z\~\!\@\#\$\%\^\&\*\(\)\_\-\+\=\.]{3,20}/.test(str)){
                $('#main-content .forms ul li div div').eq(1).text(LANG_WARMING_PASSWORD_WRONG_FORMAT);
                valid = false;
            }            
        }
        str = $('#main-content .forms ul li input').eq(1).val();
        if(str==''){
            $('#main-content .forms ul li div div').eq(2).text(LANG_WARMING_ENTER_CONFIRMPASSWORD);
            valid = false;
        }else{
            if(!/[0-9a-zA-Z\~\!\@\#\$\%\^\&\*\(\)\_\-\+\=\.]{3,20}/.test(str)){
                $('#main-content .forms ul li div div').eq(2).text(LANG_WARMING_COMFIRMPASSWORD_WRONG_FORMAT);
                valid = false;
            }            
        }

        if($('#main-content .forms ul li input').eq(0).val()!='' && $('#main-content .forms ul li input').eq(0).val()!=str){
            $('#main-content .forms ul li div div').eq(2).text(LANG_WARMING_PASSWORD_NOT_MATCH);
            valid = false;
        }

        return valid;
    });
});