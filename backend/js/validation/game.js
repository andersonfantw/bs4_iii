$(document).ready(function(){
	/*
	$('.mark_great').click(function(){
		params = $(this).val().split(',');
		mark(params[0], params[1], params[2], 1, function(data){
			if(data.code!=200){
				alert(data.msg);
			}
		});
	});
	$('.mark_writeagain').click(function(){
		params = $(this).val().split(',');
		mark(params[0], params[1], params[2], -1, function(data){
			if(data.code!=200){
				alert(data.msg);
			}
		});		
	});
	*/
	$('.grc_mark').change(function(){
		params = $(this).val().split(',');
		mark(params[0], params[1], params[2], params[3], function(data){
			if(data.code!=200){
				alert(data.msg);
			}
		});
	});
	function mark(bsid, bid, buid, val, handleData){
    $.ajax({
        type: "post",
        dataType: "json",
        url: "game.php?type=mark",
        data: {bsid:bsid, bid:bid, buid:buid, val:val},
        success: function(data){
            handleData(data);
        },
				error: function(jqXHR, exception){
        },
        async:false
    });
	}
});