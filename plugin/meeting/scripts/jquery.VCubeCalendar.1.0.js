(function ($) {
$.fn.VCubeCalendar = function(options){
	
	var $this = this;

	var settings = {
		mode:'user',	//user,manager,webadmin
		name:'VCube',
		calendarid:'#calendar',
		EventList:function(_mode,_start_limit,_end_limit,_roomid,_token,returnFunction){
			_start_limit = _start_limit - 86400;
			VCubeAPIHandler.getReservationList(_mode,_start_limit,_end_limit,_roomid,_token,function(data){
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
			VCubeAPIHandler.addReservation(_uid,_roomid,_name,_start,_end,_gid,function(data){
				returnFunction(data);
			});
		},
		onEditEvent:function(_reservationid,_roomid,_name,_start,_end,_gid,returnFunction){
			VCubeAPIHandler.updateReservation(_reservationid,_roomid,_name,_start,_end,_gid,function(data){
				returnFunction(data);
			});
		},
		onDelEvent:function(_reservationid,returnFunction){
			VCubeAPIHandler.delReservation(_reservationid,function(data){
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
