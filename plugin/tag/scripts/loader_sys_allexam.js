$(document).ready(function(){
	var param={
		path:web_url+'/plugin/tag/',
		script:['tag.class.1.0','jquery.lnettag.1.1','bootstrap-select.min'],
		css:['lnet-tag','bootstrap-select.min'],
		langJS:{'enable':true},
		langCSS:false,
		html:''
	};

	//loader example
	new Loader(param,function(_panel){
		keyValues = ToolsHandler.decodeURL('id');
		params = {
			panel:_panel,
			mode: lnettagEnum.dropdownlist,
			method: 'infoacer_pid',
			bskey: (keyValues['bskey'])?keyValues['bskey']:'',
			key: (keyValues['key'])?keyValues['key']:$.getQuery('id'),
      date: (keyValues['date'])?keyValues['date']:'1900-1-1'
		};
		if(!params.key) params.key = $.getQuery('id');
		$('#tags').LnetTag(params);
	});
});
