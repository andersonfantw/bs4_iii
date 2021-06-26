/***********************************************
page and js needs
1. reading settings to setup format icons.
2. same js, individual page(frontsite/backend)
3. settings get from api
4. language will effect both frontsite & backend
***********************************************/
//check where the js process
//isBackend=0: front, 1:backend
/* systemEnv
var regex=/\/backend\//;
var sysregex=/\/backend\/sys_/;
var isSysBackend=(document.location.href.match(sysregex)!=null);
var isBackend=(document.location.href.match(regex)!=null);
*/

var TagImportAction = function(panel){
	$('#body').prepend(panel);

	var _convert_allowfiletype;
	var _convert_filetypes = {'tag':'.tag','dic':'.dic','xls':'.xls'};
	_m = parseInt($.getQuery('m'));
	TagImportAPIHandler.getStatusCode('statuscode',systemEnv.isBackend,_m,function(_convert_status_code){
		//show allow file type
		var _convert_allowfiletype='';
		$.each(_convert_filetypes,function(key, value){
			str = key.replace('_','<br />').toUpperCase();
			var _disable='';
			if(typeof _convert_status_code[key]!='undefined'){
				switch(_convert_status_code[key][0]){
					case 0:
							_disable=' disable';
					case 1:
						$('#convert > div:first').append('<div class="icon file_'+key+_disable+'"><div><div><div></div></div>'+str+'</div></div>');
						break;
					case -1:
					default:
						break;
				}
			}
			_convert_allowfiletype += value;
		});
		$('#convert').append('<input id="convert_allowfiletype" type="hidden" value="'+_convert_allowfiletype+'.">');

		$('.icon').hover(
			function(){
				$('.talkbox').css('display','block');
				_index = $('.icon').index($(this));
				$('.arrow_t_int').css('left',(25+100*_index)+'px');
				$('.arrow_t_out').css('left',(25+100*_index)+'px');
				_type = $(this).attr('class').split(" ")[1].substr(5);
				
				//if has agree, create btn
				_btn = '';
				if(_convert_message[_convert_status_code[_type][1]].agreelink){
					_btn = "<a class='btn agree' href='/api/redirect.php?cmd="+_convert_message[_convert_status_code[_type][1]].agreelink+"'>"+_convert_btn_text.agree+"</a> | <a class='btn cancel' href='javascript:;'>"+_convert_btn_text.cancel+"</a>";
				}
				$('.mwt_border > div').html(_convert_message[_convert_status_code[_type][1]].msg+_btn);
				
				//btn setting
				$('.talkbox .agree').click(function(){
					document.location.href=_convert_message[_convert_status_code[_type][1]].agreelink;
				});
				$('.talkbox .cancel').click(function(){
					$('.talkbox').fadeOut('slow');
				});
			},
			function(){
				$('#convert').mouseleave(function(){
					$('.talkbox').fadeOut('slow');
				});
			}
		);
	});
}