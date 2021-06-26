$(document).ready(function(){
	$('input.submit').click(function(e){
		$('.forms select').each(function(){
			if($(this).val()==''){
				alert(LANG_WARMING_ALLEXAM_SET_TAGS);
				e.preventDefault();
				return false;
			}
		});
		return true;
	});
});

