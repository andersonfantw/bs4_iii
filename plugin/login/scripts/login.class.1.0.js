var LoginHandler = {
	login : function(_pid, _pwd, returnFunc){
    (new MsgAction('this')).Log('login');
    APIHandler.login(_pid,_pwd,function(){
      if(APIHandler.isSuccess(bookEnv.requestCode)){
        LoginHandler.viewLoginMode();
        
        //Àx¦s±b±K
        //ToolsHandler.setCookie('name',_pid);
        //ToolsHandler.setCookie('pwd',_pwd);
        bookEnv.pid = _pid;
        bookEnv.pwd = _pwd;

				//TemplateHandler.attachHeader_userlogin();
				if(typeof webChat=='object'){
					GVHandler.login();
					GVHandler.bindGiantview();
				}
				returnFunc();
      }else{
				alert(bookEnv.requestMsg);
      }
    });
    $(selector_dialogue_bg).click();
	},
	loginAndOpenBook : function(_pid,_pwd, returnFunc){
    (new MsgAction('this')).Log('loginAndOpenBook');
    APIHandler.loginAndOpenBook(_pid,_pwd,function(data){
      if(APIHandler.isSuccess(bookEnv.requestCode)){
        LoginHandler.viewLoginMode();

        //Àx¦s±b±K
				bookEnv.pid = _pid;
				bookEnv.pwd = _pwd;

        if(typeof webChat=='object'){
          GVHandler.login();
          GVHandler.bindGiantview();
        }
        BooksHandler.openBook(bookEnv.requestMsg);
        returnFunc();
      }else{
      	//1. click open(user not belong cate)
      	//2. login as a user
      	//3. login
      	//4. show message: user not belong cate
      	APIHandler.loginCheck(function(data1){
      		switch((data1.type)){
      			case 'u':
	        		LoginHandler.viewLoginMode();
	        		TemplateHandler.attachHeader_userlogin();
        			break;
						default:
							break;
        	}
      	});
      	alert(data.msg);
      }
    });
    $(selector_dialogue_bg).click();
	},
	logout : function(){
		APIHandler.logout();
		if(typeof webChat=='object') GVHandler.logout();
		bookEnv.pid='';
		bookEnv.pwd='';
		bookEnv.requestCode='';
		bookEnv.requestMsg='';
		LoginHandler.viewLogoutMode();
		TemplateHandler.removeHeader();
		APIHandler.isBackendLogin(function(data){
			if(data){
				APIHandler.logoutBackend();
			}
		});
	},
	bsLogout : function(){
		LoginHandler.logout();
		bookEnv.bookshelfSourceMode = 'open';
		new BooksAction();
  },
  viewLoginMode : function(){
    (new MsgAction('this')).Log('viewLoginMode');
    //¤Á´«¦Üµn¥X«ö¶s
    $('#buttonlogin').removeClass('login').addClass('logout');
	},
	viewLogoutMode : function(){
    (new MsgAction('this')).Log('viewLogoutMode');
    //¤Á´«¦Üµn¤J«ö¶s
    $('#buttonlogin').removeClass('logout').addClass('login');
  },
  chkSingleLogin: function(){
console.log('chkSingleLogin');
		APIHandler.loginCheck(function(data){
			(new MsgAction('this')).Log('chkSingleLogin');
			switch(data.type){
				case 'a':
					break;
				case 'u':
					if(data.id>0){
						settings._buid = data.id;
		    		APIHandler.chkSingleLogin(function(valid){
	  	  			if(valid){
	  	  			}else{
	  	  				alert(systemEnv.Language.login_in_different_place);
	  	  				if(ToolsHandler.isMobile()){
	  	  					document.location.href='/logout/';
	  	  				}else{
	  	  					LoginHandler.logout();
	  	  					logout_callbacks.fire();
	  	  					document.location.href='/signout/';
	  	  				}
	  	  			}
	    			});
					}
					break;
				case '-':
					LoginHandler.logout();
					document.location.href='/signout/';
					break;
			}
		});
	}
}
