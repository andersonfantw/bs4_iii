<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','auth','bookshelf_auth','tpl','inputxss','filter','status','ehttp');
require_once dirname(__FILE__).'/init.php';

if(!(LicenseManager::IsEcocatLicenseValid() || LicenseManager::IsBookshelfLicenseValid())){
	$tpl->display('backend/itutor_license.tpl');
	exit;
}

$type = $fs->valid($_GET['type'],'cmd');
$itutor = new itutor(&$db);
$exercise = new exercise(&$db);

$id = $fs->valid($_REQUEST['id'],'id');
$name = $fs->valid($_REQUEST['n'],'name');
//$sc = (int) $fs->valid($_REQUEST['sc'],'id');
//$ti = (int) $fs->valid($_REQUEST['ti'],'id');

$page = (int) $fs->valid($_GET['page'],'num');
$page = ($page==0)?1:$page;
switch ($type) {
  case 'edit':
		$data = $itutor->getList('',0,0,"id='".$id."'");
	  //$db_process_e = new db_process(&$db,'itutor_exercise','e_');
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
		$tpl->display('backend/itutor_edit.tpl');
    break;
  case 'list':
  default:    
		require_once LIBS_PATH.'/page.class.php';	
	
		$data = $itutor->getGroupList('i_id desc',($page-1)*PER_PAGE,PER_PAGE,'b.bs_id='.$bs_code);
		$record=$data['total'];
		if($record>PER_PAGE){
			$pagebar=new page(PER_PAGE,$page,$record,$url);
			$tpl->assign('pagebar',$pagebar);
		}
	
		$tpl->assign('data',$data['result']);
		$tpl->display('backend/itutor_list.tpl');
  	break;
}
