$(document).ready(function(){
	var params={
		path:web_url+'/plugin/dataimport/',
		script:['jquery-ui-1.8.12.custom.min','jquery.html5uploader','jquery.handsontable.full.min','import.class','html5uploader','handsontable','import'],
		css:['jquery-ui-1.8.12.custom','jquery.handsontable.full.min','import'],
		langJS:{'@title':'_convert_h3','@msg':'_convert_span','@talk':'_convert_talkbox_mwt_border_div','@catename@':'_catename'},
		langCSS:false,
		html:'import_assistant.html'
	};

	//loader example
	new Loader(params,function(panel){
		new ImportAction(panel);
		new Html5Uploader();
	});
});
