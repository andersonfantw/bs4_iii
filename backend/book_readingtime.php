<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','auth','bookshelf_auth','tpl','inputxss','filter','status','ehttp');
require_once dirname(__FILE__).'/init.php';

$type = $fs->valid($_GET['type'],'cmd');

//$db_process = new db_process($db,'reading_time','');
$reading_time = new reading_time($db);

$id = $fs->valid($_REQUEST['id'],'id');

$page = (int) $fs->valid($_GET['page'],'num');
$page = ($page==0)?1:$page;
switch ($type) {
  case 'edit':
	$db_process = new db_process($db,'itutor','i_');
	$data = $db_process->getList('',0,0,'i_name=\''.$name.'\' and i_slidecount='.$sc.' and i_totalinteraction='.$ti);
	$db_process_e = new db_process($db,'itutor_exercise','e_');
	$data_exercise = $db_process_e->getList('',0,0,'i_id='.$data['result'][0]['i_id']);
	$arr_exercise = array();
	$arr_index = array();
	$arr_key = array();
	$arr_key_index = 0;
	foreach($data_exercise['result'] as $val){
		if(!isset($arr_index[$val['i_id']])){
			$arr_index[$val['i_id']] = 0;
		}
		if(!array_search($val['e_reportid'],$arr_key)){
			$arr_key[$val['e_reportid']] = 1;
		}
		$arr_exercise[$val['i_id']][$val['e_reportid']] = array_merge($val);
		$arr_index[$val['i_id']]++;
	}
	$arr_key = array_merge(array_keys($arr_key));
//print_r($arr_exercise);exit;
	asort($arr_key);
	$tpl->assign('data_key',$arr_key);
	$tpl->assign('data_exercise',$arr_exercise);
	$tpl->assign('data',$data['result']);
	$tpl->display('backend/itutor_edit.tpl');
    break;
  case 'list':
  default:    
	require_once LIBS_PATH.'/page.class.php';	

	$data = $reading_time->getList('bu_id desc',($page-1)*PER_PAGE,PER_PAGE,'b_id='.$id);
	$record=$data['total'];
	if($record>PER_PAGE){
		$pagebar=new page(PER_PAGE,$page,$record,$url);
		$tpl->assign('pagebar',$pagebar);
	}
	$tpl->assign('max',$data['max']);
	$tpl->assign('data',$data['result']);
	$tpl->display('backend/book_readingtime.tpl');
  break;
}
