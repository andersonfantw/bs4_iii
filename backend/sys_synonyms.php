<?php
require_once dirname(__FILE__).'/../init/config.php';
/* require_once dirname(__FILE__).'/../api/nas.php'; */

$init = new init('db','sysauth','tpl','inputxss','filter','status','ehttp');
$type = $fs->valid($_GET['type'],'cmd');
$LogManager = new LogManager(__FILE__);
$fulltext_synonyms = new fulltext_synonyms(&$db);

if($type=='do_add' || $type=='do_update'){
  $data['fts_name'] = $fs->valid($_POST['name'],'name');
  $data['fts_content'] = $fs->valid($_POST['content'],'content');
  $data['fts_status'] = (int)$fs->valid($_POST['status'],'id');	//1:互相 2:雙向 3:單向
}

$id = (int) $fs->valid($_REQUEST['id'],'id');
$page = (int) $fs->valid($_GET['page'],'num');
$page = ($page==0)?1:$page;

switch ($type) {
  case 'add':
    $tpl->assign('data',$data['result']);
    $tpl->display('backend/sys_synonyms_edit.tpl');
    break;
  case 'edit':
    $data = $fulltext_synonyms->getByID($id);

    $tpl->assign('data',$data['result']);
    $tpl->display('backend/sys_synonyms_edit.tpl');
    break;
  case 'do_add':
    $id=$fulltext_synonyms->insert($data);
    if($id){
			$status->go('sys_synonyms.php','success',LANG_MESSAGE_ADD_SUCCESS);
    }else
      $status->back('error',LANG_ERROR_ADD_FAIL);
    break;
  case 'do_update':
  	$rows = $fulltext_synonyms->getByID($id);
    if($fulltext_synonyms->update($id,$fs->sql_safe($data))){
  		$LogManager->setData($rows);
	  	$LogManager->setNewData($data);
     	$LogManager->event('command: '.$type, sprintf('%s id=%s by SysAdmin %s',$type,$id,bssystem::getSysLoginName()));

      $status->go('sys_synonyms.php?page='.$page,'success',LANG_MESSAGE_UPDATE_SUCCESS);
    }
    else
      $status->back('error',LANG_ERROR_UPDATE_FAIL);
    break;
  case 'delete':
    /*******get ecocat api url**********/
    $data = $fulltext_synonyms->getByID($id);
    if($fulltext_synonyms->del($id)){
    	$LogManager->setData($data);
    	$LogManager->event('command: '.$type, sprintf('%s id=%s set by SysAdmin %s',$type,$id,bssystem::getSysLoginName()));
      $status->go('sys_synonyms.php?page='.$page,'success',LANG_MESSAGE_DELETE_SUCCESS);
    }else
      $status->back('error',LANG_ERROR_DELETE_FAIL);
    break;
  case 'list':
  default:
    require_once LIBS_PATH.'/page.class.php';

    $data = $fulltext_synonyms->getList('fts_id desc',($page-1)*PER_PAGE,PER_PAGE);
    $record=$data['total'];
    if($record>PER_PAGE){
      $pagebar=new page(PER_PAGE,$page,$record,$url);
      $tpl->assign('pagebar',$pagebar);
    }
    $tpl->assign('data',$data['result']);
    $tpl->display('backend/sys_synonyms_list.tpl');
    break;
}

