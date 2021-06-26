<?php
require_once dirname(__FILE__).'/../init/config.php';

$init = new init('db','sysauth','tpl','filter','status','ejson');

$type = $fs->valid($_GET['type'],'cmd');
$ini = new ini(&$db);
$ConfigManager = new ConfigManager();

switch ($type) {
  case 'do_update':
		$dataw['google_code'] = addcslashes($_POST['google_code'],"'");
		$dataw['bs_title'] = $fs->valid($_POST['bs_title'],'name');
		$dataw['bs_header_link'] = $fs->valid($_POST['bs_header_link'],'url');
		$dataw['bs_header_height'] = (int) $fs->valid($_POST['bs_header_height'],'num');
		$dataw['bs_footer_height'] = (int) $fs->valid($_POST['bs_footer_height'],'num');
		$dataw['bs_footer_content'] = $fs->valid($_POST['bs_footer_content'],'content');
		$dataw['bs_header'] = (int) $fs->valid($_POST['bs_header'],'id');
		$dataw['bs_footer'] = (int) $fs->valid($_POST['bs_footer'],'id');
		$del_bs_header = (int) $fs->valid($_POST['del_bs_header'],'id');
		$del_bs_footer = (int) $fs->valid($_POST['del_bs_footer'],'id');

		$dataw['b_writer'] = (int) $fs->valid($_POST['b_writer'],'bool');
		$dataw['b_link'] = (int) $fs->valid($_POST['b_link'],'bool');
		$dataw['b_imglink'] = (int) $fs->valid($_POST['b_imglink'],'bool');
		
		$data['distributed'] = (int)$fs->valid($_POST['distributed'],'bool');
		$data['bs_number'] = (int) $fs->valid($_POST['bs_number'],'num');
		
		$data['applicationcacheversion'] = $fs->valid($_POST['application_cache_version'],'name');
		$data['desktopcache'] = (int) $fs->valid($_POST['desktop_cache'],'bool');
		$data['mobilecache'] = (int) $fs->valid($_POST['mobile_cache'],'bool');
		
		$data['configbackend'] = (int) $fs->valid($_POST['configbackend'],'bool');
		$data['configfrontconvert'] = (int) $fs->valid($_POST['configfrontconvert'],'bool');
		$data['configmybookshelf'] = (int) $fs->valid($_POST['configmybookshelf'],'bool');
		$data['configecocat'] = (int) $fs->valid($_POST['configecocat'],'bool');
		$data['configshare'] = (int) $fs->valid($_POST['configshare'],'bool');
		$data['configdebugmode'] = (int) $fs->valid($_POST['configdebugmode'],'bool');
		$data['configi519'] = (int) $fs->valid($_POST['configi519'],'bool');
		$data['configloginmode'] = (int) $fs->valid($_POST['configloginmode'],'bool');
		$data['defaultlang'] = $fs->valid($_POST['defaultlang'],'name');
		$data['giantview'] = (int) $fs->valid($_POST['giantview'],'bool');
		$data['giantviewsystem'] = (int) $fs->valid($_POST['giantviewsystem'],'bool');
		$data['giantviewchat'] = (int) $fs->valid($_POST['giantviewchat'],'bool');
		$data['GiantviewURL'] = $fs->valid($_POST['GiantviewURL'],'url');
		$data['VCubeVersion'] = $fs->valid($_POST['VCubeVersion'],'key');
		$data['VCubeAPIBase'] = $fs->valid($_POST['VCubeAPIBase'],'url');
		$data['VCubeID'] = $fs->valid($_POST['VCubeID'],'name');
		$data['VCubePWD'] = $fs->valid($_POST['VCubePWD'],'pwd');
		$data['VCubeNoticeMail'] = $fs->valid($_POST['VCubeNoticeMail'],'email');
		$data['VCubeSeminarAPIBase'] = $fs->valid($_POST['VCubeSeminarAPIBase'],'url');
		$data['VCubeSeminarID'] = $fs->valid($_POST['VCubeSeminarID'],'name');
		$data['VCubeSeminarPWD'] = $fs->valid($_POST['VCubeSeminarPWD'],'pwd');
		$data['VCubeSeminarNoticeMail'] = $fs->valid($_POST['VCubeSeminarNoticeMail'],'email');
		$data['ZoomAPIBase'] = $fs->valid($_POST['ZoomAPIBase'],'url');
		$data['ZoomID'] = $fs->valid($_POST['ZoomID'],'name');
		$data['ZoomKey'] = $fs->valid($_POST['ZoomKey'],'name');
		$data['ZoomSecret'] = $fs->valid($_POST['ZoomSecret'],'pwd');
		$data['ZoomMeetingID'] = $fs->valid($_POST['ZoomMeetingID'],'bool');
		$data['membermode'] = (int) $fs->valid($_POST['membermode'],'num');
		$data['membersystem'] = (int) $fs->valid($_POST['membersystem'],'num');
		$data['ldapdomaintype'] = (int) $fs->valid($_POST['ldapdomaintype'],'num');
		$data['ldapprefix'] = $fs->valid($_POST['ldapprefix'],'pname');
		if($data['ldapprefix']==''){
			$data['ldapprefix'] = 'ttii';
		}
		$data['import'] = (int) $fs->valid($_POST['import'],'bool');
		$data['export'] = (int) $fs->valid($_POST['export'],'bool');
		$arr_importmode = $fs->valid($_POST['importmode'],'ARRAY');
		$arr_convertmode = $fs->valid($_POST['convertmode'],'ARRAY');
		
		//===API===
		$data['UploadQueueID'] = $fs->valid($_POST['UploadQueueID'],'acc');
		$data['UploadQueuePWD'] = $fs->valid($_POST['UploadQueuePWD'],'pwd');
		$data['UploadQueueErrorApplyTo1'] = $fs->valid($_POST['UploadQueueErrorApplyTo1'],'email');
		$data['UploadQueueErrorApplyTo2'] = $fs->valid($_POST['UploadQueueErrorApplyTo2'],'email');
		$data['UploadQueueSetBSID'] = (int) $fs->valid($_POST['UploadQueueSetBSID'],'bool');
		$data['UploadQueueDefaultBSID'] = (int) $fs->valid($_POST['UploadQueueDefaultBSID'],'num');
		$data['UploadQueueSetCID'] = (int) $fs->valid($_POST['UploadQueueSetCID'],'bool');
		$data['UploadQueueParentCate'] = $fs->valid($_POST['UploadQueueParentCate'],'name');
		$data['UploadQueueChildCate'] = $fs->valid($_POST['UploadQueueChildCate'],'name');
		$data['UploadQueueTagSettings'] = $fs->valid($_POST['UploadQueueTagSettings'],'content');
		$data['UploadQueueTagRoot'] = $fs->valid($_POST['UploadQueueTagRoot'],'name');
		$data['APPWebsiteURL'] = $fs->valid($_POST['APPWebsiteURL'],'url');
		
		
		$importmode=0;
		if(is_array($arr_importmode)){
			foreach($arr_importmode as $val){
				$importmode+=(int)$val;
			}
		}
		$convertmode=0;
		if(is_array($arr_convertmode)){
			foreach($arr_convertmode as $val){
				$convertmode+=(int)$val;
			}
		}
		$data['importmode'] = $importmode;
		$data['convertmode'] = $convertmode;
		/***********file upload*************/
		
		//$_FILES array process
		//header image
		$uploadfile=$_FILES['bs_header_file'];
		$header_file_data = common::insert_sys_image($uploadfile);
		if(!empty($header_file_data['id'])){
			//delete prev image
			$del_bs_header = $dataw['bs_header'];
			$dataw['bs_header'] = $header_file_data['id'];
		}
		if(!empty($del_bs_header)){
			$_adminid = bssystem::getLoginUID();
			common::remove_file('host',$del_bs_header,$_adminid,$bs_code);
		}
		
		//footer image
		$uploadfile=$_FILES['bs_footer_file'];
		$footer_file_data = common::insert_sys_image($uploadfile);
		if(!empty($footer_file_data['id'])){
			//delete prev image
			$del_bs_footer = $dataw['bs_footer'];
			$dataw['bs_footer'] = $footer_file_data['id'];
		}
		if(!empty($del_bs_footer)){
			$_adminid = bssystem::getLoginUID();
			common::remove_file('host',$del_bs_footer,$_adminid,$bs_code);
		}
		
		/***********file upload*************/
		
		$ini = new ini(&$db);
		$result = $ini->update('sysbs',$dataw);
		if($_SESSION['sysuser']=='admin'){
			$result = $ini->update('system',$data);
		}else{
			$ini->update('system','UploadQueueErrorApplyTo1',$data['UploadQueueErrorApplyTo1']);
			$ini->update('system','UploadQueueErrorApplyTo2',$data['UploadQueueErrorApplyTo2']);
			$result=false;
		}
		
		if($result){
			/***********write to GA code file*************/
			$ga_content = stripcslashes($_POST['google_code']);
			file_put_contents(GA_PATH.'/ga.js',$ga_content);
			/***********write to GA code file*************/
		
		  /***********write to css file*************/
		  if(!empty($header_file_data['path'])){
		  	$header_image = $header_file_data['path'];
		  }
		  if(!empty($_POST['header_image'])){
		    $header_image = $fs->valid($_POST['header_image'],'path');
		  }
		
		  if(!empty($footer_file_data['path'])){
		  	$footer_image = $footer_file_data['path'];
		  }
		  if(!empty($_POST['footer_image'])){
		    $footer_image = $fs->valid($_POST['footer_image'],'path');
		  }
		
		  if(!empty($header_image) && !empty($dataw['bs_header_height'])){
		  	unset($css);
		  	$css = array();
		  	$css['height']=$dataw['bs_header_height'];
		  	$css['image']='/'.$header_image;
		  	$ConfigManager->SetCSS('header',$css);
		  }
		
		  if(!empty($dataw['bs_footer_height'])){
		  	unset($css);
		  	$css = array();
		  	$css['height']=$dataw['bs_footer_height'];
		  	if(!empty($footer_image)){
		  		$css['image']='/'.$footer_image;
		  	}
		  	$ConfigManager->SetCSS('footer',$css);
		  }
			$ConfigManager->SaveCSS('sys');
		  /***********write to css file*************/
		
		  /***********write to json file*************/
		  unset($json);
		  $json=array();
		  $json['headerlink']=$dataw['bs_header_link'];
		  $json['footer']=$dataw['bs_footer_content'];
		  $ConfigManager->SaveJSON('sys',$json);
		  /***********write to json file*************/
		
		  /***********write to config file*************/
			unset($check);
			unset($define);
		
			$check = array();
			$check['TITLE']=true;
			$check['FOOTER_TEXT']=true;
			$check['HEADER_LINK']=true;
		
			$define['TITLE'] = $dataw['bs_title'];
			$define['FOOTER_TEXT'] = $dataw['bs_footer_content'];
			$define['HEADER_LINK'] = $dataw['bs_header_link'];
			$define['WIZARD'] = 'false';
			$define['FUNCTION_WRITER'] = $dataw['b_writer'];
			$define['FUNCTION_LINK'] = $dataw['b_link'];
			$define['FUNCTION_IMGLINK'] = $dataw['b_imglink'];
			$ConfigManager->SaveDefine('sys',$define,$check);
			/***********write to config file*************/
			if($_SESSION['sysuser']=='admin'){
				$define=array();
				//cache
				$define['APP_CACHE_VERSION'] = $data['applicationcacheversion'];
				$define['DESKTOP_CACHE'] = $data['desktopcache'];
				$define['MOBILE_CACHE'] = $data['mobilecache'];
				//system
				$define['ENABLE_DECENTRALIZED'] = $data['distributed'];
				$define['CONFIG_BACKEND'] = $data['configbackend'];
				$define['CONFIG_CONVERT'] = $data['configfrontconvert'];
				$define['CONFIG_MYBOOKSHELF'] = $data['configmybookshelf'];
				$define['CONNECT_ECOCAT'] = $data['configecocat'];
				$define['ENABLE_SHARE'] = $data['configshare'];
				$define['DEBUG_MODE'] = $data['configdebugmode'];
				$define['CONFIG_I519'] = $data['configi519'];
				$define['CONFIG_LOGINMODE'] = $data['configloginmode'];
				$define['DEFAULT_LANGUAGE'] = $data['defaultlang'];
				//plugin
				$define['ENABLE_GIANTVIEW'] = $data['giantview'];
				if($data['giantview']==1){
					$define['GiantviewSystem'] = $data['giantviewsystem'];
					$define['GiantviewChat'] = $data['giantviewchat'];
					$define['GiantviewURL'] = $data['GiantviewURL'];
				}else{
					$define['GiantviewSystem'] = 0;
					$define['GiantviewChat'] = 0;
					$define['GiantviewURL'] = '';
				}
				//if GiantviewURL='192.168' then private ip, else real ip
				if(preg_match('/192\.168/i',$data['GiantviewURL'])){
					$define['SERVER_ADDR'] = HttpServerAddrPort;
				}else{
					$define['SERVER_ADDR'] = HttpExternalIPPort;
				}
				$define['VCubeVersion'] = $data['VCubeVersion'];
				$define['VCubeAPIBase'] = $data['VCubeAPIBase'];
				$define['VCubeID'] = $data['VCubeID'];
				$define['VCubePWD'] = $data['VCubePWD'];
				$define['VCubeNoticeMail'] = $data['VCubeNoticeMail'];
				$define['VCubeSeminarAPIBase'] = $data['VCubeSeminarAPIBase'];
				$define['VCubeSeminarID'] = $data['VCubeSeminarID'];
				$define['VCubeSeminarPWD'] = $data['VCubeSeminarPWD'];
				$define['VCubeSeminarNoticeMail'] = $data['VCubeSeminarNoticeMail'];
				$define['ZoomAPIBase'] = $data['ZoomAPIBase'];
				$define['ZoomID'] = $data['ZoomID'];
				$define['ZoomKey'] = $data['ZoomKey'];
				$define['ZoomSecret'] = $data['ZoomSecret'];
				$define['ZoomMeetingID'] = $data['ZoomMeetingID'];
				//member
				$define['MEMBER_MODE'] = $data['membermode'];
				$define['MEMBER_SYSTEM'] = $data['membersystem'];
				if($data['membersystem']==1){
					if(!empty($data['import']) || !empty($data['export'])){
						$define['MEMBER_SYSTEM']=2;
					}
				}
				$define['LDAP_DOMAINTYPE'] = $data['ldapdomaintype'];;
				$define['LDAP_DOMAIN_PREFIX'] = $data['ldapprefix'];
				$define['ENABLE_IMPOERT'] = $data['import'];
				$define['ENABLE_EXPOERT'] = $data['export'];
				$define['WEBSITE_IMPORT_MODE'] = $data['importmode'];
				$define['BACKEND_IMPORT_MODE'] = $data['importmode'];
				$define['WEBSITE_CONVERT_MODE'] = $data['convertmode'];
				$define['BACKEND_CONVERT_MODE'] = $data['convertmode'];

				$define['UPLOADQUEUE_ID'] = $data['UploadQueueID'];
				$define['UPLOADQUEUE_PWD'] = $data['UploadQueuePWD'];
				$define['UPLOADQUEUE_ERROR_APPLYTO1'] = $data['UploadQueueErrorApplyTo1'];
				$define['UPLOADQUEUE_ERROR_APPLYTO2'] = $data['UploadQueueErrorApplyTo2'];
				$define['UPLOADQUEUE_SETBSID'] = $data['UploadQueueSetBSID'];
				$define['UPLOADQUEUE_BSID_DEFAULT'] = $data['UploadQueueDefaultBSID'];
				$define['UPLOADQUEUE_SETCID'] = $data['UploadQueueSetCID'];
				$define['UPLOADQUEUE_PARENT_CATE'] = $data['UploadQueueParentCate'];
				$define['UPLOADQUEUE_CHILD_CATE'] = $data['UploadQueueChildCate'];
				$define['UPLOADQUEUE_TAG_SETTINGS'] = $data['UploadQueueTagSettings'];
				$define['UPLOADQUEUE_TAG_ROOT'] = $data['UploadQueueTagRoot'];
				$define['APP_WEBSITE_URL'] = $data['APPWebsiteURL'];
		
				$ConfigManager->SaveDefine('sysconfig',$define,$check);
			}
			/***********write to config file*************/
		
			$status->go('sys_config.php','success',LANG_MESSAGE_UPDATE_SUCCESS);
		}
		else
			$status->back('error',LANG_ERROR_UPDATE_FAIL);
		break;
  case 'edit':
  default:
		switch ($type) {
		  case 'do_active':
		  	LicenseManager::registSystemActive(3);
		  	break;
		  case 'do_enable':
		  	LicenseManager::registSystemActive(1);
		  	break;
		  case 'do_disable':
		  	LicenseManager::registSystemActive(-1);
		  	break;
		}
  	$info = LicenseManager::getSystemActiveInfo();
  	$info['date'] = date('Y-m-d h:i:s',$info['date']);
		//$data = $system_setup->getList();
		$dataw = $ini->getByGroup('sysbs');
		$dataw = common::rs2ini($dataw['result']);
		$dataw = $dataw['sysbs'];
		if($_SESSION['sysuser']=='admin'){
			$data = $ini->getByGroup('system');
			$data = common::rs2ini($data['result']);
			$data = $data['system'];
		}else{
			$data = array();
			$rs = $ini->getByKey('system','UploadQueueErrorApplyTo1');
			$data['UploadQueueErrorApplyTo1'] = $rs['val'];
			$rs = $ini->getByKey('system','UploadQueueErrorApplyTo2');
			$data['UploadQueueErrorApplyTo2'] = $rs['val'];
		}

		$files = new files($db);
		if($dataw['bs_header']>0){
			$header_image_arr = $files->getByID($dataw['bs_header']);
			$tpl->assign('path_header_image',WEB_URL."/".$header_image_arr['f_path']);
			$tpl->assign('header_image',$header_image_arr['f_path']);
		}
		if($dataw['bs_footer']>0){
			$footer_image_arr = $files->getByID($dataw['bs_footer']);
			$tpl->assign('path_footer_image',WEB_URL."/".$footer_image_arr['f_path']);
			$tpl->assign('footer_image',$footer_image_arr['f_path']);
		}
		
		if($_SESSION['sysuser']=='admin'){
			if(MEMBER_SYSTEM==4){
				$data['membermode']=1;
			}
			$convertmode = intval($data['convertmode']);
			$data['convertmode_MCGZIP'] = LicenseManager::chkAuth($convertmode,8192);
			$data['convertmode_FlipbuilderZIP'] = LicenseManager::chkAuth($convertmode,4096);
			$data['convertmode_ItutorZIP'] = LicenseManager::chkAuth($convertmode,1028);
			$data['convertmode_EcocatZIP'] = LicenseManager::chkAuth($convertmode,2051);
			$data['convertmode_EcocatCMS'] = LicenseManager::chkAuth($convertmode,848);
	
			$importmode = intval($data['importmode']);
			$data['importmode_manager'] = LicenseManager::chkAuth($importmode,16);
			$data['importmode_user'] = LicenseManager::chkAuth($importmode,3);
			$data['importmode_book'] = LicenseManager::chkAuth($importmode,12);
		}
		$tpl->assign('data',$data);
		$tpl->assign('dataw',$dataw);
		$tpl->assign('sysinfo',$info);
  	$tpl->display('backend/sys_config.tpl');
  	break;
}
