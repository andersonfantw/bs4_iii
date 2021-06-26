<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','auth','bookshelf_auth','tpl','inputxss','filter','status','ehttp');
$ImportManager = new ImportManager();
$allexam = new allexam(&$db);
$scanexam_test = new scanexam_test(&$db);

$type = $fs->valid($_GET['type'],'cmd');
$m = $fs->valid($_GET['m'],'cmd');
$cmd = $fs->valid($_GET['cmd'],'cmd');
$id = $fs->valid($_REQUEST['id'],'key');
$page = (int) $fs->valid($_GET['page'],'num');
$page = ($page==0)?1:$page;

switch ($type) {
	case 'export':
		$ImportManager->Export(ImportManagerModeEnum::InfoacerExam1);
		exit;
		break;
	case 'import':
		if(empty($m)){
			$tpl->display('backend/allexam_list.tpl');
		}else{
			switch($cmd){
				case 'import_infoacer':
					$m = ScoreImportManagerModeEnum::InfoacerExam1;
					break;
			}
			$tpl->assign('mode',$m);
			$tpl->display('backend/allexam.tpl');
		}
		break;
  case 'edit':
		if(is_numeric($id)){
			$data = $allexam->getByKey($id);
		}else{
			parse_str(base64_decode($id),$url);
			$data = $allexam->getByKey($url['bskey'],$url['key'],$url['date']);
		}
  	$tpl->assign('data',$data);
    $tpl->display('backend/allexam_edit.tpl');
    break;
  case 'do_update':
		$status->go('allexam.php?page='.$page.'&q='.$q_str,'success',LANG_MESSAGE_UPDATE_SUCCESS);
    break;
  case 'delete':
  	parse_str(base64_decode($id),$url);
    if($scanexam_test->del($url['key'],$url['date']))
      $status->go('allexam.php?page='.$page,'success',LANG_MESSAGE_DELETE_SUCCESS);
    else
      $status->back('error',LANG_ERROR_DELETE_FAIL);
    break;
  case 'list':
  default:
    require_once LIBS_PATH.'/page.class.php';
    $allexam->setBSID($bs_code);
    switch($type){
    	case 'search_instant':
    	case 'search':
	      $q_str = $fs->valid($_POST['q'],'query');
	      $data = $allexam->getList('createdate desc',($page-1)*PER_PAGE,PER_PAGE,sprintf("name like '%%%s%%' or key like '%%%s%%'",$q_str,$q_str));
	      $url = 'allexam.php?type=search&q='.$q_str;
	      $tpl->assign('q_str',$q_str);
	      break;
	  	default:
      	$data = $allexam->getList('createdate desc',($page-1)*PER_PAGE,PER_PAGE);
      	break;
    }
    for($i=0;$i<count($data['result']);$i++){
    	$data['result'][$i]['type'] = trim($data['result'][$i]['type']);
    	switch($data['result'][$i]['type']){
    		case 'itutor':
    			$data['result'][$i]['id'] = $data['result'][$i]['key'];
    			break;
    		case 'infoacer':
    			$data['result'][$i]['id'] = base64_encode(sprintf("bskey=%s&key=%s&date=%s",$data['result'][$i]['bskey'],$data['result'][$i]['key'],$data['result'][$i]['createdate']));
    			break;
    	}
    }
    switch($type){
    	case 'search_instant':
    		$tpl->assign('data',$data['result']);
      	$tpl->display('backend/allexam_list_data.tpl');
      	exit;
      	break;
    }
    $record=$data['total'];
    if($record>PER_PAGE){
      $pagebar=new page(PER_PAGE,$page,$record,$url);
      $tpl->assign('pagebar',$pagebar);
    }
    $tpl->assign('data',$data['result']);
    $tpl->assign('allexam_list_data_html', $tpl->fetch('backend/allexam_list_data.tpl'));
    $tpl->display('backend/allexam_list.tpl');
    break;
}
?>