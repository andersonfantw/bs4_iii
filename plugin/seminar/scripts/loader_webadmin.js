$(document).ready(function(){
	var param={
		path:web_url+'/plugin/seminar/',
		script:['moment.min','fullcalendar','VCubeSeminar.class.1.0','jquery.Calendar.1.0','jquery.VcubeSeminarCalendar.1.0','jquery.VcubeSeminarDDL.1.0'],
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
										.replace('@Class@',_vcube_group)

										.replace('@Max@',_vcube_max);
		$('#main-content .hastable').append(_panel);
		$('#seminar_room').VCubeSeminarDDL({mode:'webadmin',calendarid:'#calendar'});
	});
});
