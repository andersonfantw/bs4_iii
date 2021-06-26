(function ($) {
$.fn.LnetTag = function(options){
	var lnettag_tag = '#tag';
	var lnettag_tagpanel = '.lnet-tag';
	var lnettag_tagpanel_selected_tagid = '.lnet-tag>span>input[name="tagid[]"]';
	var lnettag_selector_panel = '.tag-selector';
	var lnettag_selector_container = '.tag-selector ul';
	var lnettag_input = '.lnet-tag>.input';
	var lnettag_resize_button = '.lnet-tag .resize';
	var lnettag_help_panel = '.lnet-tag-help';
	var lnettag_help_button = '.lnet-tag .menu .help';
	var lnettag_addpanel = '.tag-selector .add-panel';
	var lnettag_addpanel_key = '.tag-selector .add-panel .tagkey>input';
	var lnettag_addpanel_val = '.tag-selector .add-panel .tagval>input';
	var lnettag_addpanel_save_button = '.tag-selector .add-panel .Save';
	var lnettag_addpanel_cancel_button = '.tag-selector .add-panel .Cancel';
	var lnettag_alltags = '.lnet-tag>span';
	var lnettag_all_group_tags = '.lnet-tag>span:has(div)';
	var lnettag_addtag_button = '.tag-selector .button .add';
	var lnettag_back_button = '.tag-selector .button .back';
	var lnettag_home_button = '.tag-selector .button .home';
	var lnettag_current_path = '.path';
	var lnettag_current_pathinfo = '.tag-selector .pathinfo';
	var lnettag_current_pathinfo_item = '.tag-selector .pathinfo span';
	var lnettag_bid = '.lnet-tag>.bid';

	var $this = this;

	var settings = {
		bid: 0,
		tsid: 0,
		seq: 0,
		pid: 0,
		key: '',
		date: '',
		method: '',
		mode: lnettagEnum.choose_tag,
		onSelectedTagDelete: null,
		onSelectedTagKeyUp: null,
		onSelectedTagKeyDown: null,
		onChooseTagSelect: null,
		onSuggestTagEnter: null,
		onSuggestTagDelete: null,
		onSuggestTagSelect: null
	};

  if (options) {
      $.extend(settings, options);
  }

	_loader();
	function _loader(){
		var param={
			path:web_url+'/plugin/'+lnettagEnv.path+'/',
			script:[],
			css:[],
			langJS:false,
			langCSS:false,
			html:'selector.html'
		};
		//_path = web_url+'/plugin/tag/';

		//loader example
		new Loader(param, function(panel){
			$this.prepend(panel);
			_init();
		});
	}

	function _init(){
		$(lnettag_bid).val(settings.bid);
		switch(settings.mode){
			case lnettagEnum.shortcut:
				setTagPanel(settings.tsid,settings.seq);
				setSuggestTagPanel(settings.tsid,settings.seq,'');
				$this.find(lnettag_addtag_button).remove();
				$this.find(lnettag_addpanel).remove();
				AttachEvent();
				$this.find(lnettag_selector_panel).addClass('viewer');
				break;
			case lnettagEnum.choose_tag:
				setTagPanel(settings.bid);
				setSuggestTagPanel(settings.bid,'');
				AttachEvent();
				break;
			case lnettagEnum.create_system_tag:
				setSuggestTagPanel(-1,'');
				$this.find(lnettag_tagpanel).remove();
				AttachEvent();
				$this.find(lnettag_selector_panel).addClass('create_system_tag');
				$this.find(lnettag_selector_panel).show();
				break;
			case lnettagEnum.viewer:
				setTagPanel(-1);
				setSuggestTagPanel(-1,'');
				$this.find(lnettag_addtag_button).remove();
				$this.find(lnettag_addpanel).remove();
				AttachEvent();
				$(lnettag_selector_panel).addClass('viewer');
				break;
			case lnettagEnum.setddl:
				setTagPanel(settings.method,settings.id);
				setSuggestTagPanel(-1,'');
				$this.find(lnettag_addtag_button).remove();
				$this.find(lnettag_addpanel).remove();
				AttachEvent();
				$this.find(lnettag_selector_panel).addClass('viewer');
				break;
			case lnettagEnum.dropdownlist:
				$this.find(lnettag_tagpanel).remove();
				setDropDownList();
				break;
		}
		$(document).keydown(function(event){
			if($this.find(lnettag_addpanel).is(':hidden')){
		  	switch(event.which){
		  		case 27: //[ESC]
		  			$this.find(lnettag_addpanel).hide();
		  			return false;
		  		case 187:	//+
		  			$this.find(lnettag_addtag_button).click();
		  			return false;
		  			break;
		  		case 188:	//<
		  			$this.find(lnettag_back_button).click();
		  			return false;
		  			break;
		  		case 192:	//~
		  			$this.find(lnettag_home_button).click();
		  			return false;
		  			break;
				}
			}else{
        if(event.which==27){
					$this.find(lnettag_addpanel).hide();
        }
			}
		});
	}

	function setTagPanel(){
		switch(settings.mode){
			case lnettagEnum.choose_tag:
				if(arguments.length==1){
					bid = arguments[0];
					TagAPIHandler.getBookTag(bid,function(json_tags){
						if(json_tags) set_tags(bid,json_tags);
					});
				}
				break;
			case lnettagEnum.shortcut:
				if(arguments.length==2){
					tsid = arguments[0];
					seq = arguments[1];
					TagAPIHandler.getShortcutTag(tsid,seq,function(json_tags){
						if(json_tags) set_shortcut_tags(tsid,seq,json_tags);
					});
				}
				break;
			case lnettagEnum.setddl:
				if(arguments.length==2){
					_method = arguments[0];
					_id = arguments[1];
					TagAPIHandler.getSystemTag(_method,_id,function(json_tags){
						if(json_tags) set_tags(_id,json_tags);
					});
				}
				break;
		}
/*
		TagAPIHandler.getSuggestByDropDownList(bid,'',function(availableTags){
		  $this.find(lnettag_input).autocomplete({
		    source: availableTags
		  });
		});
*/
	}
	function setSuggestTagPanel(){
		_tagids = getSelectedTagID();
		switch(arguments.length){
			case 3://lnettagEnum.shortcut:
				tsid = arguments[0];
				seq = arguments[1];
				_path = arguments[2];
				break;
			default:
				bid = arguments[0];
				_path = arguments[1];
				break;
		}
		switch(settings.mode){
			case lnettagEnum.create_system_tag:
				TagAPIHandler.getSuggestSystemTagByChoosePanel(_path,function(json_system_tags){
					console.log(json_system_tags);
					if(json_system_tags){
						set_suggest_tags(json_system_tags,_tagids);
						_path = json_system_tags.path;
					}
					$this.find(lnettag_current_path).val(_path);
				});
				break;
			case lnettagEnum.setddl:
			case lnettagEnum.viewer:
				$(lnettag_addtag_button).hide();
			case lnettagEnum.choose_tag:
				TagAPIHandler.getSuggestByChoosePanel(bid,'',_path,function(json_suggest_tags){
					console.log(json_suggest_tags);
					if(json_suggest_tags){
						set_suggest_tags(json_suggest_tags,_tagids);
						_path = json_suggest_tags.path;
					}
					$this.find(lnettag_current_path).val(_path);
				});
				break;
			case lnettagEnum.shortcut:
				$(lnettag_addtag_button).hide();
				switch(arguments.length){
					case 2:
						TagAPIHandler.getSuggestByChoosePanel(bid,'',_path,function(json_suggest_tags){
							console.log(json_suggest_tags);
							if(json_suggest_tags){
								set_suggest_tags(json_suggest_tags,_tagids);
								_path = json_suggest_tags.path;
							}
							$this.find(lnettag_current_path).val(_path);
						});
						break;
					case 3:
						TagAPIHandler.getSuggestByChoosePanelByShortcut(tsid,seq,'',_path,function(json_suggest_tags){
							console.log(json_suggest_tags);
							if(json_suggest_tags){
								set_suggest_tags(json_suggest_tags,_tagids);
								_path = json_suggest_tags.path;
							}
							$this.find(lnettag_current_path).val(_path);
						});
						break;
				}
				break;
		}
	}
	function getSelectedTagID(){
		var _arr = [];
		$this.find(lnettag_tagpanel_selected_tagid).each(function(){
			_arr.push($(this).val());
		});
		return _arr;
	}	
	function AttachEvent(){
		$this.find(lnettag_resize_button).click(function(){
			if($(this).hasClass('max')){
				$(this).removeClass('max').addClass('min');
				$this.find(lnettag_alltags).removeClass('showgroup').removeClass('hidegroup').addClass('open');
			}else{
				$(this).removeClass('min').addClass('max');
				$this.find(lnettag_alltags).removeClass('open');
				$this.find(lnettag_all_group_tags).addClass('hidegroup');
			}
		});
		$this.find(lnettag_help_button).hover(
			function(){
				$this.find(lnettag_help_panel).show();
			},
			function(){
				$this.find(lnettag_help_panel).hide();
			}
		);
		$this.find(lnettag_addtag_button).click(function(){
			$this.find(lnettag_addpanel).show();
			$this.find(lnettag_addpanel_key).focus();
		});
		$this.find(lnettag_back_button).click(function(){
			var _arr = $(lnettag_current_path).val().split(',');
			_arr.pop();
			var _path = _arr.toString();
			switch(settings.mode){
				case lnettagEnum.shortcut:
					setSuggestTagPanel(settings.tsid,settings.seq,_path);
					break;
				case lnettagEnum.setddl:
					setSuggestTagPanel(-1,_path);
					break;
				default:
					setSuggestTagPanel(_bid,_path);
					break;
			}
			$this.find(lnettag_current_pathinfo_item+':last-child').remove();
		});
		$this.find(lnettag_home_button).click(function(){
			switch(settings.mode){
				case lnettagEnum.shortcut:
					setSuggestTagPanel(settings.tsid,settings.seq,'');
					break;
				case lnettagEnum.setddl:
					setSuggestTagPanel(-1,'');
					break;
				default:
					setSuggestTagPanel(_bid,'');
					break;
			}
			$this.find(lnettag_current_pathinfo_item).remove();
		});

		$this.find(lnettag_addpanel_save_button).click(function(){
			_path = $this.find(lnettag_current_path).val();
			_key = $this.find(lnettag_addpanel_key).val();
			_val = $this.find(lnettag_addpanel_val).val();
	
			if(_val!='' && _key!=''){
				if(!input_filter_unicode(_key)){
					alert('Found invalid characters! Please enter 0-9, a-z, A-Z, and [space]');
					return;
				}
				if(!input_filter_unicode(_val)){
					alert('Found invalid characters! Please enter 0-9, a-z, A-Z, and [space]');
					return;
				}
				var _type=(settings.mode==lnettagEnum.create_system_tag)?1:0;
				TagAPIHandler.addTag(_path,_key,_val,_type,function(data){
          if(data.code=='200'){
						tid = data.msg;
						//add suggest tag
						var _value = [];
						_value[0]=tid;
						_value[1]=_key;
						_value[2]=_val
						_value[3]=_path
						_value[4]=_type;
						
						var tag = new LNET_SUGGEST_TAGS();
						tag.val(_value);
						tag.add(tid,_key,_val,_path,_type);
						$this.find(lnettag_selector_container).append(tag.toString());
          }else{
						alert(data.msg);
          }
				});
				$this.find(lnettag_addpanel_key).val('');
				$this.find(lnettag_addpanel_val).val('');
				$this.find(lnettag_addpanel).hide();
			}else{
				alert('Please enter key and value.');
			}
		});
		$this.find(lnettag_addpanel_cancel_button).click(function(){
			$this.find(lnettag_addpanel).hide();
		});
		$this.find(lnettag_addpanel_key).keydown(function(event){
			_val = $this.find(lnettag_addpanel_key).val();
			if(event.which==13 && _val!=''){
				$this.find(lnettag_addpanel_val).focus();
			}
			
		});
		$this.find(lnettag_addpanel_val).keydown(function(event){
			_val = $this.find(lnettag_addpanel_val).val();
			if(event.which==13 && _val!=''){
				$this.find(lnettag_addpanel_save_button).click();
			}
		});
		$this.find(lnettag_input).keyup(function(event){
			_val = $this.find(lnettag_input).val();
			if(event.which==13 && (_val!='')){ //[Enter]
				if(_val.length>15){
					alert('Please input less then 15 characters.');return;
				}
				//show all the same values with different path
				
				_path = '';
				_key = 'user-defined';
				TagAPIHandler.setTag(settings.bid,_path,_key,_val,function(data){
					switch(data.code){
						case '200':
							var tag = new LNET_TAGS(settings.bid);
							tag.add(0,_key,_val);
							$this.find(lnettag_tagpanel).append(tag.toString());
							$this.find(lnettag_tagpanel).append($this.find(lnettag_input));
							$this.find(lnettag_input).val('');
							break;
						case '302':
								alert('Duplicated!');
							break;
					}
					console.log(data);
				});
			}else{
/*
				TagAPIHandler.getSuggestByDropDownList(settings.bid,_val,function(availableTags){
					$this.find(lnettag_input).autocomplete({
						source: availableTags
					});
				});
*/
				_tagids = getSelectedTagID();
				TagAPIHandler.getSuggestByChoosePanel(0,_val,'',function(json_suggest_tags){
					if(json_suggest_tags) set_suggest_tags(json_suggest_tags,_tagids);
					$this.find(lnettag_current_pathinfo_item).remove();
					$this.find(lnettag_selector_panel).show();
				});
			}
			if (settings.onSelectedTagKeyUp) {
				settings.onSelectedTagKeyUp(e);
			}
			event.preventDefault();
		});
		$this.find(lnettag_input).keydown(function(e){
			if (settings.onSelectedTagKeyDown) {
				settings.onSelectedTagKeyDown(e);
			}
		});
		$this.find(lnettag_tagpanel).click(function(e){
			$this.find(lnettag_input).focus();
			if($this.find(lnettag_selector_panel).is(':visible')) $this.find(lnettag_selector_panel).hide();
			e.preventDefault();
		});
		$this.find(lnettag_tagpanel).dblclick(function(e){
			$this.find(lnettag_selector_panel).show();
			e.preventDefault();
		});
/*
		$(lnettag_addpanel).mouseleave(function(){
			if(confirm('save?')){
				save_tag();
			}
			$(lnettag_addpanel).hide();
		});
*/
	}

	function set_tags(bid,json_tags){
		for(var i in json_tags){
			v = json_tags[i];
			tid = v[0][0];
			path = '';
			var tag = new LNET_TAGS(tid);
			tag.setID(bid);
			for(var k in v){
				val = v[k][2];
				tag.val(v);
				tag.add(v[k][0],v[k][1],v[k][2],v[k][3],v[k][4]);
			}
			$this.find(lnettag_tagpanel).append(tag.toString());
			$this.find(lnettag_tagpanel).append($this.find(lnettag_input));
		}
	}
	function set_shortcut_tags(tsid,seq,json_tags){
		for(var i in json_tags){
			v = json_tags[i];
			tid = v[0][0];
			path = '';
			var tag = new LNET_TAGS(tid);
			tag.setID(tsid,seq);
			for(var k in v){
				val = v[k][2];
				tag.val(v);
				tag.add(v[k][0],v[k][1],v[k][2],v[k][3],v[k][4]);
			}
			$this.find(lnettag_tagpanel).append(tag.toString());
			$this.find(lnettag_tagpanel).append($this.find(lnettag_input));
		}
	}
	function set_suggest_tags(json_suggest_tags,exclude_id){
		$this.find(lnettag_selector_container).html('');
		for(var i in json_suggest_tags.item){
			v = json_suggest_tags.item[i];
			if($.inArray(v[0],exclude_id)==-1){
				var tag = new LNET_SUGGEST_TAGS();
				tag.val(v);
				tag.add(v[0],v[1],v[2],v[3],v[4],v[5],v[6]);
				$this.find(lnettag_selector_container).append(tag.toString());
			}
		}
	}
	function _emptySelectElement(pid){
		obj=$('<select data-pid="'+pid+'"></select>');
		obj.attr("data-live-search","true");
		obj.addClass('show-tick');
		obj.append('<option value="">--Please Select--</option>');
		return obj;
	}
	function _createDropDownList(pid,list,returnFunc){
		$obj=_emptySelectElement(pid);
		for(var j=0;j<list.data.length;j++){
			_selected = '';
			if(list.selected==list.data[j].t_id) _selected=' selected';
			$obj.append('<option value="'+list.data[j].t_id+'" '+_selected+'>'+list.data[j].val+'</option>');
		}			
		$obj.change(function(){
			tid=$(this).val();
			pid=$(this).data('pid');
			returnFunc(pid,tid,this);
		});
		return $obj;
	}
	function setDropDownList(){
		switch(settings.method){
			case 'getByPKey':
				TagAPIHandler.getTagByPKey(settings.pkey,function(data){
					$obj = _createDropDownList(data.pid,data,function(_pid,_tid,obj){
						keyValues = [];
						params = {mode: lnetchartEnum.user_learning_history,pid: _tid};
						if($.getQuery('id')!=''){
							keyValues = ToolsHandler.decodeURL('id');
						}
						if(keyValues['userid']){
							params = {
								mode: lnetchartEnum.user_learning_history,
								pid: _tid,
								userid: keyValues['userid']
							};
						}
						if(keyValues['gid']){
							params = {
								mode: lnetchartEnum.user_learning_history,
								pid: _tid,
								gid: keyValues['gid']
							};
						}
						$('#chart-panel').LnetChart(params);
					});
					$this.append($obj);
				});
				break;
			default:
				TagAPIHandler.getDropDownList(settings.method,function(n){
					for(var i=0;i<n.length;i++){
						TagAPIHandler.getDropDownListItems(settings.key,settings.date,n[i].t_id,settings.method,function(data){
							var l = TAFFY(data.items);
							//init ddl
							var _m = l({t_id:data.selected}).get();
							var _selected = data.selected;
							if(!$.isEmptyObject(_m)){
								data.selected = _m[0].pid;
							}
							var $obj2 = (!$.isEmptyObject(data.items))?_emptySelectElement(0):null;
							var $obj1 = _createDropDownList(n[i].t_id,data,function(_pid,_tid,obj){
								if($.isEmptyObject(data.items)){
									if(_tid!=''){
										TagAPIHandler.setDropDownListItems(settings.key,settings.date,_pid,settings.method,_tid,function(data){
											switch(data.code){
												case '200':
													break;
												case '500':
													break;
											}
										});
									}
								}else{
									var _d=[];
									l({pid:_tid}).each(function(r){
										_d.push(r);
									});
									list = {data:_d,selected:_tid,items:[]};
									$obj2 = _createDropDownList(_tid,list,function(_pid,_tid,obj1){
										if(_tid!=''){
											TagAPIHandler.setDropDownListItems(settings.key,settings.date,_pid,settings.method,_tid,function(data){
												switch(data.code){
													case '200':
														break;
													case '500':
														break;
												}
											});
										}
									});
		              $(obj).next().remove();
		              $(obj).parent().append($obj2);
								}
							});
		
							var _m = l({t_id:_selected}).get();
							if(!$.isEmptyObject(_m)){
								TagAPIHandler.getDropDownListItems(settings.key,settings.date,_m[0].pid,settings.method,function(data1){
									$obj2 = _createDropDownList(data.selected,data1,function(_pid,_tid,obj1){
										if(_tid!=''){
											TagAPIHandler.setDropDownListItems(settings.key,settings.date,_pid,settings.method,_tid,function(data){
												switch(data.code){
													case '200':
														break;
													case '500':
														break;
												}
											});
										}
									});
								});
							}
							$obj1.selectpicker();
							if($obj2){
								$obj2.selectpicker();
								$this.append($('<p></p>').append(n[i].val+': ').append($obj1).append($obj2));
							}else{
								$this.append($('<p></p>').append(n[i].val+': ').append($obj1));
							}
						});
					}
				});
				break;
		}
	}
	var LNET_TAGS = function(_tid){
		this.bid = 0;
		this.tsid = 0;
		this.seq = 0;
		this.tid = _tid;
		this.tags=[];
		this.val = function(v){
			this.value = v;
		};
		this.setID = function(){
			switch(arguments.length){
				case 1:
					this.bid=arguments[0];
					break;
				case 2:
					this.tsid=arguments[0];
					this.seq=arguments[1];
					break;
			}
		}
		this.add = function(id,k,v,p,s){
			var obj = {};
			obj.id = id;
			obj.key = k;
			obj.val = v;
			obj.path = (typeof p !== 'undefined') ? p : '';
			obj.type= (typeof s !== 'undefined') ? s : 0;
			this.tags.push(obj);
		};
		this.remove = function(k){
			delete this.tags[k];
		};
		this.toString = function(){
			root = this;
			if(this.tags.length==0) return;
			if(this.tags.length>0){
				var _sys_flag='';
				if(this.tags[0].id==-1 || this.tags[0].type==1){
					_sys_flag = ' class="sys"';
				}
				_tag_html="<span @sys_flag>\
										<span>\
											<span class='ui-icon'></span>\
												<span class='key'>@key</span>\
												<b>@val</b>\
												<a>x</a>\
											</span>\
											<input type='hidden' name='tagid[]' value='@tid' />\
											<input type='hidden' name='tagpath' value='@tpath' />\
										</span>";
				var obj = $(_tag_html.replace('@sys_flag',_sys_flag)
							.replace('@key',this.tags[0].key)
							.replace('@val',this.tags[0].val)
							.replace('@tid',this.tags[0].id)
							.replace('@tpath',this.tags[0].path));
				obj.find('a').click(function(e){
					$(this).parent().parent().remove();
					switch(settings.mode){
						case lnettagEnum.choose_tag:
							_bid=root.bid;
							_tid=root.tid;
							TagAPIHandler.delBookTag(_bid,_tid,function(data){
								if(data.code=='200'){
									_tagids = getSelectedTagID();
									setSuggestTagPanel(bid,'');
								}else{
									alert(data.msg);
								}
							});
							break;
						case lnettagEnum.shortcut:
							_tsid=root.tsid;
							_seq=root.seq;
							_tid=root.tid;
							TagAPIHandler.delShortcutTag(_tsid,_seq,_tid,function(data){
								_tagids = getSelectedTagID();
								setSuggestTagPanel(_tsid,_seq,'');
							});
							break;
						case lnettagEnum.setddl:
							TagAPIHandler.delSystemTag(settings.method,settings.id,_tid,function(){
							});
							break;
					}
					if (settings.onSelectedTagDelete) {
						settings.onSelectedTagDelete(e);
					}
				});
			}
			if(this.tags.length>1){
				obj.find('span:first').click(function(){
					if($(this).parent().hasClass('showgroup')){
						$(this).parent().removeClass('showgroup').addClass('hidegroup');
					}else{
						$(this).parent().removeClass('hidegroup').addClass('showgroup');
					}
				});
				obj.append('<div></div>');
				for(var i=1;i<this.tags.length;i++){
					var _sys_class='';
					if(this.tags[i].type==1){
						_sys_class = ' class="sys"';
					}
					obj.find('div').append('<span'+_sys_class+'><span class="key">'+this.tags[i].key+'</span><b>'+this.tags[i].val+'</b></span>');
				}
				obj.find('div>span:last').append('<a>x</a>');
				obj.find('div>span:last>a').click(function(e){
					$(this).parent().parent().parent().remove();
					switch(settings.mode){
						case lnettagEnum.choose_tag:
							_bid=root.bid;
							_tid=root.tid;
							TagAPIHandler.delBookTag(_bid,_tid,function(data){
								_tagids = getSelectedTagID();
								setSuggestTagPanel(bid,'');
							});
							break;
						case lnettagEnum.shortcut:
							_tsid=root.tsid;
							_seq=root.seq;
							_tid=root.tid;
							TagAPIHandler.delShortcutTag(_tsid,_seq,_tid,function(data){
								_tagids = getSelectedTagID();
								setSuggestTagPanel(_tsid,_seq,'');
							});
							break;
						case lnettagEnum.setddl:
							TagAPIHandler.delSystemTag(settings.method,settings.id,_tid,function(){
							});
							break;
					}
					
					if (settings.onSelectedTagDelete) {
						settings.onSelectedTagDelete(e);
					}
				});
				obj.addClass('hidegroup');
			}else{
				obj.addClass('single');
			}
			if($('.lnet-tag .resize').hasClass('min')){
				obj.removeClass('hidegroup').addClass('open');
			}
			return obj;
		};
	}
	
	var LNET_SUGGEST_TAGS = function(){
		this.tags=[];
		this.value=[];
		this.val = function(v){
			this.value = v;
		};		
		this.add = function(id,k,v,p,s,n,c){
			var obj = {};
			obj.id = id;
			obj.key = k;
			obj.val = v;
			obj.path = (typeof p !== 'undefined') ? p : '';
			obj.type= (typeof s !== 'undefined') ? s : 0;
			obj.refnum = n;
			obj.childnum = c;
			this.tags.push(obj);
		};
		this.remove = function(k){
			delete this.tags[k];
		};
		this.toString = function(){
			var val='';
			var _sys_class='';
			if(this.tags.length==0) return;
			if(this.tags.length>0){
				key = this.tags[0].key;
				val = this.tags[0].val;
				refnum = this.tags[0].refnum;
				childnum = this.tags[0].childnum;
				_sys_class='';
				if(this.tags[0].type==1){
					_sys_class = ' class="sys"';
				}
				_suggestTag_html = "<li title='@key' @sys_class>\
								<span>@val</span>\
								<div>\
									<a class='choose'><span></span></a>\
									<a class='enter'><span></span></a>\
									<a class='del'><span></span></a>\
								</div>\
								<input type='hidden' value='@data' />\
							</li>";
				var obj = $(_suggestTag_html.replace('@sys_class',_sys_class)
								.replace('@key',key)
								.replace('@val',val)
								.replace('@data',JSON.stringify(this.value)));
				if(refnum>0
					|| childnum>0
					|| settings.mode==lnettagEnum.viewer
					|| settings.mode==lnettagEnum.choose_tag
					|| settings.mode==lnettagEnum.shortcut
					|| settings.mode==lnettagEnum.setddl) obj.find('.del').remove();
				//create_system_tag mode, disable choose.
				if(settings.mode==lnettagEnum.create_system_tag) obj.find('.choose').remove();
				if(obj.find('div>a').length==1) obj.find('div>a').css('width','100%');
				obj.attr('title','reference num:'+childnum);

				obj.find('.choose').click(function(e){
					var btn = this;
					var val = $(this).parent().parent().find('input').val();
					var v = $.parseJSON(val);
					var _tid=v[0];
					var _key=v[1];
					var _val=v[2];
					var _path=v[3];
					var p=[];
					if(_path!='') p=_path.split(',');
					var _bid = $(lnettag_bid).val();
					switch(settings.mode){
						case lnettagEnum.viewer:
							set_tags(_bid,[[v]]);
							$(btn).parent().parent().remove();
							$(lnettag_input).val('');
							break;
						case lnettagEnum.shortcut:
							TagAPIHandler.setShortcutTag(settings.tsid,settings.seq,_tid,function(data){
								switch(data.code){
									case '200':
										set_tags(_bid,[[v]]);
										$(btn).parent().parent().remove();
										break;
									case '302':
											alert('Duplicated!');
										break;
								}

								console.log(data);
								$(lnettag_selector_panel).hide();
								setSuggestTagPanel(settings.tsid,settings.seq,'');
							});
							break;
						case lnettagEnum.setddl:
							TagAPIHandler.setSystemTag(settings.method,settings.id,_tid,function(data){
								switch(data.code){
									case '200':
										set_tags(settings.id,[[v]]);
										$(btn).parent().parent().remove();
										break;
									case '302':
										break;
								}
								console.log(data);
								$(lnettag_selector_panel).hide();
								setSuggestTagPanel(settings.id,'');
							});
							break;
						default:
							TagAPIHandler.setTag(_bid,_path,_key,_val,function(data){
								switch(data.code){
									case '200':
										set_tags(_bid,[[v]]);
										$(btn).parent().parent().remove();
										break;
									case '302':
											alert('Duplicated!');
										break;
								}
	
								console.log(data);
								$(lnettag_selector_panel).hide();
								setSuggestTagPanel(_bid,'');
			
							});
							break;
					}
					if (settings.onSuggestTagSelect) {
						settings.onSuggestTagSelect(e);
					}
				});
				obj.find('.enter').click(function(e){
					var val = $(this).parent().parent().find('input').val();
					var v = $.parseJSON(val);
					var _tid = v[0];
					var _key = v[1];
					var _val = v[2];
					var _p = v[3];
					var _path = (_p=='')?_tid:_p+','+_tid;
					var _bid = $(lnettag_bid).val();
					setSuggestTagPanel(_bid,_path);
					$tag = $('<span>>'+_key+':'+_val+' <input type="hidden" value="'+_path+'" /></span>');
					$tag.click(function(){
						var _path= $(this).find('input').val();
						setSuggestTagPanel(_bid,_path);
						$(this).nextAll().remove();
					});
					$(lnettag_current_pathinfo).append($tag);
					if (settings.onSuggestTagEnter) {
						settings.onSuggestTagEnter(e);
					}
				});
				obj.find('.del').click(function(e){
					if(confirm('Are you sure?')){
						var val = $(this).parent().parent().find('input').val();
						var v = $.parseJSON(val);
						var _tid = v[0];
						var _p = v[3];
						var _path = (_p=='')?_tid:_p+','+_tid;
						TagAPIHandler.delSysTag(_tid,_path,function(data){
							if(data.code=='200'){
								obj.remove();
							}else{
								alert(data.msg);
							}
						});
						if (settings.onSuggestTagDelete) {
							settings.onSuggestTagDelete(e);
						}
					}
				});
			}
			/*if(this.tags.length>1){
				for(var i=1;i<this.tags.length;i++){
					val += '/'+this.tags[i].val;
				}
			}*/
			
			return obj;
		};
	}
};

})(jQuery);
