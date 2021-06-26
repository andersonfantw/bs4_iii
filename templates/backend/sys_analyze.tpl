<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_analyze.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="js/validation/sys_analyze.js"></script>
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
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_ANALYZE}></div>
					<div class="portlet-content">	

						<div class="title title-spacing">
							<h2><{$smarty.const.LANG_ANALYZE}></h2>							
						</div>
						<form action="sys_analyze.php?type=do_update" method="post" enctype="multipart/form-data" class="forms" name="form2" >
						<ul>
							<li>
								<label class="desc">
								Period:
								</label>
								<div>
									<input type="radio" name="period" value="byday" /> By Day<br />
									<input type="radio" name="period" value="byweek" /> By Week<br />
									<input type="radio" name="period" value="bymonth" /> By Month<br />
									<input type="radio" name="period" value="byyear" /> By Year<br />
									<input type="radio" name="period" value="dayofweek" /> Day of Week<br />
									<input type="radio" name="period" value="hourofday" /> Hour of Day<br />
								</div>
							</li>
							<li>
								<label class="desc">
								Viewer type:
								</label>
								<div>
									<input type="radio" name="type" value="all" />ALL
									<input type="radio" name="type" value="a" />Manager
									<input type="radio" name="type" value="u" />User
									<input type="radio" name="type" value="-" />Not login
								</div>
							</li>
							<li>
								<label class="desc">
								Target:
								</label>
								<div>
									<input type="checkbox" name="query[]" value="user" />User
									<input type="checkbox" name="query[]" value="visit" />Visit
									<input type="checkbox" name="query[]" value="amount_time" />Amount Time
									<input type="checkbox" name="query[]" value="browser" />Browser
									<input type="checkbox" name="query[]" value="os" />OS
								</div>
							</li>
							<li>
								<div id="chart"></div>
							</li>
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
