<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_reflectiongame.css" rel="stylesheet" media="all" />
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
					<form action="sys_game.php?type=<{if $smarty.get.type=='add'}>do_add<{else}>do_update<{/if}>&page=<{$smarty.get.page}>" method="post" enctype="multipart/form-data" class="forms" name="form" >
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_REFLECTIONGAME_EDIT_TITLE}></div>
					<div class="portlet-content">
						<ul>
							<li>
								<label  class="desc">
								<{$smarty.const.LANG_REFLECTIONGAME_EDIT_NAME}>: 
								</label>
								<div>
									<input type="text"maxlength="255" class="field text small" id="gr_name" name="gr_name" value="<{$data.gr_name}>" />
								</div>
							</li>
							<li>
									<label  class="desc">
										*<{$smarty.const.LANG_REFLECTIONGAME_EDIT_MAP}>
									</label>
									<div>
										<input type="file" class="field" name="map_img" value="" id="map_img" />
										<input type="hidden" name="file_id" value="<{$data.file_id}>" id="file_id" />
									</div>
									<div>
									<img src="<{$cover_image}>" />
									<input type="hidden" name="map_img_id" value="<{$map_image}>" id="map_img_id" />
								  </div>
						  </li>
							<li>
								<label  class="desc">
								<{$smarty.const.LANG_REFLECTIONGAME_EDIT_AMOUNT}>: 
								</label>
								<div>
									<{if $smarty.get.type=='add' }>
									<input type="text"maxlength="5" class="field text small" id="gr_width" name="gr_width" value="<{$data.gr_width}>" /> x
									<input type="text"maxlength="5" class="field text small" id="gr_height" name="gr_height" value="<{$data.gr_height}>" />
									<{else}>
									<{$data.gr_width}> x <{$data.gr_height}>
									<{/if}> 
								</div>
							</li>
							<li class="buttons">
								<input type="submit" value="<{$smarty.const.LANG_BUTTON_SAVE}>" class="submit" onclick="return check();" />
								<input type="button" value="<{$smarty.const.LANG_BUTTON_CANCEL}>" onclick="javascript:location.href='sys_game.php?type=search&q=<{$q_str}>'"/>
							</li>
						</ul>
						<input type="hidden" name="id" value="<{$data.bs_id}>" />
					</div>
					</form>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
