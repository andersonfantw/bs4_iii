$(document).ready(function(){
	var param={
		path:web_url+'/plugin/search/',
		script:['search.class.1.0','fulltextsearch.class','jquery.chosen.min','bootstrap.min','bootstrapValidator.min','zh_TW','advsearch'],
		css:['advsearch','bootstrap.min','bootstrapValidator.min','common','Highlight-Blue','component-chosen'],
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
