$(document).ready(function(){
	var param={
		path:web_url+'/plugin/tag/',
		script:['tag.class.1.0','jquery.lnettag.1.1'],
		css:['lnet-tag'],
		langJS:{'enable':true},
		langCSS:false,
		html:''
	};

	//loader example
	new Loader(param,function(){
		_bid = $.getQuery('id');
		params={bid:_bid,mode:lnettagEnum.create_system_tag};
		$('#tag').LnetTag(params);
	});
});
