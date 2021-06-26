$(document).ready(function(){
	var param={
		path:web_url+'/plugin/tag/',
		script:['tag.class.1.0','jquery.lnettag.1.0'],
		css:['lnet-tag','jstree.min'],
		langJS:{'enable':true},
		langCSS:false,
		html:'selector.html'
	};

	//loader example
	new Loader(param,function(_panel){
		_bid = $.getQuery('id');
		params={panel:_panel,bid:_bid,mode:lnettagEnum.create_system_tag};
		$('#tag').LnetTag(params);
	});
});
