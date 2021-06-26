$(document).ready(function(){
	var tags = {
		pi:{required:true,text:'執行單位',maincate:true,options:{multiple:false,ParentKey:'pi',addButton:'#tag1 .placeholder'}},
		pn:{required:true,text:'專案名稱',subcate:true,options:{multiple:false,ParentKey:'pn',addButton:'#tag2 .placeholder'}},
		pcu:{required:true,text:'承辦科別',bookshelf:true,options:{multiple:false,ParentKey:'pcu',addButton:'#tag3 .placeholder'}},
		prt:{required:true,text:'報告類型',options:{multiple:false,ParentKey:'prt',addButton:'#tag4 .placeholder'}},
		pc:{required:true,text:'承辦人',options:{multiple:false,ParentKey:'pc',addButton:'#tag5 .placeholder'}},
		pcof:{required:true,text:'經費類別',options:{multiple:false,ParentKey:'pcof',addButton:'#tag6 .placeholder'}},
		year:{required:true,text:'年度',options:{multiple:false,ParentKey:'year',addButton:'#tag7 .placeholder'}},
		py:{required:true,text:'計畫年數',options:{multiple:false,ParentKey:'py',addButton:'#tag8 .placeholder'}},
		pty:{required:true,text:'計畫總年數',options:{multiple:false,ParentKey:'pty',addButton:'#tag9 .placeholder'}},
		pwrf:{required:true,text:'領域',options:{multiple:false,ParentKey:'pwrf',addButton:'#tag10 .placeholder'}}
	};
	_template = '<ul><li>@subject@</li><li><select style="width:300px" id="@id@">@options@</select></li></ul>';
	_template_option = '<option value="@key@">@val@</option>';
	var strj = '';

	var param={
		path:web_url+'/plugin/uploadqueue/',
		script:['JQuery.JSAjaxFileUploader'],
		css:['JQuery.JSAjaxFileUploader'],
		langJS:{},
		langCSS:false,
		html:''
	};

	var param_tag={
		path:web_url+'/plugin/tag/',
		script:['tag.class.1.0'],
		css:['queue'],
		langJS:{},
		langCSS:false,
		html:''
	};
	var param_search={
		path:web_url+'/plugin/search/',
		script:['jquery.chosen.min'],
		css:['jquery.chosen.min'],
		langJS:{},
		langCSS:false,
		html:''
	};
	new Loader([param_tag,param,param_search],function(){
		for(var i in tags){
			var memo='';
			if(tags[i].bookshelf===true){
				strj += ';$bs='+tags[i].options.ParentKey;
			}else if(tags[i].maincate===true){
				strj += ';$mc='+tags[i].options.ParentKey;
			}else if(tags[i].subcate===true){
				strj += ';$sc='+tags[i].options.ParentKey;
			}
			var strRequire = (tags[i].required)?'*':'';
			TagAPIHandler.getTagByPKey(tags[i].options.ParentKey,function(data){
				_options='';
				for(var j in data.data){
					_options+=_template_option.replace('@key@',data.data[j].key).replace('@val@',data.data[j].key+':'+data.data[j].val);
				}
				if(tags[data.pkey].bookshelf===true){
					memo='&nbsp;<span>(bookshef)</span>';
				}else if(tags[data.pkey].maincate===true){
					memo='&nbsp;<span>(maincate)</span>';
				}else if(tags[data.pkey].subcate===true){
					memo='&nbsp;<span>(subcate)</span>';
				}else{
					memo='';
				}
				$('#tags').append(_template.replace('@subject@',strRequire+tags[data.pkey].text+memo).replace('@id@',data.pkey).replace('@options@',_options));
				window[data.pkey]=$('#'+data.pkey).chosen({search_contains:true});
			});
		}
		
		$('#multiupload').JSAjaxFileUploader({
			uploadUrl:web_url+'/plugin/uploadqueue/api/api.php?cmd=upload2',
			autoSubmit:false,
			maxFileSize:943718400, //900MB
			allowExt: 'pdf|zip|itu',
			success:function(response){
				var obj = JSON.parse(response);
				if(obj.code!='200'){
					alert(obj.msg);
				}
			},
			error:function(xhr,textStatus,errorThrown){
				alert(xhr.responseText);
			},
			beforesubmit:function(settings){
				var str ='';
				for(i in tags){
					data = window[i].find('option:selected').text();
					if(data!=''){
						str += ';'+i+'='+data;
						$('#'+i+' .placeholder').css('border-color','black');
					}else{
						if(tags[i].required){
							$('#'+i+' .placeholder').css('border-color','red');
							return false;
						}
					}
				}
				if(strj!='') _strj = strj.substring(1);
				if(str!='') _str = str.substring(1);
				if((strj!='') && (str!='')){
					str = _strj+';'+_str;
				}else if(strj!=''){
					str = _strj;
				}else if(str!=''){
					str = _str;
				}
				console.log(str);
				settings.formData = {token:lnettoken,tags:str};
				return true;
			}
		});
	});
});
