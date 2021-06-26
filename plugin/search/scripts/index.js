$(document).ready(function(){
		LoginHandler.chkSingleLogin();
		setInterval(LoginHandler.chkSingleLogin, 120000);

    $('[data-toggle="tooltip"]').tooltip();

    $('#most-visited button.md-menu').click(function(){
        $('#edit-link-dialog').show();
    });
    $('#edit-link-dialog #cancel').click(function(){
        $('#edit-link-dialog').hide();
    });
    
    $('main.index a.del').click(function(){
        $('#DelQuickSearch').modal('toggle')
    });
    $('#submit').click(function(){
        $('#EditQuickSearchTitle').modal('toggle')
    });
    $('#accordion-1 .card-header').mouseover(function(){
        $('#accordion-1 .item-1').addClass('show');
        $('#accordion-1 .card-header span').text('');
    });
    $('#accordion-1 .card-header').mouseleave(function(){
        $('#accordion-1 .item-1').removeClass('show');
        $('#accordion-1 .card-header span').text($('#accordion-1 .card-body .card-text').text());
console.log($('#accordion-1 .card-body .card-text').text());
    });

		$('#fulltext').keypress(function(e){
			if(e.keyCode == 13){
				if($('#fulltext').val()==''){
					alert('請輸入關鍵字!');
				}else{
					var objPL = new ParamList();
					objPL.addText('fulltext',$('#fulltext').val());
					_url = objPL.toUrl();
					document.location.href = '/search/list/?q='+_url;
				}
			}
		});
		
		SearchAPIHandler.getQuickSearch(bs_id,function(data){
			for(i=0;i<data.length;i++){
				_template = '<div class="col"><div data-id="@id@" data-param="@param@"><a href="#" class="del">x</a><a href="/search/list/?q=@q@" class="link w2" data-toggle="tooltip" title="@condition@">@shortname@</a><span>@name@</span></div></div>';
				_str = _template.replace('@id@',data[i].q_id)
					.replace('@param@',data[i].q_content)
					.replace('@shortname@',data[i].q_shortname)
					.replace('@name@',data[i].q_name);
				var objPL = new ParamList();
				objPL.addID(data[i].q_id);
				objPL.addName(data[i].q_name);
				objPL.addShortname(data[i].q_shortname);
				objPL.parseTags(data[i].q_content);
				_str = _str.replace('@q@',objPL.toUrl()).replace('@condition@',objPL.toText());
				$('main.index > .row').prepend(_str);
			}
			$('main.index > .row a.del').click(function(){
				if(confirm('您確定要刪除嗎?')){
					_this = $(this).parent().parent();
					_id = $(this).parent().data('id');
					SearchAPIHandler.delQuickSearch(_id,function(){
						_this.remove();
					});
				}
			});
		});
});
