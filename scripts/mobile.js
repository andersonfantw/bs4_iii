$(document).ready(function(){
	$('#content-left').css('height',document.documentElement.scrollHeight+'px');

	var _menustatus=false;
	window.addEventListener('deviceorientation',function(event){
		if(window.innerWidth < window.innerHeight){
			$('#body').removeClass('mobile_landscape').addClass('mobile_portrait');
			if(event.gamma>45){
				showMenu();
			}else if(event.gamma<-20){
				hideMenu();
			}
		}else{
			$('#body').removeClass('mobile_portrait').addClass('mobile_landscape');
		}
	});

	function showMenu(){
		if(!_menustatus){
			_menustatus=true;
			$('#content-left').animate({left:0});
		}
	}
	function hideMenu(){
		if(_menustatus){
			_menustatus=false;
			$('#content-left').animate({left:'-279px'});
		}
	}

});