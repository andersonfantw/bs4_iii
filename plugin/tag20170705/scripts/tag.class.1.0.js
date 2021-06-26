var lnettagEnum={
	choose_tag:1,
	create_system_tag:2,
	viewer:4,
	shortcut:8,
	dropdownlist:16,
	setddl:32,
	tagquiz_itutor:64,
	tagquiz_infoacer:128
}

var lnettagEnv={
	path:'tag'
}

var TagAPIHandler ={
	getAPIurl : function(cmd){
		return web_url+"/plugin/"+lnettagEnv.path+"/api/api.php?cmd="+cmd;
	},
  getBookTag : function(_bid,returnFunc){
      var _msgHadler = new MsgAction('TagAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("getBookTag"),
          data: {bid:_bid}, 
          success: function(data){
              _msgHadler.Log('getBookTag data='+JSON.stringify(data));
              _msgHadler.Log('Reveive getBookTag success!');
              returnFunc(data);
          },
          error: function(){
              _msgHadler.Log('Reveive getBookTag fail!');
          },
          async:false
      });
  },
  getSuggestByDropDownList : function(_bid,_like,returnFunc){
      var _msgHadler = new MsgAction('TagAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("getSuggestByDropDownList"),
          data: {bid:_bid,like:_like}, 
          success: function(data){
              _msgHadler.Log('getSuggestByDropDownList data='+JSON.stringify(data));
              _msgHadler.Log('Reveive getSuggestByDropDownList success!');
              returnFunc(data);
          },
          error: function(){
              _msgHadler.Log('Reveive getSuggestByDropDownList fail!');
          },
          async:false
      });
  },
  getSuggestByChoosePanel : function(_bid,_like,_path,returnFunc){
      var _msgHadler = new MsgAction('TagAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("getSuggestByChoosePanel"),
          data: {bid:_bid,like:_like,path:_path}, 
          success: function(data){
              _msgHadler.Log('getSuggestByChoosePanel data='+JSON.stringify(data));
              _msgHadler.Log('Reveive getSuggestByChoosePanel success!');
              returnFunc(data);
          },
          error: function(){
              _msgHadler.Log('Reveive getSuggestByChoosePanel fail!');
          },
          async:false
      });
  },
  getSuggestByChoosePanelByShortcut : function(_tsid,_seq,_like,_path,returnFunc){
      var _msgHadler = new MsgAction('TagAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("getSuggestByChoosePanelByShortcut"),
          data: {tsid:_tsid,seq:_seq,like:_like,path:_path}, 
          success: function(data){
              _msgHadler.Log('getSuggestByChoosePanelByShortcut data='+JSON.stringify(data));
              _msgHadler.Log('Reveive getSuggestByChoosePanelByShortcut success!');
              returnFunc(data);
          },
          error: function(){
              _msgHadler.Log('Reveive getSuggestByChoosePanelByShortcut fail!');
          },
          async:false
      });
  },
  getSuggestSystemTagByChoosePanel : function(_path,returnFunc){
      var _msgHadler = new MsgAction('TagAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("getSuggestSystemTagByChoosePanel"),
          data: {path:_path}, 
          success: function(data){
              _msgHadler.Log('getSuggestSystemTagByChoosePanel data='+JSON.stringify(data));
              _msgHadler.Log('Reveive getSuggestSystemTagByChoosePanel success!');
              returnFunc(data);
          },
          error: function(){
              _msgHadler.Log('Reveive getSuggestSystemTagByChoosePanel fail!');
          },
          async:false
      });
  },
  getTagPath : function(returnFunc){
		var _msgHadler = new MsgAction('TagAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("getTagPath"),
          data: {bid:_bid,like:_like,path:_path}, 
          success: function(data){
              _msgHadler.Log('getTagPath data='+JSON.stringify(data));
              _msgHadler.Log('Reveive getTagPath success!');
              returnFunc(data);
          },
          error: function(){
              _msgHadler.Log('Reveive getTagPath fail!');
          },
          async:false
      });
  },
  getBooksByTSID : function(_bsid,_tsid,_buid,returnFunc){
		var _msgHadler = new MsgAction('TagAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("getBooksByTSID"),
          data: {bsid:_bsid,tsid:_tsid,bu_id:_buid},
          success: function(data){
              _msgHadler.Log('getBooksByTSID data='+JSON.stringify(data));
              _msgHadler.Log('Reveive getBooksByTSID success!');
              returnFunc(data);
          },
          error: function(){
              _msgHadler.Log('Reveive getBooksByTSID fail!');
          },
          async:false
      });
  },
  addTag : function(_path,_key,_val,_type,returnFunc){
      var _msgHadler = new MsgAction('TagAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("addTag"),
          data: {path:_path,key:_key,val:_val,type:_type}, 
          success: function(data){
              _msgHadler.Log('setTag data='+JSON.stringify(data));
              _msgHadler.Log('Reveive setTag success!');
             	returnFunc(data);
          },
          error: function(){
              _msgHadler.Log('Reveive setTag fail!');
              returnFunc(-1);
          },
          async:false
      });
  },
  setTag : function(_bid,_path,_key,_val,returnFunc){
      var _msgHadler = new MsgAction('TagAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("setTag"),
          data: {bid:_bid,path:_path,key:_key,val:_val}, 
          success: function(data){
              _msgHadler.Log('setTag data='+JSON.stringify(data));
              _msgHadler.Log('Reveive setTag success!');
              returnFunc(data);
          },
          error: function(){ue;
              _msgHadler.Log('Reveive setTag fail!');
          },
          async:false
      });
  },
  setShortcutTag : function(_tsid,_seq,_tid,returnFunc){
      var _msgHadler = new MsgAction('TagAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("setShortcutTag"),
          data: {tsid:_tsid,seq:_seq,tid:_tid},
          success: function(data){
              _msgHadler.Log('setShortcutTag data='+JSON.stringify(data));
              _msgHadler.Log('Reveive setShortcutTag success!');
              returnFunc(data);
          },
          error: function(){ue;
              _msgHadler.Log('Reveive setShortcutTag fail!');
          },
          async:false
      });
  },
  setSystemTag : function(_method,_id,_tid,returnFunc){
      var _msgHadler = new MsgAction('TagAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("setSystemTag"),
          data: {method:_method,id:_id,tid:_tid},
          success: function(data){
              _msgHadler.Log('setSystemTag data='+JSON.stringify(data));
              _msgHadler.Log('Reveive setSystemTag success!');
              returnFunc(data);
          },
          error: function(){ue;
              _msgHadler.Log('Reveive setSystemTag fail!');
          },
          async:false
      });
  },
  delSystemTag : function(_method,_id,_tid,returnFunc){
      var _msgHadler = new MsgAction('TagAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("delSystemTag"),
          data: {method:_method,id:_id,tid:_tid},
          success: function(data){
              _msgHadler.Log('delSystemTag data='+JSON.stringify(data));
              _msgHadler.Log('Reveive delSystemTag success!');
              returnFunc(data);
          },
          error: function(){ue;
              _msgHadler.Log('Reveive delSystemTag fail!');
          },
          async:false
      });
  },
  delBookTag : function(_bid,_tid,returnFunc){
      var _msgHadler = new MsgAction('TagAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("delBookTag"),
          data: {bid:_bid,tid:_tid}, 
          success: function(data){
              _msgHadler.Log('delBookTag data='+JSON.stringify(data));
              _msgHadler.Log('Reveive delBookTag success!');
              returnFunc(data);
          },
          error: function(){
              _msgHadler.Log('Reveive delBookTag fail!');
          },
          async:false
      });
  },
  delShortcutTag : function(_tsid,_seq,_tid,returnFunc){
      var _msgHadler = new MsgAction('TagAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("delShortcutTag"),
          data: {tsid:_tsid,seq:_seq,tid:_tid}, 
          success: function(data){
              _msgHadler.Log('delShortcutTag data='+JSON.stringify(data));
              _msgHadler.Log('Reveive delShortcutTag success!');
              returnFunc(data);
          },
          error: function(){
              _msgHadler.Log('Reveive delShortcutTag fail!');
          },
          async:false
      });
  },
  delSysTag : function(_tid,_path,returnFunc){
      var _msgHadler = new MsgAction('TagAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("delSysTag"),
          data: {path:_path,tid:_tid}, 
          success: function(data){
              _msgHadler.Log('delTag data='+JSON.stringify(data));
              _msgHadler.Log('Reveive delTag success!');
             	returnFunc(data);
          },
          error: function(){
              _msgHadler.Log('Reveive delTag fail!');
          },
          async:false
      });
  },
  getMostContributed : function(){
      var _msgHadler = new MsgAction('TagAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("getMostContributed"),
          data: {}, 
          success: function(data){
              _msgHadler.Log('getMostContributed data='+JSON.stringify(data));
              _msgHadler.Log('Reveive getMostContributed success!');
          },
          error: function(){
              _msgHadler.Log('Reveive getMostContributed fail!');
          },
          async:false
      });
  },
  getShortcutList : function(_uid,_bsid,returnFunc){
			var _msgHadler = new MsgAction('TagAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("getShortcutList"),
          data: {uid:_uid,bsid:_bsid}, 
          success: function(data){
              _msgHadler.Log('getShortcutList data='+JSON.stringify(data));
              _msgHadler.Log('Reveive getShortcutList success!');
              returnFunc(data);
          },
          error: function(){
              _msgHadler.Log('Reveive getShortcutList fail!');
          },
          async:false
       });
  },
  getShortcutTag : function(_tsid,_seq,returnFunc){
			var _msgHadler = new MsgAction('TagAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("getShortcutTag"),
          data: {tsid:_tsid,seq:_seq}, 
          success: function(data){
              _msgHadler.Log('getShortcutTag data='+JSON.stringify(data));
              _msgHadler.Log('Reveive getShortcutTag success!');
              returnFunc(data);
          },
          error: function(){
              _msgHadler.Log('Reveive getShortcutTag fail!');
          },
          async:false
       });
  },
  getImageTicket : function(_str,returnFunc){
		var _msgHadler = new MsgAction('TagAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("getImageTicket"),
        data: {str:_str}, 
        success: function(data){
            _msgHadler.Log('getImageTicket data='+JSON.stringify(data));
            _msgHadler.Log('Reveive getImageTicket success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive getImageTicket fail!');
        },
        async:false
     });
  },
  getShortcutHtml : function(_ticket,returnFunc){
		var _msgHadler = new MsgAction('TagAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("getShortcutHtml"),
        data: {t:_ticket}, 
        success: function(data){
            _msgHadler.Log('getShortcutHtml data='+data);
            _msgHadler.Log('Reveive getShortcutHtml success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive getShortcutHtml fail!');
        },
        async:false
     });
  },
  getSystemTag : function(_method,_id,returnFunc){
  	var _msgHadler = new MsgAction('TagAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("getSystemTag"),
        data: {method:_method,id:_id}, 
        success: function(data){
            _msgHadler.Log('getSystemTag data='+data);
            _msgHadler.Log('Reveive getSystemTag success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive getSystemTag fail!');
        },
        async:false
     });
  },
  getDropDownList : function(_method,returnFunc){
  	var _msgHadler = new MsgAction('TagAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("getDropDownList"),
        data: {method:_method}, 
        success: function(data){
            _msgHadler.Log('getDropDownList data='+data);
            _msgHadler.Log('Reveive getDropDownList success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive getDropDownList fail!');
        },
        async:false
		});
  },
  getDropDownListItems : function(_key,_date,_tid,_method,returnFunc){
  	var _msgHadler = new MsgAction('TagAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("getDropDownListItems"),
        data: {key:_key,date:_date,tid:_tid,method:_method},
        success: function(data){
            _msgHadler.Log('getDropDownListItems data='+data);
            _msgHadler.Log('Reveive getDropDownListItems success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive getDropDownListItems fail!');
        },
        async:false
		});
  },
  setDropDownListItems : function(_key,_date,_pid,_method,_tid,returnFunc){
  	var _msgHadler = new MsgAction('TagAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("setDropDownListItems"),
        data: {key:_key,date:_date,pid:_pid,method:_method,tid:_tid},
        success: function(data){
            _msgHadler.Log('setDropDownListItems data='+data);
            _msgHadler.Log('Reveive setDropDownListItems success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive setDropDownListItems fail!');
        },
        async:false
		});
	},
  getTagMap : function(returnFunc){
  	var _msgHadler = new MsgAction('TagAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("getTagMap"),
        data: {},
        success: function(data){
            _msgHadler.Log('getTagMap data='+data);
            _msgHadler.Log('Reveive getTagMap success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive getTagMap fail!');
        },
        async:false
		});
	},
  getTagByPKey : function(_pkey,returnFunc){
      var _msgHadler = new MsgAction('ChartAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("getDropDownListItems"),
          data: {method:'getByPKey',pkey:_pkey},
          success: function(data){
              _msgHadler.Log('getTagByPKey data='+JSON.stringify(data));
              _msgHadler.Log('Reveive getTagByPKey success!');
              returnFunc(data);
          },
          error: function(){
              _msgHadler.Log('Reveive getTagByPKey fail!');
          },
          async:true
      });  	
  },
  getItutorQuiz : function(_id,returnFunc){
      var _msgHadler = new MsgAction('ChartAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("getItutorQuiz"),
          data: {id:_id},
          success: function(data){
              _msgHadler.Log('getItutorQuiz data='+JSON.stringify(data));
              _msgHadler.Log('Reveive getItutorQuiz success!');
              returnFunc(data);
          },
          error: function(){
              _msgHadler.Log('Reveive getItutorQuiz fail!');
          },
          async:true
      });
  },
  getScanexamQuiz : function(_key,_date,returnFunc){
      var _msgHadler = new MsgAction('ChartAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("getScanexamQuiz"),
          data: {key:_key,date:_date},
          success: function(data){
              _msgHadler.Log('getScanexamQuiz data='+JSON.stringify(data));
              _msgHadler.Log('Reveive getScanexamQuiz success!');
              returnFunc(data);
          },
          error: function(){
              _msgHadler.Log('Reveive getScanexamQuiz fail!');
          },
          async:true
      });
  },
  getItutorQuizTag : function(_id,_reportid,returnFunc){
      var _msgHadler = new MsgAction('ChartAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("getItutorQuizTag"),
          data: {id:_id,reportid:_reportid},
          success: function(data){
              _msgHadler.Log('getItutorQuiz data='+JSON.stringify(data));
              _msgHadler.Log('Reveive getItutorQuiz success!');
              returnFunc(data);
          },
          error: function(){
              _msgHadler.Log('Reveive getItutorQuiz fail!');
          },
          async:true
      });
  },
  getScanexamQuizTag : function(_key,_date,_seq,returnFunc){
      var _msgHadler = new MsgAction('ChartAPIHandler');
      $.ajax({
          type: "post",
          dataType: "json",
          url: this.getAPIurl("getScanexamQuizTag"),
          data: {key:_key,date:_date,seq:_seq},
          success: function(data){
              _msgHadler.Log('getScanexamQuiz data='+JSON.stringify(data));
              _msgHadler.Log('Reveive getScanexamQuiz success!');
              returnFunc(data);
          },
          error: function(){
              _msgHadler.Log('Reveive getScanexamQuiz fail!');
          },
          async:true
      });
  },
  getSuggestByChoosePanelByTagquizItutor : function(_dockey,_reportid,_like,_path,returnFunc){
		var _msgHadler = new MsgAction('TagAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("getSuggestByChoosePanelByTagquizItutor"),
        data: {key:_dockey,reportid:_reportid,path:_path,like:_like}, 
        success: function(data){
            _msgHadler.Log('getSuggestByChoosePanelByTagquizItutor data='+JSON.stringify(data));
            _msgHadler.Log('Reveive getSuggestByChoosePanelByTagquizItutor success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive getSuggestByChoosePanelByTagquizItutor fail!');
        },
        async:false
     });
  },
  getSuggestByChoosePanelByTagquizInfoacer : function(_dockey,_date,_seq,_like,_path,returnFunc){
		var _msgHadler = new MsgAction('TagAPIHandler');
	  $.ajax({
	      type: "post",
	      dataType: "json",
	      url: this.getAPIurl("getSuggestByChoosePanelByTagquizInfoacer"),
	      data: {key:_dockey,date:_date,seq:_seq,path:_path,like:_like}, 
	      success: function(data){
	          _msgHadler.Log('getSuggestByChoosePanelByTagquizInfoacer data='+JSON.stringify(data));
	          _msgHadler.Log('Reveive getSuggestByChoosePanelByTagquizInfoacer success!');
	          returnFunc(data);
	      },
	      error: function(){
	          _msgHadler.Log('Reveive getSuggestByChoosePanelByTagquizInfoacer fail!');
	      },
	      async:false
	   });
  },
  setItutorQuizTag : function(_dockey,_reportid,_ptid,_tid,returnFunc){
    var _msgHadler = new MsgAction('ChartAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("setItutorQuizTag"),
        data: {dockey:_dockey,reportid:_reportid,ptid:_ptid,tid:_tid},
        success: function(data){
            _msgHadler.Log('setItutorQuizTag data='+JSON.stringify(data));
            _msgHadler.Log('Reveive setItutorQuizTag success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive setItutorQuizTag fail!');
        },
        async:true
    });
  },
  setScanexamQuizTag : function(_bskey,_sekey,_setdate,_seq,_ptid,_tid,returnFunc){
    var _msgHadler = new MsgAction('ChartAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("setScanexamQuizTag"),
        data: {bskey:_bskey,sekey:_sekey,setdate:_setdate,seq:_seq,ptid:_ptid,tid:_tid},
        success: function(data){
            _msgHadler.Log('setScanexamQuizTag data='+JSON.stringify(data));
            _msgHadler.Log('Reveive setScanexamQuizTag success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive setScanexamQuizTag fail!');
        },
        async:true
    });
  },
  delItutorQuizTag : function(_dockey,_reportid,_ptid,_tid,returnFunc){
    var _msgHadler = new MsgAction('ChartAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("delItutorQuizTag"),
        data: {dockey:_dockey,reportid:_reportid,ptid:_ptid,tid:_tid},
        success: function(data){
            _msgHadler.Log('delItutorQuizTag data='+JSON.stringify(data));
            _msgHadler.Log('Reveive delItutorQuizTag success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive delItutorQuizTag fail!');
        },
        async:true
    });
  },
  delScanexamQuizTag : function(_bskey,_sekey,_setdate,_seq,_ptid,_tid,returnFunc){
    var _msgHadler = new MsgAction('ChartAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("delScanexamQuizTag"),
        data: {bskey:_bskey,sekey:_sekey,setdate:_setdate,seq:_seq,ptid:_ptid,tid:_tid},
        success: function(data){
            _msgHadler.Log('delScanexamQuizTag data='+JSON.stringify(data));
            _msgHadler.Log('Reveive delScanexamQuizTag success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive delScanexamQuizTag fail!');
        },
        async:true
    });
  },
  getTagsByPKey : function(_keys,returnFunc){
    var _msgHadler = new MsgAction('ChartAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("getTagsByPKey"),
        data: {keys:_keys},
        success: function(data){
            _msgHadler.Log('getTagsByPKey data='+JSON.stringify(data));
            _msgHadler.Log('Reveive getTagsByPKey success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive getTagsByPKey fail!');
        },
        async:true
    });
  }
}
