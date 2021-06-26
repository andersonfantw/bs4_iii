<{extends file="backend/sys_base.tpl"}>
<{block name="head"}>
<link href="css/customize/sys_config.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="js/validation/sys_config.js"></script>
<script type="text/javascript" src="../scripts/loader.class.js"></script>
<script type="text/javascript" src="../plugin/tag/scripts/loader_sys_config.js"></script>
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
					<div class="portlet-header ui-widget-header"><{$smarty.const.LANG_CONFIG}></div>
					<div class="portlet-content">	
						<div id="tabs">
							<ul>
								<{if common::getcookie('sysuser')=='admin'}>
								<li><a href="#tabs1"><{$smarty.const.LANG_CONFIG_SYS_SETUP}></a></li>
								<{/if}>
								<li><a href="#tabs2"><{$smarty.const.LANG_CONFIG_BS_SETUP}></a></li>
								<{if common::getcookie('sysuser')=='admin'}>
								<li><a href="#tabs3"><{$smarty.const.LANG_CONFIG_MEMBER_SETUP}></a></li>
								<li><a href="#tabs4"><{$smarty.const.LANG_CONFIG_IMPORT_SETUP}></a></li>
								<li><a href="#tabs5"><{$smarty.const.LANG_CONFIG_TAG_SETUP}></a></li>
								<li><a href="#tabs6"><{$smarty.const.LANG_CONFIG_PLUGIN_SETUP}></a></li>
								<{/if}>
								<li><a href="#tabs7"><{$smarty.const.LANG_CONFIG_API_SETUP}></a></li>
							</ul>
							<form action="sys_config.php?type=do_update" method="post" enctype="multipart/form-data" class="forms" name="form2" >
							<{if common::getcookie('sysuser')=='admin'}>
							<div id="tabs1">
								<li>
									<label class="desc">
									System status:
									</label>
									<div>
										<{if $sysinfo.mode==3}>
											mode: BUY<br />
										<{elseif $sysinfo.mode==2}>
											mode: RENT, expired date:<{$sysinfo.date}><br />
										<{elseif $sysinfo.mode==1}>
											mode: TRIAL, active date:<{$sysinfo.date}><br />
												<input type="button" name="active" value="開通 / Active" onclick="document.location.href='sys_config.php?type=do_active'" />
										<{/if}>

										<{if $sysinfo.active}>
											<input type="button" name="disable" value="停用 / Disable" onclick="document.location.href='sys_config.php?type=do_disable'" />
										<{else}>
											<input type="button" name="enable" value="啟用 / Enable" onclick="document.location.href='sys_config.php?type=do_enable'" />
										<{/if}>
									</div>
								</li>
								<li>
									<label class="desc">
									電子書分散式保護機制 Distributed system:
									</label>
									<div>
										<input type="radio" name="distributed" value="0" <{if $data.distributed==0}>checked<{/if}> /> 停用 Disable
										<input type="radio" name="distributed" value="1" <{if $data.distributed==1}>checked<{/if}> /> 啟用 Enable
									</div>
								</li>
								<li>
									<label class="desc">
									最大書櫃數目: 
									</label>
									<div>
										<input type="text" class="field text tiny" name="bs_number" value="<{$data.bs_number}>" />
									</div>
								</li>
								<li>
									<label class="desc">
									書櫃快取 Application Cache: 
									</label>
									<div>
										<p>
											啟用快取功能Application Cache會將檔案暫存在使用者端。在啟用狀態若主機系統有更新，則需要設定新的版號，更新後，使用者才會看到更新版。請注意!設定新的版本，所有的裝置會重新再cache一次所有檔案。
										</p>
									</div>
									<div>
										快取版本 System version(目前版本:<{$data.applicationcacheversion}>) : 
										<input type="text" class="field text small" name="application_cache_version" value="<{$data.applicationcacheversion}>" /><br />

										<input type="checkbox" name="desktop_cache" value="1" <{if $data.desktopcache==1}>checked<{/if}> /> 啟用桌上型電腦快取 Enable desktop computer cache<br />
										<input type="checkbox" name="mobile_cache" value="1" <{if $data.mobilecache==1}>checked<{/if}> /> 啟用APP(Android/iOS)快取 Enable APP(Android/iOS) cache<br />
									</div>
								</li>
								<li>
									<label class="desc">
									System config:
									</label>
									<div>
										<input type="checkbox" name="configbackend" value="1" <{if $data.configbackend==1}>checked<{/if}> /> 啟用前台的"後台"快捷 Enable Backend quick link at front site<br />
										<input type="checkbox" name="configfrontconvert" value="1" <{if $data.configfrontconvert==1}>checked<{/if}> /> 啟用前台轉書 Enable convert at front site<br />
										<input type="checkbox" name="configmybookshelf" value="1" <{if $data.configmybookshelf==1}>checked<{/if}> /> 啟用我的書櫃 Enable My Bookshelf<br />
										<input type="checkbox" name="configecocat" value="1" <{if $data.configecocat==1}>checked<{/if}> /> 啟用EcocatCMS Enable EcocatCMS<br />
										<input type="checkbox" name="configshare" value="1" <{if $data.configshare==1}>checked<{/if}> /> 啟用書櫃分享 Enable Share Method<br />
										<input type="checkbox" name="configdebugmode" value="1" <{if $data.configdebugmode==1}>checked<{/if}> /> 啟用除錯模式 Enable Debuge Mode<br />
										<input type="checkbox" name="configi519" value="1" <{if $data.configi519==1}>checked<{/if}> /> 啟用i519金流 Enable i519<br />
										<input type="checkbox" name="configloginmode" value="1" <{if $data.configloginmode==1}>checked<{/if}> /> 啟用登入模式 Enable Login Mode<br />
									</div>
								</li>
								<li>
									<label class="desc">
									Convert:
									</label>
									<div>
										<input type="checkbox" name="convertmode[]" title="EcocatCMS" value="848" <{if $data.convertmode_EcocatCMS==1}>checked<{/if}> <{if !$smarty.const.CONNECT_ECOCAT }>disabled="disabled"<{/if}> /> Ecocat轉書 Ecocat Convert<br />
										<input type="checkbox" name="convertmode[]" title="EcocatZIP" value="2051" <{if $data.convertmode_EcocatZIP==1}>checked<{/if}> /> Ecocat, LBM ZIP上架書櫃 Ecocat, LBM ZIP upload to bookshelf<br />
										<input type="checkbox" name="convertmode[]" title="ItutorZIP" value="1028" <{if $data.convertmode_ItutorZIP==1}>checked<{/if}> /> Itutor ZIP上架書櫃 Itutor ZIP upload to bookshelf<br />
										<input type="checkbox" name="convertmode[]" title="FlipbuilderZIP" value="4096" <{if $data.convertmode_FlipbuilderZIP==1}>checked<{/if}> /> Flipbuilder ZIP上架書櫃 Flipbuilder ZIP upload to bookshelf<br />
										<input type="checkbox" name="convertmode[]" title="MCGZIP" value="8192" <{if $data.convertmode_MCGZIP==1}>checked<{/if}> /> MCG ZIP上架書櫃 MCG ZIP upload to bookshelf<br />
									</div>
								</li>
								<li>
									<label class="desc">
									Default Language:
									</label>
									<div>
										<input type="radio" name="defaultlang" value="zh-tw" <{if $data.defaultlang=='zh-tw'}>checked<{/if}> /> zh-tw
										<input type="radio" name="defaultlang" value="zh-cn" <{if $data.defaultlang=='zh-cn'}>checked<{/if}> /> zh-cn
										<input type="radio" name="defaultlang" value="en" <{if $data.defaultlang=='en'}>checked<{/if}> /> en
										<input type="radio" name="defaultlang" value="jp" <{if $data.defaultlang=='jp'}>checked<{/if}> /> jp
									</div>
								</li>
								<li>
									<label class="desc">
									System log:
									</label>
									<div>
										<input type="button" value="書櫃系統更新 Upgrade" onclick="window.open('<{$smarty.const.WEB_URL}>/backend/sys_logviewer.php?ln=upgrade')" />
										<input type="button" value="資料庫備份 DBBackup" onclick="window.open('<{$smarty.const.WEB_URL}>/backend/sys_logviewer.php?ln=dbbackup')" />
										<input type="button" value="排程轉書 Queue" onclick="window.open('<{$smarty.const.WEB_URL}>/backend/sys_logviewer.php?ln=uploadqueue')" />
										<input type="button" value="轉書引擎 Ecocat" onclick="window.open('<{$smarty.const.WEB_URL}>/backend/sys_logviewer.php?ln=ecocat')" />
										<input type="button" value="PHP錯誤記錄 PHP_ERRORS" onclick="window.open('<{$smarty.const.WEB_URL}>/backend/sys_logviewer.php?ln=phperrors')" />
									</div>
								</li>
								<li>
									<div class="title title-spacing">
									</div>
									<label class="desc">
									<{$smarty.const.LANG_SETUP_GOOGLECODE}>: 
									</label>
									<div>
										<textarea tabindex="2" cols="50" rows="5" class="field textarea medium" name="google_code" ><{$dataw.google_code}></textarea>
									</div>
								</li>
								<li>
									<label class="desc">
									附屬工具: 
									</label>
									<div>
										<input type="checkbox" name="b_writer" value="1" <{if $dataw.b_writer==1}>checked<{/if}>/> 作者、共同作者
										<input type="checkbox" name="b_link" value="1" <{if $dataw.b_link==1}>checked<{/if}>/> 連結
										<input type="checkbox" name="b_imglink" value="1" <{if $dataw.b_imglink==1}>checked<{/if}>/> 圖片連結
									</div>
								</li>
							</div>
							<{/if}>
							<div id="tabs2">
								<li>
									<label  class="desc">
									<{$smarty.const.LANG_SETUP_BOOKSHELFTITLE}>: 
									</label>
									<div>
										<input type="text"maxlength="255" class="field text small" id="bs_title" name="bs_title" value="<{$dataw.bs_title}>" />
									</div>
								</li>
	              <li>
	                <label  class="desc">
	                <{$smarty.const.LANG_SETUP_HEADERLINK}>
	                </label>
	                <div>
	                        <input type="text"maxlength="255" class="field text large" id="bs_header_link" name="bs_header_link" value="<{$dataw.bs_header_link}>" />
	                </div>
	              </li>
								<li>
									<label  class="desc">
									<{$smarty.const.LANG_SETUP_HEADERIMG}>: 
									</label>
									<div>
	                  <input type="button" class="field" name="bs_remove_file" value="Delete" i="" id="bs_remove_file">
										<input type="file" class="field" name="bs_header_file" value="" id="bs_header_img" />
										<input type="hidden" name="bs_header" value="<{$dataw.bs_header}>" id="bs_header" />
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
										<input type="text" class="field text tiny" name="bs_header_height" value="<{$dataw.bs_header_height}>" /> px (214px)
									</div>
								</li>
								<li>
									<label  class="desc">
									<{$smarty.const.LANG_SETUP_FOOTERIMG}>: 
									</label>
									<div>
	                  <input type="button" class="field" name="bs_remove_footerfile" value="Delete" i="" id="bs_remove_footerfile">
										<input type="file" class="field" name="bs_footer_file" value="" id="bs_footer_img" />
										<input type="hidden" name="bs_footer" value="<{$dataw.bs_footer}>" id="bs_footer" />
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
										<input type="text" class="field text tiny" name="bs_footer_height" value="<{$dataw.bs_footer_height}>" /> px
									</div>
								</li>
								<li>
									<label class="desc">
									<{$smarty.const.LANG_SETUP_FOOTER_TEXT}>: 
									</label>
									<div>
										<textarea tabindex="2" cols="50" rows="5" class="field full medium" name="bs_footer_content" ><{$dataw.bs_footer_content}></textarea>
									</div>
								</li>
							</div>
							<{if common::getcookie('sysuser')=='admin'}>
							<div id="tabs3">
								<li>
									<label class="desc">
									會員模式 Member Mode:
									</label>
									<div>
										<p>模式在系統使用後就不可變更 Don't change setting after system online.</p>
									</div>
									<div>
										<input type="radio" name="membermode" value="4" <{if $data.membermode==4}>checked<{/if}> <{if $smarty.const.MEMBER_SYSTEM==4}>disabled="disabled"<{/if}> /> 獨立帳號 Individual
										<input type="radio" name="membermode" value="1" <{if $data.membermode==1}>checked<{/if}> /> 共同帳號 Centralizs									
									</div>
								</li>
								<li>
									<label class="desc">
									會員系統 Member System:
									</label>
									<div>
										<p>模式在系統使用後就不可變更 Don't change setting after system online.</p>
									</div>
									<div>
										<input type="radio" name="membersystem" value="1" <{if $data.membersystem==1 || $data.membersystem==2}>checked<{/if}> /> 書櫃帳號 DB account
										<input type="radio" name="membersystem" value="4" <{if $data.membersystem==4}>checked<{/if}> /> NAS LDAP帳號 NAS / LDAP account
										<input type="radio" name="membersystem" value="16" <{if $data.membersystem==16}>checked<{/if}> /> 使用者註冊 Regist account		
									</div>
									<div id="NAS" <{if $data.membersystem==4}>style="display:block"<{/if}>>
										<p>
											user from:
											<input type="radio" name="ldapdomaintype" value="0" <{if $data.ldapdomaintype==0}>checked<{/if}> /> NAS User
											<input type="radio" name="ldapdomaintype" value="1" <{if $data.ldapdomaintype==1}>checked<{/if}> /> LDAP User<br />
											LDAP Prefix: <input type="text" id="ldapprefix" name="ldapprefix" value="<{$data.ldapprefix}>" />
										</p>
									</div>
								</li>
							</div>
							<div id="tabs4">
								<li>
									<label class="desc">
									啟用匯入/出 Enable Import/Export:
									</label>
									<div>
										<input type="checkbox" name="import" value="1" <{if $data.import==1}>checked<{/if}> /> Enable Import
										<input type="checkbox" name="export" value="1" <{if $data.export==1}>checked<{/if}> /> Enable Export
									</div>
								</li>
								<li>
									<label class="desc">
									匯入/出功能 Enable Import/Export function:
									</label>
									<div>
										<p>匯入/匯出功能僅在[會員系統]為[書櫃會員]時啟用.</p>
									</div>
									<div>
										<input type="checkbox" name="importmode[]" value="16" <{if $data.importmode_manager}>checked<{/if}> /> 管理員帳號 Manager account
										<input type="checkbox" name="importmode[]" value="3" <{if $data.importmode_user}>checked<{/if}> /> 使用者帳號 user account
										<input type="checkbox" name="importmode[]" value="12" <{if $data.importmode_book}>checked<{/if}> /> 電子書 books/groups
									</div>
								</li>
							</div>
							<div id="tabs5">
								<li>
									<label class="desc">
									系統標籤 system tag:
									</label>
									<div>
										<p>標籤會套用在所有的內容上(年度 學年 學期). Tag on all contents. ex:Year, School Year, Term</p>
									</div>
									<div id="systemtag">
									</div>
								</li>
								<li>
									<label class="desc">
									必設考卷標籤 Required exam tags:
									</label>
									<div>
										<p>設定為必填，在設定時以下拉選單選擇 Use dropdown list while set to require</p>
									</div>
									<div id="infoacer_pid">
									</div>
								</li>
							</ul>
						</div>
						<div id="tabs6">
							<li>
								<label class="desc">
								Giantview:
								</label>
								<div>
									<input type="checkbox" name="giantview" value="1" <{if $data.giantview==1}>checked<{/if}> /> 啟用學習互動 Enable GiantView<br />
									<input type="checkbox" name="giantviewsystem" value="1" <{if $data.giantviewsystem==1}>checked<{/if}> /> 啟用進入教室 Enable Classroom<br />
									<input type="checkbox" name="giantviewchat" value="1" <{if $data.giantviewchat==1}>checked<{/if}> /> 啟用聊天 Enable Chat<br />
									Giantview URL: <input type="text" id="GiantviewURL" name="GiantviewURL" value="<{$data.GiantviewURL}>" />
									<p>
									請設定Giantview 區域網路IP。Please set giantview private ip.<br />
									Bookshelf local ip:<{$smarty.server.SERVER_ADDR}><br />
									Default Giantview port:20028
									</p>
								</div>
							</li>
							<li>
								<label class="desc">
								VCube Setting:
								</label>
								<div>
									<span>API Version:</span>
										<input type="radio" name="VCubeVersion" value="v5" <{if $data.VCubeVersion=='v5'}>checked<{/if}> /> V5
										<input type="radio" name="VCubeVersion" value="v4" <{if $data.VCubeVersion=='v4'}>checked<{/if}> /> V4<br />
									<span>API Base:</span> <input type="text" id="VCubeAPIBase" class="APIBase" name="VCubeAPIBase" value="<{$data.VCubeAPIBase}>" /><br />
									<span>ID:</span> <input type="text" id="VCubeID" class="ID" name="VCubeID" value="<{$data.VCubeID}>" /><br />
									<span>PWD:</span> <input type="text" id="VCubePWD" class="PWD" name="VCubePWD" value="<{$data.VCubePWD}>" /><br />
									<span>Notice Mail:</span> <input type="text" id="VCubeNoticeMail" class="NoticeMail" name="VCubeNoticeMail" value="<{$data.VCubeNoticeMail}>" />
								</div>
							</li>
							<li>
								<label class="desc">
								VCube Seminar Setting:
								</label>
								<div>
									<span>API Base:</span> <input type="text" id="VCubeSeminarAPIBase" class="APIBase" name="VCubeSeminarAPIBase" value="<{$data.VCubeSeminarAPIBase}>" /><br />
									<span>ID:</span> <input type="text" id="VCubeSeminarID" class="ID" name="VCubeSeminarID" value="<{$data.VCubeSeminarID}>" /><br />
									<span>PWD:</span> <input type="text" id="VCubeSeminarPWD" class="PWD" name="VCubeSeminarPWD" value="<{$data.VCubeSeminarPWD}>" /><br />
									<span>Notice Mail:</span> <input type="text" id="VCubeSeminarNoticeMail" class="NoticeMail" name="VCubeSeminarNoticeMail" value="<{$data.VCubeSeminarNoticeMail}>" />
								</div>
							</li>
							<li>
								<label class="desc">
								Zoom Setting:
								</label>
								<div>
									<span>Meeting ID</span> 
										<input type="radio" name="ZoomMeetingID" value="0" <{if $data.ZoomMeetingID=='0'}>checked<{/if}> /> Disable
										<input type="radio" name="ZoomMeetingID" value="1" <{if $data.ZoomMeetingID=='1'}>checked<{/if}> /> Enable<br />
									<span>API Base:</span> <input type="text" id="ZoomAPIBase" class="APIBase" name="ZoomAPIBase" value="<{$data.ZoomAPIBase}>" /><br />
									<span>ID:</span> <input type="text" id="ZoomID" class="ID" name="ZoomID" value="<{$data.ZoomID}>" /><br />
									<span>KEY:</span> <input type="text" id="ZoomKey" class="PWD" name="ZoomKey" value="<{$data.ZoomKey}>" /><br />
									<span>SECRET:</span> <input type="text" id="ZoomSecret" class="PWD" name="ZoomSecret" value="<{$data.ZoomSecret}>" /><br />
								</div>
							</li>
						</div>
						<{/if}>
						<div id="tabs7">
							<li>
								<label class="desc">
								轉書排程 Upload Queue:
								</label>
								<div>
									<p>
										批次轉檔錯誤時，通知的電子郵件為
										System will notice you when convert ebook fail!
									</p>
									<span>Contact 1:</span> <input type="text" class="field text medium" name="UploadQueueErrorApplyTo1" value="<{$data.UploadQueueErrorApplyTo1}>" /><br /><br />
									<span>Contact 2:</span> <input type="text" class="field text medium" name="UploadQueueErrorApplyTo2" value="<{$data.UploadQueueErrorApplyTo2}>" /><br /><br />
								</div>
								<{if common::getcookie('sysuser')=='admin'}>
								<div>
									<p>
										啟用分類設定，分類以傳入分類id(cid)的方式設定，若未傳入cid則自動以年為主分類，月為次分類。停用分類設定，必須設定傳入的主/次分類參數名稱。<br />
										Enable CID, post cid when upload a file. systen will set year as main-cate, month as sub-cate when cid is empty. Disable CID, must post both parent cate and child cate.
									</p>
									<span>API ID:</span> <input type="text" class="field text" name="UploadQueueID" value="<{$data.UploadQueueID}>" /><br />
									<span>API PWD:</span> <input type="text" class="field text" name="UploadQueuePWD" value="<{$data.UploadQueuePWD}>" /><br /><br />

									<input type="checkbox" name="UploadQueueSetBSID" value="1" <{if $data.UploadQueueSetBSID==1}>checked<{/if}> /> 啟用書櫃設定 Enable bsid param, 設定停用書櫃設定，電子書會放入預設的書櫃。<br />
									<span>預設 default:</span> <input type="text" class="field text tiny" name="UploadQueueDefaultBSID" value="<{$data.UploadQueueDefaultBSID}>" /><br />
									<input type="checkbox" name="UploadQueueSetCID" value="1" <{if $data.UploadQueueSetCID==1}>checked<{/if}> /> 啟用分類設定 Enable cid param<br /><br />
									<div id="UploadQueueCate" <{if $data.UploadQueueSetCID!=1}>style="display:block"<{/if}>>
										以下的分類會自動設定為必填，並設定為書的標籤。參數名稱的格式為 key: 參數名稱 + 'Key'; val: 參數名<br />
										Categories below are required, and will be default tag. Parameters format are Key: param name + 'Key'; val: param name<br /><br />
										<span>Parent Cate:</span> <input type="text" class="field text" name="UploadQueueParentCate" value="<{$data.UploadQueueParentCate}>" /><br />
										<span>Child Cate:&nbsp;&nbsp;</span> <input type="text" class="field text" name="UploadQueueChildCate" value="<{$data.UploadQueueChildCate}>" /><br />
										<span>Tag settings:</span><br /><br />
										格式 / format: 逗號分隔的大小寫英文字，*開頭為必填。 Upper and lower case phrase, start with * is required.<br />
										<div>
											<textarea tabindex="2" cols="50" rows="5" class="field full medium" name="UploadQueueTagSettings" ><{$data.UploadQueueTagSettings}></textarea>
										</div>
									<div><br />
									<span>Tag root:</span> <input type="text" class="field text" name="UploadQueueTagRoot" value="<{$data.UploadQueueTagRoot}>" /><br />
								</div>
								<{/if}>
							</li>
							<{if common::getcookie('sysuser')=='admin'}>
							<li>
								<label class="desc">
								APP官網連結 Website URL:
								</label>
								<div>
									<span>Website link:</span>	<input type="text" class="field text medium" name="APPWebsiteURL" value="<{$data.APPWebsiteURL}>" />
								</div>
							</li>
							<{/if}>
						</div>
						<div class="buttons">
							<input type="submit" value="<{$smarty.const.LANG_BUTTON_SAVE}>" class="submit" onclick="return check();" />
						</div>
						</form>
					</div>					
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
<{/block}>
