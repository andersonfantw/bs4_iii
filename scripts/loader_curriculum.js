/*****************************************
 * check device and load belong js, css, html
 * *****************************************/

$(document).ready(function(){
	var params={
		path:'/',
		script:[],
		css:['curriculum'],
		langJS:{},
		langCSS:false,
		html:''
	};

	new Loader(params,function(){
		if(!ToolsHandler.isMobile()){
			$('#icon_vcube5').attr('href','https://mtg5l.vcube.com/services/download/flow.php?action_win32Download=&con_app=cn_win32');
			$('#icon_vcube5').show();
		}else if(ToolsHandler.isiOS){
			$('#icon_vcube5').attr('href','https://itunes.apple.com/tw/app/v-cube-meeting-5/id977971984?l=zh&mt=8');
			$('#icon_vcube5').show();
		}else	if(ToolsHandler.isAndroid){
			$('#icon_vcube5').attr('href','https://play.google.com/store/apps/details?id=com.vcube.mobile.vrms5');
			$('#icon_vcube5').show();
		}else{
			$('#icon_vcube5').hide();
		}
	});
});
