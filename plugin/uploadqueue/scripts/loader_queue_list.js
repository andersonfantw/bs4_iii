$(document).ready(function(){
	var param={
		path:web_url+'/plugin/uploadqueue/',
		script:['uploadqueue.class','jquery.chkConvertProgress'],
		css:['jquery.chkConvertProgress'],
		langJS:{},
		langCSS:false,
		html:''
	};

	new Loader(param,function(){
		$('#progressing').chkConvertProgress({
			lnettoken:lnettoken,
			reloadlist:function(){
				UploadqueueAPIHandler.reloadList(function(data){
					$('#queue_table').html(data);
				});
			}
		});
	});
});