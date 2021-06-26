(function ($) {
$.fn.lnetsearch = function(options){
	//py:{name:'計畫年數',controller:'input',class:'slider',prefix:'',midstr:'/',min:0,max:6,value:[1,4]},
	json = {ty:{name:'年度',controller:'input',type:'year',class:'slider',prefix:'ROC',midstr:' - ',min:94,max:106,value:[105,106]},
					pn:{name:'計畫名稱',controller:'select',class:'chosen'},
					pi:{name:'計畫編碼',controller:'select',class:'chosen'},
					py_pty:{name:'計畫年數',controller:'select|select',class:'chosen',py:{name:'計畫年數',value:[1,2,3,4,5,6,7,8,9,10,11,12,13]},pty:{name:'計畫總年數',value:[1,2,3,4,5,6,7,8,9,10,11,12,13]}},
					pcof:{name:'經費類別',controller:'select',class:'chosen'},
					pc:{name:'承辦人',controller:'select',class:'chosen'},
					pcu:{name:'承辦科別',controller:'select',class:'chosen'},
					pi:{name:'執行單位',controller:'select',class:'chosen'},
					prt:{name:'報告類型',controller:'select',class:'chosen'},
					pwrf:{name:'領域',controller:'select',class:'chosen'}};
/*
	json = {ty:{name:'年度',controller:'input',type:'year',class:'slider',prefix:'ROC',midstr:' - ',min:94,max:106,value:[105,106]},
					pn:{name:'IP廠商名稱',controller:'select',class:'chosen'},
					py:{name:'設計屬性',controller:'select',class:'chosen'},
					pcof:{name:'設計手法',controller:'select',class:'chosen'},
					pc:{name:'粉絲年齡層',controller:'select',class:'chosen'},
					pcu:{name:'設計師',controller:'select',class:'chosen'},
					pi:{name:'歷年參展經歷',controller:'select',class:'chosen'},
					prt:{name:'機關補助',controller:'select',class:'chosen'},
					pwrf:{name:'其他活動參與',controller:'select',class:'chosen'}};
	json = {ty:{name:'年齡課程',controller:'input',type:'year',class:'slider',prefix:'ROC',midstr:' - ',min:94,max:106,value:[105,106]},
					py:{name:'年度主課程',controller:'select',class:'chosen'},
					pcof:{name:'數理邏輯課程',controller:'select',class:'chosen'},
					pc:{name:'藝術操作課程',controller:'select',class:'chosen'},
					pcu:{name:'語言課程',controller:'select',class:'chosen'},
					pi:{name:'工具教材課程',controller:'select',class:'chosen'},
					prt:{name:'輔助教材課程',controller:'select',class:'chosen'},
					pwrf:{name:'其他課程',controller:'select',class:'chosen'}};
*/
	json_quicksearch={
		1:{name:'月初例行',data:{}},
		2:{name:'期中審查報告',data:{}},
		3:{name:'今年期末審查報告',data:{}}
	};
/*
	json_quicksearch={
		1:{name:'可愛插畫',data:{}},
		2:{name:'年輕女性公仔',data:{}}
	};
	json_quicksearch={
		1:{name:'二允兄弟活動',data:{}},
		2:{name:'二允兄弟今年度課程',data:{}}
	};
*/
	var _template_quicksearch = '<li id="@id@"><a>x</a><div class="@size@" data-id="@id@">@name@</div></li>';
	var _template_search_buttons = '<li class="button"><button class="search"></button><button class="save"></button></li>';
	var _template_checkbox = '<li class="@key@"><input type="checkbox" @checked@ /><span>@name@</span></li>';
	var _template_li = '<li class="@key@"><span>@name@</span><input type="hidden" value="@val@" /><div>@text@</div></li>';
	var _template_slider = '<li class="@key@"><span>@name@</span><input readonly /><div></div></li>';
	var _template_chosen = '<li class="@key@"><span>@name@</span><select>@option@</select></li>';
	var _template_py = '<li class="@key@"><span>@name@</span><div><select class="@key1@">@option1@</select>/<select class="@key2@">@option2@</select></div></li>';
	var _template_default_option = '<option>-</option>';
	var _template_option = '<option value="@key@">@val@</option>';

	var _str_header = 'Search ebook';
	var _quicksearch_iconsize = ['small','mid','large'];
	var _time_settings = ['thisyear','lastyear'];
	var arrClass = ['minimize','maximize','full-screen'];
	var arrPanel = ['#savesearchitem','#quicksearch'];

	var $this = this;
	var $quicksearch = $this.find('#quicksearch');
	var $savesearchitem = $this.find('#savesearchitem');
	var $dialogue_bg = $this.find('#dialogue_bg');
	var $searchpanel = $this.find('#searchpanel');

	var settings = {
		_table:null,
		_quicksearch:{},
		_searchsetting:{},
		loading:false,
		queryitemdata:null,
		quicksearch:false,
		defaultScreenSize:'minimize',
		mode:'normal'
	};

  if (options) {
      $.extend(settings, options);
  }

	_init();
	function _init(){
		APIHandler.buinfo(function(data){
			if(data.type=='u'){
				_buid=data.id;
				setQuickSearchList(_buid);
				_str = '';
				for(var i in data.groups){
					_str += ','+data.groups[i].g_name;
				}
				if(_str.length>0){
					_str = data.name + '(' +_str.substring(1)+ ')';
				}else{
					_str = data.name;
				}
				$this.find('.left-content>div>div').text('已登入: '+_str);

				$searchpanel.find('.left-content .qt2').click(function(){
					$searchpanel.find('.left-content li').removeClass('selected');
					$(this).addClass('selected');
					$searchpanel.find('.right-content>div:eq(0)').removeClass('quick-search').addClass('search-item');
					UnsetQuickSearchPanel();
					unsetQueryItem(data.id);
					$searchpanel.find('.right-content>div>.search-item .save').hide();
					settings._table.clear().draw();
				});
				$quicksearch.find('.button .Save').click(function(){
					_name = $quicksearch.find('.container input').val();
					if(_name==''){
						alert('Quick search name is blank!');return;
					}
					_r = getquerystring();
					$quicksearch.find('.container select').each(function(){
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
					if($quicksearch.find('.key').val()==''){
						_key = Date.now();
					}else{
						_key = $quicksearch.find('.key').val();
					}
					settings._quicksearch[_key] = {name:_name, data:_r};
					_json = JSON.stringify(settings._quicksearch);
console.log(settings._quicksearch);
console.log(_json);
					SearchAPIHandler.setQuickSearch(_buid,_json,function(data){
						//if modify quicksearch name
						if($quicksearch.find('.key').val()==''){
							setQuickSearchItem(_buid,_key,_name,_json);
						}else{
							//update name
							$searchpanel.find('#'+_key+' div').text(_name);
						}
					});
					HidePanel();
				});
				$savesearchitem.find('.container .content .Save').click(function(){
					_arr=[];
					$savesearchitem.find('.container .content li').each(function(){
						if(!$(this).find('input').prop('checked')){
							_arr.push($(this).attr('class'));
						}
					});
					//StorageHandler.setSearchItemSettingStr(1,JSON.stringify(_arr));
					_buid = settings._buid;
					_str = JSON.stringify(_arr);
					SearchAPIHandler.setSearchItemSetting(_buid,_str,function(data){
						$savesearchitem.find('.button .Cancel').click();
						setQueryItem(_buid);
						HidePanel();				
					});
				});
				setQueryItem(_buid);
			}
		});
		chkSingleLogin();
		setInterval(chkSingleLogin, 120000);

		$this.addClass(settings.mode);
		$searchpanel.removeClass().addClass(settings.defaultScreenSize);
		$this.find('.circle_button .red').click(function(){
			HidePanel();
		});
		$searchpanel.find('.circle_button .green').click(function(){
			classes = $searchpanel.attr('class').split(' ');
			_index = arrClass.indexOf(classes[0]);
			_index = (++_index>2)?2:_index;
			$searchpanel.removeClass(classes[0]).addClass(arrClass[_index]);
		});
		$searchpanel.find('.circle_button .yellow').click(function(){
			classes = $searchpanel.attr('class').split(' ');
			_index = arrClass.indexOf(classes[0]);
			_index = (--_index<0)?0:_index;
			$searchpanel.removeClass(classes[0]).addClass(arrClass[_index]);
		});
	
		$searchpanel.find('.left-content .qt1').click(function(){
			$searchpanel.find('.left-content li').removeClass('selected');
			$(this).addClass('selected');
			$searchpanel.find('.right-content>div:eq(0)').removeClass('search-item').addClass('quick-search');
		});
	  $searchpanel.find('.right-content .list>.tags>.open').click(function(){
	  	$tagpanel = $searchpanel.find('.right-content .list>.tags>div');
	  	if($tagpanel.is(':visible')){
	  		$tagpanel.hide();
	  	}else{
	  		$tagpanel.show();
	  	}
	  });
	  $searchpanel.find('.right-content>div>.list').scroll(function(){
	  	if($(this).scrollTop()!=0) $searchpanel.find('.right-content .list>.tags>div').hide();
	  	if($(this).scrollLeft()!=0) $searchpanel.find('.right-content .list>.tags>div').hide();
	  });
		$quicksearch.find('.button .Cancel').click(function(){
			HidePanel();
		});
		$quicksearch.find('.container>input').keyup(function(e){
			_str = $(this).val();
			str=_str.replace(/[^\x00-\xff]/g, "**");
			if(str.length<=8){
				$quicksearch.find('.img').removeClass().addClass('img small');
			}else if(str.length<=12){
				$quicksearch.find('.img').removeClass().addClass('img mid');
			}else if(str.length<=16){
				$quicksearch.find('.img').removeClass().addClass('img large');
			}else{
				$this.find(this).val(_str.substring(0,_str.length-1));
			}
		});
		$savesearchitem.find('.button .Cancel').click(function(){
			HidePanel();
		});
		settings._table = $searchpanel.find('.list #example').DataTable({
	      "dom": '<"top"if>rt<"bottom"p><"clear">',
	      columns:[
	      	{},
	      	{},
					{
						"render": function(data, type, row, meta){
console.log('render');
console.log(data);
console.log(type);
console.log(row);
console.log(meta);

					  	if(type === 'display'){
					      data = '<a href="' + row[10] + '">' + data + '</a>';
					  	}
					  	return data;
						}
					},
					{},
					{},
					{},
					{},
					{},
					{},
					{}
	      ]
	  });
	  settings._table.clear().draw();
	  $this.find('.list table tbody tr').mouseenter(function(event){
			$(this).css('top',event.pageY);
	  });
		$searchpanel.find('.application-panel-header > .function > div > .SearchSetting').click(function(){
			if(settings.loading){
				alert('loading');return;
			}
			ShowPanel('#savesearchitem');
		});
		$searchpanel.find('.application-panel-header > .function > div > .ShrunkFontLevel').click(function(){
			if(settings.loading){
				alert('loading');return;
			}
			_size = parseInt($this.css('font-size'));
			if(_size){
				_size -= 1;
				if(_size<14) _size=14;
				$this.css('font-size',_size+'px');
			}else{
				$this.css('font-size','15px');
			}
		});
		$searchpanel.find('.application-panel-header > .function > div > .IncreaseFontLevel').click(function(){
			if(settings.loading){
				alert('loading');return;
			}
			_size = parseInt($this.css('font-size'));
			_maxsize=19;
			_l = $searchpanel.find('.right-content>div>.search-item>ul>li').length;
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
				$this.css('font-size',_size+'px');
			}else{
				$this.css('font-size',_maxsize+'px');
			}
		});
	}
	function setQuickSearchItem(_buid,_key,_name,_condition){
		l=_name.replace(/[^\x00-\xff]/g, "**").length;
		_s = 0;
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
					.replace(/@id@/g,_key)
					.replace('@name@',_name);
		$obj = $(_str);
		$obj.find('a').click(function(e){
			e.preventDefault();
			if(confirm('您確定要刪除嗎?')){
				_key = $(this).parent().find('div').data('id');
				delete settings._quicksearch[_key];
				_json = JSON.stringify(settings._quicksearch);
				SearchAPIHandler.setQuickSearch(_buid,_json,function(data){
					$obj.empty();
				});
			}
			return false;
		});
		$obj.click(function(){
			settings._table.clear().draw();
			$(this).parent().find('li').removeClass('selected');
			$searchpanel.find('.left-content .qt2').click();
			$(this).addClass('selected');
			_key = $(this).find('div').data('id');
console.log(settings);
console.log(_key);
			_quicksearchobj = settings._quicksearch[_key];
			_name = _quicksearchobj.name;
			_condition = _quicksearchobj.data;
			SetQuickSearchPanel(_condition,_key,_name);
console.log('quick search click');
			setQueryItem(_buid,_condition);
			Search(_condition);
		});
		$searchpanel.find('.right-content >div> .quick-search ul').append($obj);
		settings._quicksearch[_key] = {name:_name,data:_condition};
	}
  function setQuickSearchList(buid){
		SearchAPIHandler.getQuickSearch(buid,function(data){
			$.each(data,function(i,v){
				setQuickSearchItem(buid,i,v.name,v.data);
			});
		});
  }
  function setQueryItem(_buid,_condition){
  	settings.queryitemdata = _condition;
		if(settings.loading){
console.log('return');
			return;
		}
		//_searchsettingstr = StorageHandler.getSearchItemSettingStr(1);
		SearchAPIHandler.getSearchItemSetting(_buid,function(data){
			settings._searchsetting = data;
console.log(settings);
	  	if(Object.keys(json).length-settings._searchsetting.length+1==$searchpanel.find('.right-content>div>.search-item>ul>li').length){
console.log('call _setQueryValue');
	  		if(settings.queryitemdata) _setQueryValue(_buid,settings.queryitemdata);
	  		return;
	  	}
	  	settings.loading=true;
			$searchpanel.find('.right-content>div>.search-item').css('visibility','hidden');
			$searchpanel.find('.application-panel-header .loading').addClass('active');
			$savesearchitem.find('.container .content ul').empty();
			$searchpanel.find('.right-content>div>.search-item>ul').empty();
			for(var k in json){
				_checked = (settings._searchsetting.indexOf(k)==-1)?'checked':'';
				_chktpl = _template_checkbox
								.replace('@key@',k)
								.replace('@name@',json[k].name)
								.replace('@checked@',_checked);
				$savesearchitem.find('.container .content ul').append(_chktpl);
	
				//init query item
				if(settings._searchsetting.indexOf(k)==-1){
					//get query options
				
					_tpl='';
					_options=_template_option
									.replace('@key@','')
									.replace('@val@','');
					switch(json[k].controller){
						case 'select':
							TagAPIHandler.getTagByPKey(k,function(data){
								_options=_template_default_option;
								for(var j in data.data){
									_options+=_template_option.replace('@key@',data.data[j].t_id).replace('@val@',data.data[j].key+':'+data.data[j].val);
								}
								_tpl =_template_chosen
										.replace('@key@',data.pkey+' '+json[data.pkey].class)
										.replace('@name@',json[data.pkey].name)
										.replace('@option@',_options);
								$searchpanel.find('.right-content>div>.search-item>ul').prepend(_tpl);
								window[data.pkey] = $searchpanel.find('.right-content>div>.search-item li.'+data.pkey+'>select').chosen({search_contains:true,width:'140px'});
								_setQueryItemComplete(_buid);
							});
							break;
						case 'input':
							_tpl =_template_slider
									.replace('@key@',k+' '+json[k].class)
									.replace('@name@',json[k].name);
							$searchpanel.find('.right-content>div>.search-item>ul').prepend(_tpl);
							TagAPIHandler.getTagByPKey(k,function(data){
								window[data.pkey] = $searchpanel.find('.right-content>div>.search-item li.'+data.pkey+'>div').slider({
									range: true,
									min: json[data.pkey].min,
									max: json[data.pkey].max,
									values: [ json[data.pkey].min, json[data.pkey].min ],
									slide: function( event, ui ) {
										_result = _setSliderText(data.pkey,ui.values[0],ui.values[1]);
									  $searchpanel.find('.right-content>div>.search-item li.'+data.pkey+'>input').val( _result.text );
									},
									change: function( event, ui ) {
										_result = _setSliderText(data.pkey,ui.values[0],ui.values[1]);
									  $searchpanel.find('.right-content>div>.search-item li.'+data.pkey+'>input').val( _result.text );
									}
								});
								_result = _setSliderText(data.pkey,
									$searchpanel.find('.right-content>div>.search-item li.'+data.pkey+'>div').slider( "values", 0 ),
									$searchpanel.find('.right-content>div>.search-item li.'+data.pkey+'>div').slider( "values", 1 ));
									$searchpanel.find('.right-content>div>.search-item li.'+data.pkey+'>input').val( _result.text );
								_setQueryItemComplete(_buid);
							});
							break;
						case 'select|select':
							pk = k.split('_');
							_options1=_template_default_option;
							for(var j in json[k][pk[0]].value){
								_options1+=_template_option.replace('@key@',j).replace('@val@',j);
							}
							_options2=_template_default_option;
							for(var j in json[k][pk[1]].value){
								_options2+=_template_option.replace('@key@',j).replace('@val@',j);
							}
	
							_tpl =_template_py
									.replace('@key@',k+' '+json[k].class)
									.replace('@key1@',pk[0])
									.replace('@key2@',pk[1])
									.replace('@name@',json[k].name)
									.replace('@option1@',_options1)
									.replace('@option2@',_options2);
							$searchpanel.find('.right-content>div>.search-item>ul').append(_tpl);
							window[pk[0]] = $searchpanel.find('.right-content>div>.search-item li select.'+pk[0]).chosen({search_contains:true,width:'70px'});
							window[pk[1]] = $searchpanel.find('.right-content>div>.search-item li select.'+pk[1]).chosen({search_contains:true,width:'70px'});
							_setQueryItemComplete(_buid);
							break;
					}
				}
			}
		});
	}
	function _setQueryItemComplete(_buid){
		if(Object.keys(json).length-Object.keys(settings._searchsetting).length==$searchpanel.find('.right-content>div>.search-item>ul>li').length){
			$searchpanel.find('.right-content>div>.search-item>ul').append(_template_search_buttons);
			$searchpanel.find('.right-content>div>.search-item .search').click(function(){
				settings._table.clear().draw();
				_qs = getquerystring();
				Search(_qs);
				$searchpanel.find('.right-content>div>.search-item .save').show();
			});
			$searchpanel.find('.right-content>div>.search-item .save').click(function(){
				ShowPanel('#quicksearch');
				_condition = getquerystring();
				SetQuickSearchPanel(_condition);
			});
			if(settings.quicksearch){
				_key = settings.quicksearch.key;
				_name = settings.quicksearch.name;
				_condition = getquerystring();
				SetQuickSearchPanel(_condition,_key,_name);
			}
			$searchpanel.find('.application-panel-header .loading').removeClass('active');
			$searchpanel.find('.right-content>div>.search-item').css('visibility','visible');
			_setQueryValue(_buid,settings.queryitemdata);
			settings.loading=false;
			settings.queryitemdata = null;
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
	/***********************************
	enable		=>	show option, setting panel check
	disable		=>	hide option, setting panel uncheck
	set val(enable)			=>	show option, set value
	set val -> disable	=>	hide option
	***********************************/
	function _setQueryValue(_buid,_condition){
		//has condition, set default value
		if(_condition){
			//_searchsettingstr = StorageHandler.getSearchItemSettingStr(1);
			SearchAPIHandler.getSearchItemSetting(_buid,function(data){
				settings._searchsetting = data;
console.log(_condition);
				for(var k in _condition){
console.log(k);
					if(settings._searchsetting.indexOf(k)>=0){
/*
						//has condition, set default value
						_tpl = _template_li
									.replace('@key@',k+' '+json[k].class)
									.replace('@name@',json[k].name)
									.replace('@val@',_condition[k].v)
									.replace('@text@',_condition[k].t);
						$searchpanel.find('.right-content>div>.search-item>ul').prepend(_tpl);
*/
					}else{
						//set value

						switch(json[k].controller){
							case 'select':
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
							case 'select|select':
								break;
						}
					}
				}
			});
		}

	}
	function unsetQueryItem(_buid){
		//_searchsettingstr = StorageHandler.getSearchItemSettingStr(1);
		SearchAPIHandler.getSearchItemSetting(_buid,function(data){
			settings._searchsetting = data;
console.log('unsetQueryItem');
			for(var k in json){
				if(settings._searchsetting.indexOf(k)==-1 && window[k]){
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
		});
	}
	function getquerystring(){
		_r = Object();
		$searchpanel.find('.right-content>div>.search-item li').each(function(){
			k=$(this).attr('class');
			k=k.split(' ')[0];
			if(typeof json[k]!='undefined'){
				if(typeof json[k].class!='undefined'){
					switch(json[k].class){
						case 'chosen':
							switch(json[k].controller){
								case 'select':
									if(window[k].val() && window[k].val()!='-'){
										_r[k] = {n:json[k].name,class:json[k].class,type:json[k].type,v:window[k].val(),t:$(window[k].selector).find('option:selected').text()};
									}
									break;
								case 'select|select':
									pk = k.split('_');
									if(window[pk[0]].val() && window[pk[0]].val()!='-'){
										_r[pk[0]] = {n:json[k][pk[0]].name,class:json[k].class,type:json[k].type,v:window[pk[0]].val(),t:$(window[pk[0]].selector).find('option:selected').text()};
									}
									if(window[pk[1]].val() && window[pk[1]].val()!='-'){
										_r[pk[1]] = {n:json[k][pk[1]].name,class:json[k].class,type:json[k].type,v:window[pk[1]].val(),t:$(window[pk[1]].selector).find('option:selected').text()};
									}
									break;
							}
							break;
						case 'slider':
							_values = window[k].slider('values');
							_result = _setSliderText(k,_values[0],_values[1]);
							if(_result['val']) _r[k] = {n:json[k].name,class:json[k].class,type:json[k].type,v:_result.val};
							break;
					}
				}
			}
		});
		console.log(JSON.stringify(_r));
		return _r;
	}
	function Search(_qs){
		_pcu=null;
		_pi=null;
		_pn=null;
		_ty_from=null;
		_ty_to=null;
		_py=null;
		_pty=null;
		_pcof=null;
		_pc=null;
		_prt=null;
		_pwrt=null;
		if(_qs['pcu']) _pcu = _qs['pcu'].v;
		if(_qs['pi']) _pi = _qs['pi'].v;
		if(_qs['pn']) _pn = _qs['pn'].v;
		if(_qs['ty']){
			var d = new Date();
			var n = d.getFullYear();
			var roc = n-1911;
			switch(_qs['ty'].v){
				case 'thisyear':
					_ty_from=roc;
					_ty_to=roc;
					break;
				case 'lastyear':
					_ty_from=roc-1;					
					_ty_to=roc-1;
					break;
				default:
					_ty_from = _qs['ty'].v[0];
					_ty_to = _qs['ty'].v[1];
					break;
			}
		}
		if(_qs['py']) _py = _qs['py'].v;
		if(_qs['pty']) _pty = _qs['pty'].v;
		if(_qs['pcof']) _pcof = _qs['pcof'].v;
		if(_qs['pc']) _pc = _qs['pc'].v;
		if(_qs['prt']) _prt = _qs['prt'].v;
		if(_qs['pwrt']) _pwrt = _qs['pwrt'].v;
		SearchAPIHandler.search(_pcu,_pi,_pn,_ty_from,_ty_to,_py,_pty,_pcof,_pc,_prt,_pwrt,function(data){
			_str = '';
			$.each(Object.keys(data),function(k,v){
console.log(data);
console.log(k);
console.log(data[v]);
				settings._table.row.add([
					data[v].b_key,
					data[v].year,
					data[v].b_name,
					data[v].py+'/'+data[v].pty,
					data[v].pcof,
					data[v].pcu,
					data[v].pi,
					data[v].pn,
					data[v].prt,
					data[v].pwrf,
					data[v].webbook_link
				]).draw( true );
			});
/*
			for(var k in Object.keys(data)){
				_str +=_template_tablecols.replace('@pnkey@',data[k])
																	.replace('@pn@',data[k])
																	.replace('@pcu@',data[k])
																	.replace('@pi@',data[k])
																	.replace('@p@',data[k])
																	.replace('@pnkey@',data[k])
																	.replace('@pnkey@',data[k])
																	.replace('@pnkey@',data[k])
																	.replace('@pnkey@',data[k]);
			}*/
			/*
			$searchpanel.find('.list #example').DataTable({
		      "dom": '<"top"if>rt<"bottom"p><"clear">'
		  });*/
		});
	}
	function ShowPanel(panelid){
		HidePanel();
		$dialogue_bg.addClass('bg_show');
		$(panelid).addClass('enable');
	}
	function HidePanel(){
		$.each(arrPanel,function(i,v){
			$(v).removeClass('enable');
		});
		$dialogue_bg.removeClass('bg_show');
	}
	function SetQuickSearchPanel(_condition,_id,_name){
		//set program data
		if(_id && _name){
			settings.quicksearch = {id:_id, name:_name};
			//set header text
			$searchpanel.find('.application-panel-header h1').text(_str_header + '-' + settings.quicksearch.name);
			//set save button
			$searchpanel.find('.right-content>div>.search-item .save').show();
			//set dialog
			$quicksearch.find('.container input').val(settings.quicksearch.name);
			$quicksearch.find('.key').val(settings.quicksearch.id);
		}
		//show condition
		_li_default = '<li class="@key@">@name@: @text@</li>';
		_li_slider_year = '<li class="@key@">@name@: <select><option value="thisyear">今年</option><option value="lastyear">去年</option></select></li>';
		_li_option = '<option value="@val@">@text@</option>';
		$quicksearch.find('.container ul').empty();
		console.log(_condition);
		for(var k in _condition){
			switch(_condition[k].class){
				case 'slider':
					switch(_condition[k].type){
						case 'year':
							_li = _li_slider_year.replace('@key@',k)
										.replace('@name@',_condition[k].n);
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
											.replace('@name@',_condition[k].n)
											.replace('@text@',_text);
							break;
					}
					break;
				case 'chosen':
				default:
					_li = _li_default.replace('@key@',k)
									.replace('@name@',_condition[k].n)
									.replace('@text@',_condition[k].t);
					break;
			}
			$quicksearch.find('.container ul').append(_li);
		}
	}
	function UnsetQuickSearchPanel(){
		settings.quicksearch = false;
		$searchpanel.find('.application-panel-header h1').text(_str_header);
		$searchpanel.find('.right-content .quick-search ul li').removeClass('selected');
		$quicksearch.find('.container input').val('');
		$quicksearch.find('.container ul').empty();
	}
  function chkSingleLogin(){
  	APIHandler.loginCheck(function(data){
  		(new MsgAction('this')).Log('chkSingleLogin');
  		switch(data.type){
  			case 'a':
  				break;
  			case 'u':
  				if(data.id>0){
  					settings._buid = data.id;
		    		APIHandler.chkSingleLogin(function(valid){
	  	  			if(valid){
	  	  			}else{
	  	  				alert(systemEnv.Language.login_in_different_place);
	  	  				if(ToolsHandler.isMobile()){
	  	  					document.location.href='/logout/';
	  	  				}else{
	  	  					LoginHandler.logout();
	  	  					logout_callbacks.fire();
	  	  					document.location.href='/signout/';
	  	  				}
	  	  			}
	    			});
  				}
  				break;
  			case '-':
 					LoginHandler.logout();
 					document.location.href='/signout/';
  				break;
  		}
  	});
  }
};
})(jQuery);