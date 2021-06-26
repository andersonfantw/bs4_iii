(function ($) {
$.fn.VCubeSeminarDDL = function(options){
	var vcube_roomid = '#roomid';

	var $this = this;

	var settings = {
		calendarid:'#calendar'
	}

  if (options) {
      $.extend(settings, options);
  }

	_init();
	function _init(){
		switch(settings.mode){
			case 'webadmin':
				$this.change(function(){
					if($(this).val()){
						$(vcube_roomid).val($(this).val());
						settings.roomid=$(this).val();
						settings.modifySchedule=true;
						$(settings.calendarid).VCubeSeminarCalendar(settings);
					}
				});
				$this.append('<option value="">--Please Select--</option>');
					VCubeSeminarAPIHandler.getRoomList(function(data){
						//$this.attr("data-live-search","true");
						//$this.addClass('show-tick');
						list=null;
						if(data){
							list = data.room;
						}
						if(list){
							if(list.length){
								for(var j=0;j<list.length;j++){
									$this.append('<option value="'+list[j].room_key+'">'+list[j].room_name+'</option>');
								}
							}else{
								$this.append('<option value="'+list.room_key+'">'+list.room_name+'</option>');
							}

							//select the first room
							$this.find('option').eq(1).attr('selected', 'selected');
							$this.change();
						}
					});
				break;
			case 'manager|user':
			case 'manager':
			case 'user':
				$(settings.calendarid).VCubeSeminarCalendar(settings);
				break;
		}
	}

};
})(jQuery);
