(function ($) {
$.fn.Calendar = function(options){
	var vcube_roomid = '#roomid';
	var vcube_select_room = 'select>option';
	
	var $this = this;

	var settings = {
		modifySchedule:false,
		EventList:null,
		onAddEvent:null,
		onEditEvent:null,
		onDelEvent:null,
		dialogPanel:'#dialog-panel',
		dialogShadow:'#dialog-shadow',
		mode:'user'	//user,manager,webadmin
	};

  if (options) {
      $.extend(settings, options);
  }
	$this.redirect = settings.redirect;
	var now = new Date();

	_init();
	function _init(){
	var _dialog = $(settings.dialogPanel).find( ".dialog-content" ).dialog('instance');
	if(!_dialog){
		//init modify panel
		$(settings.dialogShadow).click(function(){
			$(settings.dialogPanel).find( ".dialog-content" ).dialog("close");
			$this.fullCalendar('refetchEvents');
			$(settings.dialogShadow).hide();
		});
    for(i=0;i<24;i++){
			for(j=0;j<=30;j=j+30){
				str = ('0'+i).slice(-2)+':'+('0'+j).slice(-2);
				$(settings.dialogPanel).find('.time').append('<option value="'+str+'">'+str+'</option>');
			}
    }
    $this.find(".time option[value='09:00']").attr('selected', 'selected');
    for(i=30;i<=240;i=i+30){
    	_selected='';
    	if(i==60) _selected='selected';
    	$(settings.dialogPanel).find('.duration').append('<option value="'+i+'" '+_selected+'>'+i+'</option>');
    }
		VCubeAPIHandler.getAccountList(function(data){
			for(i=0;i<data.length;i++){
				$(settings.dialogPanel).find('.account').append('<option value="'+data[i].u_id+'">'+data[i].u_name+'</option>');
			}
		});
		VCubeAPIHandler.getGroupList(function(data){
			for(i=0;i<data.length;i++){
				$(settings.dialogPanel).find('.group').append('<option value="'+data[i].g_id+'">'+data[i].g_name+'</option>');
			}
		});
		$(settings.dialogPanel).find( ".dialog-content" ).dialog({
			appendTo: '.dialog-calendar',
			draggable: false,
			resizable: false,
			autoOpen: false,
			closeText: '',
			height: 380,
			width: 350,
			close: function(){
				$this.fullCalendar('refetchEvents');
				$(settings.dialogShadow).hide();
			},
			buttons: _dialogButtons('add')
		});
		$(settings.dialogPanel).find('.dialog-calendar').show();
		}
		_fullCalendar(settings.roomid);
	}
	function _dialogButtons(_method){
		var _buttons={};
		switch(_method){
			case 'edit':
				$(settings.dialogPanel).find('.group').prop( "disabled", true );
				break;
			case 'add':
				$(settings.dialogPanel).find('.group').prop( "disabled", false );
				break;
		}
		switch(_method){
			case 'edit':
				_buttons = {
					"Delete": function(){
						_reservationid = $(settings.dialogPanel).find('.reservationid').val();
						settings.onDelEvent(_reservationid,function(data){
							if(data){
								$this.fullCalendar( 'removeEvents', _reservationid);
							}else{
								
							}
						});
						$(settings.dialogPanel).find( ".dialog-content" ).dialog( "close" );
					}
				};
			case 'add':
				$.extend(true,_buttons,{
					"Ok": function() {
						_uid = $(settings.dialogPanel).find('.account option:selected').val();
						_uname = $(settings.dialogPanel).find('.account option:selected').text();
						_name = $(settings.dialogPanel).find('.classname').val();
						_start = $(settings.dialogPanel).find('.date').html()+' '+$(settings.dialogPanel).find('.time option:selected').val()+':00';
						_m = parseInt($(settings.dialogPanel).find('.duration option:selected').val());
						_end = moment(_start).add(_m,'m').format('YYYY-MM-DD HH:mm:ss');
						_gid = $(settings.dialogPanel).find('.group option:selected').val();
						_gname = $(settings.dialogPanel).find('.group option:selected').text();
						switch(_method){
							case 'add':
								settings.onAddEvent(_uid,settings.roomid,_name,_start,_end,_gid,function(data){
									if(data.code){
										alert(data.msg);
										$this.fullCalendar('refetchEvents');
									}else{
										eventData = {
											id:data.reservationid,
											title: data.name,
											start: moment(data.start),
											end: moment(data.end),
											className: Base64.encode(_m+'|'+ _uid +'='+_uname+'|'+settings.roomid+'|'+_gid+'='+_gname)
										};
										$this.fullCalendar('renderEvent', eventData, true);

										//reset dialog
										$(settings.dialogPanel).find(".time option[value='09:00']").attr('selected', 'selected');
										$(settings.dialogPanel).find('.duration option:selected').removeAttr('selected');
										$(settings.dialogPanel).find('.duration option[value=60]').attr('selected', 'selected');
									}
									$(settings.dialogPanel).find( ".dialog-content" ).dialog( "close" );
								});
								break;
							case 'edit':
								_reservationid = $(settings.dialogPanel).find('.reservationid').val();
								settings.onEditEvent(_reservationid,settings.roomid,_name,_start,_end,_gid,function(data){
									if(data.code){
										alert(data.msg);
										$this.fullCalendar('refetchEvents');
									}else{
										eventData = {
											id: _reservationid,
							        title: _name,
							        start: moment(_start),
							        end: moment(_end),
							        className: Base64.encode(_m+'|'+ _uid +'='+_uname+'|'+settings.roomid+'|'+_gid+'='+_gname)
										};
										$this.fullCalendar( 'removeEvents', _reservationid );
										$this.fullCalendar('renderEvent', eventData, true);

										//reset dialog
										$(settings.dialogPanel).find(".time option[value='09:00']").attr('selected', 'selected');
										$(settings.dialogPanel).find('.duration option:selected').removeAttr('selected');
										$(settings.dialogPanel).find('.duration option[value=60]').attr('selected', 'selected');
									}
									$(settings.dialogPanel).find( ".dialog-content" ).dialog( "close" );
								});
								break;
							}
					},
					Cancel: function() {
						$( this ).dialog( "close" );   
					}
				});
				break;
		}
		return _buttons;
	}

	function _fullCalendar(_roomid){
		$(vcube_roomid).val(_roomid);
		var d = new Date();
		_date = d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate();
		params={
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'agendaWeek,month'
			},
			defaultView:'agendaWeek',
			height:1200,
			defaultDate: _date,
      eventBorderColor:'#999',
      eventBackgroundColor:'#fff',
      eventTextColor:'#000',
			selectable: settings.modifySchedule,
			selectHelper: settings.modifySchedule,
			select : function(start, end) {
				if(settings.modifySchedule){
					$(settings.dialogPanel).find('.dialog-content').dialog({buttons:_dialogButtons('add')});
					$(settings.dialogPanel).find('.dialog-content').dialog("open");
					$(settings.dialogShadow).show();
					$(settings.dialogPanel).find('.dialog-content .date').html(start.format('YYYY-MM-DD'));
					if(start.hasTime()){
						$(settings.dialogPanel).find(".dialog-content .time option[value='"+start.format('HH:mm')+"']").attr("selected", true);
					}

					_uid = $(settings.dialogPanel).find('.account option:selected').val();
					_uname = $(settings.dialogPanel).find('.account option:selected').text();
					_name = $(settings.dialogPanel).find('.classroom').val();
					_start = $(settings.dialogPanel).find('.date').html()+'T'+$(settings.dialogPanel).find('.time option:selected').val();
					_m = parseInt($(settings.dialogPanel).find('.duration option:selected').val());
					_end = moment(_start).add(_m,'m').format('YYYY-MM-DD HH:mm:ss');
					_gid = $(settings.dialogPanel).find('.group option:selected').val();
					_gname = $(settings.dialogPanel).find('.group option:selected').text();					
					eventData = {
					        title: _name,
					        start: moment(_start),
					        end: moment(_start),
					        className: Base64.encode(_m+'|'+ _uid +'='+_uname+'|'+settings.roomid+'|'+_gid+'='+_gname)
					};
					$this.fullCalendar('renderEvent', eventData); // stick? = true
				}
			},
			eventClick: function(event) {
					if (event.url) {
						if(event.url.substr(0,3)=='api'){
							if(window.navigator.onLine){
								APIHandler.loginCheck(function(data){
									window.open('/plugin/'+zoomEnv.path+'/api/'+event.url+'.php?cmd=redirect&id='+event.id+'&name='+data.name);
								});
							}else{
								alert('Please check internet connection!');
							}
						}else{
							window.open(event.url);
						}
						return false;
					}else{
						if(settings.modifySchedule && (event.in==1)){
							$(settings.dialogPanel).find(".dialog-content" ).dialog({buttons:_dialogButtons('edit')});
							$(settings.dialogPanel).find(".dialog-content" ).dialog("open");
							$(settings.dialogShadow).show();
							$(settings.dialogPanel).find('.date').html(event.start.format('YYYY-MM-DD'));
							if(event.start.hasTime()){
								$(settings.dialogPanel).find(".time option[value='"+event.start.format('HH:mm')+"']").attr("selected", true);
							}
							$(settings.dialogPanel).find('.classroom').val(event.title);
							var ops = Base64.decode(event.className[0]).split('|');
							$(settings.dialogPanel).find(".account option[value='"+ops[1]+"']").attr("selected", true);
							$(settings.dialogPanel).find(".duration option[value='"+ops[0]+"']").attr("selected", true);
							$(vcube_roomid).val(ops[2]);
							$(settings.dialogPanel).find('.reservationid').val(event.id);
						}
					}
			},
			eventMouseover: function(calEvent, jsEvent) {
				var obj = _decodeParams(calEvent.className[0]);
				if(obj && (calEvent.title!='OCCUPIED')){
					var tooltip = '<div class="tooltipevent">' + calEvent.title + '<br />teacher: '+obj.uname+'<br />group: '+obj.gname+'</div>';
			    $("body").append(tooltip);
			  }
		    $(this).mouseover(function(e) {
		        $(this).css('z-index', 10000);
		        $('.tooltipevent').fadeIn('500');
		        $('.tooltipevent').fadeTo('10', 1.9);
		    }).mousemove(function(e) {
		        $('.tooltipevent').css('top', e.pageY + 10);
		        $('.tooltipevent').css('left', e.pageX + 20);
		    });
			},
			eventMouseout: function(calEvent, jsEvent) {
			    $(this).css('z-index', 8);
			    $('.tooltipevent').remove();
			},
			eventRender: function(event, element) {
				if(!$.isEmptyObject(event.url)){
					$(element).css('background-color','#FF4000');
				}else if(event.in){
					$(element).css('background-color','#FFCC99');
				}
			},
			eventResizeStop: function(event, jsEvent, ui, view){
				//event.end does not give new end time
				if(settings.modifySchedule){
					var ops = event.className[0].split('|');
					_reservationid = event.id;
					_roomid = ops[2];
					_name = event.title;
					_start = event.start.format('YYYY-MM-DD HH:mm:ss');
					_end = event.end.format('YYYY-MM-DD HH:mm:ss');
					_gid = $this.find('.group option:selected').val();
					_uid = ops[1];

					settings.onEditEvent(_reservationid,settings.roomid,_name,_start,_end,_gid,function(data){
						if(data.code){
							alert(data.msg);
							$this.fullCalendar('refetchEvents');
						}
					});
				}
			},
			eventStartEditable:false,
			eventDurationEditable:false,
			editable: settings.modifySchedule,
			eventLimit: true, // allow "more" link when too many events
			events: []
		};

		/*
		{
			title: 'Meeting',
			url: 'http://google.com/',
			start: '2015-02-12T10:30:00',
			end: '2015-02-12T12:30:00'
		}
		*/
		var d = new Date();
		d.setMonth(d.getMonth() - 1);
		_start_limit = Math.round(d.getTime()/1000);
		var d = new Date();
		d.setMonth(d.getMonth() + 3);
		_end_limit = Math.round(d.getTime()/1000);
		if(typeof token=='undefined') token='';
		settings.EventList(settings.mode,_start_limit,_end_limit,settings.roomid,token,function(data){
			for(var i=0;i<data.length;i++){
				if(data[i].in=='0'){
					data[i].title='OCCUPIED';
					data[i].url='';
				}
			}
			if(settings.appendSource){
				$this.fullCalendar('addEventSource',{events:data});
				$this.fullCalendar('renderEvents');
			}else{
				params.events=data;
				$this.fullCalendar('destroy');
				$this.fullCalendar(params);
			}
			$this.find('.fc-today-button').click();
		});
	}

	function _getParams(){
		_uid = $(settings.dialogPanel).find('.account option:selected').val();
		_uname = $(settings.dialogPanel).find('.account option:selected').text();
		_m = parseInt($(settings.dialogPanel).find('.duration option:selected').val());
		_gid = $(settings.dialogPanel).find('.group option:selected').val();
		_gname = $(settings.dialogPanel).find('.group option:selected').text();
		return Base64.encode(_m+'|'+ _uid +'='+_uname+'|'+settings.roomid+'|'+_gid+'='+_gname);
	}

	function _decodeParams(_className){
		if(Base64Matcher.test(_className)){
			var _str = Base64.decode(_className);
			var _arr = _str.split('|');
			var _account = _arr[1].split('=');
			var _group = _arr[3].split('=');
			obj = {
				duration: _arr[0],
				uid: _account[0],
				uname: _account[1],
				roomid: _arr[2],
				gid: _group[0],
				gname: _group[1]
			};
			return obj;
		}
	}
};
})(jQuery);
