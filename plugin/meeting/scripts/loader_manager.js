$(document).ready(function(){
	var param={
		path:web_url+'/plugin/meeting/',
		script:['moment.min','fullcalendar','vcube.class.1.0','zoom.class.1.0','jquery.Calendar.1.0','jquery.VCubeCalendar.1.0','jquery.ZoomCalendar.1.0'],
		css:['fullcalendar','vcube'],
		langJS:{'enable':true},
		langCSS:false,
		html:'calendar.html'
	};

	//loader example
	new Loader(param,function(_panel){
		_panel = _panel.replace('@schedule@',_vcube_schedule)
										.replace('@Account@',_vcube_account)
										.replace('@Date@',_vcube_date)
										.replace('@Duration@',_vcube_duration)
										.replace('@ClassName@',_vcube_name)
										.replace('@Class@',_vcube_group);
		$('#main-content .hastable').append(_panel);
		$('.room').hide();
		$('#calendar').VCubeCalendar({mode:'manager'});
		$('#calendar').ZoomCalendar({mode:'manager',appendSource:true});
	});
});
