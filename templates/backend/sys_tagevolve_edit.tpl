<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_tagevolve.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="../scripts/loader.class.js"></script>
<script type="text/javascript" src="/plugin/tag/scripts/loader_tagevolve.js"></script>
<script type="text/javascript" src="js/validation/sys_tagevolve.js"></script>
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
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_TAGEVOLVE_EDIT_TITLE}></div>
					<div class="portlet-content">
						<form action="sys_tagevolve.php?type=<{if $smarty.get.type=='add'}>do_add<{else}>do_update<{/if}>&page=<{$smarty.get.page}>" method="post" enctype="multipart/form-data" class="forms" name="form" >
							<ul>
								<li>
									<label class="desc">
										*<{$smarty.const.LANG_TAGEVOLVE_EDIT_TYPE}>
									</label>
									<div>
										<input type="radio" value="1" class="te_type" name="te_type" checked="checked" /> 分開
										<input type="radio" value="2" class="te_type" name="te_type" /> 合併
										<input type="radio" value="0" class="te_type" name="te_type" /> 更名
									</div>
								</li>
								<li>
									<label class="desc">
										<{$smarty.const.LANG_TAGEVOLVE_EDIT_UNIT}>
									</label>
									<div>
										<input type="radio" value="pi" id="tag_pi" name="tag" checked="checked" /> 執行單位
										<input type="radio" value="pcu" id="tag_pcu" name="tag" /> 承辦科別
									</div>
								</li>
								<li>
									<label class="desc">
										<{$smarty.const.LANG_TAGEVOLVE_EDIT_YEAR}>
									</label>
									<div>
										<select id="year" name="year"></select>
									</div>
								</li>
								<li>
									<label class="desc">
										<{$smarty.const.LANG_TAGEVOLVE_EDIT_OLDTAG}>
									</label>
									<div>
										<select id="oldtag" name="oldtag[]"></select>
									</div>
								</li>
								<li>
									<label class="desc">
										<{$smarty.const.LANG_TAGEVOLVE_EDIT_NEWTAG}>
									</label>
									<div>
										<select id="newtag" name="newtag[]" multiple></select>
									</div>
								</li>
								<li class="buttons">
									<input type="submit" value="<{$smarty.const.LANG_BUTTON_SAVE}>" class="submit" />
									<input type="button" value="<{$smarty.const.LANG_BUTTON_CANCEL}>" onclick="javascript:history.go(-1);"/>
								</li>
							</ul>
						</form>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
