/*****************************************
check device and load belong js, css, html
*****************************************/
// isbackend check
// device check
$(document).ready(function(){
	//order is matter
	var params={
		path:'/',
		script:['jquery-ui-1.11.4.custom.min','jquery.address-1.6.min','jquery.touchwipe.min','jquery.validate.min','bookshelf.2.4'],
		css:['jquery-ui-1.11.4.custom.min','style','regist'],
		langJS:{},
		langCSS:false,
		html:''
	};

	new Loader(params);

	var installed_plugin = ['login'];

	//load installed plugin files(css & js)
	for(var i=0;i<installed_plugin.length;i++){
		var params={
			path:web_url + '/plugin/' + installed_plugin[i] + '/',
			script:['loader'],
			css:[],
			langJS:{},
			langCSS:false,
			html:''
		};
		new Loader(params);
	}
});
