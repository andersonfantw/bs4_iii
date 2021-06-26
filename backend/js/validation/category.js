$(document).ready(function(){
    $('#main-content .forms ul li div').append('<div style="color:#f00;"></div>');
    
    $('#c_name').attr('maxlength','100');
    
    if($('#c_name').val().length>15){
        $('#main-content .forms ul li div div').eq(0).text(LANG_WARMING_BOOK_NAME_LENGTH_LIMIT);
    }
    $('#c_name').keyup(function(){
        if($(this).val().length>15){
            $('#main-content .forms ul li div div').eq(0).text(LANG_WARMING_BOOK_NAME_LENGTH_LIMIT);
        }else{
            $('#main-content .forms ul li div div').eq(0).text('');
        }
    });
    //書籍分類順序只能輸入數字
    $('#c_order').keyup(function(){
        $(this).val($(this).val().replace(/[^\d]/g,''));
    });


    //form表單驗證
    $('form').submit(function(event){
	    if(!$("#c_name").val()){
		    alert(LANG_WARNING_CATENAME_CAN_NOT_BE_NULL);
		    return false;
	    }
	});

});