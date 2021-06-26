/***********************************************
page and js needs
1. reading settings to setup format icons.
2. same js, individual page(frontsite/backend)
3. settings get from api
4. language will effect both frontsite & backend
***********************************************/
/* filetype example :[status,code]
var _status_code = {"pdf":[1,"200.2"],
								"doc":[0,"401.2"],
								"ppt":[0,"401.2"],
								"xls":[0,"401.2"],
								"lbm_zip":[1,"200.1"],
								"itu_zip":[1,"200.1"],
								"ebk":[-1,"403.1"]}
*/

var EBookConvertAction = function(){
	if(systemEnv.isBackend){
		Init();
	}else{
		$(EBOOKCONVERT_BUTTON).click(function(e){
			if(bookEnv.currentCateLevel==0){
				alert(_convert_warning_select_submenu);
				e.stopPropagation();
			}else{
				if(!convertEnv.attachEvent) Init();
				$(EBOOKCONVERT_CONTAINER).addClass('show');
				DialogueHandler.center(EBOOKCONVERT_CONTAINER);
				DialogueHandler.showMask();
			}
		});
	}
	
	function Init(){
		if(bookEnv.InitJSON.enable_ecocat){
			setSkinPanel();
			setSpellPanel();
		}else{
			$(EBOOKCONVERT_SKIN_SELECTED).remove();
		}
		setStatusCodePanel();
		AttachEvent();
		new Html5Uploader();
		convertEnv.attachEvent=true;
	}

	function setSkinPanel(){
		clearSkinPanel();
		EbookAPIHandler.getSkinList(systemEnv.bsid,function(val){
			//set skin
			convertEnv.skin=val.default;
			convertEnv.skinJSON=val.data;

			for(i=0;i<val.data.detail.length;i++) {
				_tmp = EBOOKCONVERT_SKIN_TEMPLATE;
				_tmp = $(_tmp).css({'background':'url('+val.data.detail[i].skin_image_url+')','background-size':'250px 156px','background-repeat':'no-repeat'});
				$(_tmp).find('.skinname').val(val.data.detail[i].skin);
				$(_tmp).find('.skinimg').val(val.data.detail[i].skin_image_url);

				if(val.default==val.data.detail[i].skin){
					$(_tmp).addClass('selected');
					$(EBOOKCONVERT_SKIN_SELECTED).css({'background':'url('+ val.data.detail[i].skin_image_url +')','background-size':'250px 156px','background-repeat':'no-repeat'});
				}
				$(EBOOKCONVERT_SKIN_SELECTOR+' ul').append(_tmp);
			}
			$(EBOOKCONVERT_SKIN_ITEM).click(function(e){
				$(EBOOKCONVERT_SKIN_SELECTED).css({'background':'url('+ $(this).find('.skinimg').val() +')','background-size':'250px 156px','background-repeat':'no-repeat'});
				convertEnv.skin = $(this).find('.skinname').val();
				e.stopPropagation();
			});
			$(EBOOKCONVERT_SKIN_ITEM).dblclick(function(e){
				$(EBOOKCONVERT_SKIN_SELECTED).css({'background':'url('+ $(this).find('.skinimg').val() +')','background-size':'250px 156px','background-repeat':'no-repeat'});
				convertEnv.skin = $(this).find('.skinname').val();
				showConvertPanel();
				e.stopPropagation();
			})
		});
	}

	function clearSkinPanel(){
		$(EBOOKCONVERT_SKIN_SELECTOR+' ul').empty();
	}

	function setSpellPanel(){
		EbookAPIHandler.getSpellList(systemEnv.bsid,function(val){
			convertEnv.spell=val.default;
			convertEnv.spellJSON=val.data;
			$(EBOOKCONVERT_SPELL_SELECTED).removeClass().addClass(val.default);

		});
	}

	function setStatusCodePanel(){
		EbookAPIHandler.getStatusCode(systemEnv.isBackend,function(_convert_status_code){
			//show allow file type
			$.each(convertEnv.convert_filetypes,function(key, value){
				str = key.replace('_','<br />').toUpperCase();
				var _disable='';
				switch(_convert_status_code[key][0]){
					case 0:
							_disable=' disable';
					case 1:
						_tmp=EBOOKCONVERT_STATUSICON_TEMPLATE.replace('@str',str);
						_tmp = $(_tmp).addClass('file_'+key)
													.addClass(_disable);
						$(EBOOKCONVERT_ICON_CONTAINER).append(_tmp);
						break;
					case -1:
					default:
						break;
				}
				convertEnv.convert_allowfiletype += value;
			});

			_tmp = EBOOKCONVERT_ALLOWTYPE_HIDDENINPUT.replace('@str',convertEnv.convert_allowfiletype+'.');
			$(EBOOKCONVERT_CONTAINER).append(_tmp);

			$(EBOOKCONVERT_ICONS).hover(
				function(){
					$(EBOOKCONVERT_TALKBOX_CONTAINER).css('display','block');
					_index = $(EBOOKCONVERT_ICONS).index($(this));
					$('.arrow_t_int').css('left',(25+100*_index)+'px');
					$('.arrow_t_out').css('left',(25+100*_index)+'px');
					_type = $(this).attr('class').split(" ")[1].substr(5);
					
					//if has agree, create btn
					_btn = '';
					if(_convert_message[_convert_status_code[_type][1]].agreelink){
						_btn = EBOOKCONVERT_TALKBOX_BUTTON_TEMPLATE.replace('@cmd',_convert_message[_convert_status_code[_type][1]].agreelink)
																											.replace('@agree',_convert_btn_text.agree)
																											.replace('@cancel',_convert_btn_text.cancel);
					}
					$(EBOOKCONVERT_TALKBOX_MSG).html(_convert_message[_convert_status_code[_type][1]].msg+_btn);
					
					//btn setting
					$(EBOOKCONVERT_TALKBOX_AGREE_BUTTON).click(function(){
						document.location.href=_convert_message[_convert_status_code[_type][1]].agreelink;
					});
					$(EBOOKCONVERT_TALKBOX_CANCEL_BUTTON).click(function(){
						$(EBOOKCONVERT_TALKBOX_CONTAINER).fadeOut('slow');
					});
				},
				function(){
					//$('.talkbox').mouseout(function(){
					//	$('.talkbox').fadeOut('slow');
					//});
					$(EBOOKCONVERT_CONTAINER).mouseleave(function(){
						$(EBOOKCONVERT_TALKBOX_CONTAINER).fadeOut('slow');
					});
				}
			);
		});
	}

	function AttachEvent(){
		//set skin and spell panel action
		$(EBOOKCONVERT_SKIN_SELECTED).click(function(e){
			if($(EBOOKCONVERT_SKIN_SELECTOR).is(':hidden')){
				showSkinPanel();
			}else{
				showConvertPanel();
			}
			e.stopPropagation();
		});
		$(EBOOKCONVERT_SKIN_SELECTOR).click(function(e){
			showConvertPanel();
			e.stopPropagation();
		});
		$(EBOOKCONVERT_SPELL_SELECTED).click(function(e){
			_spell='left';
			if($(EBOOKCONVERT_SPELL_SELECTED).attr('class')==_spell){
				_spell='right';
			}
			convertEnv.spell = _spell;
			$(EBOOKCONVERT_SPELL_SELECTED).removeAttr('class').addClass(_spell);
			e.stopPropagation();
			EbookAPIHandler.setSkinSettings(convertEnv.skin,convertEnv.spell,function(data){
				var _rcc = new ReturnCodeControler('ebookconvert',data);
				_val = _rcc.handling();
			});
		});
	}

	//panel control
	function showConvertPanel(){
		$(EBOOKCONVERT_SKIN_SELECTOR).hide();
		$(EBOOKCONVERT_DROPBOX).show();
		$(EBOOKCONVERT_FILEINPUT).show();
		EbookAPIHandler.setSkinSettings(convertEnv.skin,convertEnv.spell,function(data){
			var _rcc = new ReturnCodeControler('ebookconvert',data);
			_val = _rcc.handling();
		});
	}
	function showSkinPanel(){
		setSkinPanel();
		$(EBOOKCONVERT_SKIN_SELECTOR).show();
		$(EBOOKCONVERT_DROPBOX).hide();
		$(EBOOKCONVERT_FILEINPUT).hide();
	}
}

