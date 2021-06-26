(function ($) {
$.fn.VCubeMap = function(options){
	var vcube_roomid = '#roomid';
	var vcube_select_room = '.room>select>option';
	
	var $this = this;

	var settings = {
		calenderid:'#calendar',
		mode:'user'	//user,manager,webadmin
	};

  if (options) {
      $.extend(settings, options);
  }

	_init();
	function _init(){
		$this.parent().preppend(settings.panel);

		//init modify panel
		$this.parent().find('.shadow').click(function(){
			$this.parent().find( ".dialog-content" ).dialog("close");
			$(settings.calendarid).fullCalendar('refetchEvents');
			$this.parent().find('.shadow').hide();
		});
    for(i=0;i<24;i++){
			for(j=0;j<=30;j=j+30){
				str = ('0'+i).slice(-2)+':'+('0'+j).slice(-2);
				$this.parent().find('.time').append('<option value="'+str+'">'+str+'</option>');
			}
    }
    $this.parent().find(".time option[value='09:00']").attr('selected', 'selected');
    for(i=30;i<=240;i=i+30){
    	_selected='';
    	if(i==60) _selected='selected';
    	$this.parent().find('.duration').append('<option value="'+i+'" '+_selected+'>'+i+'</option>');
    }
		VCubeAPIHandler.getAccountList(function(data){
			for(i=0;i<data.length;i++){
				$this.parent().find('.account').append('<option value="'+data[i].u_id+'">'+data[i].u_name+'</option>');
			}
		});
		VCubeAPIHandler.getGroupList(function(data){
			for(i=0;i<data.length;i++){
				$this.parent().find('.group').append('<option value="'+data[i].g_id+'">'+data[i].g_name+'</option>');
			}
		});
		$this.find( ".dialog-content" ).dialog({
			appendTo: '.dialog-calender',
			draggable: false,
			resizable: false,
			autoOpen: false,
			closeText: '',
			height: 380,
			width: 350,
			close: function(){
				$this.parent().find('.calendar').fullCalendar('refetchEvents');
				$this.parent().find('.shadow').hide();
			},
			buttons: _dialogButtons('add')
		});
		switch(settings.mode){
			case 'webadmin':
				VCubeAPIHandler.login(function(){
					VCubeAPIHandler.action_get_room_list(function(data){
						_room = null;
						if(data){
							_room = data.room;
						}
						$obj = _createDropDownList(_room,function(_roomid,obj){
							$(seetings.calenderid).Calendar({
								onAddEvent:_dialogButtons('add'),
								onEditEvent:_dialogButtons('edit'),
								onDelEvent:null,
								modifySchedule:true
							});
						});
						$obj.change(function(){
							$(vcube_roomid).val($(this).val());
						});
						$this.append($obj);
						//select the first room
						$this.find(vcube_select_room).eq(1).attr('selected', 'selected');
						$this.find(vcube_select_room).change();
					});
				});
				break;
			case 'manager|user':
			case 'manager':
			case 'user':
				$(seetings.calenderid).Calendar({modifySchedule:false});
				break;
		}
		$this.append('<input id="roomid" type="hidden" />');
	}
	function _dialogButtons(_method){
		var _buttons={};
		switch(_method){
			case 'edit':
				$this.parent().find('.group').prop( "disabled", true );
				break;
			case 'add':
				$this.parent().find('.group').prop( "disabled", false );
				break;
		}
		switch(_method){
			case 'edit':
				_buttons = {
					"Delete": function(){
						_reservationid = $this.find('.reservationid').val();
						VCubeAPIHandler.delReservation(_reservationid,function(data){
							if(data){
								$(settings.calendarid).fullCalendar( 'removeEvents', _reservationid);
							}else{
								
							}
						});
						$this.parent().find( ".dialog-content" ).dialog( "close" );
					}
				};
			case 'add':
				$.extend(true,_buttons,{
					"Ok": function() {
						_uid = $this.parent().find('.account option:selected').val();
						_name = $this.parent().find('.classname').val();
						_start = $this.parent().find('.date').html()+' '+$this.find('.time option:selected').val()+':00';
						_m = parseInt($this.parent().find('.duration option:selected').val());
						_end = moment(_start).add(_m,'m').format('YYYY-MM-DD HH:mm:ss');
						_gid = $this.parent().find('.group option:selected').val();
						switch(_method){
							case 'add':
								VCubeAPIHandler.addReservation(_uid,_roomid,_name,_start,_end,_gid,function(data){
									if(data.code){
										alert(data.msg);
										$(settings.calendarid).fullCalendar('refetchEvents');
									}else{
										eventData = {
											id:data.reservationid,
											title: data.name,
											start: moment(data.start),
											end: moment(data.end),
											className:_m+'|'+ _uid +'|'+ _roomid
										};
										$(settings.calendarid).fullCalendar('renderEvent', eventData, true);

										//reset dialog
										$this.find(".time option[value='09:00']").attr('selected', 'selected');
										$this.find('.duration option:selected').removeAttr('selected');
										$this.find('.duration option[value=60]').attr('selected', 'selected');
									}
									$this.find( ".dialog-content" ).dialog( "close" );
								});
								break;
							case 'edit':
								_uid = $this.find('.account option:selected').val();
								_reservationid = $this.find('.reservationid').val();
								_roomid = $(vcube_roomid).val();
								VCubeAPIHandler.updateReservation(_reservationid,_roomid,_name,_start,_end,_gid,function(data){
									if(data.code){
										alert(data.msg);
										$(settings.calendarid).fullCalendar('refetchEvents');
									}else{
										eventData = {
											id: _reservationid,
							        title: _name,
							        start: moment(_start),
							        end: moment(_end),
							        className:_m+'|'+ _uid +'|'+ _roomid
										};
										$(settings.calendarid).fullCalendar( 'removeEvents', _reservationid );
										$(settings.calendarid).fullCalendar('renderEvent', eventData, true);

										//reset dialog
										$this.find(".time option[value='09:00']").attr('selected', 'selected');
										$this.find('.duration option:selected').removeAttr('selected');
										$this.find('.duration option[value=60]').attr('selected', 'selected');
									}
									$this.find( ".dialog-content" ).dialog( "close" );
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
	

	function _emptySelectElement(){
		obj=$('<select></select>');
		obj.attr("data-live-search","true");
		obj.addClass('show-tick');
		obj.append('<option value="">--Please Select--</option>');
		return obj;
	}
	function _createDropDownList(list,returnFunc){
		$obj=_emptySelectElement();
		if(list){
			if(list.length){
				for(var j=0;j<list.length;j++){
					$obj.append('<option value="'+list[j].room_info.room_id+'">'+list[j].room_info.room_name+'</option>');
				}
			}else{
				$obj.append('<option value="'+list.room_info.room_id+'">'+list.room_info.room_name+'</option>');
			}
			
			$obj.change(function(){
				if($(this).val()){
					_roomid=$(this).val();
					returnFunc(_roomid,this);
				}
			});
		}
		return $obj;
	}

	function _CalendarEvent(_roomid,returnFunction){
		/*
		{
			title: 'Meeting',
			url: 'http://google.com/',
			start: '2015-02-12T10:30:00',
			end: '2015-02-12T12:30:00'
		}
		*/
		var _events = [];
		var d = new Date();
    _start_limit = Math.round(d.getTime()/1000);
    d.setMonth(d.getMonth() + 3);
    _end_limit = Math.round(d.getTime()/1000);

		switch(settings.mode){
			case 'webadmin':
			case 'manager|user':
			case 'manager':
				VCubeAPIHandler.getReservationList(settings.mode,_start_limit,_end_limit,_roomid,function(data){
					APIHandler.loginCheck(function(l){
						for(var i=0;i<data.reservations.reservation.length;i++){
							_start = moment(parseInt(data.reservations.reservation[i].reservation_start_date)*1000).format('YYYY-MM-DD HH:mm:ss');
							_end = moment(parseInt(data.reservations.reservation[i].reservation_end_date)*1000).format('YYYY-MM-DD HH:mm:ss');
							_duration = (parseInt(data.reservations.reservation[i].reservation_end_date)*1000 - parseInt(data.reservations.reservation[i].reservation_start_date))/1000;
							if(data.reservations.reservation[i].in){
								_events.push({
									id:data.reservations.reservation[i].reservation_id,
									title:data.reservations.reservation[i].reservation_name,
									url:data.reservations.reservation[i].url,
									start:_start,
									end:_end,
									in:1,
									className:_duration+'|'+l.id+'|'+_roomid
								});
							}else{
								_events.push({
									id:data.reservations.reservation[i].reservation_id,
									title:'OCCUPIED',
									url:'',
									start:_start,
									end:_end,
									in:0,
									className:_duration+'|'+l.id+'|'+_roomid
								});
							}
						}
						returnFunction(_events);
					});
				});
				break;
			case 'user':
				VCubeAPIHandler.getReservationList(settings.mode,_start_limit,_end_limit,'',function(data){
					for(var i=0;i<data.length;i++){
						_events.push({
							id:data[i].vmc_reservationid,
							title:data[i].vmc_name,
							url:data[i].url,
							start:data[i].vmc_start,
							end:data[i].vmc_end,
							in:1,
							className:data[i].duration +'|'+ data[i].u_id +'|'+ data[i].vmc_roomid
						});
					}
					returnFunction(_events);
				});
				break;
		}
	}
};
})(jQuery);