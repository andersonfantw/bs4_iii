$(document).ready(function(){
	var params={
		path:web_url+'/plugin/scoreimport/',
		script:['jquery-ui-1.8.12.custom.min','jquery.html5uploader','jquery.handsontable.full.min','scoreimport.class','html5uploader','handsontable','scoreimport'],
		css:['jquery-ui-1.8.12.custom','jquery.handsontable.full.min','scoreimport'],
		langJS:{'@title':'_convert_h3','@msg':'_convert_span','@talk':'_convert_talkbox_mwt_border_div','@catename@':'_catename'},
		langCSS:false,
		html:'scoreimport_assistant.html'
	};

	//loader example
	new Loader(params,function(panel){
		new ScoreImportAction(panel);
		new Html5Uploader();
	});
});