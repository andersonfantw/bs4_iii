var Html5Uploader = function(){
	var _userconvert_fileTemplate='\
	<div id="f{{id}}" class="item">\
		<div class="preview"></div>\
		<div class="ProgressContainer"><div class="progressbar"></div></div>\
	</div>';
	
	var _timestamp;
	var allowfiletype=new Array('tag','dic','xls');
	
	function slugify(text){
		text=text.replace(/[^-a-zA-Z0-9,&\s]+/ig,'');
		text=text.replace(/-/gi,"_");
		text=text.replace(/\s/gi,"_");
		return text;
	}
	
	$(document).ready(function(){
	  $ctl = $("#dropbox, #convert input");
	  $ctl.html5Uploader({
	      onClientLoadStart:function(e,fileReader,file){
		    	var sub = file.name.substring(file.name.lastIndexOf('.'));
		    	//Check see if is avalible format
					var isallow=false;
					if($('#convert_allowfiletype').val().indexOf(sub+'.')>=0) isallow=true;

	        if(!isallow){
						alert('Incorrect file format!');
						document.location.reload();
						switch(fileReader.readyState){
							case 0: //EMPTY
							case 2: //DONE
								fileReader.result = null;
								break;
							case 1: //LOADING
								fileReader.readyState = 2;
								fileReader.result = null;
								break;
						}
						fileReader.abort();
						return -1;
		    	}
	      },
	      onClientError:function(e, file){
	      	console.log("error", e.target.error.message);
	      	//alert('Ooooops! something wrong! Please try again!');
	      },
	      onClientLoad:function(e,file){
	
	    	},
	      onServerLoadStart:function(e,file){
	      	//check filesize, process while size less than 1G
	      	if(file.size>importEnum.max_upload_filesize){
	      		alert('Please upload file less than 900MB!');
	      		document.location.reload();
	      	}
	
					$ctl.unbind();
					//$('#convert input').hide();
	        _timestamp = new Date().getTime();
	        var upload=$("#upload");
	        //if(upload.is(":hidden")){
	        //    upload.show();
	        //}
	        upload.append(_userconvert_fileTemplate.replace(/{{id}}/g,_timestamp));
	        var sub = file.name.substring(file.name.lastIndexOf('.')+1);
			    $('#convert').addClass('converting');
			    //Check see if is avalible format
	        $("#f"+_timestamp).find(".preview")
	          .append("<div class=\""+sub+"\"></div>")
	          .append("<div class=\"filename\">"+file.name+"</div><div class=\"status\">uploading...</div>");
	        $("#f"+_timestamp).find(".progressbar").progressbar({value:0});
	      },
	      onServerProgress:function(e,file){
	        if(e.lengthComputable){
	            $("#f"+_timestamp).find(".progressbar > div").css("background","#3142c2");
	            var percentComplete=(e.loaded/e.total)*100;
	            $("#f"+_timestamp).find(".progressbar")
	                .progressbar({value:percentComplete});
	        }
	      },
	      onServerLoad:function(e,file){
	          $("#f"+_timestamp).find(".progressbar")
	              .progressbar({value:100});
	      },
	      onSuccess:function(e,file,responseText){
	      	/*
	      	different file type will give different success code, responseText to decide how to reactive
	      	(nothing): Server not response!
	      	responseText = {"code":value,"msg":value,"link":value}, check ErrorHandler.class.php to know more.
	      	UplaodComplete code=200.1
	      	Cloudconvert   code=200.2
	      	Ecocatconvert  code=200.3, check progress
	      	*/
	      	if(responseText==''){
	        	alert('Server not response!');
	        	document.location.reload();
	      	}else{
	      		content = '<div id="message"><h3>%title%</h3><br />%content%</div><br /><br /><input type="button" onclick="document.location.href=document.location.href" value="back" />';
	      		obj = JSON.parse(responseText);
	      		switch(obj.code){
	      			case '200.20':
	      			case '200.21':
	      			case '200.22':
	      				ConvertCloseout(false);
	      				break;
	      			case '200.23':
	      				ConvertCloseout(obj.link);
	      				break;
	      			case '200':
	      			case '200.24':
	      			case '200.25':
	      				if(obj.content){
	      					ConvertCloseout('stay');
	      					_str = content.replace('%title%','Bold doc key updated.')
	      												.replace('%content%',obj.content);
	      					$('#convert').html(_str);
	      					$('#convert b.doc').attr('title','these keys of dockey updated');
	      				}else{
	      					ConvertCloseout(false);
	      				}
	      				break;
	      			case '200.43':
	      				$('#convert').remove();
	      				setHandsontable(obj);
	      				break;
	      			case '406.91':
	      				_str = content.replace('%title%',obj.title)
	      											.replace('%content%',obj.msg);
	      				$('#convert').html(_str);
	      				//alert(obj.msg);
	      				//document.location.reload();
	      				break;
	            default:        //show report list
	            	ConvertCloseout('stay');
	      				_str = content.replace('%title%','Bold doc key will be update. And Bold tags are missing. Please add these tags first.')
	      											.replace('%content%',obj.content);
	      				$('#convert').html(_str);
	            	$('#convert b').attr('title',obj.msg);
	            	$('#convert b.doc').attr('title','these keys of dockey will be update');
	              break;
	      		}
	      	}
	     	}
	  });
	});
	
	function ConvertCloseout(_link){
	  $('.status').text('Done');
	  $('#convert').addClass('done');
	  if(_link!='stay'){
		  setTimeout(function(){
		    ConvertCancel(_link);
		  },5000);
		}
	}
	
	function ConvertCancel(_link){
		$('#convert').attr('class','');
		$('.status').text('');
		$('#upload > div').remove();
		$('.progressbar').remove();
		$('.preview').hide();
		if(_link){
			document.location.href=_link;
		}else{
			document.location.reload();
		}
	}
}
