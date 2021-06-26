<{extends file="backend/base.tpl"}>
<{block name="head"}>
<script>
_enable_cowriter=<{$smarty.const.FUNCTION_WRITER}>;
_enable_links=<{$smarty.const.FUNCTION_LINK}>;
_enable_imglinks=<{$smarty.const.FUNCTION_IMGLINK}>;
//_enable_cowriter=false;
//_enable_links=false;
//_enable_imglinks=false;
var bid=<{$smarty.get.id}>;
</script>
<link href="css/customize/book.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="js/validation/book.js"></script>

<script type="text/javascript" src="../scripts/loader.class.js"></script>
<script type="text/javascript" src="../plugin/tag/scripts/loader_book_edit.js"></script>
<{/block}>
<{block name="content"}>
			<div id="main-content">
				<{if $status_code !=''}>
				<script>setTimeout(function(){jQuery('#status_bar').fadeOut('slow');}, 2000);</script>
				<div class="response-msg ui-corner-all <{$status_code}>" id="status_bar">
				  <{$status_desc}>
				</div>
				<{/if}>
				<div><img id="iconorgpic" /></div>
				<div class="clearfix"></div>
				<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all form-container">
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_BOOKS_EDIT_TITLE}></div>
					<div class="portlet-content">
						<form action="book.php?type=<{if $smarty.get.type=='add'}>do_add<{else}>do_update<{/if}>&page=<{$smarty.get.page}>" method="post" enctype="multipart/form-data" class="forms" name="form" >
							<ul>
								<li>
									<label  class="desc">
										*<{$smarty.const.LANG_BOOKS_EDIT_BOOKNAME}>
									</label>
									<div>
										<input type="text"maxlength="255" class="field text small" id="b_name" name="b_name" value="<{$data.b_name}>" />
									</div>
								</li>
								<li>
									<label  class="desc">
										*<{$smarty.const.LANG_BOOKS_EDIT_BOOKKEY}>
									</label>
									<div>
										<{$data.b_key}>
										<input type="hidden" id="b_key" name="b_key" value="<{$data.b_key}>" />
									</div>
								</li>
								<li>
									<label  class="desc">
										*<{$smarty.const.LANG_BOOKS_EDIT_COVER}>
									</label>
									<div>
										<input type="file" class="field" name="img" value="" id="img" />
										<input type="hidden" name="file_id" value="<{$data.file_id}>" id="file_id" />
									</div>
									<div>
									<img src="<{$cover_image}>" />
									<input type="hidden" name="cover_image" value="<{$cover_image}>" id="cover_image" />
								</div>
								</li>
								<li>
									<label  class="desc">
										<{$smarty.const.LANG_BOOKS_EDIT_WEBBOOKLINK}>
									</label>
									<div>
										<input type="text" class="field text medium" name="webbook_link" value="<{$data.webbook_link}>" <{if $data.is_webbook==0 && $smarty.get.type!='add'}>readonly  style="background:#ccc" alt=""<{/if}> />
										<input type="checkbox" name="webbook_show" value="1" <{if $data.webbook_show==1}>checked="checked"<{/if}> <{if $data.is_webbook==0 && $smarty.get.type!='add'}>readonly style="background:#ccc" alt=""<{/if}> /> show
									</div>
								</li>
								<li>
									<label class="desc">
										<{$smarty.const.LANG_BOOKS_EDIT_IBOOKLINK}>
									</label>
									<div>
										<{$smarty.const.LANG_BOOKS_EDIT_IBOOKLINK_DESC}><br /><br />
										<input type="text" class="field text medium" name="ibook_link" value="<{$data.ibook_link}>" <{if $data.is_ibook==0 && $smarty.get.type!='add'}>readonly  style="background:#ccc" alt=""<{/if}> />
										<input type="checkbox" name="ibook_show" value="1" <{if $data.ibook_show==1}>checked="checked"<{/if}> <{if $data.is_ibook==0 && $smarty.get.type!='add'}>onclick="return false" style="background:#ccc" alt=""<{/if}> /> show
									</div>
								</li>
								<li>
									<label  class="desc">
										*<{$smarty.const.LANG_BOOKS_EDIT_SHOWLINK}>
									</label>
									<div>
										<{foreach from=$category item="val" name=myloop}>
                      <{if $val.sub_category}><div style="margin-bottom:5px;"><{$val.c_name}><br />
                      <{foreach from=$val.sub_category item="subval"}>
											<span style="padding-left:5px;"><input type="checkbox" id="c_<{$subval.c_id}>" name="c_id[]" value="<{$subval.c_id}>"<{if $subval.checked}>checked="checked"<{/if}>><label for="c_<{$subval.c_id}>"><{$subval.c_name}></label></span>
                      <{/foreach}>
                      </div>
                      <{/if}>
										<{/foreach}>
									</div>
								</li>
								<li>
									<label class="desc">
										<{$smarty.const.LANG_BOOKS_EDIT_SETTAG}>
									</label>
									<div id="tag"></div>
								</li>
								<li>
									<label class="desc">
										<{$smarty.const.LANG_BOOKS_EDIT_DESC}>
									</label>
									<div>
										<textarea tabindex="2" cols="50" rows="5" class="field textarea medium" name="b_description" ><{$data.b_description}></textarea>
									</div>
								</li>
                 <li>
                  <label  class="desc">
                    <{$smarty.const.LANG_BOOKS_EDIT_ORDER}>
                  </label>
                  <div>
                    <input type="text" maxlength="5" class="field text small" id="b_order" name="b_order" value="<{$data.b_order}>" />
                  </div>
                </li>
