/*****************************************
check device and load belong js, css, html
*****************************************/
// isbackend check
// device check

$(document).ready(function(){
	//order is matter
	var params={
		path:'/',
		script:['jquery-ui-1.11.4.custom.min','jquery.validate.min'],
		css:['jquery-ui-1.11.4.custom.min','style','regist'],
		langJS:{},
		langCSS:false,
		html:''
	};

	new Loader(params);

});
