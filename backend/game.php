<?php
require_once dirname(__FILE__).'/../config.php';
$init = new init('db','auth','tpl','inputxss','filter','status','ehttp');
require_once dirname(__FILE__).'/init.php';
/* require_once dirname(__FILE__).'/../api/nas.php'; */
require_once('Crypt/Blowfish.php');

global $bs_code;
$type = $fs->valid($_GET['type'],'cmd');
$game_reflection = new game_reflection($db);

if($type=='do_add' || $type=='do_update'){
  $game_data['grc_mark'] = (int)$fs->valid($_POST['grc_mark'],'num');
}

$id = (int) $fs->valid($_REQUEST['id'],'id');
$page = (int) $fs->valid($_GET['page'],'id');
$page = ($page==0)?1:$page;


switch ($type) {
	case 'mark':
		$bsid = (int) $fs->valid($_POST['bsid'],'id');
		$bid = (int) $fs->valid($_POST['bid'],'id');
		$buid = (int) $fs->valid($_POST['buid'],'id');
		$val = (int) $fs->valid($_POST['val'],'name');
		$rs = $game_reflection->updateCommonMark($bsid,$bid,$buid,$val);
    if($rs){
    	$ee->Message('200');
    }else{
    	$ee->ERROR('500.41');
    }
		break;
  case 'edit':
    $data = $game_reflection->getCommonList($id, $bs_code, $bid, 'gr_id desc',0,0,'');
    $tpl->assign('data',$data['result']);
    $tpl->display('backend/game_edit.tpl');
    break;
  case 'do_update':
    if($game_reflection->common_update($id,$bs_code,$fs->sql_safe($game_data))){
        $status->go('game.php?page='.$page,'success',LANG_MESSAGE_UPDATE_SUCCESS);
    }
    else
      $status->back('error',LANG_ERROR_UPDATE_FAIL);
    break;
  case 'list':
  default:
    require_once LIBS_PATH.'/page.class.php';

    $data = $game_reflection->getCommonList($bs_code, $id, 'gr_id desc',($page-1)*PER_PAGE,PER_PAGE);
    $record=$data['total'];

    if($record>PER_PAGE){
      $pagebar=new page(PER_PAGE,$page,$record,$url);
      $tpl->assign('pagebar',$pagebar);
    }
    $tpl->assign('data',$data['result']);
    $tpl->display('backend/game_list.tpl');
    break;
}