<!--
<{if $smarty.const.MEMBER==0 }>
                <li>
                  <label  class="desc">
                    <{$smarty.const.LANG_BOOKS_EDIT_NEWBOOK}>
                  </label>
                  <div>
                    <label><input type="radio" name="b_top" value="1" <{if $data.b_top=='1'}>checked="checked"<{/if}>/> <{$smarty.const.LANG_CONST_YES}></label>
                    <label><input type="radio" name="b_top" value="0" <{if $data.b_top!='1'}>checked="checked"<{/if}> /> <{$smarty.const.LANG_CONST_NO}></label>
                  </div>
                </li>
<{/if}>
-->
								<li>
									<label  class="desc">
										<{$smarty.const.LANG_BOOKS_EDIT_ISVISIBLE}>
									</label>
									<div>
										<label><input type="radio" name="b_status" value="1" <{if $data.b_status!='0'}>checked="checked"<{/if}>/> <{$smarty.const.LANG_CONST_VISIBLE}></label> 
										<label><input type="radio" name="b_status" value="0" <{if $data.b_status=='0'}>checked="checked"<{/if}> /> <{$smarty.const.LANG_CONST_INVISIBLE}></label> 
									</div>
								</li>
								<li class="buttons">
<{*
									<input type="button" value="<{$smarty.const.LANG_BUTTON_SAVE}>" class="submit"  onclick="$('form').submit();" />
*}>
									<input type="submit" value="<{$smarty.const.LANG_BUTTON_SAVE}>" class="submit" />
									<input type="button" value="<{$smarty.const.LANG_BUTTON_CANCEL}>" onclick="javascript:history.go(-1);"/>
								</li>
							</ul>
							<input type="hidden" name="id" value="<{$data.b_id}>" />
							<input type="hidden" name="icons_data" class="icons_data" value="<{$data.icons_data}>" />
						</form>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<input type="hidden" class="writer_data" value="<{$data.writer_data}>" />
<input type="hidden" class="cowriters_data" value="<{$data.cowriter_data}>" />
<input type="hidden" class="l_data" value="<{$data.link_data}>" />
<{/block}>
