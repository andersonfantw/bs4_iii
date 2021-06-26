<{extends file="backend/base.tpl"}>
<{block name="head"}>
<link href="css/customize/setup.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="js/validation/setup.js"></script>
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
					<form action="setup.php?type=do_update" method="post" enctype="multipart/form-data" class="forms" name="form" >
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_SETUP}></div>
					<div class="portlet-content">	
						<div class="title title-spacing">
							<h2><{$smarty.const.LANG_SETUP_TITLE_SETUP}></h2>					
						</div>
						<ul>
							<li>
								<label><{$smarty.const.LANG_SETUP_ONLIST}>: </label>
								<label><input type="checkbox" name="bs_list_status" value="1" <{if $data.bs_list_status==1}>checked="checked"<{/if}>/></label>
							</li>
							<li>
            		<label><{$smarty.const.LANG_SETUP_BOOKBUTTON}>: </label>
              	<label><input type="checkbox" name="is_webbook" value="1" <{if $data.is_webbook==1}>checked="checked"<{/if}>/> <{$smarty.const.LANG_SETUP_WEBBOOK}></label>
                <label><input type="checkbox" name="is_ibook" value="1" <{if $data.is_ibook==1}>checked="checked"<{/if}>/> <{$smarty.const.LANG_SETUP_IBOOK}></label>
              </li>
<{*
							<li>
            		<label><{$smarty.const.LANG_SETUP_TRANSCRIPT}>: </label>
              	<label><input type="checkbox" name="is_transcript" value="1" <{if $data.is_webbook==1}>checked="checked"<{/if}>/> <{$smarty.const.LANG_SETUP_TRANSCRIPT}></label>
              </li>
*}>
							<{if $smarty.const.ENABLE_GIANTVIEW && ($smarty.const.GiantviewSystem || $smarty.const.GiantviewChat) }>
							<li>
								<label><{$smarty.const.LANG_SETUP_GIANTVIEW_CHAT}>: </label>
								<{if $smarty.const.GiantviewChat}>
								<label><input type="checkbox" name="enable_giantview_chat" value="1" <{if $data.enable_giantview_chat==1}>checked="checked"<{/if}>/> <{$smarty.const.LANG_SETUP_GIANTVIEW_CHAT}></label>
								<{/if}>
								<{if $smarty.const.GiantviewSystem}>
								<label><input type="checkbox" name="enable_giantview_system" value="1" <{if $data.enable_giantview_system==1}>checked="checked"<{/if}>/> <{$smarty.const.LANG_SETUP_GIANTVIEW_SYSTEM}></label>
								<{/if}>
							</li>
							<{/if}>
<{*
							<li>
								<label>CloudConver : </label>
								<label><input type="checkbox" name="is_cloudconvert" value="1" <{if $data.is_cloudconvert==1}>checked="checked"<{/if}>/></label>
							</li>
*}>
							<{if $data.is_member!=1}>
							<li>
								<label>All Book: </label>
								<input type="checkbox" name="is_allbook" value="1" <{if $data.is_allbook!=0}>checked="checked"<{/if}>/>
							</li>
							<li>
								<label>New Book: </label>
								<input type="checkbox" name="is_newbook" value="1" <{if $data.is_newbook!=0}>checked="checked"<{/if}>/>
							</li>
							<{/if}>
						</ul>
						</ul>					
						<div class="title title-spacing">
							<h2><{$smarty.const.LANG_SETUP_TITLE_INFO}></h2>							
						</div>
						<ul>
							<li>
								<label  class="desc">
								<{$smarty.const.LANG_SETUP_BOOKSHELFTITLE}>: 
								</label>
								<div>
									<input type="text"maxlength="255" class="field text small" id="bs_title" name="bs_title" value="<{$data.bs_title}>" />
								</div>
							</li>
              <li>
                <label  class="desc">
                <{$smarty.const.LANG_SETUP_HEADERLINK}>
                </label>
                <div>
									<input type="text"maxlength="255" class="field text large" id="bs_header_link" name="bs_header_link" value="<{$data.bs_header_link}>" />
                </div>
              </li>
							<li>
								<label  class="desc">
								<{$smarty.const.LANG_SETUP_HEADERIMG}>: 
								</label>
								<div>
									<input type="button" class="field" name="bs_remove_file" value="Delete" id="bs_remove_file" />
									<input type="file" class="field" name="bs_header_file" value="" id="bs_header_img" />
									<input type="hidden" name="bs_header" value="<{$data.bs_header}>" id="bs_header" />
									<input type="hidden" name="del_bs_header" value="" id="del_bs_header" />
								</div>
								<div>
									<img src="<{$path_header_image}>" />
									<input type="hidden" name="header_image" value="<{$header_image}>" id="header_image" />
								</div>
								</li>
							<li>
								<label  class="desc">
								<{$smarty.const.LANG_SETUP_HEADER_HEIGHT}>: 
								</label>
								<div>
									<input type="text" class="field text medium" name="bs_header_height" value="<{$data.bs_header_height}>" /> px (ex:214px)
								</div>
							</li>
							<li>
								<label  class="desc">
								<{$smarty.const.LANG_SETUP_FOOTERIMG}>: 
								</label>
								<div>
									<input type="button" class="field" name="bs_remove_footerfile" value="Delete" id="bs_remove_footerfile">
									<input type="file" class="field" name="bs_footer_file" value="" id="bs_footer_img" />
									<input type="hidden" name="bs_footer" value="<{$data.bs_footer}>" id="bs_footer" />
									<input type="hidden" name="del_bs_footer" value="" id="del_bs_footer" />
								</div>
								<div>
									<img src="<{$path_footer_image}>" />
									<input type="hidden" name="footer_image" value="<{$footer_image}>" id="footer_image" />
								</div>
								</li>
							<li>
								<label  class="desc">
								<{$smarty.const.LANG_SETUP_FOOTER_HEIGHT}>: 
								</label>
								<div>
									<input type="text" class="field text medium" name="bs_footer_height" value="<{$data.bs_footer_height}>" /> px
								</div>
							</li>
							<li>
								<label class="desc">
								<{$smarty.const.LANG_SETUP_FOOTER_TEXT}>: 
								</label>
								<div>
									<textarea tabindex="2" cols="50" rows="5" class="field textarea medium" name="bs_footer_content" ><{$data.bs_footer_content}></textarea>
								</div>
							</li>
							<li class="buttons">
								<input type="submit" value="<{$smarty.const.LANG_BUTTON_SAVE}>" class="submit" onclick="return check();" />
							</li>
						</ul>
						<input type="hidden" name="mid" value="<{$data.is_member}>" />
					</div>
					</form>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
