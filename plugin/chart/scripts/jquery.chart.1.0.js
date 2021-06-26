(function ($) {
$.fn.LnetChart = function(options){
	var _highlight_score = 50;
/*
	var _color = [
		'014D65',
		'4661EE',
		'EC5657',
		'1BCDD1',
		'8FAABB',
		'B08BEB',
		'3EA0DD',
		'F5A52A',
		'23BFAA',
		'FAA586',
		'EB8CC6'];
*/
	var _color = [
		['FF3300','ff8566'],	//¾ï
		['CC33CC','e699e6'],	//µµ¬õ
		['29cc00','5cff33'],	//ºñ
		['0033FF','6685ff'],	//ÂÅ
		['FFFF00','feffb3'],	//¶À
		['FF3333','ffcccc'],	//¬õ
		['3333ff','b3b3ff']		//µµ
	];
	var _chart_summary_imageTemplate='<h4></h4><a></a><p></p><span></span>';

	var $this = this;

	var settings = {
		mode: lnetchartEnum.bs_summary_total,
		userid:'',
		maximum: 300,
		interval: 30,
		width: 225,
		height: 125
	}
  if (options) {
      $.extend(settings, options);
  }

	switch(settings.mode){
		case lnetchartEnum.bs_summary:
			ChartAPIHandler.getBSSummary(function(data){
				doChart('.total',data.total);
				doChart('.MostBook',data.MostBook);
				doChart('.MostRead',data.MostRead);
				doChart('.MostReading',data.MostReading);
			});
			break;
		case lnetchartEnum.book_summary:
			ChartAPIHandler.getBookSummary(function(data){
				doChart('.total',data.total);
				doChart('.MostReadOfUser',data.MostReadOfUser);
				doChart('.MostReadOfManager',data.MostReadOfManager);
				doChart('.MostReadingOfUser',data.MostReadingOfUser);
				doChart('.MostReadingOfManager',data.MostReadingOfManager);
			});
			break;
		case lnetchartEnum.user_summary:
			ChartAPIHandler.getUserSummary(function(data){
				doChart('.total',data.total);
				doChart('.os',data.os);
				doChart('.browser',data.browser);
				doChart('.device',data.device);
			});
			break;
		case lnetchartEnum.tag_summary:
			ChartAPIHandler.getTagSummary(function(data){
				doChart('.total',data.total);
				doChart('.MostRef',data.MostRef);
			});
			break;
		case lnetchartEnum.book_summary_total:
			break;
		case lnetchartEnum.book_summary_MostReadOfUser:
			break;
		case lnetchartEnum.book_summary_MostReadOfManager:
			break;
		case lnetchartEnum.book_summary_MostReadingOfUser:
			break;
		case lnetchartEnum.book_summary_MostReadingOfManager:
			break;
		case lnetchartEnum.user_summary_total:
			break;
		case lnetchartEnum.user_summary_os:
			break;
		case lnetchartEnum.user_summary_browser:
			break;
		case lnetchartEnum.user_summary_device:
			break;
		case lnetchartEnum.tag_summary_total:
			break;
		case lnetchartEnum.tag_summary_MostRef:
			break;
		case lnetchartEnum.user_learning_history:
			settings.minimum=0;
			settings.maximum=100;
			settings.width=800;
			settings.height=600;
			settings.interval=1;
			ChartAPIHandler.getLearningHistory(settings.pid,settings.gid,settings.userid,function(data){
				if(settings.pid){
					settings.height = data.data.length*35;
					settings.chartkey = 'chart';
					doChart('.chart',data);
				}
			});
		case lnetchartEnum.querystring:
			settings.minimum=0;
			settings.maximum=100;
			settings.width=945;
			settings.height=600;
			settings.interval=1;
			ChartAPIHandler.queryChart(settings.querystring,function(data){
				_maximum=100;
				Y2Title={rw:_chart_rw,s:_chart_s,lt:_chart_lt+'(min)'};
				arr = settings.chartkey.split('_');
				settings.axisXTitle = arr[1];
				settings.axisY2Title = Y2Title[arr[0]];
				switch(arr[0]){
					case 'rw':
					case 's':
						settings.toolTipContent="{label} {y}%";
						break;
					case 'lt':
						settings.toolTipContent="{label} {y}min";
						var _maximum = 100;
						for(i=0;i<data.data.length;i++){
							var _y = (data.data[i].y==null)?0:parseInt(data.data[i].y);
							if(_y>_maximum) _maximum=_y;
						}
						if(_maximum>=100){
							_m=_maximum/100;
							_n=2;
							while(_m>10){
								_m=_maximum/Math.pow(10,++_n);
							}
						}
						settings.maximum=(Math.floor(_m)+1)*Math.pow(10,_n);
						break;
				}
				settings.height = data.data.length*35;
				doChart('.'+settings.chartkey,data);
			});
			break;
	}

	function addHexColor(c1, c2) {
		var hexStr = (parseInt(c1, 16) + parseInt(c2, 16)).toString(16);
		while (hexStr.length < 6) { hexStr = '0' + hexStr; } // Zero pad.
		return hexStr;
	}

	function doChart(selector,_data){
		switch(_data.type){
			case 'bar':
			case 'pie':
			case 'stackedBar':
			case 'line':
				if(settings.chartkey){
					selector = '.'+settings.chartkey;
				}

				var chart = $this.find(selector).CanvasJSChart();
				if($this.find(selector).length && typeof chart != 'undefined'){
					_length = chart.options.data.length;
					_toolTipContent = "{label} {y}%";
					if(settings.toolTipContent) _toolTipContent=settings.toolTipContent;
					_d = {
						type: "bar",
						name: settings.title,
						indexLabelFontSize: 8,
						showInLegend: "true",
						axisYType: "secondary",
						toolTipContent: _toolTipContent,
						color:'#'+_color[_length][0],
						dataPoints:[]
					}
					for(i=0;i<_data.data.length;i++){
						var _y = (_data.data[i].y==null)?0:parseInt(_data.data[i].y);
						if(_data.data[i].indexlabel){
							var _i={y:_y,label:_data.data[i].label,indexLabel:_data.data[i].indexlabel};
						}else{
							var _i={y:_y,label:_data.data[i].label};
						}
						if(_y<_highlight_score){
							//_c = addHexColor(_color[_length],'110000');
							_c = _color[_length][1];
							$.extend(_i, {color:'#'+_c });
						}
						_d.dataPoints.push(_i);
					}
					chart.options.data.push(_d);
					chart.render();
				}else{
					//create div
					$this.append('<div class="'+settings.chartkey+'"></div>');
					$this.find(selector).CanvasJSChart(doParam(_data.title,_data.type,_data.data));
				}
				break;
			case 'image':
				$this.find(selector).append(_chart_summary_imageTemplate);
				$this.find(selector).find('h4').text(_data.title);
				if(!$.isEmptyObject(_data.data)){
					if(_data.data[0].image){
						$this.find(selector).css({'background-image':'url('+_data.data[0].image+')',
																			'background-repeat':'no-repeat',
																			'background-position':'center center',
																			'background-size':'contain'});
					}
					$this.find(selector).find('span').text(_data.data[0].name);
					$this.find(selector).find('a').text(_data.data[0].num);
				}
				break;
		}
	}

	function doParam(_title,_type,_data){
		var _interval=settings.interval;
		var _width=settings.width;
		var _height=settings.height;
		var _d=[];
		var _option={};
		switch(_type){
			case 'pie':
				_d = [{
					type:'pie',
					showInLegend:true,
					indexLabelFontSize: 8,
					showInLegend:true,
					indexLabel: "{label} #percent%",
					toolTipContent:"{legendText} {y}",
					dataPoints:[]
				}]
				for(i=0;i<_data.length;i++){
					var _i={y:_data[i].y,legendText:_data[i].label,label:_data[i].label};
					_d[0].dataPoints.push(_i);
				}
				break;
			case 'bar':
				_length = 0;
				if(_height<300) _height=300;
				_toolTipContent = "{label} {y}%";
				if(settings.toolTipContent) _toolTipContent=settings.toolTipContent;
				_d = [{
					type: "bar",
					name: settings.title,
					indexLabelFontSize: 8,
					showInLegend: "true",
					axisYType: "secondary",
					toolTipContent: _toolTipContent,
					color:'#'+_color[_length][0],
					dataPoints:[]
				}]
				for(i=0;i<_data.length;i++){
					var _y = (_data[i].y==null)?0:parseInt(_data[i].y);
					if(_data[i].indexlabel){
						var _i={y:_y,label:_data[i].label,indexLabel:_data[i].indexlabel};
					}else{
						var _i={y:_y,label:_data[i].label};
					}
					if(_y<_highlight_score){
						//_c = addHexColor(_color[_length],'110000');
						_c = _color[_length][1];
						$.extend(_i, {color:'#'+_c });
					}
					_d[0].dataPoints.push(_i);
				}
				_option={
					axisX:{
						title:settings.axisXTitle,
						interval: 1,
						gridThickness: 0,
						labelFontSize: 10,
						labelFontStyle: "normal",
						labelFontWeight: "normal",
						labelFontFamily: "Lucida Sans Unicode"
					},
					axisY2:{
						title:settings.axisY2Title,
						interlacedColor: "rgba(1,77,101,.2)",
						gridColor: "rgba(1,77,101,.1)",
						labelFontSize: 14,
						minimum:settings.minimum,
						maximum:settings.maximum
					}
				}
				break;
			case 'stackedBar':
				_height=160;
				_d = [];
				_jname = ['NotLogin','Manager','User'];
				_j = [_data.notlogin, _data.manager, _data.user];
				for(k=0;k<_j.length;k++){
					_d.push({
						type:'stackedBar',
						legendText:_jname[k],
						showInLegend:true,
						dataPoints:[]
					});
          for(i=0;i<_j[k].length;i++){
            var _i={y:parseInt(_j[k][i].y),label:_j[k][i].x};
            _d[k].dataPoints.push(_i);
          }
				}
				break;
			case '':
				if(data.amount_time){
					_maximum=2000;
					_interval=200;
				}
				break;
		}

		var option={
			width:_width,
			height:_height,
			zoomEnabled: false,
			animationEnabled: true,
			title:{
				text: _title
			},
	    theme: "theme2",
	    toolTip:{
	            shared: true
	    },
			legend:{
				verticalAlign: "bottom",
				horizontalAlign: "center",
				fontSize: 15,
				fontFamily: "Lucida Sans Unicode"
	
			},
			data: _d,
      legend: {
        cursor:"pointer",
        itemclick : function(e) {
          if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
          	e.dataSeries.visible = false;
          }
          else {
            e.dataSeries.visible = true;
          }
          chart.render();
        }
      }
		}
		$.extend(option, _option);
		return option;
	}
};
})(jQuery);
