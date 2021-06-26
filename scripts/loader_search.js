/*****************************************
check device and load belong js, css, html
*****************************************/
// isbackend check
// device check
var settings = {
	ExpiredList:false,
	buid:-1,
	uid:-1
};
var loginEnv = {
	show : false,
	header : false,
	redirect : false
};
$(document).ready(function(){
	//order is matter
	var params={
		path:'/',
		script:['jquery.address-1.6.min','jquery.validate.min','bookshelf.2.5'],
		css:[],
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

$(document).ready(function(){
	$("form").validate({
		rules: {
			pwd1: {
				required: true,
				minlength: 6,
				maxlength: 20
			},
			pwd2: {
				required: true,
				minlength: 6,
				maxlength: 20,
				equalTo: '#pwd1'
			}
		},
		messages: {
			pwd1: {
				required: "Required!",
				minlength: "Please enter your password between 6~20 characters.",
				maxlength: "Please enter your password between 6~20 characters."
			},
			pwd2: {
				required: "Required!",
				minlength: "Please enter your password between 6~20 characters.",
				maxlength: "Please enter your password between 6~20 characters.",
				equalTo: "Password is different!"
			}
		}
	});
});
