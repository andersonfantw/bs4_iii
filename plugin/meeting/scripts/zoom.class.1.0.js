var zoomEnum={
}

var zoomEnv={
	path:'meeting'
}

var ZoomAPIHandler={
	getAPIurl : function(cmd){
		return web_url+"/plugin/"+zoomEnv.path+"/api/apiZ.php?cmd="+cmd;
	},
  getAccountList : function(returnFunc){
    var _msgHadler = new MsgAction('ZoomAPIHandler');
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
    var _msgHadler = new MsgAction('ZoomAPIHandler');
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
    var _msgHadler = new MsgAction('ZoomAPIHandler');
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
  addReservation : function(_uid,_Zoomid,_name,_start,_end,_gid,returnFunc){
    var _msgHadler = new MsgAction('ZoomAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("addReservation"),
        data: {uid:_uid,Zoomid:_Zoomid,name:_name,start:_start,end:_end,gid:_gid}, 
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
  updateReservation : function(_uuid,_roomid,_name,_start,_end,_gid,returnFunc){
    var _msgHadler = new MsgAction('ZoomAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("updateReservation"),
        data: {uuid:_uuid,roomid:_roomid,name:_name,start:_start,end:_end,gid:_gid}, 
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
  delReservation : function(_roomid,returnFunc){
    var _msgHadler = new MsgAction('ZoomAPIHandler');
    $.ajax({
        type: "post",
        url: this.getAPIurl("delReservation"),
        data: {roomid:_roomid}, 
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
    var _msgHadler = new MsgAction('ZoomAPIHandler');
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
    var _msgHadler = new MsgAction('ZoomAPIHandler');
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
    var _msgHadler = new MsgAction('ZoomAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("action_get_Zoom_list"),
        data: {}, 
        success: function(data){
            _msgHadler.Log('action_get_Zoom_list data='+JSON.stringify(data));
            _msgHadler.Log('Reveive action_get_Zoom_list success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive action_get_Zoom_list fail!');
        },
        async:false
    });
  },
  action_create : function(_Zoomid,returnFunc){
    var _msgHadler = new MsgAction('ZoomAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("action_create"),
        data: {Zoomid:_Zoomid}, 
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
  action_get_list : function(_Zoomid,_start_limit,_end_limit,returnFunc){
    var _msgHadler = new MsgAction('ZoomAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("action_get_list"),
        data: {Zoomid:_Zoomid,start_limit:_start_limit,end_limit:_end_limit}, 
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
  action_add : function(_Zoomid,_name,_start,_end,_sender_email,returnFunc){
    var _msgHadler = new MsgAction('ZoomAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("action_add"),
        data: {Zoomid:_Zoomid,name:_name,start:_start,end:_end,sender_email:_sender_email}, 
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
  action_update : function(_Zoomid,_name,_start,_end,_sender_email,returnFunc){
    var _msgHadler = new MsgAction('ZoomAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("action_update"),
        data: {Zoomid:_Zoomid,name:_name,start:_start,end:_end,sender_email:_sender_email}, 
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
    var _msgHadler = new MsgAction('ZoomAPIHandler');
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
    var _msgHadler = new MsgAction('ZoomAPIHandler');
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
    var _msgHadler = new MsgAction('ZoomAPIHandler');
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
    var _msgHadler = new MsgAction('ZoomAPIHandler');
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
  action_start : function(_Zoomid,_meetingid,_name,returnFunc){
    var _msgHadler = new MsgAction('ZoomAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("action_start"),
        data: {Zoomid:_Zoomid,meetingid:_meetingid,name:_name}, 
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
  action_stop : function(_Zoomid,returnFunc){
    var _msgHadler = new MsgAction('ZoomAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("action_stop"),
        data: {Zoomid:_Zoomid}, 
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
    var _msgHadler = new MsgAction('ZoomAPIHandler');
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
