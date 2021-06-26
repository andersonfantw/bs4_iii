<?php
  require_once dirname(__FILE__).'/../init/config.php';
  $init = new init('db','filter','ejson');

  global $bs_code;
  $bs_code = (int) $fs->valid($_POST['bs'],'id');
  $uid = $_SESSION['accmapping'+$bs_code];
  $ConfigManager = new ConfigManager($uid,$bs_code);
  $_path = $ConfigManager->getDefineUserbase();
  if(!empty($_path)){
  	include_once $_path;
  }
  include_once $ConfigManager->getDefineSyspath();

	$wid = common::get_wonderbox_id();
	$wonderbox_id='';
	if($wid['rc']==0) $wonderbox_id=$wid['wbox_id'];

  $data['bs'] = $bs_code;
  $data['mapping_device'] = MAPPING_DEVICE;
  $data['debug_mode'] = DEBUG_MODE;
  $data['webbook'] = WEBBOOK_STATUS;
  $data['ibook'] = IBOOK_STATUS;
  $data['member'] = MEMBER;
  $data['newbook'] = NEWBOOK;
  $data['allbook'] = ALLBOOK;
  $data['headerlink'] = HEADER_LINK;
  $data['bstitle'] = TITLE;
  $data['footertext'] = FOOTER_TEXT;
  if(LicenseManager::chkAuth(MEMBER_SYSTEM,MemberSystemEnum::Normal) ||
  		LicenseManager::chkAuth(MEMBER_SYSTEM,MemberSystemEnum::Import)	||
  		LicenseManager::chkAuth(MEMBER_SYSTEM,MemberSystemEnum::Regist)){
  			$MEMBER_SYSTEM = 'self';
  }else{
  			$MEMBER_SYSTEM = '';
  }
  $data['member_system'] = $MEMBER_SYSTEM;
  $data['buid'] = $_SESSION['buid'];
  $data['acc'] = $_SESSION['acc'];
  $data['style'] = '';
  $data['wid'] = $wonderbox_id;
  $data['serveraddr'] = SERVER_ADDR;
  $data['httpdomain'] = HttpExternalIPPort;
  $data['enable_ecocat'] = CONNECT_ECOCAT;

  $output = $json = new Services_JSON();
  header('Content-Type: application/json; charset=utf-8');
  echo $json->encode($data);
  exit;
  /*$tpl->assign('data',$data['result']);
  $tpl->display('index.tpl');*/
