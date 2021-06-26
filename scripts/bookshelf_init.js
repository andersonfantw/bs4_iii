objCurrentCateId=null;
objCurrentBookId=null;

$(window).load(function(){
    objCurrentCateId = document.getElementById('currentCateId');
    objCurrentBookId = document.getElementById('currentBookId');
    var motion = new MotionAction();
    var css = new StyleAction();
		var _d = new DialogueAction();
		var _l = new LoginAction();
    var _m = new MenuAction();
    var _b = new BooksAction();
    var _s = new SearchAction();
    var _k = new KeyboardAction();

    //設定config中的設定值
    if(bookEnv.InitJSON.headerlink!=''){
        $(selector_header).css('cursor','pointer');
        $(selector_header).click(function(){
            //ToolsHandler.MM_openBrWindow(systemEnv.userDefinedSetting.headerlink,'header');
            window.open(bookEnv.InitJSON.headerlink,'header');
        });
    }
    
//	  if(systemEnv.userDefinedSetting.footer!=''){
//        $(selector_footer).html(systemEnv.userDefinedSetting.footer.replace(/\n/gi,'<br />').replace(/\*RED/,'<font color="red">').replace(/RED\*/,'</font>'));
//    }

});