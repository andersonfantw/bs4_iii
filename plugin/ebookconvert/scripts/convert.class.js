var convertEnv = {
	path:'ebookconvert',
	attachEvent:false,
	connect_gamereflection:false,
	max_upload_filesize:900000000,
	skin:'',
	skinJSON:[],
	spell:'left',
	spellJSON:[],
	convert_filetypes:{'pdf':'.pdf',
											'doc':'.doc.docx',
											'ppt':'.ppt.pptx',
											'xls':'.xls.xlsx',
											'lbm_zip':'.zip',
											'itu_zip':'.zip.itu',
											'ebk':'.ebk'},
	convert_allowfiletype:''
}

var EbookAPIHandler ={
		getAPIurl : function(cmd){
			return web_url+"/plugin/"+convertEnv.path+"/api/api.php?cmd="+cmd;
		},
    getStatusCode : function(_site,returnFunction){
        //var _msgHadler = new MsgAction('APIHandler');
        $.ajax({
            type: "post",
            dataType: "json",
            url: this.getAPIurl("statuscode"),
            data: {cmd:'statuscode', site:_site},
            success: function(data){
                returnFunction(data);
                //_msgHadler.Log('getStatusCode data='+JSON.stringify(data));
                //_msgHadler.Log('Reveive getStatusCode success!');
            },
            error: function(){
                //_msgHadler.Log('Reveive getStatusCode fail!');
            },
            async:false
        });
    },
    getSkinList : function(_bs,returnFunction){
        $.ajax({
            type: "post",
            dataType: "json",
            url: this.getAPIurl("GetSkinList"),
            data: {bs:_bs},
            success: function(data){
                returnFunction(data);
                //_msgHadler.Log('getStatusCode data='+JSON.stringify(data));
                //_msgHadler.Log('Reveive getStatusCode success!');
            },
            error: function(){
                //_msgHadler.Log('Reveive getStatusCode fail!');
            },
            async:false
        });
    },
    getSpellList : function(_bs,returnFunction){
        $.ajax({
            type: "post",
            dataType: "json",
            url: this.getAPIurl("GetSpellList"),
            data: {bs:_bs},
            success: function(data){
                returnFunction(data);
                //_msgHadler.Log('getStatusCode data='+JSON.stringify(data));
                //_msgHadler.Log('Reveive getStatusCode success!');
            },
            error: function(){
                //_msgHadler.Log('Reveive getStatusCode fail!');
            },
            async:false
        });
    },
    setSkinSettings : function(_skin,_spell,returnFunction){
        $.ajax({
            type: "post",
            dataType: "json",
            url: this.getAPIurl("SetSkinSettings"),
            data: {skin:_skin,spell:_spell},
            success: function(data){
                returnFunction(data);
                //_msgHadler.Log('getStatusCode data='+JSON.stringify(data));
                //_msgHadler.Log('Reveive getStatusCode success!');
            },
            error: function(){
                //_msgHadler.Log('Reveive getStatusCode fail!');
            },
            async:false
        });
    },
    convertProcess : function(_bsid,_cid,_processid,_t,_filename,returnFunction){
    	data = {bsid:_bsid, c:_cid, pid:_processid, t:_t, filename:_filename};
        $.ajax({
            type: "post",
            dataType: "json",
            url: web_url+"/plugin/ebookconvert/api/api.php?cmd=ConvertProcess",
            data: {bs:_bsid, c:_cid, pid:_processid, t:_t, filename:_filename},
            success: function(data){
                returnFunction(data);
                //_msgHadler.Log('getStatusCode data='+JSON.stringify(data));
                //_msgHadler.Log('Reveive getStatusCode success!');
            },
            error: function(){
                //_msgHadler.Log('Reveive getStatusCode fail!');
            },
            async:true
        });
    }
}
