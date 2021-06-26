(function ($) {
$.fn.LnetTagTagquiz = function(options){
	var lnettagquiz_current_path = '#tagselector .path';
	var lnettagquiz_tag_settagpanel = '#set-tag-panel';
	var lnettagquiz_tag_settagpanel_item_param = '#set-tag-panel .param';
	var lnettagquiz_tag_selector = '#tagselector';
	var lnettagquiz_tag_selector_panel = '#tagselector .panel';
	var lnettagquiz_tag_close = '#tagselector .close';
	var _template = '<ul><li data-seq="@seq@">@reportid@</li><li>@tags@</li></ul>';
	var _tag_template = '<div class="@class@"><span>@key@</span>:<span>@val@</span></div>';
	var $this = this;

	var settings = {
	}
  if (options) {
      $.extend(settings, options);
  }
	_loader();
	function _loader(){
		$this.prepend(settings.panel);
		switch(settings.method){
			case lnettagEnum.tagquiz_itutor:
				TagAPIHandler.getItutorQuiz(settings.key,function(data){
					for(i=0;i<data.quiz.length;i++){
						_tags = '';
						for(j=0;j<data.systags[data.quiz[i].seq].length;j++){
							if(data.systags[data.quiz[i].seq][j].length>0){
								_tags += _tag_template
											.replace('@class@','sys')
											.replace('@key@',data.systags[data.quiz[i].seq][j][0][1])
											.replace('@val@',data.systags[data.quiz[i].seq][j][0][2]);
							}
						}
						for(j=0;j<data.tags[data.quiz[i].seq].length;j++){
							if(data.tags[data.quiz[i].seq][j].length>0){
								_tags += _tag_template
											.replace('@class@','')
											.replace('@key@',data.tags[data.quiz[i].seq][j][0][1])
											.replace('@val@',data.tags[data.quiz[i].seq][j][0][2]);
							}
						}	
						_obj = _template
								.replace('@reportid@',data.quiz[i].reportid)
								.replace('@seq@',data.quiz[i].seq)
								.replace('@tags@',_tags);
						obj = $(_obj);
						$this.find(lnettagquiz_tag_settagpanel).append(obj);
						_param = {key:settings.key,reportid:data.quiz[i].seq};
						_openTagSelector(obj,_param);
					}
				});
				break;
			case lnettagEnum.tagquiz_infoacer:
				TagAPIHandler.getScanexamQuiz(settings.key,settings.date,function(data){
					for(i=0;i<data.quiz.length;i++){
						_tags = '';
						for(j=0;j<data.systags[data.quiz[i].seq].length;j++){
							if(data.systags[data.quiz[i].seq][j].length>0){
								_tags += _tag_template
											.replace('@class@','sys')
											.replace('@key@',data.systags[data.quiz[i].seq][j][0][1])
											.replace('@val@',data.systags[data.quiz[i].seq][j][0][2]);
							}
						}
						for(j=0;j<data.tags[data.quiz[i].seq].length;j++){
							if(data.tags[data.quiz[i].seq][j].length>0){
								_tags += _tag_template
											.replace('@class@','')
											.replace('@key@',data.tags[data.quiz[i].seq][j][0][1])
											.replace('@val@',data.tags[data.quiz[i].seq][j][0][2]);
							}
						}
						_obj = _template
								.replace('@reportid@',data.quiz[i].reportid)
								.replace('@seq@',data.quiz[i].seq)
								.replace('@tags@',_tags);
						obj = $(_obj);
						$this.find(lnettagquiz_tag_settagpanel).append(obj);
						_param = {bskey:settings.bskey,key:settings.key,date:settings.date,seq:data.quiz[i].seq};
						_openTagSelector(obj,_param);
					}
				});
				break;
		}

    $(lnettagquiz_tag_close).click(function(){
	_param = $(lnettagquiz_tag_settagpanel_item_param).val();
	_json = JSON.parse(Base64.decode(_param));

	var _arr = $(lnettagquiz_current_path).val().split(',');
        $(lnettagquiz_tag_selector).hide();
    });
    $(lnettagquiz_tag_close).click(function(){
	$(lnettagquiz_tag_settagpanel+' *').remove();
	_loader();
        $(lnettagquiz_tag_selector).hide();
    });
	}

	function _openTagSelector(obj,_params){
		$(obj).click(_param,function(e){
			//set tag selector panel
			$(lnettagquiz_tag_selector_panel+' *').remove();
			var param={
				path:web_url+'/plugin/tag/',
				script:['tag.class.1.0','jquery.lnettag.1.1'],
				css:['lnet-tag'],
				langJS:{'enable':true},
				langCSS:false,
				html:'selector.html'
			};
			new Loader(param,function(_panel){
				_bid = $.getQuery('id');
				params={panel:_panel,bid:_bid,mode:settings.method};
				$.extend(params,_params);
				$(lnettagquiz_tag_selector_panel).LnetTag(params) ;
			});
			_p = $(obj).position();
			$(lnettagquiz_tag_selector).css({top:_p.top+'px'});
			$(lnettagquiz_tag_selector).show();
			_tmp = Base64.encode(JSON.stringify(e.data));
			$(lnettagquiz_tag_settagpanel_item_param).val(_tmp);
		})
	}

	//_dockey,_reportid,_ptid,_tids
	//TagAPIHandler.setItutorQuiz(settings.key,_reportid,_ptid,_tids,function(data){
		
	//});
	//_bskey,_sekey,_setdate,_seq,_ptid,_uid,_tids
	//TagAPIHandler.setScanexamQuiz(_bskey,settings.key,_setdate,_seq,_ptid,_uid,_tids,function(data){
	//});

}
})(jQuery);
