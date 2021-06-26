<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_bookshelf_share.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="js/validation/sys_bookshelf_share.js"></script>
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
        $.post("sys_bookshelf_share.php?type=search_bookshelf", //post 
            $("#q").serialize(),  
                function(data){ 
                    //show results if more than 2 characters 
                    if (data != 'hide') { 
                        $("#bookshelf").html(data); 
                    } 
            }); 
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
					<form action="sys_bookshelf_share.php?type=<{if $smarty.get.type=='add'}>do_add<{else}>do_update<{/if}>&page=<{$smarty.get.page}>" method="post" enctype="multipart/form-data" class="forms" name="form" >
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_SHARE_SET_EDIT_TITLE}></div>
					<div class="portlet-content">
						<ul>
							<li>
								<label  class="desc">
								<{$smarty.const.LANG_SHARE_SET_EDIT_SELECTBOOKSHELF}>: 
								</label>
								<div>
										<div><{$smarty.const.LANG_SHARE_SET_EDIT_SEARCH}>: 
											<input type="text"maxlength="255" class="field text small" id="q" name="q" value="<{$smarty.get.q}>" />
										</div>
								</div>
								<div>
									<select name="bs_id" id="bookshelf" style="width: 300px" Size="10">
									<{foreach from=$bookshelf_data item="val" name=myloop}>
										<option value="<{$val.bs_id}>" <{if $val.bs_id==$data.bs_id}>selected<{/if}>><{$val.bs_name}></option>
									<{/foreach}>
									</select>
								</div>
							</li>
							<li>
								<label  class="desc">
								IP: 
								</label>
								<div>
									<input type="text"maxlength="255" class="field text small" id="bss_ip" name="bss_ip" value="<{$data.bss_ip}>" />
								</div>
							</li>
							<li>
								<label  class="desc">
								<{$smarty.const.LANG_SHARE_SET_EDIT_ACCOUNT}>: 
								</label>
								<div>
									<input type="text"maxlength="255" class="field text small" id="bss_account" name="bss_account" value="<{$data.bss_account}>" />
								</div>
							</li>
							<li>
								<label  class="desc">
								<{$smarty.const.LANG_SHARE_SET_EDIT_PASSWORD}>: 
								</label>
								<div>
									<input type="text"maxlength="255" class="field text small" id="bss_password" name="bss_password" value="<{$data.bss_password}>" />
								</div>
							</li>
							<li class="buttons">
								<input type="submit" value="<{$smarty.const.LANG_BUTTON_SAVE}>" class="submit" onclick="return check();" />
								<input type="button" value="<{$smarty.const.LANG_BUTTON_CANCEL}>" onclick="javascript:history.go(-1)"/>
							</li>
						</ul>
						<input type="hidden" name="id" value="<{$data.bss_id}>" />
					</div>
					</form>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
