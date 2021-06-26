$(document).ready(function(){
	$('input.submit').click(function(){
		switch($('input[name=tag]:checked').val()){
			case 'pi':
				_s = '執行單位';
				break;
			case 'pcu':
				_s = '承辦科別';
				break;
		}
		if($('#oldtag').val()===null || $('#newtag').val()===null){
			alert('請選擇'+_s);
			return false;
		}
		if($.isArray($('#oldtag').val())){
			if($('#oldtag').val().length<=1){
				alert('您必須選擇兩個以上的'+_s);
				return false;
			}
			if($.inArray($('#newtag').val(),$('#oldtag').val())>-1){
				alert('合併的'+_s+'與目的'+_s+'重複了!');
				return false;
			}
		}else{
			if($('#newtag').val().length<=1){
				alert('您必須選擇兩個以上的'+_s);
				return false;
			}
			if($.inArray($('#oldtag').val(),$('#newtag').val())>-1){
				alert('分開的'+_s+'與目的'+_s+'重複了!');
				return false;
			}
		}
	});
});