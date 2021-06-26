$(document).ready(function(){
/*
	var params={
		path:web_url+'/plugin/tag/',
		script:['jquery-ui-1.8.12.custom.min','jquery.html5uploader','html5uploader','tagimport.class','tagimport','jquery.handsontable.full.min','handsontable'],
		css:['jquery-ui-1.8.12.custom','tagimport','jquery.handsontable.full.min'],
		langJS:{'@title':'_convert_h3','@msg':'_convert_span','@talk':'_convert_talkbox_mwt_border_div','@catename@':'_catename'},
		langCSS:false,
		html:'tagimport_assistant.html'
	};

	//loader example
	new Loader(params,function(panel){
		new TagImportAction(panel);
		new Html5Uploader();
	});
*/
	var params={
		path:web_url+'/plugin/tag/',
		script:['tag.class.1.0','jquery.html5uploader','html5uploader','tagimport.class','tagimport','jquery.handsontable.full.min','jquery.handsontable.doubleclick.min','handsontable'],
		css:['tagimport','jquery.handsontable.full.min'],
		langJS:{'@title':'_convert_h3','@msg':'_convert_span','@talk':'_convert_talkbox_mwt_border_div','@catename@':'_catename'},
		langCSS:false,
		html:'tagimport_assistant.html'
	};

	//loader example
	new Loader(params,function(panel){
		new TagImportAction(panel);
		new Html5Uploader();
	});
});