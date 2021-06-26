<?php
require_once dirname(__FILE__).'/../init/config.php';

$init = new init('db','sysauth','tpl','inputxss','filter','status','ehttp');
$type = $fs->valid($_GET['type'],'cmd');
$db_process = new db_process(&$db,'system_account','su_');

$su_id = (int) $fs->valid($_REQUEST['id'],'id');

if($type=='do_update'){
	$password = $fs->valid($_POST['su_password'],'pwd');
	$password2 = $fs->valid($_POST['su_password2'],'pwd');
  if(!empty($password)){
    if($password!=$password2)
    {
      $status->back('error',LANG_WARNING_PASSWORD_NOT_MATCH);
      exit;
    }
    $data['su_password'] = BASE64_ENCODE($password);
  }

  $data['su_name'] = $fs->valid($_POST['su_name'],'acc');
}

switch ($type) {
  case 'edit':
    $data = $db_process->getByID($su_id);
    $tpl->assign('data',$data);
    $tpl->display('backend/sys_system_account_edit.tpl');
    break;
  case 'do_update':
    if($db_process->update($su_id,$fs->sql_safe($data))){
    	if(CONNECT_ECOCAT){
				$crypt = base64_decode($data['su_password']);
				$crypt = common::encryptString($crypt);
    	  $dbr->call_sp("call Sync_account('{$data['su_name']}','{$crypt}');");
    	}
      $status->go('sys_system_account.php?','success',LANG_MESSAGE_UPDATE_SUCCESS);
    }else
      $status->back('error',LANG_ERROR_UPDATE_FAIL);
    break;
  case 'list':
  default:    
    require_once LIBS_PATH.'/page.class.php';	

    $data = $db_process->getList('su_name desc');
    $_sysuser = common::getcookie('sysuser');
    if($_sysuser=='webadmin'){
      $data = $db_process->getList('su_name desc',0,0,"su_name!='admin'");
    }else{
      $data = $db_process->getList('su_name desc');
    }
    $tpl->assign('data',$data['result']);
    $tpl->display('backend/sys_system_account_list.tpl');
    break;
}
