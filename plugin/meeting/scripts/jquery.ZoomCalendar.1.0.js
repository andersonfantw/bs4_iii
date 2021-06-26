(function ($) {
$.fn.ZoomCalendar = function(options){
	var zoom_roomid = '#roomid';

	var $this = this;

	var settings = {
		mode:'user',
		name:'Zoom',
		calendarid:'#calendar',
		EventList:function(_mode,_start_limit,_end_limit,_roomid,_token,returnFunction){
			_start_limit = _start_limit - 86400;
			ZoomAPIHandler.getReservationList(_mode,_start_limit,_end_limit,_roomid,_token,function(data){
				for(var i=0;i<data.length;i++){
					if(data[i].in=='0'){
						data[i].title='OCCUPIED';
						data[i].url='';
					}
				}
				returnFunction(data);
			});
		},
		onAddEvent:function(_uid,_roomid,_name,_start,_end,_gid,returnFunction){
			ZoomAPIHandler.addReservation(_uid,_roomid,_name,_start,_end,_gid,function(data){
				returnFunction(data);
			});
		},
		onEditEvent:function(_uuid,_roomid,_name,_start,_end,_gid,returnFunction){
			ZoomAPIHandler.updateReservation(_uuid,_roomid,_name,_start,_end,_gid,function(data){
				returnFunction(data);
			});
		},
		onDelEvent:function(_reservationid,returnFunction){
			ZoomAPIHandler.delReservation(_reservationid,function(data){
				returnFunction(data);
			});
		},
		modifySchedule:false
	};

  if (options) {
      $.extend(settings, options);
  }

	$(settings.calendarid).Calendar(settings);

};
})(jQuery);
