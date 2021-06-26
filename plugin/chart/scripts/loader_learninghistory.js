$(document).ready(function(){
	var param={
		path:web_url+'/plugin/chart/',
		script:['jquery.canvasjs.min','chart.class.1.0','jquery.chart.1.0'],
		css:['jquery.chart.1.0'],
		langJS:{},
		langCSS:false,
		html:''
	};

	//loader example
	new Loader(param,function(panel){
		keyValues = [];
		if($.getQuery('id')!=''){
			keyValues = ToolsHandler.decodeURL('id');
		}
		params = {
			mode: lnetchartEnum.user_learning_history
		};
		if(keyValues['userid']){
			params = {
				mode: lnetchartEnum.user_learning_history,
				userid: keyValues['userid']
			};
		}
		if(keyValues['gid']){
			params = {
				mode: lnetchartEnum.user_learning_history,
				gid: keyValues['gid']
			};
		}
		$('#chart-panel').LnetChart(params);
	});
});