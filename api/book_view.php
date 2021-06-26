<?php
  require_once dirname(__FILE__).'/../init/config.php';
  $init = new init('db','tpl','inputxss','filter','getIP','ejson');

  global $bs_code;
  $bs_code = (int) $fs->valid($_POST['bs'],'id');
//  $ConfigManager = new ConfigManager();
//  include_once $ConfigManager->getDefineUserbase();
//  include_once $ConfigManager->getDefineSyspath();
  $bv_type_arr = array('webbook'=>0, 'ibook'=>1, ''=> 2);
  $bv_type = $fs->valid($_POST['openmode'],'cmd');

  $data['b_id'] = (int) $fs->valid($_POST['bid'],'id');
  if($data['b_id'] < 1 ) exit;

  $data['bu_name'] = $fs->valid($_POST['ac'],'acc');
  $data['bv_ip'] = $USER_IP;
  $data['bv_time'] = date("Y-m-d H:i:s");
  $data['bv_type'] = $bv_type_arr[$bv_type];
  $data['bs_id'] = $bs_code;

  $json = new Services_JSON();
  $db_process = new db_process(&$db,'books_views','bv_'); 
  if($db_process->insert($data))
  {
    $book = new book(&$db);
    if($book->update_views($data['b_id'],$bv_type)){
		$out_data['code'] = '200';
		$out_data['msg'] = 'ok';
		echo $json->encode($out_data);exit;
	}
  }
  
  $out_data['code'] = '501';
  $out_data['msg'] = 'error';
  echo $json->encode($out_data);exit;
