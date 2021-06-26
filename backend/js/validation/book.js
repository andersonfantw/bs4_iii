$(document).ready(function(event){
    $('#main-content .forms ul li div').append('<div style="color:#f00;"></div>');
    if(_enable_cowriter)
        $('#main-content .portlet-content ul').prepend('<li><label class="desc">作者 &nbsp; <input class="add_writer" type="button" value="'+LANG_BOOKS_EDIT_WRITER_BTN_ADD+'" /></label></li><li>'+LANG_BOOKS_EDIT_WRITER_WRITER+':<div class="writer"><input type="text" /><div></ii>');
    
    if(_enable_links)
        $('#main-content .portlet-content ul li [name=b_description]').parent().parent().before('<li><label class="desc">'+LANG_BOOKS_EDIT_LINK_LINK+' &nbsp; <input class="add_link" type="button" value="'+LANG_BOOKS_EDIT_LINK_BTN_ADD+'" /></label></li><li><div class="link_row">'+LANG_BOOKS_EDIT_LINK_NAME+':<input class="name" type="text" /> &nbsp; '+LANG_BOOKS_EDIT_LINK_LINK+':<input class="link" type="text" /><div></ii>');

    if(_enable_imglinks)
        $('#main-content .portlet-content ul li [name=b_description]').parent().parent().before('<li><label class="desc">'+LANG_BOOKS_EDIT_IMGLINK_LINK+' &nbsp; <input class="add_imglink" type="button" value="'+LANG_BOOKS_EDIT_IMGLINK_BTN_ADD+'" /></label></li><li><div class="imglink_row upload"><div class="upload">'+LANG_BOOKS_EDIT_IMGLINK_IMAGE+':<input class="icon" name="icon" type="file" /></div><div class="img"><img /><input type="button" class="btnDel" value="'+LANG_BOOKS_EDIT_IMGLINK_BTN_DEL+'" onclick="switchToUploadPanel(this)" /></div> &nbsp; '+LANG_BOOKS_EDIT_IMGLINK_LINK+':<input name="imglink" class="link" type="text" /><div></ii>');
    
    $('#b_name').attr('maxlength','100');
    if($('#b_name').val().length>34){
        $('#b_name').next().text(LANG_WARMING_BOOK_NAME_LENGTH_LIMIT);
    }
    $('#b_name').keyup(function(){
        if($(this).val().length>34){
            $('#b_name').next().text(LANG_WARMING_BOOK_NAME_LENGTH_LIMIT);
        }else{
            $('#b_name').next().text('');
        }
    });
    $('.add_writer').click(function(){
	    if($('.cowriter').length==0){
		    $('.writer').append(LANG_BOOKS_EDIT_WRITER_COWRITER+':<div class="cowriter"><input type="text" /></div>');
	    }else{
		    $('.cowriter').append('<input type="text" />');
	    }
    });

    $('.add_link').click(function(){
			$('.link_row').parent().append('<div class="link_row">'+LANG_BOOKS_EDIT_LINK_NAME+':<input class="name" type="text" /> &nbsp; '+LANG_BOOKS_EDIT_LINK_LINK+':<input class="link" type="text" /><div>');
    });

    $('.add_imglink').click(function(){
			$('.imglink_row').parent().append('<div class="imglink_row upload"><div class="upload">'+LANG_BOOKS_EDIT_IMGLINK_IMAGE+':<input class="icon" name="icon" type="file" /></div><div class="img"><img /><input type="button" value="'+LANG_BOOKS_EDIT_IMGLINK_BTN_DEL+'" onclick="switchToUploadPanel(this)" /></div> &nbsp; '+LANG_BOOKS_EDIT_IMGLINK_LINK+':<input name="imglink" class="link" type="text" /><div>');
    });

    $('form').submit(function(event){
        //form表單驗證
        if(!$("#b_name").val()){
	        alert(LANG_WARMING_BOOKNAME_CAN_NOT_BE_NULL);
	        $("#b_name").focus();
	        return false;
        }

        if(!$("#img").val() && !$("#file_id").val() ){
	        alert(LANG_WARMING_SETUP_COVER);
	        $("#img").focus();
	        return false;
        }

				_content = $('textarea[name="b_description"]').text();

        //功能資料處理
        _links = '';
        _link_data = '';
        $('.link_row').each(function(){
            if($(this).find('.link').val()!=''){
                _link=$(this).find('.link').val();
                _name=$(this).find('.name').val();
                if(_name==''){
                    _name=_link;
                }

                _links += '<a href="'+_link+'">'+_name+'</a><br />';
                _link_data += _name+','+_link+';';
            }
        });
        if(_enable_links && (_links!='')){
            _links = LANG_BOOKS_EDIT_LINK_TITLE+': <br />'+_links+'<input type="hidden" class="l_data" value="'+_link_data+'" /><hr /><br />'
            _content = _links+_content;
        }

        _writers = '';
        _writer_data = '';
        _cowriters_data = '';
        i=0;
        if($('.writer input').val()!=''){
	        _writers += LANG_BOOKS_EDIT_WRITER_WRITER+': '+$('.writer input').val();
	        _writer_data = $('.writer input').val();
        }
        $('.cowriter input').each(function(){
	        if($(this).val()!=''){
                if(i==0){
                        if(_writers!='') _writers += '<br />';
    	                _writers += LANG_BOOKS_EDIT_WRITER_COWRITER+': ';
                }
                if(i>0) _cowriters_data += ',';
                _cowriters_data += $(this).val();
                i++;
	        }
        });
        if(_enable_cowriter && (($('.writer input').val()!='') || (_cowriters_data!=''))){
            _writers += _cowriters_data + '<input type="hidden" class="writer_data" value="'+_writer_data+'" /><input type="hidden" class="cowriter_data" value="'+_cowriters_data+'" /><hr /><br />';
            _content = _writers+_content;
        }
        $('textarea[name="b_description"]').text(_content);
        sleep(0.5); //為了讓送出的資料來得及放到b_description內
    });

    if($('.writer_data').val()!=''){
	    $('.writer input').val($('.writer_data').val());	
    }

    if($('.cowriters_data').val().length>0){
	    $($('.cowriters_data').val().split(',')).each(function(index, value){
            if($('.cowriter').length==0){
    	            $('.writer').append('共同作者:<div class="cowriter"><input type="text" value="'+value+'" /></div>');
            }else{
    	            $('.cowriter').append('<input type="text" value="'+value+'" />');
            }		
	    });
    }

    if($('.l_data').val().length>0){
	    $($('.l_data').val().split(';')).each(function(index, value){
	        arr = value.split(',');
	        if(arr[0]==arr[1]) arr[0]='';
	        if(value=='') arr[1]='';
	        if(index==0){
	            $('.link_row:eq(0) .name').val(arr[0]);
	            $('.link_row:eq(0) .link').val(arr[1]);
	        }else{
                $('.link_row').parent().append('<div class="link_row">'+LANG_BOOKS_EDIT_LINK_NAME+':<input class="name" type="text" value="'+arr[0]+'" /> &nbsp; '+LANG_BOOKS_EDIT_LINK_LINK+':<input class="link" type="text" value="'+arr[1]+'" /><div>');
	        }
	    });
    }

    if($('.icons_data').val().length>0){
        $($('.icons_data').val().split(';')).each(function(index, value){
	        arr = value.split(',');
          if(index==0){
              $('.imglink_row').removeClass('upload').addClass('img');
              $('.imglink_row .img img').attr('src',arr[0]);
              $('.imglink_row .link').val(arr[1]);
          }else{
              $('.imglink_row').parent().append('<div class="imglink_row img"><div class="upload">'+LANG_BOOKS_EDIT_IMGLINK_IMAGE+':<input class="icon" name="icon" type="file" /></div><div class="img"><img src="'+arr[0]+'" /><input type="button" class="btnDel" value="'+LANG_BOOKS_EDIT_IMGLINK_BTN_DEL+'" onclick="switchToUploadPanel(this)" /></div> &nbsp; '+LANG_BOOKS_EDIT_IMGLINK_LINK+':<input name="imglink" class="link" type="text" value="'+arr[1]+'" /><div>');
          }
          $('.imglink_row .img img').mouseenter(function(){
          	$('#iconorgpic').attr('src',$(this).attr('src'));
          	_p = $(this).position();
          	$('#iconorgpic').css({top:_p.top});
          	$('#iconorgpic').show();
          });
          $('.imglink_row .img img').mouseleave(function(){
          	$('#iconorgpic').hide();
          });
        });
    }

    //書籍分類順序只能輸入數字
    $('#b_order').keyup(function(){
        $(this).val($(this).val().replace(/[^\d]/g,''));
    });
});
function switchToUploadPanel(objDel){
    $(objDel).parent().parent().removeClass('img');
    $(objDel).parent().parent().addClass('upload');
    //刪除的圖片資料的區塊也要一起刪除
    _icons_data='';
    _url=$(objDel).prev().attr('src');
    arr = $('.icons_data').val().split(';');
    for(var i in arr){
        if(arr[i].indexOf(_url)==-1)
            _icons_data += ';'+arr[i];
    }
    if(_icons_data.length>0)
        _icons_data = _icons_data.substring(1);
    $('.icons_data').val(_icons_data);
}

function sleep( seconds ) {
	var timer = new Date();
	var time = timer.getTime();
	do
		timer = new Date();
	while( (timer.getTime() - time) < (seconds * 1000) );
}
