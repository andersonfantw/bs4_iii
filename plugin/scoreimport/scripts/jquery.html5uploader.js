(function ($) {
    $.fn.html5Uploader = function (options) {

        var crlf = '\r\n';
        var boundary = "ttii";
        var dashes = "--";

				var querystring;
				if(systemEnv.isSysBackend){
					querystring = "&site=1&m="+mode;
				}else if(systemEnv.isBackend){
					querystring = "&site=1&m="+mode+"&bs="+systemEnv.bsid+"&uid="+$.cookie('adminid');
				}else{
					querystring = "&site=0&m="+mode+"&bs="+systemEnv.bsid+"&uid="+uid;
				}

        var settings = {
            "name": "uploadedFile",
            "postUrl": web_url+"/plugin/scoreimport/api/api.php?cmd=import"+querystring,
            "onClientAbort": null,
            "onClientError": null,
            "onClientLoad": null,
            "onClientLoadEnd": null,
            "onClientLoadStart": null,
            "onClientProgress": null,
            "onServerAbort": null,
            "onServerError": null,
            "onServerLoad": null,
            "onServerLoadStart": null,
            "onServerProgress": null,
            "onServerReadyStateChange": null,
            "onSuccess": null
        };

        if (options) {
            $.extend(settings, options);
        }

        return this.each(function (options) {
            var $this = $(this);
            if ($this.is('[type="file"]')) {
	            $this.bind("change", function () {
        	    	var files = this.files;
	    					if(files.length>1) alert('Only process one file at the time!');
	              //for (var i = 0; i < files.length; i++) {
	              //    fileHandler(files[i]);
	              //}
	    					fileHandler(files[0]);
	            });
            }else{
            	$this.bind("dragenter dragover", function () {
                	return false;
	        		}).bind("drop", function (e) {
								if($this.hasClass('converting') || $this.hasClass('done')){
		    					alert('Please wait!');
			    				return false;
		    				}else{
			    				var files = e.originalEvent.dataTransfer.files;
				    			if(files.length>1) alert('Only process one file at the time!');
									//for (var i = 0; i < files.length; i++) {
									//    fileHandler(files[i]);
									//}
						    	fileHandler(files[0]);
									return false;
								}
        			});
            }
        });

        function fileHandler(file) {
	    			var fileReader = new FileReader();
            fileReader.onabort = function (e) {
                if (settings.onClientAbort) {
                    settings.onClientAbort(e, file);
                }
            };
            fileReader.onerror = function (e) {
                if (settings.onClientError) {
                    settings.onClientError(e, file);
                }
            };
            fileReader.onload = function (e) {
                if (settings.onClientLoad) {
                    settings.onClientLoad(e, file);
                }
            };
            fileReader.onloadend = function (e) {
                if (settings.onClientLoadEnd) {
                    settings.onClientLoadEnd(e, file);
                }
            };
            fileReader.onloadstart = function (e) {
                if (settings.onClientLoadStart) {
                    settings.onClientLoadStart(e, this, file);
                }
            };
            fileReader.onprogress = function (e) {
                if (settings.onClientProgress) {
                    settings.onClientProgress(e, file);
                }
            };
            fileReader.readAsDataURL(file);

            var xmlHttpRequest = new XMLHttpRequest();
            xmlHttpRequest.upload.onabort = function (e) {
                if (settings.onServerAbort) {
                    settings.onServerAbort(e, file);
                }
            };
            xmlHttpRequest.upload.onerror = function (e) {
                if (settings.onServerError) {
                    settings.onServerError(e, file);
                }
            };
            xmlHttpRequest.upload.onload = function (e) {
                if (settings.onServerLoad) {
                    settings.onServerLoad(e, file);
                }
            };
            xmlHttpRequest.upload.onloadstart = function (e) {
                if (settings.onServerLoadStart) {
                    settings.onServerLoadStart(e, file);
                }
            };
            xmlHttpRequest.upload.onprogress = function (e) {
                if (settings.onServerProgress) {
                    settings.onServerProgress(e, file);
                }
            };
            xmlHttpRequest.onreadystatechange = function (e) {
                if (settings.onServerReadyStateChange) {
                    settings.onServerReadyStateChange(e, file, xmlHttpRequest.readyState);
                }
                if (settings.onSuccess && xmlHttpRequest.readyState == 4 && xmlHttpRequest.status == 200) {
                    settings.onSuccess(e, file, xmlHttpRequest.responseText);
                }
            };
	          xmlHttpRequest.open("POST", settings.postUrl, true);

	          if (file.getAsBinary) { // Firefox
	
	              var data = dashes + boundary + crlf +
	                  "Content-Disposition: form-data;" +
	                  "name=\"" + settings.name + "\";" +
	                  "filename=\"" + unescape(encodeURIComponent(file.name)) + "\"" + crlf +
	                  "Content-Type: application/octet-stream" + crlf + crlf +
	                  file.getAsBinary() + crlf +
	                  dashes + boundary + dashes;

	              xmlHttpRequest.setRequestHeader("Content-Type", "multipart/form-data;boundary=" + boundary);
	              xmlHttpRequest.sendAsBinary(data);
	
	          } else if (window.FormData) { // Chrome
	
	              var formData = new FormData();
	              formData.append(settings.name, file);
	
	              xmlHttpRequest.send(formData);
/*
								var size = file.size;
								var sliceSize = 256;
								var start = 0;
								
								setTimeout(loop, 1);
								
								function loop() {
								  var end = start + sliceSize;
								  if (size - end < 0) {
								    end = size;
								  }
								  
									var slice = file.mozSlice ? file.mozSlice :
				              file.webkitSlice ? file.webkitSlice :
				              file.slice ? file.slice : noop;
									var s = slice.bind(file)(start, end);
									
								  var formdata = new FormData();
									xmlHttpRequest.open("POST", settings.postUrl, true);

								  formdata.append('start', start);
								  formdata.append('end', end);
								  formdata.append('file', file);
								 
								  xmlHttpRequest.send(formdata);
								
								  if (end < size) {
								    start += sliceSize;
								    setTimeout(loop, 1);
								  }
								}
*/
	
	          }

        }
    };

})(jQuery);
