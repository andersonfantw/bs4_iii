var Html5Uploader = function(){
	var _userconvert_fileTemplate='\
<div id="f{{id}}" class="item">\
	<div class="preview"></div>\
	<div class="ProgressContainer"><div class="progressbar"></div></div>\
</div>';
	var _timestamp;

	Init();
/*
	if(systemEnv.isBackend){
		Init();
	}else{
		$(EBOOKCONVERT_BUTTON).click(function(){
			//set language
			Init();
		});
	}
*/

	function Init(){
		//set language
		if(!SetLang()){
			return;
		}

		//show panel
		$(EBOOKCONVERT_CONTAINER).addClass('show');
		DialogueHandler.center(EBOOKCONVERT_CONTAINER);
		DialogueHandler.showMask();

		querystring = _postUrl();

		function _postUrl(){
			var querystring;
			if(systemEnv.isBackend){
				querystring = "site=1&c="+$.getQuery('id');
			}else{
				querystring = "site=0&c="+bookEnv.currentCateId;
			}
			querystring += "&bs="+systemEnv.bsid+"&uid="+uid+"&spell="+convertEnv.spell+'&skin='+convertEnv.skin;
			return querystring;
		}

		//plug html5uploader
    $ctl = $(EBOOKCONVERT_UPLOAD_OBJECT);
    $ctl.html5Uploader({
		postUrl:function(){
			querystring = _postUrl();
			return web_url+"/plugin/ebookconvert/api/api.php?"+querystring;
		},
    onClientLoadStart:function(e,fileReader,file){
/*
      	checkGameReflectionStatus(function(data){
      		switch(data.seq){
      			case -1:
      				//fileReader.abort();
      				//return -1;
      				break;
      			case 0:
      				alert('\u904A\u6232\u5730\u5716\u6C92\u6709\u66F4\u591A\u7A7A\u9593\u589E\u52A0\u65B0\u7684\u66F8\u7C4D\u4E86!');
      				fileReader.abort();
      				return -1;
      				break;
      			default:
      				break;
      		}
      	});
*/
	    	var sub = file.name.substring(file.name.lastIndexOf('.'));
	    	//Check see if is avalible format
        var isallow=false;
        if($(EBOOKCONVERT_ALLOWTYPE).val().indexOf(sub+'.')>=0) isallow=true;

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
      	if(file.size>convertEnv.max_upload_filesize){
      		alert('Please upload file less than 900MB!');
      		document.location.reload();
      	}

				$ctl.unbind();
				$('#convert input').hide();
        _timestamp = new Date().getTime();
        var upload=$(EBOOKCONVERT_UPLOAD);
        //if(upload.is(":hidden")){
        //    upload.show();
        //}
        upload.append(_userconvert_fileTemplate.replace(/{{id}}/g,_timestamp));
        var sub = file.name.substring(file.name.lastIndexOf('.')+1);
		    $(EBOOKCONVERT_CONTAINER).addClass('converting');
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
      				process(obj,_timestamp,file);
      				break;
      			default:
      				if(obj.link){
      					if(confirm(obj.msg)){
      						ConvertCloseout(obj.link);
      					}else{
      						ConvertCancel(false);
      					}
              }else{
              	alert(obj.msg);
                ConvertCancel(false);
      				}
      				break;
      		}
      	}
     	}
    });
	}

	function SetLang(){
		if(systemEnv.isBackend){
			_catename = decodeURI($.getQuery('cn'));
		}else{
			if(bookEnv.currentCateLevel==0){
				alert(_convert_warning_select_submenu);
				DialogueHandler.hideMask();
				return false;
			}else{
				_catename = bookEnv.currentCateName;
			}
		}
		_span = _convert_span.replace('@catename@',_catename);
		$(EBOOKCONVERT_TITLE).html(_convert_h3);
		$(EBOOKCONVERT_MESSAGE).html(_span);
		$(EBOOKCONVERT_TALK).html(_convert_talkbox_mwt_border_div);
		return true;
	}

	function ConvertCloseout(_link){
	  $('.status').text('Done');
	  $(EBOOKCONVERT_CONTAINER).addClass('done');
	  setTimeout(function(){
	    ConvertCancel(_link);
	  },5000);
	}
	
	function ConvertCancel(_link){
		$(EBOOKCONVERT_CONTAINER).attr('class','');
		$('.status').text('');
		$(EBOOKCONVERT_PROGRESS_CONTAINER).remove();
		$('.progressbar').remove();
		$('.preview').hide();
		if(_link){
			document.location.href=_link;
		}else{
			document.location.reload();
		}
	}

	function process(obj, _t, file){
		if(systemEnv.isBackend){
			_cid = $.getQuery('id');
		}else{
			_cid = bookEnv.currentCateId;
		}
		EbookAPIHandler.convertProcess(systemEnv.bsid,_cid,obj.detail.process_id,_t,file.name,function(obj){
			if(obj.code){
				alert(obj.msg);
				ConvertCancel(false);
			}else{
				$("#f"+obj.detail.timestamp).find(".progressbar").css("border","1px solid #8d31c2;");
				$("#f"+obj.detail.timestamp).find(".progressbar > div").css("background","#8d31c2");
				$("#f"+obj.detail.timestamp).find(".progressbar").progressbar({value:parseInt(obj.detail.rate)});
				if(obj.detail.rate=="100"){
				  ConvertCloseout(false);
				}else{
				  process(obj, _t, file);
				}
			}
		});
	}
	
	function bookSettings(){
    var rebuild_compleate_flag = false;
    var group_list = $.parseJSON($("#book_group_list_json").val());
    var main_url = $("#main_url_id").val();
    var run_convert_message = $("#run_convert").val();
    var convert_process_url = main_url + "index.php?action=book_convert_progress";
    var convert_url = main_url + "index.php?action=book_convert_build_regist";
    var skin;
    var spell;
    var process_id;
    var book_id;
    var process_flag = false;
    var default_palet_color = "#ffffff";
    var default_link_area_mouse_out_transparent_rate= 10;
    var default_link_area_mouse_out_width_rate= 1;
    var default_link_area_mouse_over_width_rate= 1;
    var default_link_area_mouse_over_transparent_rate = 40;
    var nonble_numcheck_error_message = $("#nonble_numcheck_error_message").val();

    var convert_settings = {"skin":"",
                           "group":"",
                           "onetime_token": "",
                           "setting": {
                           		 "convert_type":"html5",
                               "language":"chinese",
                               "spell": "",
                               "fliptype": "",
                               "link": {
                                   "mouseout_color": "",
                                   "mouseout_color_transparent": "",
                                   "mouseover_color": "",
                                   "mouseover_color_transparent": "",
                                   "mouseover_line_width": "",
                                   "mouseout_line_color": "",
                                   "mouseover_line_color": ""
                               },
                               "nonble": {
                                   "display": "",
                                   "startpage": "",
                                   "startpage_num": "",
                                   "lastpage_num": ""
                               },
                               "sns": {
                                   "facebook": 0,
                                   "twitter": 0,
                                   "instagram": 0
                               }
                           }
    };

    // skin
    $("#" + convert_settings["skin"]).css({"opacity":"0.4","border": "2px solid #0F29E7"});

    // spell
    convert_settings["setting"]["spell"] = "left";
    $("#spell-left").addClass("active");

    // flip
    convert_settings["setting"]["fliptype"] = "flip";
    $("#flip").addClass("active");

    // group
    convert_settings["group"] = group_list;

    // onetime_token
    convert_settings["onetime_token"] = $("#onetime_token").val();

    // after area
    $("#area_after_convert").css("display", "none");
    $("#alert").css("display", "none");

    // link
    convert_settings["setting"]["link"]["mouseout_color"] = default_palet_color;
    convert_settings["setting"]["link"]["mouseover_color"] = default_palet_color;
    convert_settings["setting"]["link"]["mouseout_line_color"] = default_palet_color;
    convert_settings["setting"]["link"]["mouseover_line_color"] = default_palet_color;

    // link width
    convert_settings["setting"]["link"]["mouseout_line_width"] = default_link_area_mouse_out_width_rate;
    convert_settings["setting"]["link"]["mouseover_line_width"] = default_link_area_mouse_over_width_rate;

    // link transperent
    convert_settings["setting"]["link"]["mouseout_color_transparent"] = default_link_area_mouse_out_transparent_rate; 
    convert_settings["setting"]["link"]["mouseover_color_transparent"] = default_link_area_mouse_over_transparent_rate;

    // nonble
    convert_settings["setting"]["nonble"]["display"] = 0;
    convert_settings["setting"]["nonble"]["startpage"] = $("#nonble_display_start_page").val();
    convert_settings["setting"]["nonble"]["startpage_num"] = $("#nonble_startpage_num").val();
    convert_settings["setting"]["nonble"]["lastpage_num"] = $("#nonble_lastpage_num").val();
	}
}
