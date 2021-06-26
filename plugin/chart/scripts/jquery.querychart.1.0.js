(function ($) {
$.fn.querychart = function(options){
	var chartselector_buttom_default = '#selector_panel label.default';
	var chartselector_buttom_query = '#selector_panel label.query';
	var chartselector_panel_default = '#selector_panel > div[class^="default_"]';
	var chartselector_panel_default_webadmin = '#selector_panel > div.default_webadmin';
	var chartselector_panel_default_manager = '#selector_panel > div.default_manager';
	var chartselector_panel_default_button = '#selector_panel > div[class^="default_"] a';
	var chartselector_panel_query = '#selector_panel > div.query';
	var chartselector_selector = '#selector_panel .chart_selector';
	var chartselector_selectedtag = '.selected_tags';
	var chartselector_selectedtag_addtag = '.selected_tags .add_tag';
	var chartselector_selectedtag_resettag = '.selected_tags .reset_tag';
	var chartselector_selectedtag_tagid = '.selected_tags .selector_y_axis';
	var chartselector_selector_tag = '.selector_tags';
	var chartselector_selector_buttom_level = '.query .bounds .level';
	var chartselector_selector_buttom_grade = '.query .bounds .grade';
	var chartselector_selector_buttom_learningtime = '.query .bounds .learningtime';
	var chartselector_selector_start = '#selector_panel .query .selector_startdate';
	var chartselector_selector_end = '#selector_panel .query .selector_enddate';
	var chartselector_selector_xaxis = '#selector_panel .query input[name=x_axis]';
	var chartselector_selector_yaxis = '#selector_panel .query .selector_y_axis';
	var chartselector_selector_bookshelfs = '#selector_panel .query .selector_bookshelfs';
	var chartselector_selector_groups = '#selector_panel .query .selector_groups';
	var chartselector_selector_users = '#selector_panel .query .selector_users';
	var chartselector_selector_books = '#selector_panel .query .selector_books';
	var chartselector_selector_difficulty = '#selector_panel .query .selector_difficulty';
	var chartselector_selector_semester = '#selector_panel .query .selector_semester';
	var chartselector_buttom_add = '#selector_panel .button > .add';
	var chartselector_buttom_reset = '#selector_panel .button > .reset';
	var chartselector_panel = '#panel';
	var chartselector_rw_percent = '.query .rw_percent_panel .rw_percent';
	var chartselector_rw_percent_panel = '.query .rw_percent_panel';
	var chartselector_rw_percent_val = '.query .rw_percent_panel .rw_percent_val';
	var chartselector_listallsubject = '.query .listallsubject';
	
	var _template_selectoption='<option value="@val@">@text@</option>';
	var _template_selector='<div><button class="close">X</button><div>@condition@</div><input class="query" type="hidden" value="@query@" /><input class="key" type="hidden" value="@query@" /></div>';
	var _template_yaxis_item='<li><a>@val@</a></li>';
	var _template_selectedtag='<span class="axis" title="axis">@tag@</span>'

	var _query_key = {start:_chart_start,end:_chart_end,r:_chart_r,co:_chart_co,rwp:'rwp',las:'las',bs:_chart_bookshelf,b:_chart_book,g:_chart_g,bu:_chart_bu,d:_chart_difficulty,s:_chart_semester};
	var _query_val = {r:{rw:_chart_rw,s:_chart_s,lt:_chart_lt},co:{subject:_chart_subject,ability:_chart_ability}};

	var $this = this;

	var settings = {
	};

  if (options) {
      $.extend(settings, options);
  }

	_loader();
	function _loader(){
		$this.prepend(settings.panel);
		
		$(chartselector_selector_tag+' *').remove();
		$(chartselector_selector_tag).append('<ul></ul>');
		
		$this.find(chartselector_selector_buttom_level).click(function(){
			$this.find(chartselector_rw_percent).prop("disabled", false);
			$this.find(chartselector_rw_percent_panel).removeClass('disabled');
		});
		$this.find(chartselector_selector_buttom_grade).click(function(){
			$this.find(chartselector_rw_percent).prop("disabled", "disabled");
			$this.find(chartselector_rw_percent_panel).addClass('disabled');
		});
		$this.find(chartselector_selector_buttom_learningtime).click(function(){
			$this.find(chartselector_rw_percent).prop("disabled", "disabled");
			$this.find(chartselector_rw_percent_panel).addClass('disabled');
		});

		$this.find(chartselector_rw_percent).change(function(){
			$this.find(chartselector_rw_percent_val).text($(this).val());
		});
		$this.find(chartselector_rw_percent).on('input', function () {
			$(this).trigger('change');
		});
		
		TagAPIHandler.getTagsByPKey('',function(data){
			for(i=0;i<data.length;i++){
				_getTags(data[i],$(chartselector_selector_tag));
			}
		});
		$(chartselector_selectedtag_addtag).click(function(){
			$this.find(chartselector_selector_tag+'>ul').show();
		});
		$(chartselector_selectedtag_resettag).click(function(){
			$this.find(chartselector_selectedtag_tagid).val('');
			$this.find(chartselector_selectedtag+'>div>*').remove();
		});
		ChartAPIHandler.getDDLItemBookshelfs(settings.site,function(data){
			for(i=0;i<data.result.length;i++){
				_option = _template_selectoption.replace('@val@',data.result[i].bs_id).replace('@text@',data.result[i].bs_name);
				$this.find(chartselector_selector_bookshelfs).append(_option);
			}
		});
		ChartAPIHandler.getDDLItemGroups(settings.site,function(data){
			for(i=0;i<data.result.length;i++){
				_option = _template_selectoption.replace('@val@',data.result[i].g_id).replace('@text@',data.result[i].g_name);
				$this.find(chartselector_selector_groups).append(_option);
			}
		});
		ChartAPIHandler.getDDLItemBooks(0,function(data){
			for(i=0;i<data.result.length;i++){
				_option = _template_selectoption.replace('@val@',data.result[i].b_id).replace('@text@',data.result[i].b_name);
				$this.find(chartselector_selector_books).append(_option);
			}
		});
		ChartAPIHandler.getDDLItemDifficulty(function(data){
			for(i=0;i<data.length;i++){
				_option = _template_selectoption.replace('@val@',data[i].t_id).replace('@text@',data[i].val);
				$this.find(chartselector_selector_difficulty).append(_option);
			}
		});
		ChartAPIHandler.getDDLItemSemester(function(data){
			for(i=0;i<data.length;i++){
				_option = _template_selectoption.replace('@val@',data[i].t_id).replace('@text@',data[i].val);
				$this.find(chartselector_selector_semester).append(_option);
			}
		});
		$this.find(chartselector_selector_groups).change(function(){
			$this.find(chartselector_selector_users+' *').remove();
			_option = _template_selectoption.replace('@val@','').replace('@text@','----');
			$this.find(chartselector_selector_users).append(_option);
			ChartAPIHandler.getDDLItemUsers($(this).val(),function(data){
				for(i=0;i<data.result.length;i++){
					_option = _template_selectoption.replace('@val@',data.result[i].bu_id).replace('@text@',data.result[i].bu_name);
					$this.find(chartselector_selector_users).append(_option);
				}
			});
		});
	}

	var panel_default = 'webadmin';
	set_panel_default();
	function set_panel_default(){
		switch(panel_default){
			case 'webadmin':
				$this.find(chartselector_panel_default_webadmin).show();
				$this.find(chartselector_panel_default_manager).hide();
				break;
			case 'manager':
				$this.find(chartselector_panel_default_webadmin).hide();
				$this.find(chartselector_panel_default_manager).show();
				break;
		}
	}
	$this.find(chartselector_selector_tag+'>ul').mouseleave(function(){
		$this.find(chartselector_selector_tag).hide();
	});
	$this.find(chartselector_selectedtag_addtag).click(function(){
		$this.find(chartselector_selector_tag+'>ul').show();
		if($this.find(chartselector_selector_tag).is(':hidden')){
			$this.find(chartselector_selector_tag).show();
		}else{
			$this.find(chartselector_selector_tag).hide();
		}
	});
	$this.find(chartselector_buttom_default).click(function(){
		set_panel_default();
		$this.find(chartselector_panel_query).hide();
	});
	$this.find(chartselector_buttom_query).click(function(){
		$this.find(chartselector_panel_default).hide();
		$this.find(chartselector_panel_query).show();
	});
	$this.find(chartselector_panel_default_button).click(function(){
		$this.find(chartselector_panel+' *').remove();
		_query = $(this).parent().find('input').val();
		_arr_query = _query.split('|');
		$this.find(chartselector_selector+' > *').remove();
		_createQuerystring(_arr_query);
		/*
		for(i=0;i<_arr_query.length;i++){
			_q = _arr_query[i].split('&');
			_str = '';
			for(j=0;j<_q.length;j++){
				_a = _q[j].split('=');
				_str += _query_key[_a[0]]+'='+_query_val[_a[0]][_a[1]]+'<br />';
			}
			_str = _template_selector.replace('@condition@',_str);
			$obj = $(_str);
			$obj.find('.close').click(function(){
				$(this).parent().remove();
			});
			$(chartselector_selector).append($obj);
		}*/
	});
	$this.find(chartselector_buttom_add).click(function(){
		//chartselector_selector_startdate
		//chartselector_selector_enddate
		$this.find(chartselector_selector_xaxis).parent().parent().removeClass('required');
		if(typeof $this.find(chartselector_selector_xaxis+':checked').val() === "undefined"){
			$this.find(chartselector_selector_xaxis).parent().parent().addClass('required');
		}else{
			$this.find(chartselector_selector_xaxis).prop('disabled',true);
			$this.find(chartselector_rw_percent).prop('disabled',true);
		}
		$this.find(chartselector_selectedtag_addtag).parent().removeClass('required');
		if($this.find(chartselector_selector_yaxis).val()==''){
			$this.find(chartselector_selectedtag_addtag).addClass('required');
			return false;
		}else{
			$this.find(chartselector_selectedtag_addtag).prop('disabled',true);
			$this.find(chartselector_listallsubject).prop('disabled',true);
		}
		_query ={
			start:$(chartselector_selector_start).val(),
			end:$(chartselector_selector_end).val(),
			r:$(chartselector_selector_xaxis+':checked').val(),
			co:$(chartselector_selector_yaxis).val(),
			rwp:$(chartselector_rw_percent).val(),
			las:$(chartselector_listallsubject).is(':checked')?1:'',
			bs:$(chartselector_selector_bookshelfs).val()+':'+$(chartselector_selector_bookshelfs+' :selected').text(),
			g:$(chartselector_selector_groups).val()+':'+$(chartselector_selector_groups+' :selected').text(),
			bu:$(chartselector_selector_users).val()+':'+$(chartselector_selector_users+' :selected').text(),
			b:$(chartselector_selector_books).val()+':'+$(chartselector_selector_books+' :selected').text(),
			d:$(chartselector_selector_difficulty).val(),
			s:$(chartselector_selector_semester).val()
		}
		_createQuerystring([$.param(_query)]);
	});
	$this.find(chartselector_buttom_reset).click(function(){
		$this.find(chartselector_selector_xaxis).prop('disabled',false);
		$this.find(chartselector_selectedtag_addtag).prop('disabled',false);
		$this.find(chartselector_selector_xaxis+':checked').prop('checked',false);
		$this.find(chartselector_selectedtag+' >div>*').remove();
		$this.find(chartselector_selector+' *').remove();
		$this.find(chartselector_panel+' *').remove();

		$this.find(chartselector_rw_percent).prop('disabled','disabled');
		$this.find(chartselector_rw_percent_panel).addClass('disabled');
		$this.find(chartselector_rw_percent).val(100);
		$this.find(chartselector_listallsubject).prop('disabled',false);
		$this.find(chartselector_listallsubject).prop('checked',false);
		$this.find(chartselector_selector_bookshelfs+' > option:eq(0)').prop('selected',true);
		$this.find(chartselector_selector_books+' > option:eq(0)').prop('selected',true);
		$this.find(chartselector_selector_groups+' > option:eq(0)').prop('selected',true);
		$this.find(chartselector_selector_users+' > option:eq(0)').prop('selected',true);
		$this.find(chartselector_selector_difficulty+' > option:eq(0)').prop('selected',true);
		$this.find(chartselector_selector_semester+' > option:eq(0)').prop('selected',true);

	});
	function _createQuerystring(arr){
		for(i=0;i<arr.length;i++){
			_s=true;
			_query='';
			_q = arr[i].split('&');
			_str = '';
			_strtitle='';
			_str1 = '';
			for(j=0;j<_q.length;j++){
				_a = _q[j].split('=');
				_a[1] = decodeURIComponent(_a[1].replace(/\+/g,  " "));
				if(_a[1]!='' && _a[1]!=':----'){
					_v = _a[1].split(':');
					switch(_a[0]){
						case 'r':
							_r = _v[0];
							_str += _query_key[_a[0]]+'='+_query_val[_a[0]][_a[1]]+'<br />';
							_query += '&'+_q[j];
							break;
						case 'co':
							_co = _v[0];
							_str += _query_key[_a[0]]+'='+_a[1]+'<br />';
							_strtitle += _query_key[_a[0]]+'='+_a[1]+';';
							_query += '&'+_a[0]+'='+_v[2];
							break;
						case 'las':
							_str += _query_key[_a[0]]+'='+_v[0]+'<br />';
							_query += '&'+_q[j];
							break;
						case 'rwp':
						case 'start':
						case 'end':
							_str += _query_key[_a[0]]+'='+_v[0]+'<br />';
							_str1 += _query_key[_a[0]]+'='+_v[0]+';';
							_query += '&'+_q[j];
							break;
						default:
							_str += _query_key[_a[0]]+'='+_v[1]+'<br />';
							_str1 += _query_key[_a[0]]+'='+_v[1]+';';
							_query += '&'+_q[j];
							break;
					}
				}
			}
			if(_query!='') _query=_query.substr(1);

			//see if any same query.
			$this.find(chartselector_selector).find('input').each(function(){
				if($(this).val()==_query){
					_s=false;
					alert('Already has the same result!');
					return false;
				}
			});
			if(_s){
				_str = _template_selector.replace('@condition@',_str).replace('@query@',_query);
				$obj = $(_str);
				$obj.find('.close').click(function(){
					$(this).parent().remove();
				});
				$this.find(chartselector_selector).append($obj);

				params = {
					mode: lnetchartEnum.querystring,
					chartkey: _r+'_'+_co,
					title: _str1,
					querystring: Base64.encode(_query)
				};
				$(chartselector_panel).LnetChart(params);
			}
		}
	}

	function _getTags(data,$item){
		_item = _template_yaxis_item.replace('@val@',data.val);
		$obj = $(_item);
		$obj.mouseenter(data,function(event){
			$obj1 = $(this);
			$obj1.find('ul').remove();
			$obj1.append('<ul></ul>');
			TagAPIHandler.getTagsByPKey(event.data.key,function(data1){
				for(j=0;j<data1.length;j++){
					_getTags(data1[j],$obj1);
				}
			});
		});
		$obj.click(data,function(event){
			$this.find(chartselector_selector_yaxis).val(event.data.key+':'+event.data.val+':'+event.data.t_id);
			_item = _template_selectedtag.replace('@tag@',event.data.key+':'+event.data.val);
			$this.find(chartselector_selectedtag+'>div>*').remove();
			$this.find(chartselector_selectedtag+'>div').append(_item);
			event.stopPropagation();
		});
		$item.find('ul').append($obj);
	}
};
})(jQuery);
