<?php
  require_once dirname(__FILE__).'/../init/config.php';
  $init = new init('db','tpl','inputxss','filter','ejson');
  $ConfigManager = new ConfigManager();

  global $bs_code;
  $bs_code = (int) $fs->valid($_POST['bs'],'id');
  $_path = $ConfigManager->getDefineUserbase();
  if(!empty($_path)){
  	include_once $_path;
  }
  include_once $ConfigManager->getDefineSyspath();
  $q_str = $fs->valid($_POST['q'],'query');
 
  $book = new book(&$db);
  //$data = $book->getPublicListByKeyword($q_str,'b_order desc,b_id desc','','');
  $book->reset();
  $book->setBSID($bs_code);
  $book->setKeyword($q_str);
  $data = $book->getList('b_order desc,b_id desc',0,0,'');
  $data = $data['result'];
  
  foreach($data as $key=>$val)
  {
    $data[$key]['f_path'] = HostManager::getBookshelfBase().$data[$key]['f_path'];	
    $data[$key]['b_description']=nl2br(htmlspecialchars_decode($data[$key]['b_description']));
    if(MEMBER){  
      $data[$key]['webbook_link'] = WEBBOOK_STATUS;
      $data[$key]['ibook_link'] = IBOOK_STATUS;      
    }
    if(empty($val['b_description'])){
      $data[$key]['b_description'] = '';      
    }
  }
    

  $output = $json = new Services_JSON();
  header('Content-Type: application/json; charset=utf-8');
  echo $json->encode($data);
  exit;
