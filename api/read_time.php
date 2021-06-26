<?php
  session_start();

  require_once dirname(__FILE__).'/../init/config.php';
  $init = new init('db','tpl','inputxss','filter','getIP','ejson');
  $reading_time = new reading_time(&$db);
  date_default_timezone_set('Asia/Taipei');

  $data['b_id'] = (int) $fs->valid($_POST['bid'],'id');
  $data['bu_id'] = $fs->valid($_COOKIE['buid'],'id');
  $data['c_id'] = (int) $fs->valid($_POST['cid'],'id');
  $data['remote_ip'] = $USER_IP;
  $data['session_id'] = session_id();
  if($data['b_id'] < 1) exit;

	$timestamp = $fs->valid($_POST['timestamp'],'timestamp');
  if(empty($timestamp)){
		$data['start_time'] = date("Y-m-d H:i:s"); 
  }else{
		$data['start_time'] = $timestamp;
  }

	$rs = $reading_time->getByPKey($data['b_id'],$data['c_id'],$data['start_time']);
	if($rs){
		$bid = $data['b_id'];
		$cid = $data['c_id'];
		$starttime = $data['start_time'];
		$data['end_time'] = date("Y-m-d H:i:s");
		unset($data['b_id']);
		unset($data['c_id']);
		unset($data['start_time']);
		$rs1 = $reading_time->updateByPKey($bid,$cid,$starttime,$data);
		$data['start_time'] = $starttime;
	}else{
		$data['end_time'] = $data['start_time'];
		$rs1 = $reading_time->insert($data);
	}
  //$sql = sprintf("insert into %sreading_time set remote_ip='%s', session_id='%s',  b_id=%u,bu_id=%u,c_id=%u,start_time='%s', end_time='%s' ON DUPLICATE KEY UPDATE end_time='%s'",DB_PREFIX, $USER_IP, session_id(), $data['b_id'], $data['bu_id'], $data['c_id'], $data['start_time'], $data['start_time'], date("Y-m-d H:i:s") );
  //$rs = $db->query($sql);

  $json = new Services_JSON();
  if($rs1)
  {
		$out_data['code'] = '200';
		$out_data['msg'] = 'ok';
		$out_data['timestamp'] = $data['start_time'];
		
		echo $json->encode($out_data);exit;
  }
  
  $out_data['code'] = '501';
  $out_data['msg'] = 'error';
  echo $json->encode($out_data);exit;
