var uploadqueueEnv={
	path:'uploadqueue'
}
var UploadqueueAPIHandler ={
	getAPIurl : function(cmd){
		return web_url+"/plugin/"+uploadqueueEnv.path+"/api/api.php?cmd="+cmd;
	},
	getBackendURL : function(cmd){
		return web_url+"/backend/sys_queue.php?type="+cmd;
	},
	chkConvertProgress: function(_lnettoken,returnFunc){
    var _msgHadler = new MsgAction('UploadqueueAPIHandler');
    $.ajax({
        type: "post",
        dataType: "json",
        url: this.getAPIurl("progress"),
        data: {token:_lnettoken}, 
        success: function(data){
            _msgHadler.Log('chkConvertProgress data='+JSON.stringify(data));
            _msgHadler.Log('Reveive chkConvertProgress success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive chkConvertProgress fail!');
        },
        async:false
    });
	},
	reloadList: function(returnFunc){
    var _msgHadler = new MsgAction('UploadqueueAPIHandler');
    $.ajax({
        type: "post",
        dataType: "html",
        url: this.getBackendURL("reloadlist"),
        data: {},
        success: function(data){
            _msgHadler.Log('reloadList data='+data);
            _msgHadler.Log('Reveive reloadList success!');
            returnFunc(data);
        },
        error: function(){
            _msgHadler.Log('Reveive reloadList fail!');
        },
        async:false
    });
	}
}