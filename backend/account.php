<?php
require_once dirname(__FILE__).'/../config.php';
$init = new init('db','auth','tpl','inputxss','filter','status','ehttp');
require_once dirname(__FILE__).'/init.php';
require_once('Crypt/Blowfish.php');

$type = $fs->valid($_GET['type'],'cmd');
$db_process = new db_process(&$db,'account','u_');

$account = $fs->valid($_COOKIE['adminuser'],'acc');

if($type=='do_update'){
	$id = (int) $fs->valid($_COOKIE['adminid'],'id');
	$data['u_password'] = $fs->valid($_POST['u_password'],'pwd');
	$u_password2 = $fs->valid($_POST['u_password2'],'pwd');
	if(empty($data['u_password'])){
		$status->back('error',LANG_WARNING_PASSWORD_CANT_NOT_BE_NULL);
		exit;
	}
	if($data['u_password']!=$u_password2)
	{
		$status->back('error',LANG_WARNING_PASSWORD_NOT_MATCH);
		exit;
	}
	$data['u_password'] = base64_encode($data['u_password']);

}

switch($type) {
  case 'do_update':
    if($db_process->update($id,$fs->sql_safe($data))){
    	if(CONNECT_ECOCAT){
				$cbf = new Crypt_Blowfish(ENCRYPT_KEY);
				$crypt = base64_decode($data['u_password']);
				$crypt = $cbf->encrypt($crypt);
				$crypt = base64_encode($crypt);
				
				$dbr->call_sp("call Sync_account('{$account}','{$crypt}');");
			}
		$status->go('account.php','success',LANG_MESSAGE_UPDATE_SUCCESS);
	}else
		$status->back('error',LANG_ERROR_UPDATE_FAIL);
    break;
  case 'edit':
  default:
	$tpl->assign('account',$account);
	$tpl->display('backend/account_edit.tpl');
    break; 
}
