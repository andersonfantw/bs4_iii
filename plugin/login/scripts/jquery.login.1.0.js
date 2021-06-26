(function ($) {
$.fn.login = function(options){
	var $this = this;

	var settings = {
		loginPanel : '#buttonlogin',
		show : true,
		header : false,
		redirect : false
	};

  if (options) {
      $.extend(settings, options);
  }

	_init();
  function _init(){
		chkSingleLogin();
		setInterval(chkSingleLogin, 120000);

    if(bookEnv.InitJSON.member || settings.show){
        //畫面設定
        //登入/登出按鈕設定
        if(bookEnv.InitJSON.buid>0){
            $(settings.loginPanel).addClass('logout');
        }else{
            $(settings.loginPanel).addClass('login');
        }
        $(settings.loginPanel).show();
    }

    //按鈕功能列的登入功能開啟登入畫面
    $this.find('.btnlogin').click(function(){
        showLogin();
    });

    //我的書櫃按鈕
    $this.find('.btnmybs').click(function(){
        bookEnv.currentCateName = systemEnv.Language.mybs+'-'+ MenuHandler.getSelectedSubMenuText();
	    	bookEnv.currentCateId = MenuHandler.getSelectedSubMenuID();
        bookEnv.bookshelfSourceMode = 'my';
        _msgHandler.Log('LoginAction: bookshelfSourceMode='+bookEnv.bookshelfSourceMode);
        bookEnv.currentCateId = $('#currentCateId').val();
        BooksHandler.bindBookshelf('my','');
	    	BooksHandler.viewMyBsMode();
    });

    //公開的書櫃按鈕
    $this.find('.btnopenbs').click(function(){
        bookEnv.currentCateName = MenuHandler.getSelectedSubMenuText();
        bookEnv.currentCateId = MenuHandler.getSelectedSubMenuID();
        bookEnv.bookshelfSourceMode = 'open';
        _msgHandler.Log('LoginAction: bookshelfSourceMode='+bookEnv.bookshelfSourceMode);
        bookEnv.currentCateId = $('#currentCateId').val();
        BooksHandler.bindBookshelf(bookEnv.currentCateId,bookEnv.currentCateName);
    		BooksHandler.viewOpenBsMode();
    });

    $this.find('#login input').keydown(function(event){
        if(event.which==13){
            $this.find('#login .button div.submit').click();
        }
    });

		$this.find('#login .button div.forget').click(function(){
			//load forget password panel, bind event
			showForget()
		});

		function _forget_submit(){
			if($this.find("#forget_account").val()==''){
            alert('請輸入帳號!');
            return;
			}
			if($this.find("#forget_email").val()==''){
            alert('請輸入Email!');
            return;
			}
			$this.find('#forget .button div.submit').off();
			_acc = $this.find("#forget_account").val();
			_email = $this.find("#forget_email").val();
			APIHandler.forget(_acc,_email,function(data){
				switch(data.code){
					case '200':	//success
						alert(data.msg);
						$(selector_dialogue_bg).click();
						break;
					case '':
					default:
						alert(data.msg);
						break;
				}
				$this.find('#forget .button div.submit').on('click',_forget_submit);
			});
		}
		$this.find('#forget .button div.submit').click(function(){
			_forget_submit();
		});
    $this.find('#login .button div.submit').click(function(){
        if($this.find('#login h3 input').eq(0).val()==''){
            alert('請輸入帳號!');
            return;
        }
        if($this.find('#login h3 input').eq(1).val()==''){
            alert('請輸入密碼!');
            return;
        }

        //admin login, don't support loginsAndOpenBook
        _login_backend_prefix = '@';
        _pid = $this.find('#login input[type=text]').val();
        if(_pid.indexOf(_login_backend_prefix)==0){
        	_pid = _pid.substr(_login_backend_prefix.length);
        	_pwd = $('#login input[type=password]').val();
        	APIHandler.loginBackend(_pid,_pwd,function(data){
						if(APIHandler.isSuccess(data.code)){
							$(selector_dialogue_bg).click();
							LoginHandler.viewLoginMode();
							login_callbacks.fire();
							if(settings.header){
								TemplateHandler.attachHeader_adminlogin();
							}
							if(settings.redirect){
								RedirectToUserPage();
							}
						}else{
							alert(data.msg);
						}
        	});
        }else{
          switch(bookEnv.InitJSON.member_system){
    	        case 'self':
            	    _pwd = ToolsHandler.MD5($this.find('#login input[type=password]').val());
                  break;
    	        default:
            	    _pwd = $this.find('#login input[type=password]').val();
                  break;
    	    }

          //由登入註記判斷登入模式
    	    if(bookEnv.loginAndOpenBook){
            	LoginHandler.loginAndOpenBook(_pid,_pwd,function(){
	            	login_callbacks.fire();
								if(settings.header){
									TemplateHandler.attachHeader_userlogin();
								}
								if(settings.redirect){
									RedirectToUserPage();
								} 
	            });
          }else{
    	        LoginHandler.login(_pid,_pwd,function(){
    	        	login_callbacks.fire();
								if(settings.header){
									TemplateHandler.attachHeader_userlogin();
								}
								if(settings.redirect){
									RedirectToUserPage();
								}    	        	
    	        });
          }

    	    if(bookEnv.bookshelfSourceMode=='my'){
            	bookEnv.currentCateName = systemEnv.Language.mybs+'-' + bookEnv.currentCateName;
              new BooksAction();
    	    }
  			}
    });

		//按鈕功能列的登出功能登出帳號
    $this.find('.btnlogout > div.logout').click(function(){
    	logout_callbacks.fire();
			if(settings.header){
				TemplateHandler.attachHeader_adminlogin();
			}
			if(settings.redirect){
				RedirectToUserPage();
			}
			LoginHandler.bsLogout();
    });
		//list lougout
    $this.find('.btnlogout > div.listlogout').click(function(){
    	logout_callbacks.fire();
			if(settings.header){
				TemplateHandler.removeHeader();
			}
			if(settings.redirect){
				RedirectToListPage();
			}
			LoginHandler.logout();
    });	
  }

	function showLogin(){
		(new MsgAction('this')).Log('showLogin');
		$(selector_book_content).removeClass('show');
		DialogueHandler.showMask();
		$(selector_login).addClass('show');
		DialogueHandler.center(selector_login);
		$this.find('#login h3 input').eq(0).focus();
  }
  function showForget(){
    (new MsgAction('this')).Log('showForget');
    $(selector_book_content).removeClass('show');
    $(selector_login).removeClass('show');
    DialogueHandler.showMask();
    $(selector_forget).addClass('show');
    DialogueHandler.center(selector_forget);
    $this.find('#forget h3 input').eq(0).focus();
  }
  function chkSingleLogin(){
  	APIHandler.loginCheck(function(data){
  		(new MsgAction('this')).Log('chkSingleLogin');
  		switch(data.type){
  			case 'a':
					if(settings.header){
						TemplateHandler.attachHeader_adminlogin();
					}
  				LoginHandler.viewLoginMode();
  				break;
  			case 'u':
  				if(data.id>0){
		    		APIHandler.chkSingleLogin(function(valid){
	  	  			if(valid){
  	  					if(settings.header){
  	  						TemplateHandler.attachHeader_userlogin();
  	  					}
	  	  			}else{
	  	  				alert(systemEnv.Language.login_in_different_place);
	  	  				if(ToolsHandler.isMobile()){
	  	  					document.location.href='/logout/';
	  	  				}else{
	  	  					LoginHandler.bsLogout();
	  	  					if(settings.header){
	  	  						TemplateHandler.removeHeader();
	  	  					}
	  	  					if(settings.redirect){
	  	  						 RedirectToListPage();
	  	  					}
	  	  					logout_callbacks.fire();
	  	  				}
	  	  			}
	    			});
  				}
  				break;
  			case '-':
  				TemplateHandler.removeHeader();
  				if($(settings.loginPanel).hasClass('logout')){
  					LoginHandler.logout();
  				}
  				break;
  		}
  	});
  }
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

};
})(jQuery);