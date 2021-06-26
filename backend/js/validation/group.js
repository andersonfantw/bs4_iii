$(document).ready(function(){
/*    $('#main-content .forms ul li input[type=text]').attr('maxlength','20');
    $('#main-content .forms ul li div').append('<div style="color:#f00;"></div>');

    $('#main-content .buttons .submit').click(function(){
        var valid = true;
    
        //清空前一次的訊息
        $('#main-content .forms ul li div div[style="#f00;"]').text('');
        //驗證是否輸入
        str = $('#main-content .forms ul li input').eq(0).val();
        if(str==''){
            $('#main-content .forms ul li div div').eq(0).text('請輸入群組名稱!');
            valid = false;
        }else{
            if(!/.{2,20}/.test(str)){
                $('#main-content .forms ul li div div').eq(0).text('群組名稱長度為2到20字!');
                valid = false;
            }
        }*/
/*
        str = $('#main-content .forms ul li input').eq(1).val();
        if(str==''){
            $('#main-content .forms ul li div div').eq(1).text('請輸入群組帳號!');
            valid = false;
        }else{
            if(!/[a-zA-Z][0-9a-zA-Z]{2,19}/.test(str)){
                $('#main-content .forms ul li div div').eq(1).text('群組帳號要以英文及數字組成，長度為3到20字!');
                valid = false;
            }
        }

        str = $('#main-content .forms ul li input').eq(2).val();
        if(str!=''){
            if(!/[0-9a-zA-Z\~\!\@\#\$\%\^\&\*\(\)\_\-\+\=\.]{3,20}/.test(str)){
                $('#main-content .forms ul li div div').eq(2).text('群組密碼需要以0~9,a~z,A~Z及符號(~!@#$%^&*()_-+=.)組成，長度為3到20字!');
                valid = false;
            }            
        }
        str = $('#main-content .forms ul li input').eq(3).val();
        if(str!=''){
            if(!/[0-9a-zA-Z\~\!\@\#\$\%\^\&\*\(\)\_\-\+\=\.]{3,20}/.test(str)){
                $('#main-content .forms ul li div div').eq(3).text('群組確認密碼需要以0~9,a~z,A~Z及符號(~!@#$%^&*()_-+=.)組成，長度為3到20字!');
                valid = false;
            }            
        }

        if($('#main-content .forms ul li input').eq(2).val()!='' && $('#main-content .forms ul li input').eq(2).val()!=str){
            $('#main-content .forms ul li div div').eq(3).text('兩次密碼輸入不相同!');
            valid = false;
        }*/

        return valid;
    });
});