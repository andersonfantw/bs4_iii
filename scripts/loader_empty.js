/*****************************************
check device and load belong js, css, html
*****************************************/
// isbackend check
// device check

$(document).ready(function(){
  var _isBackend=(document.location.href.match(/\/backend\//g)!=null);
	if(_isBackend){
		/*if(ToolsHandler.isMobile){
		}else{
		}*/
	}else{
		/*
		if(ToolsHandler.isMobile){
		}else{
		}*/

		//order is matter
		var params={
			path:'/',
			script:['jquery.address-1.6.min','jquery.touchwipe.min','bookshelf.2.4','bookshelf_init'],
			css:['style'],
			langJS:{},
			langCSS:false,
			html:''
		};
	}

	new Loader(params);
});
