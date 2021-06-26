var searchEnv={
	path:'search'
}

var SearchAPIHandler ={
	getAPIurl : function(cmd){
		return web_url+"/plugin/"+searchEnv.path+"/api/api.php?cmd="+cmd;
	},
	getSearchAPIurl : function(){
		return web_url+"/plugin/"+searchEnv.path+"/api/EBookSearch.php";
	},
	getQueueAPIurl : function(cmd){
		return web_url+"/api/queue/api.php?cmd="+cmd;
	},
	search : function(_page,_orderby,_fulltext,_pcu,_pi,_pn,_year,_ty_from,_ty_to,_py,_pty,_pcof,_pc,_prt,_pwrf,returnFunc){
		var _msgHadler = new MsgAction('SearchAPIHandler');
		var tags = ['total_count','year','pn','pi','pcu','pc','pwrf'];
		var _trim
		
		_legnth = 10;
		_start = (_page-1) * _legnth; //page 1 ~
    _ty = (_ty_from||_ty_to)?'year':_year;
    if(tags.indexOf(_orderby)==-1) _orderby = tags[0];
    if(_fulltext>2){
  		_arr = [_fulltext];
  	}
    if(_fulltext.indexOf('(')==-1 && _fulltext.indexOf(')')==-1 && _fulltext.indexOf('|')==-1 && _fulltext.indexOf('&')==-1){
    	_arr = _fulltext.replace(' ',',&,').split(',');
    }else{
	    _str = _fulltext.replace('(',',(,')
	    					.replace(')',',),')
	    					.replace('&',',&,')
	    					.replace('|',',|,');
	    _arr = _str.substr(1,_str.length-2).split(',');
  	}

  	_f=0;_operands=[];
  	for(i=0;i<_arr.length;i++){
  		_s = _arr[i].trim();
  		_arr[i] = _s;
  		if(_s=='(') _f++;
  		if(_s==')') _f--;
  		if(_operands.indexOf(_s)==-1) _operands.push(_s);
  	}
  	if(_f!=0){
  		_msgHadler.Log('Search expration fail!');
  	}else{
	    this.synonyms(_operands,function(data){
	    	for(i=0;i<data.length;i++){
	    		for(j=0;j<_arr.length;j++){
	    			if(_arr[j]==data[i].k){
	    				_arr[j]='('+_arr[j]+'|'+data[i].s.replace(',','|')+')';
	    			}
	    		}
	    	}
		    $.ajax({
		        type: "post",
		        dataType: "json",
		        url: this.getSearchAPIurl(),
		        data: {token:'token',total_num_info:1,start:_start,length:_length,kw_exp:_fulltext,kw_operands:_operands,pcu:_pcu,pi:_pi,pn:_pn,ty:_ty,tyf:_ty_from,tyt:_ty_to,py:_py,pty:_pty,pcof:_pcof,pc:_pc,prt:_prt,pwrf:_pwrf},
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
	    });
  	}
	},
	synonyms : function(_keywords,returnFunc){
		var str=StorageHandler.getSynonyms();
		str.split(';');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("synonyms"),
        data: {keywords:_keywords},
        success: function(data){
        		//data = [{k:'keyword',s:'a,b,c,d'},{}....]
            _msgHadler.Log('synonyms data='+JSON.stringify(data));
            _msgHadler.Log('Reveive synonyms success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive synonyms fail!');
        },
        async:false
    });
	},
	validAllSynonyms : function(returnFunc){
    var _msgHadler = new MsgAction('SearchAPIHandler');
    $.ajax({
        type: "post",
        dataType: "text",
        url: this.getAPIurl("validAllSynonyms"),
        data: {type:'valid',buid:_buid},
        success: function(data){
            _msgHadler.Log('search data='+JSON.stringify(data));
            _msgHadler.Log('Reveive validAllSynonyms success!');
            if(!StorageHandler.getSynonyms()){
            	returnFunc(false);
            }else{
            	returnFunc(ToolsHandler.MD5(lzw_decode(StorageHandler.getSynonyms()))==data);
            }
        },
        error: function(){
            _msgHadler.Log('Reveive validAllSynonyms fail!');
        },
        async:false
    });
	},
	getAllSynonyms : function(returnFunc){
    var _msgHadler = new MsgAction('SearchAPIHandler');
    SearchAPIHandler.validAllSynonyms(function(v){
    	if(v){
    			returnFunc($.parseJSON(StorageHandler.getSynonyms()));
    	}else{
		    $.ajax({
		        type: "post",
		        dataType: "json",
		        url: this.getAPIurl("getAllSynonyms"),
		        success: function(data){
		        		//data = [{k:'keyword',s:'keyword,a,b,c,d'},.....]
		            _msgHadler.Log('getAllSynonyms data='+JSON.stringify(data));
		            _msgHadler.Log('Reveive getAllSynonyms success!');
		            StorageHandler.setSynonyms(JSON.stringify(data));
		            returnFunc(data);
		        },
		        error: function(){
		            _msgHadler.Log('Reveive getAllSynonyms fail!');
		        },
		        async:false
		    });
		  }
	  });
	},
	addQuickSearch : function(_name,_shortname,_content,returnFunc){
    var _msgHadler = new MsgAction('SearchAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("addQuickSearch"),
        data: {name:_name,shortname:_shortname,content:_content},
        success: function(data){
        		//data = [{k:'keyword',s:'keyword,a,b,c,d'},.....]
            _msgHadler.Log('addQuickSearch data='+JSON.stringify(data));
            _msgHadler.Log('Reveive addQuickSearch success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive getAllSynonyms fail!');
        },
        async:false
    });
	},
	updateQuickSearch : function(_id,_name,_shortname,_content,returnFunc){
    var _msgHadler = new MsgAction('SearchAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("updateQuickSearch"),
        data: {id:_id,name:_name,shortname:_shortname,content:_content},
        success: function(data){
        		//data = [{k:'keyword',s:'keyword,a,b,c,d'},.....]
            _msgHadler.Log('updateQuickSearch data='+JSON.stringify(data));
            _msgHadler.Log('Reveive updateQuickSearch success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive getAllSynonyms fail!');
        },
        async:false
    });
	},
	delQuickSearch : function(_id,returnFunc){
    var _msgHadler = new MsgAction('SearchAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("delQuickSearch"),
        data: {id:_id},
        success: function(data){
        		//data = [{k:'keyword',s:'keyword,a,b,c,d'},.....]
            _msgHadler.Log('delQuickSearch data='+JSON.stringify(data));
            _msgHadler.Log('Reveive delQuickSearch success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive getAllSynonyms fail!');
        },
        async:false
    });
	},
	checkQuickSearchName : function(_id,_name,returnFunc){
    var _msgHadler = new MsgAction('SearchAPIHandler');
  	$.ajax({
      type: "post",
      dataType: "json",
      url: SearchAPIHandler.getAPIurl("checkQuickSearchName"),
      data: {id:_id,name:_name},
      success: function(data){
          _msgHadler.Log('search data='+JSON.stringify(data));
          _msgHadler.Log('Reveive checkQuickSearchName success!');
          returnFunc(data);
      },
      error: function(){
          _msgHadler.Log('Reveive checkQuickSearchName fail!');
      },
      async:false
		});
	},
  validQuickSearch : function(_buid,returnFunc){
    var _msgHadler = new MsgAction('SearchAPIHandler');
    $.ajax({
        type: "post",
        dataType: "text",
        url: this.getAPIurl("validQuickSearch"),
        data: {type:'valid'},
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
	        success: function(data){
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
  OpenBook : function(_theURL){
		APIHandler.loginCheck1(_theURL,function(theURL){
			winName='';
			popupwidth = screen.width;
			popupheight = screen.height;
      if (navigator.userAgent.indexOf('Safari') >= 0) {
          var _newWin = window.open(theURL,winName,'width=' + (popupwidth + 1) + ',height=' + (popupheight + 1) + ',resizable=yes');
      } else {
          var _newWin = window.open(theURL,winName,'width=' + popupwidth + ',height=' + popupheight + ',resizable=yes');
      }
      if(!_newWin || _newWin.closed || typeof _newWin.closed=='undefined'){
      	alert(systemEnv.Language.popblockerwarning);
      }
		});
  }
}
