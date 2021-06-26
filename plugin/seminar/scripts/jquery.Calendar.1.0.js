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

	var now = new Date();

	_init();
	function _init(){
		//init modify panel
		$(settings.dialogShadow).click(function(){
			$(settings.dialogPanel).find( ".dialog-content" ).dialog("close");
			$this.fullCalendar('refetchEvents');
			$(settings.dialogShadow).hide();
		});
		var _dialog = $(settings.dialogPanel).find( ".dialog-content" ).dialog('instance');
		if(!_dialog){
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
			VCubeSeminarAPIHandler.getAccountList(function(data){
				for(i=0;i<data.length;i++){
					$(settings.dialogPanel).find('.account').append('<option value="'+data[i].u_id+'">'+data[i].u_name+'</option>');
				}
			});
			VCubeSeminarAPIHandler.getGroupList(function(data){
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
						_name = $(settings.dialogPanel).find('.classname').val();
						_start = $(settings.dialogPanel).find('.date').html()+' '+$(settings.dialogPanel).find('.time option:selected').val()+':00';
						_m = parseInt($(settings.dialogPanel).find('.duration option:selected').val());
						_end = moment(_start).add(_m,'m').format('YYYY-MM-DD HH:mm:ss');
						_gid = $(settings.dialogPanel).find('.group option:selected').val();
						_max = $(settings.dialogPanel).find('.max').val();
						switch(_method){
							case 'add':
								settings.onAddEvent(_uid,settings.roomid,_name,_start,_end,_gid,_max,function(data){
									if(data.code){
										alert(data.msg);
										$this.fullCalendar('refetchEvents');
									}else{
										eventData = {
											id:data.reservationid,
											title: data.name,
											start: moment(data.start),
											end: moment(data.end),
											className:_m+'|'+ _uid +'|'+ settings.roomid + '|' + _max
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
								_uid = $(settings.dialogPanel).find('.account option:selected').val();
								_reservationid = $(settings.dialogPanel).find('.reservationid').val();
								_max = $(settings.dialogPanel).find('.max').val();
								settings.onEditEvent(_reservationid,settings.roomid,_name,_start,_end,_gid,_max,function(data){
									if(data.code){
										alert(data.msg);
										$this.fullCalendar('refetchEvents');
									}else{
										eventData = {
											id: _reservationid,
										        title: _name,
										        start: moment(_start),
										        end: moment(_end),
										        className:_m+'|'+ _uid +'|'+ settings.roomid +'|'+_max
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
			height:1000,
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

					title = $(settings.dialogPanel).find('.classroom').val();
					_start = $(settings.dialogPanel).find('.date').html()+'T'+$(settings.dialogPanel).find('.time option:selected').val();
					start = moment(_start);
					_m = $(settings.dialogPanel).find('.duration option:selected').val();
					end = moment(_start).add(_m,'m');
					eventData = {
					        title: title,
					        start: start,
					        end: end
					};
					$this.fullCalendar('renderEvent', eventData); // stick? = true
				}
			},
			eventClick: function(event) {
				if (event.url) {
					switch(event.url){
						case 'redirect':
							APIHandler.loginCheck(function(data){
								
								window.open('/plugin/'+vcubeEnv.path+'/api/redirect.php?cmd=get_vcube_url&reservationid='+event.id+'&name='+data.name);
							});
							break;
						default:
							window.open(event.url);
							break;
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
						var ops = event.className[0].split('|');
						$(settings.dialogPanel).find(".account option[value='"+ops[1]+"']").attr("selected", true);
						$(settings.dialogPanel).find(".duration option[value='"+ops[0]+"']").attr("selected", true);
						$(vcube_roomid).val(ops[2]);
						$(settings.dialogPanel).find('.reservationid').val(event.id);
					}
				}
			},
			eventMouseover: function(calEvent, jsEvent) {
			    var tooltip = '<div class="tooltipevent">' + calEvent.title + '</div>';
			    $("body").append(tooltip);
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
					_max = ops[3];

					settings.onEditEvent(_reservationid,settings.roomid,_name,_start,_end,_gid,_max,function(data){
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
    _start_limit = Math.round(d.getTime()/1000);
    d.setMonth(d.getMonth() + 3);
    _end_limit = Math.round(d.getTime()/1000);
		settings.EventList(settings.mode,_start_limit,_end_limit,settings.roomid,function(data){
			for(var i=0;i<data.length;i++){
				if(data[i].in=='0'){
					data[i].title='OCCUPIED';
					data[i].url='';
				}
			}
			params.events=data;
			$this.fullCalendar('destroy');
			$this.fullCalendar(params);
			$this.find('.fc-today-button').click();
		});
	}
};
})(jQuery);
