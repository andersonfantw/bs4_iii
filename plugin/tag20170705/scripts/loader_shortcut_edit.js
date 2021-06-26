$(document).ready(function(){
	var param={
		path:web_url+'/plugin/tag/',
		script:['tag.class.1.0','jquery.lnettag.1.1','shortcut'],
		css:['jquery.lnettag.shortcut','lnet-tag'],
		langJS:{'enable':true},
		langCSS:false,
		html:'selector.html'
	};

	new Loader(param,function(_panel){
		_tsid = $.getQuery('id');
		$('#tag1').LnetTag({panel:_panel,tsid:_tsid,seq:1,mode:lnettagEnum.shortcut});
		$('#tag2').LnetTag({panel:_panel,tsid:_tsid,seq:2,mode:lnettagEnum.shortcut});
		$('#tag3').LnetTag({panel:_panel,tsid:_tsid,seq:3,mode:lnettagEnum.shortcut});
	});
});