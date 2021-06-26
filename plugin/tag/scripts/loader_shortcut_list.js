$(document).ready(function(){
	var param={
		path:web_url+'/plugin/tag/',
		script:['tag.class.1.0','jquery.lnettag.1.1','shortcut'],
		css:['jquery.lnettag.shortcut','lnet-tag'],
		langJS:{'enable':true},
		langCSS:false,
		html:''
	};
	
	new Loader(param,function(){});
});
