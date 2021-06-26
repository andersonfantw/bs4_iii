$(document).ready(function(){
	json = {ty:{name:'年度',controller:'input',type:'year',class:'slider',prefix:'ROC',midstr:' - ',min:94,max:106,value:[105,106]},
					pn:{name:'計畫名稱',controller:'select',class:'chosen'},
					pi:{name:'計畫編碼',controller:'select',class:'chosen'},
					py:{name:'計畫年數',controller:'input',class:'slider',prefix:'',midstr:'/',min:0,max:6,value:[1,4]},
					pcof:{name:'經費類別',controller:'select',class:'chosen'},
					pc:{name:'承辦人',controller:'select',class:'chosen'},
					pcu:{name:'承辦科別',controller:'select',class:'chosen'},
					pi:{name:'執行單位',controller:'select',class:'chosen'},
					prt:{name:'報告類型',controller:'select',class:'chosen'},
					pwrf:{name:'領域',controller:'select',class:'chosen'}};
	json_quicksearch={
		1:{name:'月初例行',data:{}},
		2:{name:'期中審查報告',data:{}},
		3:{name:'今年期末審查報告',data:{}}
	};

	_template_quicksearch = '<li><a class="@size@" data-id="@id@">@name@</a></li>';
	_template_search_buttons = '<li class="button"><button class="search"></button><button class="save"></button></li>';
	_template_checkbox = '<li class="@key@"><input type="checkbox" @checked@ /><span>@name@</span></li>';
	_template_li = '<li class="@key@"><span>@name@</span><input type="hidden" value="@val@" /><div>@text@</div></li>';
	_template_slider = '<li class="@key@"><span>@name@</span><input readonly /><div></div></li>';
	_template_chosen = '<li class="@key@"><span>@name@</span><select style="width:140px;height:20px;">@option@</select></li>';
	_template_py = '<li class="@key@"><span>@name@</span><div><input type="text" class="@key@1" />/<input type="text" class="@key@2" /></div></li>';
	_template_option = '<option value="@key@">@val@</option>';

	_str_header = 'Search ebook';
	_quicksearch_iconsize = ['small','mid','large'];
	_time_settings = ['thisyear','lastyear'];
	arrClass = ['minimize','maximize','full-screen'];
	arrPanel = ['#savesearchitem','#quicksearch'];

	searchsettings = {
		loading:false,
		queryitemdata:null,
		quicksearch:false
	};
	$('.circle_button .red').click(function(){
		HidePanel();
	});
	$('#searchpanel .circle_button .green').click(function(){
		classes = $('#searchpanel').attr('class').split(' ');
		_index = arrClass.indexOf(classes[0]);
		_index = (++_index>2)?2:_index;
		$('#searchpanel').removeClass(classes[0]).addClass(arrClass[_index]);
	});
	$('#searchpanel .circle_button .yellow').click(function(){
		classes = $('#searchpanel').attr('class').split(' ');
		_index = arrClass.indexOf(classes[0]);
		_index = (--_index<0)?0:_index;
		$('#searchpanel').removeClass(classes[0]).addClass(arrClass[_index]);
	});

	$('#searchpanel .left-content .qt1').click(function(){
		$('#searchpanel .left-content li').removeClass('selected');
		$(this).addClass('selected');
		$('#searchpanel .right-content>div:eq(0)').removeClass('search-item').addClass('quick-search');
	});
	$('#searchpanel .left-content .qt2').click(function(){
		$('#searchpanel .left-content li').removeClass('selected');
		$(this).addClass('selected');
		$('#searchpanel .right-content>div:eq(0)').removeClass('quick-search').addClass('search-item');
		UnsetQuickSearch();
		unsetQueryItem();
		$('#searchpanel .right-content>div>.search-item .save').hide();
	});
  $('#searchpanel .right-content .list>.tags>.open').click(function(){
  	$tagpanel = $('#searchpanel .right-content .list>.tags>div');
  	if($tagpanel.is(':visible')){
  		$tagpanel.hide();
  	}else{
  		$tagpanel.show();
  	}
  });
  $('#searchpanel .right-content>div>.list').scroll(function(){
  	if($(this).scrollTop()!=0) $('#searchpanel .right-content .list>.tags>div').hide();
  	if($(this).scrollLeft()!=0) $('#searchpanel .right-content .list>.tags>div').hide();
  });
	$('#quicksearch .button .Cancel').click(function(){
		HidePanel();
	});
	$('#quicksearch .button .Save').click(function(){
		_name = $('#quicksearch .container input').val();
		if(_name==''){
			alert('Quick search name is blank!');return;
		}
		_r = getquerystring();
		$('#quicksearch .container select').each(function(){
			_k = $(this).parent().attr('class');
			if(typeof json[_k]!='undefined'){
				if(typeof json[_k].type!='undefined'){
					switch(json[_k].type){
						case 'year':
							_arr = $(this).val().split(',');
							_r[_k] = (_arr.length>1)?_arr:$(this).val();
							break;
					}
				}
			}
		});
		/*
		SearchAPIHandler.getQuickSearch(1,function(data){
			
		});*/
		_s = StorageHandler.getQuickSearchStr(1);
		_arr = json_quicksearch;
		if(_s){
			_arr = $.parseJSON(_s);
		}
		_arr[4]={name:_name, data:_r};
		StorageHandler.setQuickSearchStr(1,_arr);
		HidePanel();
	});
	$('#quicksearch .container>input').keyup(function(e){
		_str = $(this).val();
		str=_str.replace(/[^\x00-\xff]/g, "**");
		if(str.length<=8){
			$('#quicksearch .img').removeClass().addClass('img small');
		}else if(str.length<=12){
			$('#quicksearch .img').removeClass().addClass('img mid');
		}else if(str.length<=16){
			$('#quicksearch .img').removeClass().addClass('img large');
		}else{
			$(this).val(_str.substring(0,_str.length-1));
		}
	});
	$('#savesearchitem .button .Cancel').click(function(){
		HidePanel();
	});
	$('#savesearchitem .container .content .Save').click(function(){
		_arr=[];
		$('#savesearchitem .container .content li').each(function(){
			if(!$(this).find('input').prop('checked')){
				_arr.push($(this).attr('class'));
			}
		});
		StorageHandler.setSearchItemSettingStr(1,JSON.stringify(_arr));
		$('#savesearchitem .button .Cancel').click();
		setQueryItem();
		HidePanel();
	});
	$('#searchpanel .list #example').DataTable({
      "dom": '<"top"if>rt<"bottom"p><"clear">'
  });
  $('.list table tbody tr').mouseenter(function(event){
		$(this).css('top',event.pageY);
  });
	$('#searchpanel .application-panel-header > .function > div > .SearchSetting').click(function(){
		if(searchsettings.loading){
			alert('loading');return;
		}
		ShowPanel('#savesearchitem');
	});
	$('#searchpanel .application-panel-header > .function > div > .ShrunkFontLevel').click(function(){
		if(searchsettings.loading){
			alert('loading');return;
		}
		_size = parseInt($('#body').css('font-size'));
		if(_size){
			_size -= 1;
			if(_size<14) _size=14;
			$('#body').css('font-size',_size+'px');
		}else{
			$('#body').css('font-size','15px');
		}
	});
	$('#searchpanel .application-panel-header > .function > div > .IncreaseFontLevel').click(function(){
		if(searchsettings.loading){
			alert('loading');return;
		}
		_size = parseInt($('#body').css('font-size'));
		_maxsize=19;
		_l = $('#searchpanel .right-content>div>.search-item>ul>li').length;
		switch(true){
			case _l<8:
				_maxsize=19;
				break;
			case _l<11:
				_maxsize=16;
				break;
		}
		if(_size){
			_size += 1;
			if(_size>_maxsize) _size=_maxsize;
			$('#body').css('font-size',_size+'px');
		}else{
			$('#body').css('font-size',_maxsize+'px');
		}
	});
	setQueryItem();
	setQuickSearchList();
  function setQuickSearchList(){
		_quicksearchstr = StorageHandler.getQuickSearchStr(1);
		if(_quicksearchstr){
			_quicksearcharr = $.parseJSON(_quicksearchstr);
		}else{
			_quicksearcharr=json_quicksearch;
		}
		$.each(_quicksearcharr,function(i,v){
			l=v.name.replace(/[^\x00-\xff]/g, "**").length;
			switch(true){
				case l<9:
					_s = 0;
					break;
				case l<13:
					_s = 1;
					break;
				case l<17:
					_s = 2;
					break;
			}
			_str = _template_quicksearch
						.replace('@size@',_quicksearch_iconsize[_s])
						.replace('@id@',i)
						.replace('@name@',v.name);
			$('#searchpanel .right-content >div> .quick-search ul').append(_str);
		});
		$('#searchpanel .right-content .quick-search ul li').click(function(){
			$(this).parent().find('li').removeClass('selected');
			$('#searchpanel .left-content .qt2').click();
			$(this).addClass('selected');
			_id = $(this).find('a').data('id');
			_quicksearchstr = StorageHandler.getQuickSearchStr(1);
			if(_quicksearchstr){
				_quicksearcharr = $.parseJSON(_quicksearchstr);
			}else{
				_quicksearcharr=json_quicksearch;
			}
			_name = _quicksearcharr[_id].name;
			_condition = _quicksearcharr[_id].data;
			SetQuickSearch(_condition,_id,_name);
console.log('quick search click');
			setQueryItem(_condition);
		});
  }
  function setQueryItem(_condition){
  	searchsettings.queryitemdata = _condition;
		if(searchsettings.loading){
console.log('return');
			return;
		}
		_searchsettingstr = StorageHandler.getSearchItemSettingStr(1);
		_searchsetting = {};
		if(typeof _searchsettingstr!='undefined'){
			_searchsetting = $.parseJSON(_searchsettingstr);
		}
  	if(Object.keys(json).length-_searchsetting.length+1==$('#searchpanel .right-content>div>.search-item>ul>li').length){
console.log('call _setQueryValue');
  		if(searchsettings.queryitemdata) _setQueryValue(searchsettings.queryitemdata);
  		return;
  	}
  	searchsettings.loading=true;
		$('#searchpanel .right-content>div>.search-item').css('visibility','hidden');
		$('#searchpanel .application-panel-header .loading').addClass('active');
		$('#savesearchitem .container .content ul').empty();
		$('#searchpanel .right-content>div>.search-item>ul').empty();
		for(var k in json){
			//pk = k.split('_');
			_checked = (!_searchsetting.hasOwnProperty(k))?'checked':'';
			_chktpl = _template_checkbox
							.replace('@key@',k)
							.replace('@name@',json[k].name)
							.replace('@checked@',_checked);
			$('#savesearchitem .container .content ul').append(_chktpl);

			//init query item
			if(!_searchsetting.hasOwnProperty(k)){
				//get query options
				TagAPIHandler.getTagByPKey(k,function(data){
					_tpl='';
					_options=_template_option
									.replace('@key@','')
									.replace('@val@','');
					switch(json[data.pkey].controller){
						case 'select':
							for(var j in data.data){
								_options+=_template_option.replace('@key@',data.data[j].key).replace('@val@',data.data[j].key+':'+data.data[j].val);
							}
							_tpl =_template_chosen
									.replace('@key@',data.pkey+' '+json[data.pkey].class)
									.replace('@name@',json[data.pkey].name)
									.replace('@option@',_options);
							$('#searchpanel .right-content>div>.search-item>ul').prepend(_tpl);
							window[data.pkey] = $('#searchpanel .right-content>div>.search-item li.'+data.pkey+'>select').chosen({search_contains:true});
							$('#searchpanel .right-content>div>.search-item li.'+data.pkey+'>div').css('width',140);
							break;
						case 'input':
							_tpl =_template_slider
									.replace('@key@',data.pkey+' '+json[data.pkey].class)
									.replace('@name@',json[data.pkey].name);
							$('#searchpanel .right-content>div>.search-item>ul').prepend(_tpl);
							window[data.pkey] = $('#searchpanel .right-content>div>.search-item li.'+data.pkey+'>div').slider({
								range: true,
								min: json[data.pkey].min,
								max: json[data.pkey].max,
								values: [ json[data.pkey].min, json[data.pkey].min ],
								slide: function( event, ui ) {
									_result = _setSliderText(data.pkey,ui.values[0],ui.values[1]);
								  $('#searchpanel .right-content>div>.search-item li.'+data.pkey+'>input').val( _result.text );
								},
								change: function( event, ui ) {
									_result = _setSliderText(data.pkey,ui.values[0],ui.values[1]);
								  $('#searchpanel .right-content>div>.search-item li.'+data.pkey+'>input').val( _result.text );
								}
							});
							_result = _setSliderText(data.pkey,
								$('#searchpanel .right-content>div>.search-item li.'+data.pkey+'>div').slider( "values", 0 ),
								$('#searchpanel .right-content>div>.search-item li.'+data.pkey+'>div').slider( "values", 1 ));
							$('#searchpanel .right-content>div>.search-item li.'+data.pkey+'>input').val( _result.text );
							break;
						case 'input|input':
							_tpl =_template_py
									.replace('@key@',data.pkey+' '+json[data.pkey].class)
									.replace('@name@',json[data.pkey].name);
							$('#searchpanel .right-content>div>.search-item>ul').append(_tpl);
							break;
					}

					if(Object.keys(json).length-_searchsetting.length==$('#searchpanel .right-content>div>.search-item>ul>li').length){
						$('#searchpanel .right-content>div>.search-item>ul').append(_template_search_buttons);
						$('#searchpanel .right-content>div>.search-item .search').click(function(){
							Search();
							$('#searchpanel .right-content>div>.search-item .save').show();
						});
						$('#searchpanel .right-content>div>.search-item .save').click(function(){
							ShowPanel('#quicksearch');
							_condition = getquerystring();
							SetQuickSearch(_condition);
						});
						if(searchsettings.quicksearch){
							_id = searchsettings.quicksearch.id;
							_name = searchsettings.quicksearch.name;
							_condition = getquerystring();
							SetQuickSearch(_condition,_id,_name);
						}
						$('#searchpanel .application-panel-header .loading').removeClass('active');
						$('#searchpanel .right-content>div>.search-item').css('visibility','visible');
						_setQueryValue(searchsettings.queryitemdata);
						searchsettings.loading=false;
						searchsettings.queryitemdata = null;
					}
				});
			}
		}
	}
	function _setSliderText(k,_min,_max){
console.log('_setSliderText');
console.log(_max);
console.log(_min);
		if(_min==json[k].min){
			switch(json[k].type){
				case 'year':
					switch(_max){
						case json[k].max:
							//this year
							_val = {text:' 今年',val:'thisyear'};
							break;
						case json[k].max-1:
							//last year
							_val = {text:' 去年',val:'lastyear'};
							break;
						default:
							//not set
							_val = {text:' 不設定'};
							break;
					}
					break;
				default:
					_val = {text:' 不設定'};
					break;
			}
		}else{
			_val = {text:json[k].prefix + _min + json[k].midstr + json[k].prefix + _max, val:[_min,_max]};
		}
		return _val;
	}
	function _setQueryValue(_condition){
		//has condition, set default value
		if(_condition){
			_searchsettingstr = StorageHandler.getSearchItemSettingStr(1);
			_searchsetting = {};
			if(typeof _searchsettingstr!='undefined'){
				_searchsetting = $.parseJSON(_searchsettingstr);
			}
console.log(_condition);
			for(var k in _condition){
				if(_searchsetting.hasOwnProperty(k)){
					//has condition, set default value
					_tpl = _template_li
								.replace('@key@',k+' '+json[k].class)
								.replace('@name@',json[k].name)
								.replace('@val@')
								.replace('@text@');
					$('#searchpanel .right-content>div>.search-item>ul').prepend(_tpl);
				}else{
					//set value
console.log(json[k].controller);
					switch(json[k].controller){
						case 'select':
console.log(_condition[k].v);
							$(window[k].selector).val(_condition[k].v).trigger("chosen:updated");
							break;
						case 'input':
							switch(json[k].type){
								case 'year':
									_index = -2;
									if(!Array.isArray(_condition[k])){
										_index = _time_settings.indexOf(_condition[k]);
									}
console.log('_setQueryValue');
console.log(_index);
									switch(_index){
										case -2:
											$(window[k]).slider('values',0,json[k].min);
											$(window[k]).slider('values',1,json[k].min);
											break;
										case -1:
											$(window[k]).slider('values',0,_condition[k][0]);
											$(window[k]).slider('values',1,_condition[k][1]);
											break;
										default:
											$(window[k]).slider('values',0,json[k].min);
											$(window[k]).slider('values',1,json[k].max-_index);
											break;
									}
									break;
								default:
									$(window[k]).slider('values',0,_condition[k][0]);
									$(window[k]).slider('values',1,_condition[k][1]);
									break;
							}
							break;
						case 'input|input':
							break;
					}
				}
			}
		}

	}
	function unsetQueryItem(){
		_searchsettingstr = StorageHandler.getSearchItemSettingStr(1);
		_searchsetting = {};
		if(typeof _searchsettingstr!='undefined'){
			_searchsetting = $.parseJSON(_searchsettingstr);
		}
console.log('unsetQueryItem');
		for(var k in json){
			if(!_searchsetting.hasOwnProperty(k) && window[k]){
				switch(json[k].controller){
					case 'select':
						$(window[k].selector).val('').trigger("chosen:updated");
						break;
					case 'input':
						$(window[k]).slider('values',0,0);
						$(window[k]).slider('values',1,0);
						break;
					case 'input|input':
						break;
				}
			}
		}
	}
	function getquerystring(){
		_r = Object();
		$('#searchpanel .right-content>div>.search-item li').each(function(){
			k=$(this).attr('class');
			k=k.split(' ')[0];
			if(typeof json[k]!='undefined'){
				if(typeof json[k].class!='undefined'){
					switch(json[k].class){
						case 'chosen':
							if(window[k].val()!=''){
								_r[k] = {v:window[k].val(),t:$(window[k].selector).find('option:selected').text()};
							}
							break;
						case 'slider':
							_values = window[k].slider('values');
							_result = _setSliderText(k,_values[0],_values[1]);
							if(_result['val']) _r[k] = _result.val;
							break;
					}
				}
			}
		});
		console.log(JSON.stringify(_r));
		return _r;
	}
	function Search(){
		getquerystring();
	}
	function ShowPanel(panelid){
		HidePanel();
		$('#dialogue_bg').addClass('bg_show');
		$(panelid).addClass('enable');
	}
	function HidePanel(){
		$.each(arrPanel,function(i,v){
			$(v).removeClass('enable');
		});
		$('#dialogue_bg').removeClass('bg_show');
	}
	function SetQuickSearch(_condition,_id,_name){
		//set program data
		if(_id && _name){
			searchsettings.quicksearch = {id:_id, name:_name};
			//set header text
			$('#searchpanel .application-panel-header h1').text(_str_header + '-' + searchsettings.quicksearch.name);
			//set save button
			$('#searchpanel .right-content>div>.search-item .save').show();
			//set dialog
			$('#quicksearch .container input').val(searchsettings.quicksearch.name);
		}
		//show condition
		_li_default = '<li class="@key@">@name@: @text@</li>';
		_li_slider_year = '<li class="@key@">@name@: <select><option value="thisyear">今年</option><option value="lastyear">去年</option></select></li>';
		_li_option = '<option value="@val@">@text@</option>';
		$('#quicksearch .container ul').empty();
		console.log(_condition);
		for(var k in _condition){
			if(typeof json[k]!='undefined'){
				if(typeof json[k].class!='undefined'){
					switch(json[k].class){
						case 'chosen':
							_li = _li_default.replace('@key',k)
											.replace('@name@',json[k].name)
											.replace('@text@',_condition[k].t);
							break;
						case 'slider':
							switch(json[k].type){
								case 'year':
									_li = _li_slider_year.replace('@key@',k)
												.replace('@name@',json[k].name);
									if(typeof _condition[k]=='string'){
										_li = $(_li).val(_condition[k]);
									}else{
										_val = _condition[k][0]+','+_condition[k][1];
										_text = json[k].prefix+_condition[k][0]+json[k].midstr+json[k].prefix+_condition[k][1];
										_option = _li_option.replace('@val@',_val)
													.replace('@text@',_text);
										$(_li).append(_option);
									}
									break;
								default:
									_text = json[k].prefix+_condition[k][0]+json[k].midstr+json[k].prefix+_condition[k][1];
									_li = _li_default.replace('@key@',k)
													.replace('@name@',json[k].name)
													.replace('@text@',_text);
									break;
							}
							break;
					}
				}
			}
			$('#quicksearch .container ul').append(_li);
		}
	}
	function UnsetQuickSearch(){
		searchsettings.quicksearch = false;
		$('#searchpanel .application-panel-header h1').text(_str_header);
		$('#searchpanel .right-content .quick-search ul li').removeClass('selected');
		$('#quicksearch .container input').val('');
		$('#quicksearch .container ul').empty();
	}
  /*
  $('.list table').anythingZoomer({
		clone: true
	});*/
	
});