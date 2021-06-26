$(document).ready(function(){
		LoginHandler.chkSingleLogin();
		setInterval(LoginHandler.chkSingleLogin, 120000);
		$('#QuickSearchTitleForm').bootstrapValidator({
		    message: 'This value is not valid',
		    feedbackIcons: {
		        valid: 'glyphicon glyphicon-ok',
		        invalid: 'glyphicon glyphicon-remove',
		        validating: 'glyphicon glyphicon-refresh'
		    },
		    fields: {
		    	QuickSearchName:{
		          validators: {
		          	notEmpty: {},
			          stringLength:{
			          		min:1,
			          		max:40
			          },
			          callback: {
			          	message: '請填寫全文檢索，或選擇查詢條件!',
			          	callback: function(value, validator){
										var objPL = new ParamList();
										objPL = getParams();
										if(objPL.isEmpty()){
											validator.updateMessage('QuickSearchName', 'callback', ' 請填寫全文檢索，或選擇查詢條件!');
											return false;
										}else return true;
				          }
			          }
		          }
		      },
		      QuickSearchShortName:{
		      		tigger: 'change',
		          validators: {
		          	notEmpty: {},
			          stringLength:{
			          		min:1,
			          		max:2
			          }
		          }
		      }
		    }
		}).on('error.field.bv', function(e, data) {
		    console.log('error.field.bv -->', data.element);
		    if (data.bv.getSubmitButton()) {
		      data.bv.disableSubmitButtons(false);
		    }
		}).on('success.field.bv', function(e, data) {
		    console.log('success.field.bv -->', data.element);
		    if (data.bv.getSubmitButton()) {
		      data.bv.disableSubmitButtons(false);
		    }
		}).on('added.field.bv', function(e, data) {
		    console.log('Added element -->', data.field, data.element);
		}).on('removed.field.bv', function(e, data) {
		    console.log('Removed element -->', data.field, data.element);
		});

		//arrCtl = Array('pwrf','prt','pi','pcu','pc','pcof');
		arrCtl = FulltextConst.ChosenKeys;
		var objPL = new ParamList();

		_template_option = '<option value="@key">@val</option>';
		_template_defaultoption = '<option value="">請選擇</option>';
		
    $('#most-visited button.md-menu').click(function(){
        $('#edit-link-dialog').show();
    });
    $('#edit-link-dialog #cancel').click(function(){
        $('#edit-link-dialog').hide();
    });

    $('main.index a.del').click(function(){
        $('#DelQuickSearch').modal('toggle')
    });
    $('#QuickSearchName').blur(function(){
    	if($(this).val()!='' && $('#QuickSearchShortName').val()==''){
    		$('#QuickSearchShortName').val($('#QuickSearchName').val().substr(0,2));
    		$('#QuickSearchTitleForm').data("bootstrapValidator").updateStatus('QuickSearchShortName','VALIDATED',null);
    	}
    });
    $('#reset').click(function(){
    	if($.getQuery('q')){
    		//edit mode reset
    		objPL.parse($.getQuery('q'));
console.log(objPL);
    		setValueToControl(objPL);
    	}else{
    		//search or add reset
				setValueToControl(objPL);
    	}
    });

    $('#save').click(function(){
 			objPL = getParams();
 			if($('#sid').val()>0){$('#EditQuickSearchTitle h2.modal-title a').html('更新');}
 			if(objPL.isEmpty()){
 				alert('請填寫全文檢索，或選擇查詢條件!');
 			}else{
				$('#EditQuickSearchTitle').modal('toggle');
			}
    });
    $('#submit').click(function(){
			objPL = getParams();
			_url = objPL.toUrl();
			if(objPL.isEmpty()){
				alert('請填寫全文檢索，或選擇查詢條件!');
			}else{
				document.location.href = '/search/list/?q='+_url;
			}
			return false;
    });
    
    $('#saveQuickSearch').click(function(){

    	$('#QuickSearchTitleForm').bootstrapValidator('validate');
    	if(!$('#QuickSearchTitleForm').data('bootstrapValidator').isValid()){
    		return;
    	}
    	_name = $('#QuickSearchName').val();
    	_shortname = $('#QuickSearchShortName').val(); 
 			SearchAPIHandler.checkQuickSearchName($('#sid').val(),_name,function(data){
 				if(data.hasName){
 					if(!confirm('您已經有相同名稱，請問要繼續儲存嗎?')){
 						return;
 					}
 				}
	    	objPL = getParams();
	    	objPL.urlEncode = false;
	    	_url = objPL.toUrl();
	    	p = _url.split('[@]');
	    	if($('#sid').val()>0){
		    	SearchAPIHandler.updateQuickSearch(p[0],p[1],p[2],p[3],function(data){
		    		if(data.code=='200'){
		    			alert('更新成功!');
		    			$('#EditQuickSearchTitle button.close').click();
		    			return false;
		    		}
		    	});
	    	}else{
		    	SearchAPIHandler.addQuickSearch(p[1],p[2],p[3],function(data){
		    		if(data.code=='200'){
		    			$('#sid').val(data.id);
		    			alert('新增成功!');
		    			$('#EditQuickSearchTitle button.close').click();
		    			return false;
		    		}
		    	});
		    }
	    	console.log(_url);

 			});
    });


		for(i=0;i<arrCtl.length;i++){
			TagAPIHandler.getAllTagByPKey(arrCtl[i],function(data){
				$('#'+data.pkey).empty();
				_arr = data.data;
				//_arr = data.data.sort();
				//_arr.reverse();
				for(var j in _arr){
					$('#'+data.pkey).append(_template_option.replace('@key',data.data[j].key).replace('@val',data.data[j].val));
				}
				$('#'+data.pkey).chosen({search_contains:true});
				$('#loaded').val(parseInt($('#loaded').val())+1);
				if($('#loaded').val()==8){
					setTimeout(function(){$('#reset').click();},500);
				}
			});
		}

		TagAPIHandler.getAllTagByPKey('year',function(data){
			$('#year_from').empty();
			$('#year_from').append(_template_defaultoption);
			$('#year_from').append(_template_option.replace('@key','thisyear').replace('@val','今年'));
			$('#year_from').append(_template_option.replace('@key','lastyear').replace('@val','去年'));
			$('#year_to').empty();
			$('#year_to').append(_template_defaultoption);
			$('#year_to').append(_template_option.replace('@key','thisyear').replace('@val','今年'));
			$('#year_to').append(_template_option.replace('@key','lastyear').replace('@val','去年'));
			for(var j in data.data){
				$('#year_from').append(_template_option.replace('@key',data.data[j].key).replace('@val',data.data[j].val));
				$('#year_to').append(_template_option.replace('@key',data.data[j].key).replace('@val',data.data[j].val));
			}
	    $('#year_from').chosen();
	    $('#year_to').chosen();
	    $('#year_from').on('change',function(evt, params){
	    	thisyear = (new Date()).getFullYear()-1911;
    		switch($('#year_from').val()){
    			case '':
    				TagAPIHandler.getAllTagByPKey('year',function(data){
							$('#year_to').empty();
							$('#year_to').append(_template_defaultoption);
							$('#year_to').append(_template_option.replace('@key','thisyear').replace('@val','今年'));
							$('#year_to').append(_template_option.replace('@key','lastyear').replace('@val','去年'));
							for(var j in data.data){
								$('#year_to').append(_template_option.replace('@key',data.data[j].key).replace('@val',data.data[j].val));
							}
    					$('#year_to').trigger("chosen:updated");
    				});
    				break;
    			case thisyear:
    			case 'thisyear':
    				$('#year_to').empty();
    				$('#year_to').trigger("chosen:updated");
    				break;
    			case thisyear-1:
    			case 'lastyear':
						$('#year_to').empty();
						$('#year_to').append(_template_defaultoption);
						$('#year_to').append(_template_option.replace('@key','thisyear').replace('@val','今年'));
						$('#year_to').trigger("chosen:updated");
    				break;
    			default:
    				TagAPIHandler.getAllTagByPKey('year',function(data){
    					$('#year_to').empty();
    					$('#year_to').append(_template_defaultoption);
							$('#year_to').append(_template_option.replace('@key','thisyear').replace('@val','今年'));
							$('#year_to').append(_template_option.replace('@key','lastyear').replace('@val','去年'));
    					for(var j in data.data){
    						if(data.data[j].val>$('#year_from option:selected').text()){
    							$('#year_to').append(_template_option.replace('@key',data.data[j].key).replace('@val',data.data[j].val));
    						}
    					}
    					$('#year_to').trigger("chosen:updated");
    				});
    				break;
    		}
	    });
		});
		
		if($.getQuery('q')){
			$('body').addClass('save');
			//do set param
		}

		/*
		return ParamList Object
		*/
		function getParams(){
			//get params from controls
			var o = new ParamList();
			o.addID($('#sid').val());
			o.addName($('#QuickSearchName').val());
			o.addShortname($('#QuickSearchShortName').val());
			for(i=0;i<FulltextConst.AllKeys.length;i++){
				_v = $('#'+FulltextConst.AllKeys[i]).val();
console.log(FulltextConst.AllKeys[i]);
console.log(_v);
				if(_v){
					if(['fulltext','pn','year_from','year_to'].indexOf(FulltextConst.AllKeys[i])>=0){
						_v =[_v];
					}
					for(j=0;j<_v.length;j++){
						_text = $('#'+FulltextConst.AllKeys[i]).find('option[value="'+_v[j]+'"]:selected').text();
console.log(FulltextConst.AllKeys[i]);
console.log(_v[j]);
console.log(_text);
						if(['fulltext','pn'].indexOf(FulltextConst.AllKeys[i])>=0){
							o.addText(FulltextConst.AllKeys[i],_v[j],_text);
						}else{
							o.addTag(FulltextConst.AllKeys[i],_v[j],_text);
						}
					}
				}
			}
			return o;
		}

		function setValueToControl(obj){
			//set params to controls
			$('#sid').val(obj.getValue('sid'));
			$('#QuickSearchName').val(obj.getValue('name'));
			$('#QuickSearchShortName').val(obj.getValue('shortname'));
	  	for(j=0;j<FulltextConst.ChosenKeys.length;j++){
	  		_v = obj[FulltextConst.ChosenKeys[j]].getKeys();
	  		switch(FulltextConst.ChosenKeys[j]){
	  			case 'year_from':
	  			case 'year_to':
	  				_v = _v.join('');
	  				break;
	  		}
				if(FulltextConst.ChosenKeys[j]=='year_from'){
					$('#year_from').val(_v).trigger("chosen:updated").change();
				}else if(FulltextConst.ChosenKeys[j]=='year_to'){
					setTimeout(function(){
	  				$('#year_to').val(_v).trigger("chosen:updated");
	  			},200);
	  		}else{
	  			$('#'+FulltextConst.ChosenKeys[j]).val(_v).trigger("chosen:updated");
	  		}
	  	}
	  	$('#fulltext').val(obj.getValue('fulltext'));
	  	$('#pn').val(obj.getValue('pn'));
		}
/*
    var options = {items:[
      {header: '功能'},
      {text: '編輯', href: 'advsearch.html'},
    ]}
    $('main.index > .row  >.col > div').contextify(options);
*/
});
