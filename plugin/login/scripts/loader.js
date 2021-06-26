$(document).ready(function(){
	var param={
		path:web_url+'/plugin/login/',
		script:['login.class.1.0','jquery.login.1.0'],
		css:['login'],
		langJS:false,
		langCSS:false,
		html:'login.html'
	};

	//loader example
	new Loader(param,function(panel){
		$('#body').prepend(panel);
		$('#body').login(loginEnv);
		var _d = new DialogueAction();
		//var _l = new LoginAction();
	});
});
