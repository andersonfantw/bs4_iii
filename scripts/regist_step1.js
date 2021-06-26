$(document).ready(function(){
	$("form").validate({
		rules: {
			activecode: "required"
		},
		messages: {
			activecode: "Required!"
		}
	});
});
