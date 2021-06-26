$(document).ready(function(){
	var param={
		path:web_url+'/plugin/blackboard/',
		script:['jquery.Blackboard','typed.min'],
		css:['blackboard'],
		langJS:{'@class_is_beginning':'_class_is_beginning'},
		langCSS:false,
		html:'notice.html'
	};

	var $Blackboard;
	//loader example
	new Loader(param,function(panel){
		$('#header').prepend(panel);
		$Blackboard = $('#blackboard').Blackboard({
			beforePlay : function(){
				$('#header').addClass('autoHeight');
			},
			setReloadSource : function(){
				isAddSource = false;
				EnableVcubeNotice();
			},
			stopCallback : function(){
				$('#header').removeClass('autoHeight');
			}
		});
		EnableVcubeNotice();
	});

	login_callbacks.add(EnableVcubeNotice);
	logout_callbacks.add(DisableVcubeNotice);

	var isAddSource = false;
	function EnableVcubeNotice(){
		APIHandler.loginCheck(function(l){
			if(l.type!='-' && !isAddSource){
				isAddSource = true;
				//add sources
				$Blackboard.Blackboard('addSource',function(returnFunc){
					VCubeAPIHandler.IsClassBegin(l.type,l.id,function(data){
						var arr = [];
						if(!$.isArray(data)) data = [data];
						for(i=0;i<data.length;i++){
							_info = buildBlockboardData(data[i]);
							_info.type = 'vcubemeeting';
							_info.link = '/plugin/meeting/api/redirect.php?cmd=get_vcube_url&reservationid='+data[i].id+'&name='+l.name;
							arr.push(_info);
							$Blackboard.Blackboard('show');
						}
						returnFunc(arr);
					});
				});
				$Blackboard.Blackboard('addSource',function(returnFunc){
					ZoomAPIHandler.IsClassBegin(l.type,l.id,function(data){
						var arr = [];
						if(!$.isArray(data)) data = [data];
						for(i=0;i<data.length;i++){
							_info = buildBlockboardData(data[i]);
							_info.type = 'zoom';
							_info.link = '/plugin/meeting/api/redirect.php?cmd=get_zoom_url&reservationid='+data[i].id+'&name='+l.name;
							arr.push(_info);
							$Blackboard.Blackboard('show');
						}
						returnFunc(arr);
					});
				});
				$Blackboard.Blackboard('addSource',function(returnFunc){
					VCubeSeminarAPIHandler.IsSeminarBegin(l.type,l.id,function(data){
						var arr = [];
						if(!$.isArray(data)) data = [data];
						for(i=0;i<data.length;i++){
							_info = buildBlockboardData(data[i]);
							_info.type = 'vcubeseminar';
							_info.link = '/plugin/seminar/api/redirect.php?cmd=get_vcubeseminar_url&seminarkey='+data[i].id+'&name='+l.name;
							arr.push(_info);
							$Blackboard.Blackboard('show');
						}
						returnFunc(arr);
					});
				});
				$Blackboard.Blackboard('play');
			}
		});
	}

	function buildBlockboardData(data){
		var _admissiontime= Date.parse(data.admissiontime);
		var _start= Date.parse(data.start);
		var _end= Date.parse(data.end);
		var _now = Date.now();

		var _msg = _class_is_beginning;
		if(_now < _admissiontime){
		}else if(_now > _admissiontime && _now < _start){
			_msg += _blackboard_countdown_meetingbegin;
		}else if(_now > _start && _now < _end){
			_msg += _blackboard_countdown_meetingend;
		}

		return {
			info: data.name+' '+_blackboard_admissiontime+' '+data.admissiontime+'<br />'+_blackboard_starttime+' '+data.start,
			msg: _msg,
			admissiontime: data.admissiontime,
			starttime: data.start,
			endtime: data.end
		};
	}
	
	function DisableVcubeNotice(){
		$Blackboard.Blackboard('destroy');
	}

});