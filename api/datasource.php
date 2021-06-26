<?php
	$http_host = 'http://'.$_SERVER['HTTP_HOST'];
  require_once dirname(__FILE__).'/../init/config.php';
  $ConfigManager = new ConfigManager();
  $_path = $ConfigManager->getDefineUserbase();
  if(!empty($_path)){
  	include_once $_path;
  }
  include_once $ConfigManager->getDefineSyspath();
  $init = new init('db','tpl','inputxss','getIP','filter');
  $type = $fs->valid($_GET['type'],'cmd');
  $bss_account = $fs->valid($_GET['ac'],'acc');
  $bss_password = $fs->valid($_GET['pw'],'pwd');
  $bs_id = (int) $fs->valid($_GET['bs'],'id');

  $bookshelf_share = new bookshelf_share($db,'bookshelf_share');
  if(!$bookshelf_share->AuthCheck($bs_id,$USER_IP,$bss_account,$bss_password)){
    header('HTTP/1.0 403 Forbidden');
    exit;
  }

  //測試連線的話，到這就結束了
  if( $type=='connnect_only'){
    $rs = $bookshelf_share->getShareBookshelfInfo($bs_id);
    //如果此分享書櫃沒書籍就不回傳任何東西
    if($rs['books_count']>0){
      echo $rs['books_count'];
    }
    exit;
  }

  if($type=='xml'){
    $rs = $bookshelf_share->getShareBooksByBSID($bs_id);
    $bs_unique_id = md5(DATA_SOURCE_PATH.'datasource.php?bs='.$bs_id);
    foreach ($rs as $key => $val) {
      $rs[$key]['f_path'] = $http_host.$rs[$key]['f_path'];
      $rs[$key]['webbook_link'] = $http_host.DATA_SOURCE_PATH.'sourcebook.php?type=webbook&amp;id='.$val['b_id'].'&amp;bs='.$bs_id.'&amp;key='.$bs_unique_id;
      $rs[$key]['ibook_link'] = $http_host.DATA_SOURCE_PATH.'sourcebook.php?type=ibook&amp;id='.$val['b_id'].'&amp;bs='.$bs_id.'&amp;key='.$bs_unique_id;
    }
    $tpl->assign('bs_unique_id',$bs_unique_id);
    $tpl->assign('data',$rs);
    $tpl->display('api/datasource_xml.tpl');
    exit;
  }

  /*require_once LIBS_PATH.'/json.php';
  $output = $json = new Services_JSON();
  header('Content-Type: application/json; charset=utf-8');
  echo $json->encode($data);*/
  exit;
