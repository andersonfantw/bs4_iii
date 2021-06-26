var importEnum = {
	path:'tag',
	max_upload_filesize:900000000
}

var TagImportAPIHandler ={
		getAPIurl : function(cmd){
			return web_url+"/plugin/"+importEnum.path+"/api/api.php?cmd="+cmd;
		},
    getStatusCode : function(_cmd,_site,_m,returnFunction){
        //var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: this.getAPIurl('statuscode'),
            data: {cmd:_cmd, site:_site, m:_m},
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