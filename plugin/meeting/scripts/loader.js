$(document).ready(function(){
	var param={
		path:web_url+'/plugin/meeting/',
		script:['vcube.class.1.0','zoom.class.1.0'],
		css:[],
		langJS:false,
		langCSS:false,
		html:''
	};

	//loader example
	new Loader(param,function(){
	});
});
