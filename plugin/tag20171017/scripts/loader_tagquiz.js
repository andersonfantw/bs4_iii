$(document).ready(function(){
	var param={
		path:web_url+'/plugin/tag/',
		script:['tag.class.1.0','jquery.lnettag.tagquiz.1.0'],
		css:['tagquiz'],
		langJS:{},
		langCSS:false,
		html:'tagquiz.html'
	};

	//loader example
	new Loader(param,function(_panel){
		_id = $.getQuery('id');
		if(_id==parseInt(_id)){
			params = {
				panel:_panel,
				method: lnettagEnum.tagquiz_itutor,
				key: _id
			};
		}else{
			keyValues = ToolsHandler.decodeURL('id');
			if(keyValues['date']){
				params = {
					panel:_panel,
					method: lnettagEnum.tagquiz_infoacer,
					bskey: (keyValues['bskey'])?keyValues['bskey']:'',
					key: (keyValues['key'])?keyValues['key']:$.getQuery('id'),
					date: (keyValues['date'])?keyValues['date']:'1900-1-1'
				};
			}else{
				params = {
					panel:_panel,
					method: lnettagEnum.tagquiz_itutor,
					key: (keyValues['key'])?keyValues['key']:$.getQuery('id')
				};				
			}
		}
		$('#quiztags').LnetTagTagquiz(params);
	});
});