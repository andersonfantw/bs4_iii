var searchEnv={
	path:'search'
}

var SearchAPIHandler ={
	getAPIurl : function(cmd){
		return web_url+"/plugin/"+searchEnv.path+"/api/api.php?cmd="+cmd;
	},
	getQueueAPIurl : function(cmd){
		return web_url+"/api/queue/api.php?cmd="+cmd;
	},
  search : function(_i,_pcu,_pi,_pn,_year,_ty_from,_ty_to,_py,_pty,_pcof,_pc,_prt,_pwrf,returnFunc){
    var _msgHadler = new MsgAction('SearchAPIHandler');
    _ty = (_ty_from||_ty_to)?'year':_year;
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getQueueAPIurl("search"),
        data: {index:_i,pcu:_pcu,pi:_pi,pn:_pn,ty:_ty,tyf:_ty_from,tyt:_ty_to,py:_py,pty:_pty,pcof:_pcof,pc:_pc,prt:_prt,pwrf:_pwrf},
        success: function(data){
            _msgHadler.Log('search data='+JSON.stringify(data));
            _msgHadler.Log('Reveive search success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive search fail!');
        },
        async:false
    });
  },
  doNext : function(i,_pcu,_pi,_pn,_year,_ty_from,_ty_to,_py,_pty,_pcof,_pc,_prt,_pwrt,returnFunc){
  	setTimeout(function(){
	  	SearchAPIHandler.search(i,_pcu,_pi,_pn,_year,_ty_from,_ty_to,_py,_pty,_pcof,_pc,_prt,_pwrt,function(data){
	  		returnFunc(data);
	  	});
	  },2000);
  },
  validQuickSearch : function(_buid,returnFunc){
    var _msgHadler = new MsgAction('SearchAPIHandler');
    $.ajax({
        type: "post",
        dataType: "text",
        url: this.getAPIurl("validQuickSearch"),
        data: {type:'valid',buid:_buid},
        success: function(data){
            _msgHadler.Log('search data='+JSON.stringify(data));
            _msgHadler.Log('Reveive validQuickSearch success!');
            if(!StorageHandler.getQuickSearchStr(_buid)){
            	returnFunc(false);
            }else{
            	returnFunc(ToolsHandler.MD5(lzw_decode(StorageHandler.getQuickSearchStr(_buid)))==data);
            }
        },
        error: function(){
            _msgHadler.Log('Reveive validQuickSearch fail!');
        },
        async:false
    });
  },
  getQuickSearch : function(_buid,returnFunc){
    var _msgHadler = new MsgAction('SearchAPIHandler');
    SearchAPIHandler.validQuickSearch(_buid,function(v){
    	if(v){
    		returnFunc($.parseJSON(StorageHandler.getQuickSearchStr(_buid)));
    	}else{
	    	$.ajax({
	        type: "post",
	        dataType: "json",
	        url: SearchAPIHandler.getAPIurl("getQuickSearch"),
	        data: {buid:_buid},
	        success: function(data){
console.log(data);
	            _msgHadler.Log('search data='+JSON.stringify(data));
	            _msgHadler.Log('Reveive getQuickSearch success!');
	            returnFunc(data);
	        },
	        error: function(){
	            _msgHadler.Log('Reveive getQuickSearch fail!');
	        },
	        async:false
				});
			}
    });
  },
  setQuickSearch : function(_buid,_str,returnFunc){
    var _msgHadler = new MsgAction('SearchAPIHandler');
    $.ajax({
      type: "post",
      dataType: "json",
      url: this.getAPIurl("setQuickSearch"),
      data: {buid:_buid,str:_str},
      success: function(data){
          _msgHadler.Log('search data='+JSON.stringify(data));
          _msgHadler.Log('Reveive setQuickSearch success!');
          StorageHandler.setQuickSearchStr(_buid,_str);
          returnFunc();
      },
      error: function(){
          _msgHadler.Log('Reveive setQuickSearch fail!');
      },
      async:false
    });
  },
  validSearchItemSetting : function(_buid,returnFunc){
    var _msgHadler = new MsgAction('SearchAPIHandler');
    $.ajax({
        type: "post",
        dataType: "text",
        url: this.getAPIurl("validSearchItemSetting"),
        data: {type:'valid',buid:_buid},
        success: function(data){
            _msgHadler.Log('search data='+JSON.stringify(data));
            _msgHadler.Log('Reveive validSearchItemSetting success!');
            if(!StorageHandler.getSearchItemSettingStr(_buid)){
            	returnFunc(false);
            }else{
            	returnFunc(ToolsHandler.MD5(lzw_decode(StorageHandler.getSearchItemSettingStr(_buid)))==data);
            }
        },
        error: function(){
            _msgHadler.Log('Reveive validSearchItemSetting fail!');
        },
        async:false
    });
  },
  getSearchItemSetting : function(_buid,returnFunc){
    var _msgHadler = new MsgAction('SearchAPIHandler');
    SearchAPIHandler.validSearchItemSetting(_buid,function(v){
    	if(v){
    		returnFunc($.parseJSON(StorageHandler.getSearchItemSettingStr(_buid)));
    	}else{
	    	$.ajax({
	        type: "post",
	        dataType: "json",
	        url: SearchAPIHandler.getAPIurl("getSearchItemSetting"),
	        data: {buid:_buid},
	        success: function(data){
	            _msgHadler.Log('search data='+JSON.stringify(data));
	            _msgHadler.Log('Reveive getSearchItemSetting success!');
	            returnFunc(data);
	        },
	        error: function(){
	            _msgHadler.Log('Reveive getSearchItemSetting fail!');
	        },
	        async:false
				});
			}
    });
  },
  setSearchItemSetting : function(_buid,_str,returnFunc){
    var _msgHadler = new MsgAction('SearchAPIHandler');
    $.ajax({
      type: "post",
      dataType: "json",
      url: this.getAPIurl("setSearchItemSetting"),
      data: {buid:_buid,str:_str},
      success: function(data){
          _msgHadler.Log('search data='+JSON.stringify(data));
          _msgHadler.Log('Reveive setSearchItemSetting success!');
          StorageHandler.setSearchItemSettingStr(_buid,_str);
          returnFunc();
      },
      error: function(){
          _msgHadler.Log('Reveive setSearchItemSetting fail!');
      },
      async:false
    });
  },
  MM_openBrWindow : function(_theURL){
	APIHandler.loginCheck1(_theURL,function(theURL){
		ToolsHandler.MM_openBrWindow(theURL);
	});
  }
}
