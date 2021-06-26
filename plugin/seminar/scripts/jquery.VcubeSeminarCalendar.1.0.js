(function ($) {
$.fn.VCubeSeminarCalendar = function(options){
	var $this = this;

	var settings = {
		mode:'user',	//user,manager,webadmin
		calendarid:'#calendar',
		EventList:function(_mode,_start_limit,_end_limit,_roomid,returnFunction){
			_start_limit = _start_limit - 2592000; //86400 * 30
			VCubeSeminarAPIHandler.getReservationList(_mode,_start_limit,_end_limit,_roomid,function(data){
				for(var i=0;i<data.length;i++){
					if(data[i].in=='0'){
						data[i].title='OCCUPIED';
						data[i].url='';
					}
				}
				returnFunction(data);
			});
		},
		onAddEvent:function(_uid,_roomid,_name,_start,_end,_gid,_max,returnFunction){
			VCubeSeminarAPIHandler.addReservation(_uid,_roomid,_name,_start,_end,_gid,_max,function(data){
				returnFunction(data);
			});
		},
		onEditEvent:function(_seminarkey,_roomkey,_name,_start,_end,_gid,_max,returnFunction){
			VCubeSeminarAPIHandler.updateReservation(_seminarkey,_roomkey,_name,_start,_end,_gid,_max,function(data){
				returnFunction(data);
			});
		},
		onDelEvent:function(_seminarkey,returnFunction){
			VCubeSeminarAPIHandler.delReservation(_seminarkey,function(data){
				returnFunction(data);
			});
		},
		modifySchedule:false
	};

  if (options) {
      $.extend(settings, options);
  }
	$this.Calendar(settings);
};
})(jQuery);
