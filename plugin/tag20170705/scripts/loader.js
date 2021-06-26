$(document).ready(function(){
	var param={
		path:web_url+'/plugin/tag/',
		script:['tag.class.1.0','jquery.lnettag.shortcut'],
		css:['jquery.lnettag.shortcut','lnet-tag'],
		langJS:{'enable':true},
		langCSS:false,
		html:''
	};

	//loader example
	new Loader(param,function(){
		$('#content-left').LnetTagShortcut({});
	});
});
