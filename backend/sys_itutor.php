<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','sysauth','tpl','inputxss','filter','status','ehttp');
require_once dirname(__FILE__).'/init.php';

if(!(LicenseManager::IsEcocatLicenseValid() || LicenseManager::IsBookshelfLicenseValid())){
	$tpl->display('backend/itutor_license.tpl');
	exit;
}

$type = $fs->valid($_GET['type'],'cmd');
$itutor = new itutor($db);
$exercise = new exercise($db);

$id = $fs->valid($_REQUEST['id'],'key');
$name = $fs->valid($_REQUEST['n'],'name');
$sc = (int) $fs->valid($_REQUEST['sc'],'num');
$ti = (int) $fs->valid($_REQUEST['ti'],'num');

$page = (int) $fs->valid($_GET['page'],'num');
$page = ($page==0)?1:$page;
switch ($type) {
	default:
  case 'list':
		$data = $itutor->getList('',0,0,"id='".$id."'");
	  //$db_process_e = new db_process($db,'itutor_exercise','e_');
	  $data_exercise = $exercise->getList('',0,0,"id='".$id."'");
		$arr_exercise = array();
		$arr_index = array();
		$arr_key = array();
		$arr_key_index = 0;
		foreach($data_exercise['result'] as $val){
			if(!isset($arr_index[$val['i_id']])){
				$arr_index[$val['i_id']] = 0;
			}
			//if(!array_search($val['e_reportid'],$arr_key)){
				$arr_key[$val['e_reportid']] = 1;
			//}
			$val['e_result']=(mb_strlen($val['e_result'])==2)?1:0;
			$arr_exercise[$val['i_id']][$val['e_reportid']] = array_merge($val);
			$arr_index[$val['i_id']]++;
		}
		$arr_key = array_merge(array_keys($arr_key));
		asort($arr_key);
		$tpl->assign('data_key',$arr_key);
		$tpl->assign('data_exercise',$arr_exercise);
		$tpl->assign('data',$data['result']);
		$tpl->display('backend/sys_itutor_list.tpl');
    break;
}
