$(document).ready(function(){
	var param={
		path:web_url+'/plugin/search/',
		script:['fulltextsearch.class','bootstrap.min','search.class.1.0','index'],
		css:['search','bootstrap.min','common','component-chosen','Highlight-Blue'],
		langJS:false,
		langCSS:false,
		html:''
	};
	var param_login={
		path:web_url+'/plugin/login/',
		script:['login.class.1.0'],
		css:[],
		langJS:false,
		langCSS:false,
		html:''
	};
	var param_tag={
		path:web_url+'/plugin/tag/',
		script:['tag.class.1.0'],
		css:['queue'],
		langJS:false,
		langCSS:false,
		html:''
	};
	//loader example
	$('body').hide();
	new Loader([param_tag,param_login,param],function(_panel){
		setTimeout(function(){
			$('body').show();
		},2000);
	});
});
