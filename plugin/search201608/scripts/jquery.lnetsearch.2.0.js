(function ($) {
$.fn.lnetsearch = function(options){
	//py:{name:'計畫年數',controller:'input',class:'slider',prefix:'',midstr:'/',min:0,max:6,value:[1,4]},
	json = {year_year:{name:'年度',controller:'select|select',class:'chosen',type:'peroid',year:{name:'年度',server:true,value:{thisyear:'今年',lastyear:'去年'}}},
					pn:{name:'計畫名稱',controller:'input',class:'input'},
					py_pty:{name:'計畫年數',controller:'select|select',class:'chosen',py:{name:'計畫年數',server:true},pty:{name:'計畫總年數',server:true}},
					pcof:{name:'經費類別',controller:'select',class:'chosen'},
					pc:{name:'承辦人',controller:'select',class:'chosen'},
					pcu:{name:'承辦科別',controller:'select',class:'multipleSelect'},
					pi:{name:'執行單位',controller:'select',class:'multipleSelect'},
					prt:{name:'報告類型',controller:'select',class:'multipleSelect'},
					pwrf:{name:'領域',controller:'select',class:'multipleSelect'}};
	_hash = {
		year:'year_year',
		pn:'pn',
		pi:'pi',
		py:'py_pty',
		pty:'py_pty',
		pcof:'pcof',
		pc:'pc',
		pcu:'pcu',
		prt:'prt',
		pwrf:'pwrf'};
	json_quicksearch={
		1:{name:'月初例行',data:{}},
		2:{name:'期中審查報告',data:{}},
		3:{name:'今年期末審查報告',data:{}}
	};
	var _template_quicksearch = '<li id="@id@" title="@condition@"><a>x</a><div class="@size@" data-id="@id@">@name@</div></li>';
	var _template_search_buttons = '<li class="button"><button class="search" title="搜尋"></button><button class="save" title="儲存搜尋結果"></button></li>';
	var _template_checkbox = '<li class="@key@"><input type="checkbox" @checked@ /><span>@name@</span></li>';
	var _template_li = '<li class="@key@"><span>@name@</span><input type="hidden" value="@val@" /><div>@text@</div></li>';
	var _template_slider = '<li class="@key@"><span>@name@</span><input readonly /><div></div></li>';
	var _template_input = '<li class="@key@"><span>@name@</span><input value="@val@"></li>';
	var _template_chosen = '<li class="@key@"><span>@name@</span><select @multiple@>@option@</select></li>';
	var _template_select_select = '<li class="@key@"><span>@name@</span><div><select class="@key1@">@option1@</select>@sep@<select class="@key2@">@option2@</select></div></li>';
	var _template_default_option = '<option>-</option>';
	var _template_option = '<option value="@key@">@val@</option>';

	var _page_num = 200;
	var _str_header = '法人科專報告電子書';
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
		loaded:0,
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
		_loadOptions();
		APIHandler.buinfo(function(data){
			if(data.type=='u'){
				_buid=data.id;
				setQuickSearchList(_buid);
				_str = '';
				for(var i in data.groups){
					_str += ','+data.groups[i].g_name;
				}
				if(_str.length>0){
					_str = _str.substring(1);
				}
				$this.find('.left-content>div>div').attr('title',_str).text('已登入: '+data.uname);

				$searchpanel.find('.left-content .qt2').click(function(){
					$searchpanel.find('.right-content>div>.search-item>div').click();
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
					//set header text
					$searchpanel.find('.application-panel-header h1').text(_str_header + '-' + _name);

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
							setQuickSearchItem(_buid,_key,_name,_r);
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
			$searchpanel.find('.right-content>div>.search-item>div').click();
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
	  $searchpanel.find('.right-content>div>.search-item>div').click(function(){
	  	$searchpanel.find('.right-content>.search-item').removeClass('close');
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
        "language": {
			    "decimal":        "",
			    "emptyTable":     "沒有資料",
			    "info":           "顯示 _START_ 至 _END_ 筆 ,共 _TOTAL_ 筆",
			    "infoEmpty":      "顯示 0 至 0 筆,共 0 筆",
			    "infoFiltered":   "(在 _MAX_ 筆資料中搜尋)",
			    "infoPostFix":    "",
			    "thousands":      ",",
			    "lengthMenu":     "每頁顯示 _MENU_ 筆",
			    "loadingRecords": "仔入中...",
			    "processing":     "搜尋中...",
			    "search":         "搜尋:",
			    "zeroRecords":    "沒有符合的資料",
			    "paginate": {
			        "first":      "第一頁",
			        "last":       "最後一頁",
			        "next":       "下一頁",
			        "previous":   "上一頁"
			    },
			    "aria": {
			        "sortAscending":  ": 將欄位正排序",
			        "sortDescending": ": 將欄位反排序"
			    }
        },
	      "pageLength": 100,
	      "order": [[ 1, "desc" ]],
	      columns:[
	      	{
	      		orderable: false,
						"render": function(data, type, row, meta){
					  	if(type === 'display'){
					  		_link1 = row[8].replace('http://127.0.0.1','');
					  		_link2 = row[9].replace('http://127.0.0.1','');
					    	data = '<a href="javascript:ToolsHandler.MM_openBrWindow(\'' + _link1 + '\');">開啟</a> &nbsp; <a href="javascript:ToolsHandler.MM_openBrWindow(\'' + _link2 + '\');">下載</a>';
					  	}
					  	return data;
						}
	      	},
	      	{orderData: [1,0]},
					{
						"render": function(data, type, row, meta){
					  	if(type === 'display'){
					  		_data = data.split('_');
					  		_data.pop();
					  		data = _data.join('_');
					  	}
					  	return data;
						}
					},
					{orderData: [2,1]},
					{orderData: [3,1]},
					{orderData: [4,1]},
					{orderData: [5,1]},
					{orderData: [6,1]}
	      ]
	  });
	  settings._table.clear().draw();
	  settings._table.column(1).data().sort();
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
		$searchpanel.find('.function > .logout').click(function(){
			if(confirm('您確定要登出嗎?')){
				APIHandler.logout();
				document.location.href='/signout/';
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
		_str_condition='搜尋條件:';
		for(var _c in _condition){
			_text='';
			if($.isArray(_condition[_c].t)){
				for(var _t in _condition[_c].t){
					_text+=","+_condition[_c].t[_t];
				}
			}else{
				_text=','+_condition[_c].t;
			}
			if(_text) _text=_text.substring(1);
			_str_condition+="\n"+_condition[_c].n+":"+_text;
		}
		_str = _template_quicksearch
					.replace('@condition@',_str_condition)
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
  //check option data is renew in one day.
  function _validOptions(){
  	_k = 'lnetsearch_option_valid';
  	if(window.localStorage[_k]){
  		window.localStorage[_k]=new Date().getTime();
  		return false;
  	}else{
  		_n = new Date().getTime();
  		return ((_n - window.localStorage[_k])<86400000);
  	}
  }
  //load all options
  function _loadOptions(){
  	if(settings.loading) return false;
  	if(!_validOptions()){
  		_n=0;
	  	for(var k in json){
	  		settings.loading=true;
	  		pk=k.split('_');
	  		for(var l in pk){
	  			_n++;
		  		TagAPIHandler.getAllTagByPKey(pk[l],function(data){
		  			settings.loaded++;
		  			if(settings.loaded==_n){
		  				settings.loading=false;
		  				return true;
		  			}
		  		});
		  	}
	  	}
	  }
  }
  function setQueryItem(_buid,_condition){
  	settings.queryitemdata = _condition;
		if(settings.loading){
console.log('return');
			setTimeout(function(){
				setQueryItem();
			},2000);
			return;
		}
		unsetQueryItem(_buid);
		//_searchsettingstr = StorageHandler.getSearchItemSettingStr(1);
		SearchAPIHandler.getSearchItemSetting(_buid,function(data){
			settings._searchsetting = data;
console.log(settings);
	  	if(Object.keys(json).length-settings._searchsetting.length+1==$searchpanel.find('.right-content>div>.search-item>ul>li').length){
console.log('call _setQueryValue');
	  		if(settings.queryitemdata) _setQueryValue(_buid,settings.queryitemdata);
	  		return;
	  	}
	  	//settings.loading=true;
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
								data = JSON.parse(StorageHandler.getTagStr(k+'_all'));
							//TagAPIHandler.getTagByPKey(k,function(data){
								_options='';
								for(var j in data.data){
									_options+=_template_option.replace('@key@',data.data[j].t_id).replace('@val@',data.data[j].val);
								}
								switch(json[k].class){
									case 'chosen':
										_tpl =_template_chosen
												.replace('@key@',k+' '+json[k].class)
												.replace('@name@',json[k].name)
												.replace('@multiple@','')
												.replace('@option@',_template_default_option+_options);
										$searchpanel.find('.right-content>div>.search-item>ul').prepend(_tpl);
										window[k] = $searchpanel.find('.right-content>div>.search-item li.'+k+'>select').chosen({search_contains:true,width:'160px'});
										break;
									case 'multipleSelect':
										_tpl =_template_chosen
												.replace('@key@',k+' '+json[k].class)
												.replace('@name@',json[k].name)
												.replace('@multiple@','multiple')
												.replace('@option@',_options);
										$searchpanel.find('.right-content>div>.search-item>ul').prepend(_tpl);
										window[k] = $searchpanel.find('.right-content>div>.search-item li.'+k+'>select').multipleSelect({filter:true,height:'20px',width:'160px'});
										break;
								}
								_setQueryItemComplete(_buid);
							//});
							break;
						case 'input':
								data = JSON.parse(StorageHandler.getTagStr(k+'_all'));
							//TagAPIHandler.getTagByPKey(k,function(data){
								switch(json[k].class){
									case 'input':
										_tpl =_template_input
												.replace('@key@',k+' '+json[k].class)
												.replace('@val@','')
												.replace('@name@',json[k].name);
										$searchpanel.find('.right-content>div>.search-item>ul').prepend(_tpl);
										window[k] = $searchpanel.find('.right-content>div>.search-item>ul>li.'+k+'>input');
										break;
									case 'slider':
										_tpl =_template_slider
												.replace('@key@',k+' '+json[k].class)
												.replace('@name@',json[k].name);
										$searchpanel.find('.right-content>div>.search-item>ul').prepend(_tpl);
										window[k] = $searchpanel.find('.right-content>div>.search-item li.'+k+'>div').slider({
											range: true,
											min: json[k].min,
											max: json[k].max,
											values: [ json[k].min, json[k].min ],
											slide: function( event, ui ) {
												_result = _setSliderText(data.pkey,ui.values[0],ui.values[1]);
											  $searchpanel.find('.right-content>div>.search-item li.'+k+'>input').val( _result.text );
											},
											change: function( event, ui ) {
												_result = _setSliderText(data.pkey,ui.values[0],ui.values[1]);
											  $searchpanel.find('.right-content>div>.search-item li.'+k+'>input').val( _result.text );
											}
										});
										_result = _setSliderText(data.pkey,
											$searchpanel.find('.right-content>div>.search-item li.'+data.pkey+'>div').slider( "values", 0 ),
											$searchpanel.find('.right-content>div>.search-item li.'+data.pkey+'>div').slider( "values", 1 ));
											$searchpanel.find('.right-content>div>.search-item li.'+data.pkey+'>input').val( _result.text );
										_setQueryItemComplete(_buid);
										break;
								}
							//});
							break;
						case 'select|select':
							pk = k.split('_');
							_k1=pk[0];_k2=pk[1];_sep = '/';
							if(json[k].type=='peroid'){
								_k1=pk[0]+'1';_k2=pk[1]+'2';_sep='-';
							}
							_options1=_template_default_option;
							for(var j in json[k][pk[0]].value){
								_options1+=_template_option.replace('@key@',j).replace('@val@',json[k][pk[0]].value[j]);
							}
							_options2=_template_default_option;
							/*
							for(var j in json[k][pk[1]].value){
								_options2+=_template_option.replace('@key@',j).replace('@val@',json[k][pk[1]].value[j]);
							}*/
							if(json[k][pk[0]].server){
								data = JSON.parse(StorageHandler.getTagStr(pk[0]+'_all'));
								for(var j in data.data){
									_options1+=_template_option.replace('@key@',data.data[j].t_id).replace('@val@',data.data[j].val);
								}
							}
							if(json[k][pk[1]].server){
								data = JSON.parse(StorageHandler.getTagStr(pk[1]+'_all'));
								for(var j in data.data){
									_options2+=_template_option.replace('@key@',data.data[j].t_id).replace('@val@',data.data[j].val);
								}
							}

							_tpl =_template_select_select
									.replace('@key@',k+' '+json[k].class)
									.replace('@key1@',_k1)
									.replace('@key2@',_k2)
									.replace('@name@',json[k].name)
									.replace('@option1@',_options1)
									.replace('@option2@',_options2)
									.replace('@sep@',_sep);
							$searchpanel.find('.right-content>div>.search-item>ul').append(_tpl);
							window[_k1] = $searchpanel.find('.right-content>div>.search-item li select.'+_k1).chosen({search_contains:true,width:'80px'});
							window[_k2] = $searchpanel.find('.right-content>div>.search-item li select.'+_k2).chosen({search_contains:true,width:'80px'});
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
				if(Search(_qs)){
					$searchpanel.find('.right-content>div>.search-item .save').show();
				}
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
						switch(json[_hash[k]].controller){
							case 'select':
								switch(json[k].class){
									case 'chosen':
										$(window[k].selector).val(_condition[k].v).trigger("chosen:updated");
										break;
									case 'multipleSelect':
										$(window[k].selector).val(_condition[k].v);
										$(window[k].selector).multipleSelect("refresh");
										break;
								}
								break;
							case 'input':
								switch(json[k].class){
									case 'input':
										$(window[k].selector).val(_condition[k].v);
										break;
									case 'slider':
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
console.log(k);
				if(settings._searchsetting.indexOf(k)==-1){
					switch(json[k].controller){
						case 'select':
							switch(json[k].class){
								case 'chosen':
									if(window[k]) $(window[k].selector).val('').trigger("chosen:updated");
									break;
								case 'multipleSelect':
									if(window[k]) $(window[k].selector).multipleSelect("uncheckAll");
									break;
							}
							break;
						case 'select|select':
							pk = k.split('_');
							_k1 = pk[0];
							_k2 = pk[1];
							if(json[k].type=='peroid'){
								_k1 = pk[0]+'1';
								_k2 = pk[1]+'2';
							}
							if(window[_k1]) $(window[_k1].selector).val('').trigger("chosen:updated");
							if(window[_k2]) $(window[_k2].selector).val('').trigger("chosen:updated");
							break;
						case 'input':
							if(window[k]){
								switch(json[k].class){
									case 'input':
										$(window[k].selector).val('');
										break;
									case 'slider':
										$(window[k]).slider('values',0,0);
										$(window[k]).slider('values',1,0);
										break;
								}
							}
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
									if(json[k].type=='peroid'){
										_k1 = pk[0]+'1';
										_k2 = pk[1]+'2';
										//ACCEPT
										//1. pk[0]=(default ex:thisyear | lastyear)
										//2. pk[0]=(int1) & pk[1]=(int2) & (int1)<(int2)
										//3. pk[0]=(int1)
										//WRONG
										//pk[0]=(default ex:thisyear,lastyear) & pk[1]=(something)
										//pk[0]=empty
										if(window[_k1].val() && window[_k1].val()!='-'){
											if(json[k][pk[0]].value[window[_k1].val()]){
												//1. output defaults
												_r[pk[0]] = {n:json[k][pk[0]].name,class:json[k].class,type:json[k].type,v:window[_k1].val(),t:$(window[_k1].selector).find('option:selected').text()};
											}else{
												if(window[_k2].val() && window[_k2].val()!='-'){
													//2. findout value between pk[0]~pk[1]
													data = JSON.parse(StorageHandler.getTagStr(pk[0]+'_all'));
													_v=[];_t=[];
													for(var m in data.data){
														if(data.data[m].val>=$(window[_k1].selector).find('option:selected').text() && data.data[m].val<=$(window[_k2].selector).find('option:selected').text()){
															_v.push(data.data[m].t_id);
															_t.push(data.data[m].val);
														}
													}
													if(!$.isEmptyObject(_v)){
														_r[pk[0]] = {n:json[k][pk[0]].name,class:json[k].class,type:json[k].type,v:_v,t:_t};
													}else{
														alert(json[k].name+'輸入的區間不正確!');
														return {};
													}
												}else{
													//3. only set pk[0]
													_r[pk[0]] = {n:json[k][pk[0]].name,class:json[k].class,type:json[k].type,v:window[_k1].val(),t:$(window[_k1].selector).find('option:selected').text()};
												}
											}
										}
									}else{
										if(window[pk[0]].val() && window[pk[0]].val()!='-'){
											_r[pk[0]] = {n:json[k][pk[0]].name,class:json[k].class,type:json[k].type,v:window[pk[0]].val(),t:$(window[pk[0]].selector).find('option:selected').text()};
										}
										if(window[pk[1]].val() && window[pk[1]].val()!='-'){
											_r[pk[1]] = {n:json[k][pk[1]].name,class:json[k].class,type:json[k].type,v:window[pk[1]].val(),t:$(window[pk[1]].selector).find('option:selected').text()};
										}
									}
									break;
							}
							break;
						case 'multipleSelect':
							if(!$.isEmptyObject(window[k].multipleSelect("getSelects"))){
								_r[k] = {n:json[k].name,class:json[k].class,type:json[k].type,v:window[k].multipleSelect("getSelects"),t:window[k].multipleSelect("getSelects", "text")};
							}
							break;
						case 'slider':
							_values = window[k].slider('values');
							_result = _setSliderText(k,_values[0],_values[1]);
							if(_result['val']) _r[k] = {n:json[k].name,class:json[k].class,type:json[k].type,v:_result.val};
							break;
						case 'input':
							_value = $(window[k].selector).val();
							if(_value!=''){
								_r[k] = {n:json[k].name,class:json[k].class,type:json[k].type,t:_value,v:_value};
							}
							break;
					}
				}
			}
		});
		console.log(JSON.stringify(_r));
		return _r;
	}
	function Search(_qs){
		$searchpanel.find('.list>.loading').show();
		//if($.isEmptyObject(_qs)){
		//	return;
		//}
		_pcu=null;
		_pi=null;
		_pn=null;
		_year=null;
		_ty_from=null;
		_ty_to=null;
		_py=null;
		_pty=null;
		_pcof=null;
		_pc=null;
		_prt=null;
		_pwrf=null;
		if(_qs['pcu']) _pcu = _qs['pcu'].v;
		if(_qs['pi']) _pi = _qs['pi'].v;
		if(_qs['pn']) _pn = _qs['pn'].v;
		if(_qs['year']){
			var d = new Date();
			var n = d.getFullYear();
			var roc = n-1911;
			data = JSON.parse(StorageHandler.getTagStr('year_all'));
			switch(_qs['year'].v){
				case 'thisyear':
					for(var m in data.data){
						if(data.data[m].val==roc){
							_year=[data.data[m].t_id];
						}
					}
					break;
				case 'lastyear':
					for(var m in data.data){
						if(data.data[m].val==(roc-1)){
							_year=[data.data[m].t_id];
						}
					}
					break;
				default:
					_year = _qs['year'].v;
					break;
			}
		}
		/*if(_qs['ty']){
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
		}*/
		if(_qs['py']) _py = _qs['py'].v;
		if(_qs['pty']) _pty = _qs['pty'].v;
		if(_qs['pcof']) _pcof = _qs['pcof'].v;
		if(_qs['pc']) _pc = _qs['pc'].v;
		if(_qs['prt']) _prt = _qs['prt'].v;
		if(_qs['pwrf']) _pwrf = _qs['pwrf'].v;
		i = StorageHandler.nextLoadingIndex(searchEnv.path);
		if(_pcu || _pi || _pn || _year || _ty_from || _ty_to || _py || _pty || _pcof || _pc || _prt || _pwrf){
			SearchAPIHandler.doNext(i,_pcu,_pi,_pn,_year,_ty_from,_ty_to,_py,_pty,_pcof,_pc,_prt,_pwrf,function(data){
				$searchpanel.find('.list>.loading').hide();
				if($.isEmptyObject(data)){
					StorageHandler.resetLoadingIndex(searchEnv.path);
				}else if(data.code){
					alert(data.msg);
				}else{
					$.each(Object.keys(data),function(k,v){
						settings._table.row.add([
							{},
							data[v].year,
							data[v].b_name,
							data[v].prt,
							data[v].pi,
							data[v].pcu,
							data[v].pc,
							data[v].pwrf,
							data[v].webbook_link,
							data[v].ibook_link
						]).draw( true );
					});
					if(Object.keys(data).length<_page_num){
						StorageHandler.resetLoadingIndex(searchEnv.path);
					}else{
						Search(_qs);
					}
				}
			});
			//StorageHandler.resetLoadingIndex(searchEnv.path);
			$searchpanel.find('.right-content>.search-item').addClass('close');
			return true;
		}else{
			$searchpanel.find('.list>.loading').hide();
			alert('請輸入參數');
			StorageHandler.resetLoadingIndex(searchEnv.path);
			return false;
		}
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
				case 'input':
					_li = _li_default.replace('@key@',k)
									.replace('@name@',_condition[k].n)
									.replace('@text@',_condition[k].v);
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
		$quicksearch.find('.key').val('');
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
