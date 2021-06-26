$(document).ready(function(){
	var param={
		path:web_url+'/plugin/tag/',
		script:['tag.class.1.0','jquery.lnettag.1.0','bootstrap-select.min'],
		css:['lnet-tag','bootstrap-select.min'],
		langJS:{'enable':true},
		langCSS:false,
		html:''
	};

	//loader example
	new Loader(param,function(_panel){
		params = {
			panel:_panel,
			mode: lnettagEnum.dropdownlist,
			method: 'getByPKey',
			pkey: 'Subject'
		};
		$('#subject').LnetTag(params);
	});
});
