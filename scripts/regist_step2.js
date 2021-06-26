$(document).ready(function(){
	$('.birth').datepicker({changeYear: true,yearRange: "-80:+0"});

	jQuery.validator.addMethod('regex',function(value, element, regexpr){
		return regexpr.test(value);
	});
	$("form").validate({
			rules: {
				name: {
					required: true,
					maxlength: 20
				},
				birthday: {
					required: true,
					maxlength: 10,
					regex: /^[01][0-9]\/[0-3][0-9]\/(19|20)[0-9]{2}$/
				},
				email: {
					required: true,
					maxlength: 100,
					email: true,
					remote: '/api/api.php?cmd=chkUserEmail'
				},
				account: {
					required: true,
					minlength: 6,
					maxlength: 20,
					remote: '/api/api.php?cmd=chkUserAccount'
				},
				password: {
					required: true,
					minlength: 6,
					maxlength: 20
				},
				confirm_password: {
					required: true,
					minlength: 6,
					maxlength: 20,
					equalTo: "#password"
				},
				career: {
					required: true
				}
			},
			messages: {
				name: {
					required: "Please enter a username",
					maxlength: "Your name must consist of most 20 characters"
				},
				birthday: {
					required: "Please enter a birthday",
					maxlength: "Your birthday must consist of most 10 characters",
					regex: "Please enter valid date format mm/dd/yyyy"
				},
				email: {
					required: "Please enter a valid email",
					maxlength: "Your email must consist of most 100 characters"
				},
				account: {
					required: "Please enter a account",
					maxlength: "Your account must consist of most 20 characters",
					minlength: "Your account must be at least 6 characters long"
				},
				password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 6 characters long"
				},
				confirm_password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 6 characters long",
					equalTo: "Please enter the same password as above"
				},
				career: {
					required: "Please choose your career"
				}
			}
	});
});
