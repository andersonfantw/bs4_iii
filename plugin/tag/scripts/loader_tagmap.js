$(document).ready(function(){
	var param={
		path:web_url+'/plugin/tag/',
		script:['tag.class.1.0','jquery.lnettag.map.1.0'],
		css:['lnet-tag','tagmap'],
		langJS:{'enable':true},
		langCSS:false,
		html:''
	};

	//loader example
	new Loader(param,function(){
		params={};
		$('#tagmap').LnetTagMap(params);
	});
});
