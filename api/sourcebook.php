<?php
  require_once dirname(__FILE__).'/../init/config.php';
  $ConfigManager = new ConfigManager();
  $_path = $ConfigManager->getDefineUserbase();
  if(!empty($_path)){
  	include_once $_path;
  }
  include_once $ConfigManager->getDefineSyspath();
  $init = new init('db','tpl','inputxss','getIP','filter','ehttp');  
  $type = $fs->valid($_GET['type'],'cmd');
  $key = $fs->valid($_GET['key'],'key');
  $bs_id = (int) $fs->valid($_GET['bs'],'id');
  $id = (int) $fs->valid($_GET['id'],'id');

  if($key != md5(DATA_SOURCE_PATH.'datasource.php?bs='.$bs_id) ){
    header('HTTP/1.0 403 Forbidden');
    exit;
  }

  $book = new book(&$db,'books');
  $rs = $book->getPublicByID($id);
  if($rs){
    if($type=='webbook' && WEBBOOK_STATUS){      
      header("Location:".str_replace(LocalHost,'',$rs['webbook_link']) );
      exit;
    }
    if($type=='ibook' && IBOOK_STATUS){
      header("Location:".str_replace(LocalHost,'',$rs['ibook_link']) );
      exit;
    }
  }
  $ee->Error('404');
