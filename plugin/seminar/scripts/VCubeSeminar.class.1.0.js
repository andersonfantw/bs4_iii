var vcubeSeminarEnum={
}

var vcubeSeminarEnv={
	path:'seminar'
}

var VCubeSeminarAPIHandler={
	getAPIurl : function(cmd){
		return web_url+"/plugin/"+vcubeSeminarEnv.path+"/api/api.php?cmd="+cmd;
	},
  getAccountList : function(returnFunc){
    var _msgHadler = new MsgAction('VCubeSeminarAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("getAccountList"),
        data: {}, 
        success: function(data){
            _msgHadler.Log('getAccountList data='+JSON.stringify(data));
            _msgHadler.Log('Reveive getAccountList success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive getAccountList fail!');
        }
    });
  },
  getGroupList : function(returnFunc){
    var _msgHadler = new MsgAction('VCubeSeminarAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("getGroupList"),
        data: {}, 
        success: function(data){
            _msgHadler.Log('getGroupList data='+JSON.stringify(data));
            _msgHadler.Log('Reveive getGroupList success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive getGroupList fail!');
        }
    });
  },
  getRoomList : function(returnFunc){
    var _msgHadler = new MsgAction('VCubeSeminarAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("getRoomList"),
        data: {}, 
        success: function(data){
            _msgHadler.Log('getRoomList data='+JSON.stringify(data));
            _msgHadler.Log('Reveive getRoomList success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive getRoomList fail!');
        }
    });
  },
  getReservationList : function(_mode,_start,_end,_param,returnFunc){
    var _msgHadler = new MsgAction('VCubeSeminarAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("getReservationList"),
        data: {mode:_mode,start:_start,end:_end,param:_param}, 
        success: function(data){
            _msgHadler.Log('getReservationList data='+JSON.stringify(data));
            _msgHadler.Log('Reveive getReservationList success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive getReservationList fail!');
        }
    });
  },
  addReservation : function(_uid,_roomkey,_name,_start,_end,_gid,_max,returnFunc){
    var _msgHadler = new MsgAction('VCubeSeminarAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("addReservation"),
        data: {uid:_uid,roomkey:_roomkey,name:_name,start:_start,end:_end,gid:_gid,max:_max}, 
        success: function(data){
            _msgHadler.Log('addReservation data='+JSON.stringify(data));
            _msgHadler.Log('Reveive addReservation success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive addReservation fail!');
        }
    });
  },
  updateReservation : function(_seminarkey,_roomkey,_name,_start,_end,_gid,_max,returnFunc){
    var _msgHadler = new MsgAction('VCubeSeminarAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("updateReservation"),
        data: {seminarkey:_seminarkey,roomkey:_roomkey,name:_name,start:_start,end:_end,gid:_gid,max:_max}, 
        success: function(data){
            _msgHadler.Log('updateReservation data='+JSON.stringify(data));
            _msgHadler.Log('Reveive updateReservation success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive updateReservation fail!');
        }
    });
  },
  delReservation : function(_seminarkey,returnFunc){
    var _msgHadler = new MsgAction('VCubeSeminarAPIHandler');
    $.ajax({
        type: "post",
        url: this.getAPIurl("delReservation"),
        data: {seminarkey:_seminarkey}, 
        success: function(data){
            _msgHadler.Log('delReservation data='+JSON.stringify(data));
            _msgHadler.Log('Reveive delReservation success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive delReservation fail!');
        }
    });
  },
  IsSeminarBegin : function(_mode,_buid, returnFunc){
    var _msgHadler = new MsgAction('VCubeAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("IsSeminarBegin"),
        data: {mode:_mode,buid:_buid},
        success: function(data){
            _msgHadler.Log('IsClassBegin data='+JSON.stringify(data));
            _msgHadler.Log('Reveive IsClassBegin success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive IsClassBegin fail!');
        },
        async:false
    });
  }
}
