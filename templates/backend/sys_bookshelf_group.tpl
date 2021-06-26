<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_bookshelf.css" rel="stylesheet" media="all" />
<style type="text/css">
		p{padding: 0;}
		/* Recommended styles */
		.tsmsselect {
			width: 40%;
			float: left;
		}
		
		.tsmsselect select {
			width: 100%;
		}
		
		.tsmsoptions {
			width: 20%;
			float: left;
		}
		
		.tsmsoptions p {
			margin: 2px;
			text-align: center;
			font-size: larger;
			cursor: pointer;
		}
		
		.tsmsoptions p:hover {
			color: White;
			background-color: Silver;
		}
	</style>
<script language="javascript">
/*function check(){
	var alert_str = '';
	if(!$("#b_name").val()){
		alert_str = '<{$smarty.const.LANG_WARNING_BOOKNAME_CAN_NOT_BE_NULL}>\n';		
	}
	
	if(!$("#img").val() && !$("#file_id").val() ){
		alert_str += '<{$smarty.const.LANG_WARNING_BOOKCOVER_CANT_NOT_BE_NULL}>';		
	}
	
	if(alert_str!=''){
		alert(alert_str);
		return false;
	}	
	return true;
}*/
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
					<form action="sys_bookshelf.php?type=do_group&page=<{$smarty.get.page}>" method="post" enctype="multipart/form-data" class="forms" name="form" >
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_SYSBOOKSHELFS_SETGROUP}></div>
					<div class="portlet-content">
						<div>
						<select name="groups[]" id="myselect" class="multiselect" size="6" multiple="true">
						<{foreach from=$data item="val" name=myloop}>
							<option value="<{$val.g_id}>"<{if $val.checked}>selected<{/if}>><{$val.g_name}></option>
						<{/foreach}>
						</select>
						</div>
						<div>
						<input type="submit" value="<{$smarty.const.LANG_BUTTON_SAVE}>" class="submit" />
						<input type="button" value="<{$smarty.const.LANG_BUTTON_CANCEL}>" onclick="javascript:location.href='sys_bookshelf.php?type=search&q=<{$q_str}>'"/>
						<input type="hidden" name="id" value="<{$smarty.get.id}>" />
						<input type="hidden" name="bookshelf_q" value="<{$q_str}>" />
						</div>
					</div>
					</form>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<script type="text/javascript" src="js/jquery.twosidedmultiselect.js"></script>
<script type="text/javascript">
$(".multiselect").twosidedmultiselect();
</script>			
<{/block}>
