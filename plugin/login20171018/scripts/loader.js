$(document).ready(function(){
	var loginEnv={
		redirect:false
	};

	var param={
		path:web_url+'/plugin/login/',
		script:[],
		css:['login'],
		langJS:false,
		langCSS:false,
		html:'login.html'
	};

	//loader example
	new Loader(param,function(panel){
		$('#header').prepend(panel);
		var _d = new DialogueAction();
		var _l = new LoginAction(true);
		if(typeof loginEnv.redirect == 'undefined' || loginEnv.redirect){
			login_callbacks.add(RedirectToUserPage);
			logout_callbacks.add(RedirectToListPage);
		}
	});

	function RedirectToUserPage(){
		exceptlist = ['regist'];
		APIHandler.loginCheck(function(data){
			arr = window.location.pathname.split('/');
			isExcept = -1;
			if(arr.length>1){
				isExcept = $.inArray(arr[1],exceptlist);
			}
			switch(data.type){
				case 'u':
		      if(document.location.href.indexOf('/user/')>0 || isExcept==0){
		      	document.location.reload();
		      }else{
		        document.location.href='/user/'+data.name+'/#/?cid=undefined';
		      }
		      break;
		     case 'a':
		      if(document.location.href.indexOf('/'+data.name+'/')>0 || isExcept==0){
		      	document.location.reload();
		      }else{
		        document.location.href='/'+data.name+'/';
		      }
		     	break;
			}
		});
	}
	function RedirectToListPage(){
		document.location.href='/';
	}
});
