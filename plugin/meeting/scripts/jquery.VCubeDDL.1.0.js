(function ($) {
$.fn.VCubeDDL = function(options){
	var vcube_roomid = '#roomid';

	var $this = this;

	var settings = {
		mode:'user',
		calendarid:'#calendar',
		EventList:function(_mode,_start_limit,_end_limit,_roomid,_token,returnFunction){
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

	_init();
	function _init(){
		switch(settings.mode){
			case 'webadmin':
				$this.change(function(){
					if($(this).val()){
						$(vcube_roomid).val($(this).val());
						settings.roomid=$(this).val();
						settings.modifySchedule=true;
						$(settings.calendarid).Calendar(settings);
					}
				});
				$this.append('<option value="">--Please Select--</option>');
				VCubeAPIHandler.login(function(){
					VCubeAPIHandler.action_get_room_list(function(data){
						//$this.attr("data-live-search","true");
						//$this.addClass('show-tick');
						list=null;
						if(data){
							list = data.room;
						}
						if(list){
							if(list.length){
								for(var j=0;j<list.length;j++){
									$this.append('<option value="'+list[j].room_info.room_id+'">'+list[j].room_info.room_name+'</option>');
								}
							}else{
								$this.append('<option value="'+list.room_info.room_id+'">'+list.room_info.room_name+'</option>');
							}

							//select the first room
							$this.find('option').eq(1).attr('selected', 'selected');
							$this.change();		
						}
					});
				});
				break;
			case 'manager|user':
			case 'manager':
			case 'user':
				$(settings.calendarid).Calendar(settings);
				break;
		}
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
};
})(jQuery);
