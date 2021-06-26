$(document).ready(function(){
	$('input[name=is_member][value=1]').click(function(){
		$('input[name=mybookshelf]').parent().show();
	});
	$('input[name=is_member][value=0]').click(function(){
		$('input[name=mybookshelf]').parent().hide();
	});
	$('input.submit').click(function(){
		if($('#bs_name').val()==''){
			alert(LANG_WARMING_BOOKSHELF_ENTER_NAME);
			return false;
		}
		if($('#bookshelf_accounts option:selected').length==0){
			alert(LANG_WARMING_BOOKSHELF_SELECT_ADMIN);
			return false;
		}
		return true;
	});
});
