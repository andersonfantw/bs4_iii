<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_setup.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="js/validation/sys_setup.js"></script>
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
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_SETUP}></div>
					<div class="portlet-content">	
						<div class="title title-spacing">
							<h2><{$smarty.const.LANG_SETUP_TITLE_INFO}></h2>							
						</div>
						<form action="sys_setup.php?type=do_update" method="post" enctype="multipart/form-data" class="forms" name="form2" >
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
                  <input type="button" class="field" name="bs_remove_file" value="Delete" i="" id="bs_remove_file">
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
									<input type="text" class="field text medium" name="bs_header_height" value="<{$data.bs_header_height}>" /> px (214px)
								</div>
							</li>
							<li>
								<label  class="desc">
								<{$smarty.const.LANG_SETUP_FOOTERIMG}>: 
								</label>
								<div>
                  <input type="button" class="field" name="bs_remove_footerfile" value="Delete" i="" id="bs_remove_footerfile">
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
							<{if common::getcookie('sysuser')=='admin'}>
							<li>
								<div class="title title-spacing">
															
								</div>
								<label class="desc">
								<{$smarty.const.LANG_SETUP_GOOGLECODE}>: 
								</label>
								<div>
									<textarea tabindex="2" cols="50" rows="5" class="field textarea medium" name="google_code" ><{$data.google_code}></textarea>
								</div>
							</li>
							<li>
								<label class="desc">
								最大書櫃數目: 
								</label>
								<div>
									<input type="text" class="field text medium" name="bs_number" value="<{$data.bs_number}>" />
								</div>
							</li>
							<li>
								<label class="desc">
								附屬工具: 
								</label>
								<div>
									<input type="checkbox" name="b_writer" value="1" <{if $data.b_writer==1}>checked<{/if}>/> 作者、共同作者
									<input type="checkbox" name="b_link" value="1" <{if $data.b_link==1}>checked<{/if}>/> 連結
									<input type="checkbox" name="b_imglink" value="1" <{if $data.b_imglink==1}>checked<{/if}>/> 圖片連結
								</div>
							</li>
							<{/if}>
							<li class="buttons">
								<input type="submit" value="<{$smarty.const.LANG_BUTTON_SAVE}>" class="submit" onclick="return check();" />
							</li>
						</ul>
						</form>						
					</div>					
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
