<{extends file="backend/base.tpl"}>
<{block name="head"}>
<link href="css/customize/shortcut.css" rel="stylesheet" media="all" />

<script type="text/javascript" src="../scripts/loader.class.js"></script>
<script type="text/javascript" src="../plugin/tag/scripts/loader_shortcut_edit.js"></script>
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
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_BOOKS_EDIT_TITLE}></div>
					<div class="portlet-content">
						<form action="shortcut.php?type=<{if $smarty.get.type=='add'}>do_add<{else}>do_update<{/if}>&page=<{$smarty.get.page}>" method="post" enctype="multipart/form-data" class="forms" name="form" >
							<ul>
								<li>
									<label  class="desc">
										*<{$smarty.const.LANG_SHORTCUT_EDIT_NAME}>
									</label>
									<div>
										<input type="text"maxlength="255" class="field text small" id="ts_name" name="ts_name" value="<{$data.ts_name}>" />
										<input type="button" name="preview" value="<{$smarty.const.LANG_SHORTCUT_BTN_PREVIEW}>" id="preview" />
										<br />
										<div id="shortcutimg">
											<{$data.img_html}>
										</div>
										<input type="hidden" name="fid" value="<{$data.file_id}>" id="fid" />
								</li>
								<li>
									<label class="desc">
										<{$smarty.const.LANG_SHORTCUT_EDIT_DESC}>
									</label>
									<div>
										<textarea tabindex="2" cols="50" rows="5" class="field textarea medium" name="ts_description" ><{$data.ts_description}></textarea>
									</div>
								</li>
								<li>
									<label class="desc">
										<{$smarty.const.LANG_SHORTCUT_EDIT_SETTAG}>
									</label>
									<div>
										<{$smarty.const.LANG_SHORTCUT_EDIT_SETTAG_HINT}>
										<img style="max-width:700px !important;margin-bottom:20px;" src="../plugin/tag/images/shortcut_diagram.png" />
									</div>
									<{$smarty.const.LANG_SHORTCUT_EDIT_SETTAG_SET1}>
									<div id="tag1"></div>
									<{$smarty.const.LANG_SHORTCUT_EDIT_SETTAG_SET2}>
									<div id="tag2"></div>
									<{$smarty.const.LANG_SHORTCUT_EDIT_SETTAG_SET3}>
									<div id="tag3"></div>
								</li>
								<li class="buttons">
<{*
									<input type="button" value="<{$smarty.const.LANG_BUTTON_SAVE}>" class="submit"  onclick="$('form').submit();" />
*}>
									<input type="submit" value="<{$smarty.const.LANG_BUTTON_SAVE}>" class="submit" />
									<input type="button" value="<{$smarty.const.LANG_BUTTON_CANCEL}>" onclick="javascript:history.go(-1);"/>
								</li>
							</ul>
							<input type="hidden" name="id" value="<{$data.ts_id}>" />
							<input type="hidden" name="ticket" id="ticket" />
						</form>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
