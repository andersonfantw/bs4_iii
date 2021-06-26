$(document).ready(function(){
	var param={
		path:web_url+'/plugin/tag/',
		script:['tag.class.1.0'],
		css:['lnet-tag'],
		langJS:{'enable':true},
		langCSS:false,
		html:''
	};
	var param_search={
		path:web_url+'/plugin/search/',
		script:['jquery.chosen.min'],
		css:['jquery.chosen.min'],
		langJS:false,
		langCSS:false,
		html:''
	};
	//loader example
	var _template_option = '<option value="@key@">@val@</option>';
	new Loader([param,param_search],function(){
		TagAPIHandler.getAllTagByPKey('year',function(data){
			_options=_template_option.replace('@key@','').replace('@val@','');
			for(var j in data.data){
				_options+=_template_option.replace('@key@',data.data[j].t_id+':'+data.data[j].val).replace('@val@',data.data[j].val);
			}
			$('#year').append(_options);
			$('#year').chosen();
		});
		$('input[type=radio][name=te_type]').change(function(){
			if($('input[type=radio][name=te_type]:checked').val()==2){
				$('#oldtag').prop('multiple','multiple');
				$('#newtag').prop('multiple','');
				$('#year').parent().parent().hide();
			}else{
				$('#oldtag').prop('multiple','');
				$('#newtag').prop('multiple','multiple');
				$('#year').parent().parent().show();
			}
			$('#oldtag').chosen("destroy");	
			$('#newtag').chosen("destroy");
			$('#oldtag').chosen({search_contains:true});
			$('#newtag').chosen({search_contains:true});
		});
		$('#oldtag').chosen({search_contains:true});
		$('#newtag').chosen({search_contains:true});
		init('pi');

		$('#tag_pi').click(function(){
			init('pi');
		});
		$('#tag_pcu').click(function(){
			init('pcu');
		});
	});

	function init(ptag){
		$('#oldtag').empty();
		$('#newtag').empty();
		TagAPIHandler.getTagByPKey(ptag,function(data){
			_options=_template_option.replace('@key@','').replace('@val@','');
			for(var j in data.data){
				_options+=_template_option.replace('@key@',data.data[j].t_id+':'+data.data[j].val).replace('@val@',data.data[j].val);
			}
			$('#oldtag').append(_options);
			$('#newtag').append(_options);
			$('#oldtag').trigger("chosen:updated");	
			$('#newtag').trigger("chosen:updated");
		});

	}
});
