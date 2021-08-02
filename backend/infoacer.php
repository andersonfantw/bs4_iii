<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','auth','bookshelf_auth','tpl','inputxss','filter','status','ehttp');
$ImportManager = new ImportManager();
$allexam = new allexam($db);
$scanexam_quiz = new scanexam_quiz($db);
$scanexam_user = new scanexam_user($db);
$scanexam_exercise = new scanexam_exercise($db);

$m = $fs->valid($_GET['m'],'num');
$cmd = $fs->valid($_GET['cmd'],'cmd');
$path_parts = common::path_info($_SERVER['HTTP_REFERER']);
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
    $tpl->display('backend/infoacer_edit.tpl');
    break;
  case 'do_update':
    if($bookshelf->update($id,$fs->sql_safe($data))){
			//add bookshelf sys_config
			$define=array();
			$define['BSGiantviewSystem'] = $data['giantviewsystem'];
			$define['BSGiantviewChat'] = $data['giantviewchat'];
			$define['ENABLE_MYBOOKSHELF'] = $data['mybookshelf'];

			$uid = $account->getUIDByBSID($id);
			$ConfigManager = new ConfigManager($uid,$id);
			$ConfigManager->SaveDefine('userconfig',$define);
      $q_str = $fs->valid($_POST['bookshelf_q'],'query');
      if(empty($q_str)){
        $status->go('bookshelf.php?page='.$page,'success',LANG_MESSAGE_UPDATE_SUCCESS);
      }else{
        $status->go('bookshelf.php?page='.$page.'&type=search&q='.$q_str,'success',LANG_MESSAGE_UPDATE_SUCCESS);
      }
    }
    else
      $status->back('error',LANG_ERROR_UPDATE_FAIL);
    break;
  case 'delete':
    if($scanexam_test->del($se_key,$set_date))
      $status->go('allexam.php?page='.$page,'success',LANG_MESSAGE_DELETE_SUCCESS);
    else
      $status->back('error',LANG_ERROR_DELETE_FAIL);
    break;
  case 'list':
  default:
    require_once LIBS_PATH.'/page.class.php';
    parse_str(base64_decode($id),$url);

    if($type=='search_instant'){

      $q_str = $fs->valid($_POST['q'],'query');
	    $scanexam_user->reset();
	    $scanexam_user->setBSKey($url['bskey']);
	    $scanexam_user->setSEKey($url['key']);
	    $scanexam_user->setDate($url['date']);
			$data = $scanexam_user->getList('',0,0,"bu_name like '%".$q_str."%'");
			
      $tpl->assign('data',$data['result']);
      $tpl->assign('q_str',$q_str);
      $tpl->display('backend/infoacer_list_data.tpl');
			exit;
		}else{
	    $format_user = "bs_key='%s' and se_key='%s' and set_date='%s'";
	    $format_exercise = "bs_key='%s' and se_key='%s' and set_date='%s' and seq=%u";
	    $where_user  = sprintf($format_user,$url['bs_key'],$url['se_key'],$url['set_date'],$url['bu_id']);
	    $where_exercise = sprintf($format_exercise,$url['bs_key'],$url['se_key'],$url['set_date'],$url['seq'],$url['bu_id']);
	
	    $scanexam_quiz->reset();
	    $scanexam_quiz->setSEKey($url['key']);
		  $data_quiz = $scanexam_quiz->getList('',0,0,'');
	    $scanexam_user->reset();
	    $scanexam_user->setBSKey($url['bskey']);
	    $scanexam_user->setSEKey($url['key']);
	    $scanexam_user->setDate($url['date']);
			$data = $scanexam_user->getList('',0,0,'');
			$scanexam_exercise->reset();
			$scanexam_exercise->setBSKey($url['bskey']);
	    $scanexam_exercise->setSEKey($url['key']);
	    $scanexam_exercise->setDate($url['date']);
		  $data_exercise = $scanexam_exercise->getList('',0,0,'');
		}
		$arr_exercise = array();
		$arr_key = array();
		foreach($data_exercise['result'] as $val){
			if(!in_array($val['seq'],$arr_key)){
				array_push($arr_key,$val['seq']);
			}
			$arr_exercise[$val['bu_id']][$val['seq']] = $val;
		}

    $tpl->assign('data_quiz',$data_quiz['result']);
		$tpl->assign('data_key',$arr_key);
		$tpl->assign('data_exercise',$arr_exercise);
		$tpl->assign('data',$data['result']);
    $tpl->display('backend/infoacer_list.tpl');
    break;
}
?>