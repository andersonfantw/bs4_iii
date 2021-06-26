var vcubeEnum={
}

var vcubeEnv={
	path:'meeting'
}

var VCubeAPIHandler={
	getAPIurl : function(cmd){
		return web_url+"/plugin/"+vcubeEnv.path+"/api/apiV.php?cmd="+cmd;
	},
  getAccountList : function(returnFunc){
    var _msgHadler = new MsgAction('VCubeAPIHandler');
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
    var _msgHadler = new MsgAction('VCubeAPIHandler');
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
  getReservationList : function(_mode,_start,_end,_param,_token,returnFunc){
    var _msgHadler = new MsgAction('VCubeAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("getReservationList"),
        data: {mode:_mode,start:_start,end:_end,param:_param,token:_token}, 
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
  addReservation : function(_uid,_roomid,_name,_start,_end,_gid,returnFunc){
    var _msgHadler = new MsgAction('VCubeAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("addReservation"),
        data: {uid:_uid,roomid:_roomid,name:_name,start:_start,end:_end,gid:_gid}, 
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
  updateReservation : function(_reservationid,_roomid,_name,_start,_end,_gid,returnFunc){
    var _msgHadler = new MsgAction('VCubeAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("updateReservation"),
        data: {reservationid:_reservationid,roomid:_roomid,name:_name,start:_start,end:_end,gid:_gid}, 
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
  delReservation : function(_reservationid,returnFunc){
    var _msgHadler = new MsgAction('VCubeAPIHandler');
    $.ajax({
        type: "post",
        url: this.getAPIurl("delReservation"),
        data: {reservationid:_reservationid}, 
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
  login : function(returnFunc){
    var _msgHadler = new MsgAction('VCubeAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("login"),
        data: {}, 
        success: function(data){
            _msgHadler.Log('login data='+JSON.stringify(data));
            _msgHadler.Log('Reveive login success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive login fail!');
        },
        async:false
    });
  },
  logout : function(returnFunc){
    var _msgHadler = new MsgAction('VCubeAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("logout"),
        data: {}, 
        success: function(data){
            _msgHadler.Log('logout data='+JSON.stringify(data));
            _msgHadler.Log('Reveive logout success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive logout fail!');
        },
        async:false
    });
  },
  action_get_room_list : function(returnFunc){
    var _msgHadler = new MsgAction('VCubeAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("action_get_room_list"),
        data: {}, 
        success: function(data){
            _msgHadler.Log('action_get_room_list data='+JSON.stringify(data));
            _msgHadler.Log('Reveive action_get_room_list success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive action_get_room_list fail!');
        },
        async:false
    });
  },
  action_create : function(_roomid,returnFunc){
    var _msgHadler = new MsgAction('VCubeAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("action_create"),
        data: {roomid:_roomid}, 
        success: function(data){
            _msgHadler.Log('action_create data='+JSON.stringify(data));
            _msgHadler.Log('Reveive action_create success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive action_create fail!');
        },
        async:false
    });
  },
  action_get_list : function(_roomid,_start_limit,_end_limit,returnFunc){
    var _msgHadler = new MsgAction('VCubeAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("action_get_list"),
        data: {roomid:_roomid,start_limit:_start_limit,end_limit:_end_limit}, 
        success: function(data){
            _msgHadler.Log('action_get_list data='+JSON.stringify(data));
            _msgHadler.Log('Reveive action_get_list success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive action_get_list fail!');
        },
        async:false
    });
  },
  action_add : function(_roomid,_name,_start,_end,_sender_email,returnFunc){
    var _msgHadler = new MsgAction('VCubeAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("action_add"),
        data: {roomid:_roomid,name:_name,start:_start,end:_end,sender_email:_sender_email}, 
        success: function(data){
            _msgHadler.Log('action_add data='+JSON.stringify(data));
            _msgHadler.Log('Reveive action_add success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive action_add fail!');
        },
        async:false
    });
  },
  action_update : function(_roomid,_name,_start,_end,_sender_email,returnFunc){
    var _msgHadler = new MsgAction('VCubeAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("action_update"),
        data: {roomid:_roomid,name:_name,start:_start,end:_end,sender_email:_sender_email}, 
        success: function(data){
            _msgHadler.Log('action_update data='+JSON.stringify(data));
            _msgHadler.Log('Reveive action_update success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive action_update fail!');
        },
        async:false
    });
  },
  action_delete : function(_reservationid,returnFunc){
    var _msgHadler = new MsgAction('VCubeAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("action_delete"),
        data: {reservationid:_reservationid}, 
        success: function(data){
            _msgHadler.Log('action_delete data='+JSON.stringify(data));
            _msgHadler.Log('Reveive action_delete success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive action_delete fail!');
        },
        async:false
    });
  },
  action_get_invite : function(_reservationid,returnFunc){
    var _msgHadler = new MsgAction('VCubeAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("action_get_invite"),
        data: {reservationid:_reservationid}, 
        success: function(data){
            _msgHadler.Log('action_get_invite data='+JSON.stringify(data));
            _msgHadler.Log('Reveive action_get_invite success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive action_get_invite fail!');
        },
        async:false
    });
  },
  action_add_invite : function(_reservationid,_email,returnFunc){
    var _msgHadler = new MsgAction('VCubeAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("action_add_invite"),
        data: {reservationid:_reservationid,email:_email}, 
        success: function(data){
            _msgHadler.Log('action_add_invite data='+JSON.stringify(data));
            _msgHadler.Log('Reveive action_add_invite success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive action_add_invite fail!');
        },
        async:false
    });
  },
  action_delete_invite : function(_reservationid,returnFunc){
    var _msgHadler = new MsgAction('VCubeAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("action_delete_invite"),
        data: {reservationid:_reservationid}, 
        success: function(data){
            _msgHadler.Log('action_delete_invite data='+JSON.stringify(data));
            _msgHadler.Log('Reveive action_delete_invite success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive action_delete_invite fail!');
        },
        async:false
    });
  },
  action_start : function(_roomid,_meetingid,_name,returnFunc){
    var _msgHadler = new MsgAction('VCubeAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("action_start"),
        data: {roomid:_roomid,meetingid:_meetingid,name:_name}, 
        success: function(data){
            _msgHadler.Log('action_start data='+JSON.stringify(data));
            _msgHadler.Log('Reveive action_start success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive action_start fail!');
        },
        async:false
    });
  },
  action_stop : function(_roomid,returnFunc){
    var _msgHadler = new MsgAction('VCubeAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("action_stop"),
        data: {roomid:_roomid}, 
        success: function(data){
            _msgHadler.Log('action_stop data='+JSON.stringify(data));
            _msgHadler.Log('Reveive action_stop success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive action_stop fail!');
        },
        async:false
    });
  },
  IsClassBegin : function(_mode,_buid, returnFunc){
    var _msgHadler = new MsgAction('VCubeAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("IsClassBegin"),
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
