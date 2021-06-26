/*****************************************
 * check device and load belong js, css, html
 * *****************************************/

$(document).ready(function(){
	var params={
		path:'/',
		script:['jquery.address-1.6.min','jquery.touchwipe.min','bookshelf.2.4','jquery.BookshelfList.1.0'],
		css:['style'],
		langJS:{},
		langCSS:false,
		html:''
	};

	new Loader(params,function(){
		$('#bslist').BookshelfList();
	});

	var installed_plugin = ['login','seminar'];

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
