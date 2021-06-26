$(document).ready(function(){
	var param={
                path:web_url+'/plugin/tag/',
                script:['tag.class.1.0','jquery.lnettag.1.0'],
		css:['lnet-tag'],
		langJS:{'enable':true},
		langCSS:false,
		html:''
	};

	//loader example
/*
	new Loader(param,function(){
		_bid = $.getQuery('id');
		params={bid:_bid,mode:lnettagEnum.viewer};
		$('#tag').LnetTag(params);
	});
*/
	//loader example
	new Loader(param,function(){
		_bid = $.getQuery('id');
		params={
			bid:_bid,
			mode:lnettagEnum.viewer,
			onSuggestTagSelect:function(e){
				_search();
			},
			onSelectedTagDelete:function(e){
				_search();
			}
		};
		$('#tag').LnetTag(params);
	});

	function _search(){
		var _arr = [];
		$('.lnet-tag>span>input[name="tagid[]"]').each(function(){
			_arr.push($(this).val());
		});
		if($("#q").val()=='' && _arr.length==0){
			document.location.reload();
		}else{
			$.post("book.php?type=search_top10", //post 
				{q:$("#q").val(),tagid:_arr},
				function(data){ 
					$("#books_table").html(data);
				}
			);
		}
	}	
});
