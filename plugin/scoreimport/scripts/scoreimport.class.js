var importEnum = {
	path:'scoreimport',
	max_upload_filesize:900000000
}

var ScoreImportAPIHandler ={
		getAPIurl : function(cmd){
			return web_url+"/plugin/"+importEnum.path+"/api/api.php?cmd="+cmd;
		},
    getStatusCode : function(_cmd,_site,returnFunction){
        //var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: this.getAPIurl('statuscode'),
            data: {cmd:_cmd, site:_site},
            success: function(data){
                returnFunction(data);
                //_msgHadler.Log('getStatusCode data='+JSON.stringify(data));
                //_msgHadler.Log('Reveive getStatusCode success!');
            },
            error: function(){
                bookEnv.IsDefaultData=true;
                //_msgHadler.Log('Reveive getStatusCode fail!');
            },
            async:false
        });
    }
}