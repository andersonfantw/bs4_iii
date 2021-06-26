$(document).ready(function(){
	var param={
		path:web_url+'/plugin/tag/',
		script:['tag.class.1.0','jquery.lnettag.1.1','bootstrap-select.min'],
		css:['lnet-tag','bootstrap-select.min'],
		langJS:{'enable':true},
		langCSS:false,
		html:'selector.html'
	};

	//loader example
	new Loader(param,function(_panel){
		param1={panel:_panel,method:'system',mode:lnettagEnum.setddl};
		param2={panel:_panel,method:'infoacer_pid',mode:lnettagEnum.setddl,id:0};
		$('#systemtag').LnetTag(param1);
		$('#infoacer_pid').LnetTag(param2);
	});
});
