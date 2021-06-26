$(document).ready(function(){
	$('#bs_remove_file').click(function(){
		$('#del_bs_header').val($('#bs_header').val());
		$('#bs_header').val(0);
		$('#header_image').val('');
		$('#header_image').prev().attr('src','').css('height','0px');
	});
$('#bs_remove_footerfile').click(function(){
		$('#del_bs_footer').val($('#bs_footer').val());
                $('#bs_footer').val(0);
                $('#footer_image').val('');
                $('#footer_image').prev().attr('src','').css('height','0px');
        });

});
