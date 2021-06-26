<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_bookshelf.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="js/validation/sys_bookshelf.js"></script>
<script language="javascript">
$(function() {
	//clear search field & change search text color 
    $("#q").focus(function() { 
        $("#q").css('color','#333333'); 
        var sv = $("#q").val(); //get current value of search field 
        if (sv == 'Search') { 
            $("#q").val(''); 
        } 
    }); 

    //post form on keydown or onclick, get results 
    $("#q").bind('keyup click', function() { 
        $.post("sys_bookshelf.php?type=search_bookshelf_account", //post 
            $("#q").serialize(),  
                function(data){ 
                    //show results if more than 2 characters 
                    if (data != 'hide') { 
                        $("#bookshelf_accounts").html(data); 
                    } 
            }); 
    });

		var _prev_title=''
    $('#bookshelf_accounts').click(function(){
    	if($('#bs_name').val()=='' || $('#bs_name').val()==_prev_title){
    		_prev_title = $('#bookshelf_accounts option:selected').text()+" bookshelf";
    		$('#bs_name').val(_prev_title);
    	}
    });

});
</script>
<{/block}>
<{block name="content"}>
			<div id="main-content">
				<{if $status_code !=''}>
				<script>setTimeout(function(){jQuery('#status_bar').fadeOut('slow');}, 2000);</script>
				<div class="response-msg ui-corner-all <{$status_code}>" id="status_bar">
				  <{$status_desc}>
				</div>
				<{/if}>
				<div class="clearfix"></div>
				<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all form-container">
					<form action="sys_bookshelf.php?type=<{if $smarty.get.type=='add'}>do_add<{else}>do_update<{/if}>&page=<{$smarty.get.page}>" method="post" enctype="multipart/form-data" class="forms" name="form" >
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_BOOKSHELFS_EDIT_TITLE}></div>
					<div class="portlet-content">
						<ul>
							<li>
								<label class="desc">
								* <{$smarty.const.LANG_BOOKSHELFS_EDIT_BOOKSHELFNAME}>: 
								</label>
								<div>
									<input type="text" maxlength="255" class="field text small" id="bs_name" name="bs_name" value="<{$data.bs_name}>" />
								</div>
							</li>
							<li>
								<label class="desc">
								<{$smarty.const.LANG_BOOKSHELFS_EDIT_BOOKSHELFKEY}>: 
								</label>
								<div>
									<input type="text" maxlength="255" class="field text small" id="bs_key" name="bs_key" value="<{$data.bs_key}>" /><br />
									(default: account + bsid)
								</div>
							</li>
							<li>
								<label class="desc">
								<{$smarty.const.LANG_BOOKSHELFS_EDIT_EXPIRED_LINK}>: 
								</label>
								<div>
									<input type="text" maxlength="255" class="field text large" id="expiredlink" name="expiredlink" value="<{$data.expiredlink}>" />
								</div>
							</li>
							<li>
              	<label><{$smarty.const.LANG_BOOKSHELFS_EDIT_AUTH}>: </label>
              	<{if $smarty.get.type=='add'}>
                	<label><input type="radio" name="is_member" value="0" <{if $data.is_member==0 || $smarty.get.type=='add'}>checked="checked"<{/if}>/> <{$smarty.const.LANG_CONST_PUBLIC}></label>
                  <label><input type="radio" name="is_member" value="1" <{if $data.is_member==1 || $smarty.get.type=='add'}>checked="checked"<{/if}>/> <{$smarty.const.LANG_CONST_MEMBER}></label>
                  <{else}>
                  	<{if $data.is_member==1}><{$smarty.const.LANG_CONST_MEMBER}><{else}><{$smarty.const.LANG_CONST_PUBLIC}><{/if}>
                  <{/if}>
              </li>
							<{if $smarty.const.GiantviewSystem || $smarty.const.GiantviewChat || $smarty.const.CONFIG_MYBOOKSHELF }>
							<li>
							<label class="desc"><{$smarty.const.LANG_SYSBOOKSHELFS_EDIT_GIANTVIEW}>: </label>
							<div>
								<{if $smarty.const.ENABLE_GIANTVIEW }>
									<{if $smarty.const.GiantviewSystem }>
									<span><input type="checkbox" name="giantviewsystem" value="1" <{if $data.giantviewsystem==1}>checked<{/if}> /> <{$smarty.const.LANG_SYSBOOKSHELFS_ENABLE_GIANTVIEW_SYSTEM}></span>
									<{/if}>
									<{if $smarty.const.GiantviewChat }>
									<span><input type="checkbox" name="giantviewchat" value="1" <{if $data.giantviewchat==1}>checked<{/if}> /> <{$smarty.const.LANG_SYSBOOKSHELFS_ENABLE_GIANTVIEW_CHAR}></span>
									<{/if}>
								<{/if}>
								<{if $smarty.const.CONFIG_MYBOOKSHELF }>
									<span><input type="checkbox" name="mybookshelf" value="1" <{if $data.mybookshelf==1}>checked<{/if}> /> <{$smarty.const.LANG_SYSBOOKSHELFS_ENABLE_MYBOOKSHELF}></span>
								<{/if}>
							</div>
							</li>
							<{/if}>
							<{if $smarty.get.type=='add'}>
							<li>
								<label  class="desc">
								<{$smarty.const.LANG_SYSBOOKSHELFS_EDIT_OWNER}>: 
								</label>
								<div>
										<div><{$smarty.const.LANG_SYSBOOKSHELFS_EDIT_SEARCH}>: 
											<input type="text"maxlength="255" class="field text small" id="q" name="q" value="<{$smarty.get.q}>" />
										</div>
								</div>
								<div>
									<select name="u_id" id="bookshelf_accounts" style="width: 300px" Size="10">
									<{$bookshelf_account_data_html}>
									</select>
								</div>
							</li>
							<{else}>
								<{$smarty.const.LANG_SYSBOOKSHELFS_EDIT_OWNER}>: <{$smarty.get.u_name}>
							<{/if}>
							<li class="buttons">
								<input type="submit" value="<{$smarty.const.LANG_BUTTON_SAVE}>" class="submit" onclick="return check();" />
								<input type="button" value="<{$smarty.const.LANG_BUTTON_CANCEL}>" onclick="javascript:location.href='sys_bookshelf.php?type=search&q=<{$q_str}>'"/>
							</li>
						</ul>
						<input type="hidden" name="id" value="<{$data.bs_id}>" />
						<input type="hidden" name="bookshelf_q" value="<{$q_str}>" />
					</div>
					</form>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
