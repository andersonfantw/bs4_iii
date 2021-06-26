$(document).ready(function(){
	var param={
		path:web_url+'/plugin/seminar/',
		script:['VCubeSeminar.class.1.0'],
		css:[],
		langJS:false,
		langCSS:false,
		html:''
	};

	//loader example
	new Loader(param,function(panel){
	});
});

