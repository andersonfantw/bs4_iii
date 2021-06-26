$(document).ready(function(){
	var params={
		path:web_url+'/plugin/tag/',
		script:['jquery-ui-1.8.12.custom.min','jquery.html5uploader','html5uploader','tagimport.class','tagimport'],
		css:['jquery-ui-1.8.12.custom','tagimport'],
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