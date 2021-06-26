/*****************************************
check device and load belong js, css, html
*****************************************/
// isbackend check
// device check

$(document).ready(function(){
	//order is matter
	var params={
		path:'/',
		script:['jquery.address-1.6.min','transcript'],
		css:['transcript'],
		langJS:{},
		langCSS:false,
		html:''
	};

	new Loader(params);

});
