<{extends file="backend/base.tpl"}>
<{block name="head"}>
<link href="css/customize/bookshelf_user.css" rel="stylesheet" media="all" />
<script type="text/javascript">
$(function() {
      $(".delete").click(function(event) {
          return confirm('<{$smarty.const.LANG_WARNING_DELETE_CONFIRM}>');
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
				<div class="title">
					<h3><{$smarty.const.LANG_ACTIVECODE}></h3>		
				</div>
				<div>
						<form action="activecode.php?type=search" method="post" class="forms" name="form" id="search">
							<ul>
								<li>
									<label  class="desc">
										<{$smarty.const.LANG_ACTIVECODE_LIST_SEARCH}>
									</label>
									<div>
										<input type="text"maxlength="255" class="field text small" id="q" name="q" value="<{$smarty.get.q}>" /><input type="submit" value="<{$smarty.const.LANG_BUTTON_SEARCH}>" class="submit" />
										<input type="hidden" name="type" value="search" />
									</div>
								</li>
							</ul>
						</form>
				</div>
					<div class="other">						
						<div class="button float-right">
							<a href="activecode.php?type=add" class="btn ui-state-default"><span class="ui-icon ui-icon-circle-plus"></span><{$smarty.const.LANG_ACTIVECODE_BTN_ADD}></a>
						</div>
						<div class="clearfix"></div>
					</div>
				<div class="hastable">
					<table cellspacing="0">
						<thead>
							<tr>
								<td><{$smarty.const.LANG_ACTIVECODE_LIST_COL_CODE}></td>
								<td><{$smarty.const.LANG_ACTIVECODE_LIST_COL_TERM}></td>
								<td><{$smarty.const.LANG_ACTIVECODE_LIST_COL_DATA}></td>
								<td><{$smarty.const.LANG_ACTIVECODE_LIST_COL_CREATEDATE}></td>
								<td><{$smarty.const.LANG_ACTIVECODE_LIST_COL_REGIST}></td>
								<td><{$smarty.const.LANG_CONST_MANAGEMENT}></td>
							</tr>
						</thead>
						<tbody id="books_table">
						<{foreach from=$data item="val" name=myloop}>   
							<tr<{if $smarty.foreach.myloop.iteration is even}> class="alt"<{/if}>>
								<td>
									<{$val.ac_code}>
								</td>
								<td>
									<{$val.ac_term}>
								</td>
								<td>
									<{$val.ac_data}>
								</td>
                <td>
                  <{$val.createdate}>
                </td>
                <td>
                  <{$val.registdate}><br />~<br /><{$val.expireddate}>
                </td>
								<td>
									<a class="btn_no_text btn ui-state-default ui-corner-all tooltip delete" title="<{$smarty.const.LANG_BTNHINT_DELETE}>" href="activecode.php?type=delete&id=<{urlencode($val.ac_code)}>&page=<{$smarty.get.page}>">
										<span class="ui-icon ui-icon-circle-close"></span>
									</a>
								</td>
							</tr>
						<{/foreach}>
						</tbody>
					</table>
					<{if $pagebar}>
					<div id="pagebar"><{$pagebar->showPageBar()}></div>
					<{/if}>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
