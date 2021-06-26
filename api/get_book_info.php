<?php
  require_once dirname(__FILE__).'/../init/config.php';
  $init = new init('db','tpl','inputxss','ejson');

  global $bs_code;
  $bs_code = (int) $fs->valid($_POST['bs'],'id');
  $ConfigManager = new ConfigManager(0,$bs_code);
  $_path = $ConfigManager->getDefineUserbase();
  if(!empty($_path)){
  	include_once $_path;
  }
	include_once $ConfigManager->getDefineSyspath();
  $id = (int) $fs->valid($_POST['id'],'id');
  if($id<1)
    exit;
 
  $book = new book(&$db);
  $data = $book->getPublicByID($id);  

  //$fp = explode('/',$data['f_path']);
  //WEB_URL
  //$data['f_path'] = $fp[0].'/m_'.$fp[1];
  $userbase = HostManager::getBookshelfBase();
  $path = common::path_info($data['f_path']);
  $data['f_path'] = WEB_URL.$userbase.'/'.$path['dirname'].'/m_'.$path['basename'];
  $data['b_description']=nl2br(htmlspecialchars_decode($data['b_description']));

  if(MEMBER){
		$data['webbook_link'] = WEBBOOK_STATUS;
		$data['webbook_show'] = WEBBOOK_STATUS && $data['webbook_show'];
		$data['ibook_link'] = IBOOK_STATUS;
		$data['ibook_show'] = IBOOK_STATUS && $data['ibook_show'];
  }else{
		if(!WEBBOOK_STATUS){
			$data['webbook_show'] = 0;
			$data['webbook_link'] = '';
		}else{
			$link = str_replace(HttpLocalIPPort,'',$data['webbook_link']);
			$link = str_replace(LocalHost,'',$link);
			$data['webbook_link']=$link;
		}
		if(!IBOOK_STATUS){
			$data['ibook_show'] = 0;
			$data['ibook_link'] = '';
		}else{
			$link = str_replace(HttpLocalIPPort,'',$data['ibook_link']);
			$link=str_replace(LocalHost,'',$link);
			$data['ibook_link']=$link;
		}
  }
  $output = $json = new Services_JSON();
  header('Content-Type: application/json; charset=utf-8');
  echo $json->encode($data);
  exit;
  /*$tpl->assign('data',$data['result']);
  $tpl->display('index.tpl');*/
