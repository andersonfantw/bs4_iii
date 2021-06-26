$(document).ready(function(){
	$("form").validate({
			rules: {
				name: {
					required: true,
					maxlength: 20
				},
				email: {
					required: true,
					maxlength: 100,
					email: true
				}
			},
			messages: {
				name: {
					required: "Please enter a username",
					maxlength: "Your name must consist of most 20 characters"
				},
				email: {
					required: "Please enter a valid email",
					maxlength: "Your email must consist of most 100 characters"
				}
			}
	});
});
