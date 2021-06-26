var tagselector_tag_panel = '.lnet-tag';
var tagselector_tagselector_panel = '#tag-selector';
var tagselector_input = '.lnet-tag>.input';
var tagselector_resize_button = '.lnet-tag .resize';
var tagselector_help_panel = '.lnet-tag-help';
var tagselector_help_button = '.lnet-tag .menu .help';
var tagselector_add_panel = '#tag-selector .add-panel';
var tagselector_add_panel_key = '#tag-selector .add-panel .tagkey>input';
var tagselector_add_panel_val = '#tag-selector .add-panel .tagval>input';
var tagselector_add_panel_save_button = '#tag-selector .add-panel .Save';
var tagselector_add_panel_cancel_button = '#tag-selector .add-panel .Cancel';
var tagselector_alltags = '.lnet-tag>span';
var tagselector_all_group_tags = '.lnet-tag>span:has(div)';
var tagselector_add_tag_button = '#tag-selector .button .add';
var tagselector_current_path = '.lnet-tag>.path';
var tagselector_current_pathinfo = '#tag-selector .pathinfo';
var tagselector_bid = '.lnet-tag>.bid';

	//var _tag_basepath = web_url+'/plugin/tag';
	var _tag_basepath = '/plugin/tag';

function init(bid){
//bid=2;
	$(tagselector_bid).val(bid);
	Tag_APIHandler.getBookTag(bid,function(json_tags){
		if(json_tags) set_tags(bid,json_tags);
	});
	Tag_APIHandler.getSuggestByDropDownList(bid,'',function(availableTags){
	  $(tagselector_input).autocomplete({
	    source: availableTags
	  });
	});
	Tag_APIHandler.getSuggestByChoosePanel(bid,'','',function(json_suggest_tags){
		if(json_suggest_tags){
			set_suggest_tags(json_suggest_tags);
			_path = json_suggest_tags.path;
		}
		$(tagselector_current_path).val(_path);
	});


	$(tagselector_resize_button).click(function(){
		if($(this).hasClass('max')){
			$(this).removeClass('max').addClass('min');
			$(tagselector_alltags).removeClass('showgroup').removeClass('hidegroup').addClass('open');
		}else{
			$(this).removeClass('min').addClass('max');
			$(tagselector_alltags).removeClass('open');
			$(tagselector_all_group_tags).addClass('hidegroup');
		}
		
	});
	$(tagselector_help_button).hover(
		function(){
			$(tagselector_help_panel).show();
		},
		function(){
			$(tagselector_help_panel).hide();
		}
	);
	$(tagselector_add_tag_button).click(function(){
		$(tagselector_add_panel).show();
	});
/*
	$(tagselector_add_panel).mouseleave(function(){
		confirm('save?');
		$(tagselector_add_panel).hide();
	});
*/
	$(tagselector_add_panel_save_button).click(function(){
		_path = $(tagselector_current_path).val();
		_key = $(tagselector_add_panel_key).val();
		_val = $(tagselector_add_panel_val).val();
		if(_val!='' && _key!=''){
			if(!input_filter(_key)){
				alert('Please enter 0-9, a-z, A-Z, and [space]');
				return;
			}
			if(!input_filter(_val)){
				alert('Please enter 0-9, a-z, A-Z, and [space]');
				return;
			}
			Tag_APIHandler.addTag(_path,_key,_val,function(tid){
				if(tid>0){
					//add suggest tag
					var _value = [];
					_value[0]=tid;
					_value[1]=_key;
					_value[2]=_val;
					_value[3]=_path;
					_value[4]=0;
/*
					var _value = '["@tid","@key","@val","@path","0"]';
					_value = _value.replace('@tid',tid)
							.replace('@key',_key)
							.replace('@val',_val)
							.replace('@path',_path);
*/
					var tag = new LNET_SUGGEST_TAGS();
					tag.val(_value);
					tag.add(tid,_key,_val,_path,0);
					$('#tag-selector ul').append(tag.toString());
				}else{
					alert('add tad error!');
				}
			});
			$(tagselector_add_panel_key).val('');
			$(tagselector_add_panel_val).val('');
			$(tagselector_add_panel).hide();
		}else{
			alert('Please enter key and value.');
		}
	});
	$(tagselector_add_panel_cancel_button).click(function(){
		$(tagselector_add_panel).hide();
	});
	$(tagselector_input).keyup(function(event){
		_val = $(tagselector_input).val();
		if(event.which==13 && (_val!='')){ //[Enter]
			if(_val.length>15){
				alert('Please input less then 15 characters.');return;
			}
			_path = '';
			_key = 'user-defined';
			Tag_APIHandler.setTag(bid,_path,_key,_val,function(data){
				switch(data.code){
					case '200':
						var tag = new LNET_TAGS();
						tag.add(0,_key,_val);
						$(tagselector_tag_panel).append(tag.toString());
						$(tagselector_tag_panel).append($(tagselector_input));
						$(tagselector_input).val('');
						break;
					case '302':
							alert('Duplicated!');
						break;
				}
				console.log(data);
			});
			//addTag($('.lnet-tag>input').val());
		}else{
			Tag_APIHandler.getSuggestByDropDownList(bid,_val,function(availableTags){
				$(tagselector_input).autocomplete({
					source: availableTags
				});
			});
		}
	});
	$(tagselector_tag_panel).click(function(){
		$(tagselector_input).focus();
		if($(tagselector_tagselector_panel).is(':visible')) $(tagselector_tagselector_panel).hide();
	});
	$(tagselector_tag_panel).dblclick(function(){
		$(tagselector_tagselector_panel).show();
	});
}
function set_tags(bid,json_tags){
	for(var i in json_tags){
		v = json_tags[i];
		tid = v[0][0];
		path = '';
		var tag = new LNET_TAGS(bid,path,tid);
		for(var k in v){
			val = v[k][2];
			tag.val(v);
			tag.add(v[k][0],v[k][1],v[k][2],v[k][3],v[k][4]);
		}
		$(tagselector_tag_panel).append(tag.toString());
		$(tagselector_tag_panel).append($('.lnet-tag>input'));
	}
}

function set_suggest_tags(json_suggest_tags){
	$('#tag-selector ul').html('');
	for(var i in json_suggest_tags.item){
		var tag = new LNET_SUGGEST_TAGS();
		v = json_suggest_tags.item[i];
		tag.val(v);
		tag.add(v[0],v[1],v[2],v[3],v[4]);
		$('#tag-selector ul').append(tag.toString());
	}
}

function input_filter(str){
	var reg = new RegExp("^([\u2E80-\u9FFF\\w ]+)$");
	return reg.test(str);
}
$(document).ready(function(){
	bid = $.getQuery('id');
	init(bid);
});
