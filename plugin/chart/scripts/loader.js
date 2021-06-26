$(document).ready(function(){
	var param={
		path:web_url+'/plugin/chart/',
		script:['jquery.canvasjs.min','chart.class.1.0','jquery.chart.1.0'],
		css:['jquery.chart.1.0'],
		langJS:{},
		langCSS:false,
		html:'chart_summary.html'
	};

	//loader example
	new Loader(param,function(panel){
		$('#main-wrapper').append(panel);
		$('#chart-panel .bs-panel').LnetChart({mode: lnetchartEnum.bs_summary});
		$('#chart-panel .book-panel').LnetChart({mode: lnetchartEnum.book_summary});
		$('#chart-panel .user-panel').LnetChart({mode: lnetchartEnum.user_summary});
		$('#chart-panel .tag-panel').LnetChart({mode: lnetchartEnum.tag_summary});
	});
});