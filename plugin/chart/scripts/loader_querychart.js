$(document).ready(function(){
	var param={
		path:web_url+'/plugin/tag/',
		script:['tag.class.1.0'],
		css:[],
		langJS:{},
		langCSS:false,
		html:''
	};
	new Loader(param,function(){
	});

	var param={
		path:web_url+'/plugin/chart/',
		script:['jquery.canvasjs.min','chart.class.1.0','jquery.chart.1.0'],
		css:[],
		langJS:{},
		langCSS:false,
		html:''
	};
	params = {
	};
	new Loader(param,function(){
	});

	var param={
		path:web_url+'/plugin/chart/',
		script:['chart.class.1.0','jquery.querychart.1.0'],
		css:['jquery.querychart.1.0'],
		langJS:{'enable':true},
		langCSS:false,
		html:'query_panel.html'
	};

	//loader example
	new Loader(param,function(_panel){
		_panel = _panel.replace('@title@',_chart_title)
										.replace('@btn_rw@',_chart_btn_all_rw)
										.replace('@btn_rw_desc@',_chart_btn_all_rw_desc)
										.replace('@btn_s@',_chart_btn_all_s)
										.replace('@btn_s_desc@',_chart_btn_all_s_desc)
										.replace('@x_axis@',_chart_x_axis)
										.replace('@rw@',_chart_rw)
										.replace('@s@',_chart_s)
										.replace('@lt@',_chart_lt)
										.replace('@y_axis@',_chart_y_axis)
										.replace('@settag@',_chart_set_tag)
										.replace('@resettag@',_chart_reset_tag)
										.replace('@option@',_chart_option)
										.replace('@time@',_chart_period)
										.replace('@gorup_user@',_chart_group_user)
										.replace('@bookshelf@',_chart_bookshelf)
										.replace('@book@',_chart_book)
										.replace('@difficulty@',_chart_difficulty)
										.replace('@semester@',_chart_semester)
										.replace('@btn_add@',_chart_btn_add)
										.replace('@btn_reset@',_chart_btn_reset);
		//0:front, 1: manager, 2:webadmin
		params = {panel:_panel,site:2};
		$('#chart-panel').querychart(params);
	});
});