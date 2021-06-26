<{extends file="backend/base.tpl"}>
<{block name="head"}>
<link href="css/customize/category.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="js/validation/category.js"></script>
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
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_CATE_EDIT_TITLE}></div>
					<div class="portlet-content">
						<form action="category.php?type=<{if $smarty.get.type=='add'}>do_add<{else}>do_update<{/if}>&page=<{$smarty.get.page}>" method="post" enctype="multipart/form-data" class="forms" name="form" >
							<ul>
								<li>
									<label  class="desc">
										*<{$smarty.const.LANG_CATE_EDIT_CATENAME}>
									</label>
									<div>
										<input type="text" tabindex="1" maxlength="255" class="field text small" id="c_name" name="c_name" value="<{$data.c_name}>" />
									</div>
								</li>
                <li>
                  <label  class="desc">
                    *<{$smarty.const.LANG_CATE_EDIT_BELONG}>
                  </label>
                  <div>
                    <select name="c_parent_id">
                      <option value="0"><{$smarty.const.LANG_CONST_NONE}></option>
                      <{foreach from=$parent_data item="val"}>
                      <option value="<{$val.c_id}>"<{if $val.c_id==$smarty.get.pid || $val.c_id==$data.c_parent_id}> selected="selected"<{/if}>><{$val.c_name}></option>
                      <{/foreach}>
                    </select>
                  </div>
                </li>
								<li>
									<label  class="desc">
										<{$smarty.const.LANG_CATE_EDIT_DESC}>
									</label>
									<div>
										<textarea tabindex="2" cols="50" rows="5" class="field textarea small" name="c_description" ><{$data.c_description}></textarea>
									</div>
								</li>
                <li>
                  <label  class="desc">
                    <{$smarty.const.LANG_CATE_EDIT_ORDER}>
                  </label>
                  <div>
                    <input type="text" tabindex="1" maxlength="5" class="field text small" id="c_order" name="c_order" value="<{$data.c_order}>" />
                  </div>
                </li>
                 <li class="buttons">
                  <input type="submit" value="<{$smarty.const.LANG_BUTTON_SAVE}>" class="submit" />
                  <input type="button" value="<{$smarty.const.LANG_BUTTON_CANCEL}>" onclick="javascript:history.go(-1);"/>
                </li>
							</ul>
							<input type="hidden" name="id" value="<{$data.c_id}>" />
						</form>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
