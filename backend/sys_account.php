<?php
require_once dirname(__FILE__).'/../init/config.php';
require_once('Crypt/Blowfish.php');

$init = new init('db','sysauth','tpl','inputxss','filter','status','ehttp');
$type = $fs->valid($_GET['type'],'cmd');
$account = new account(&$db);
$account_manager = new AccountManager;

$u_id = (int) $fs->valid($_REQUEST['id'],'id');

$password = $fs->valid($_POST['u_password'],'pwd');
$password2 = $fs->valid($_POST['u_password2'],'pwd');

$page = (int) $fs->valid($_GET['page'],'num');
$page = ($page==0)?1:$page;
  
if($type=='do_add'){
  if(empty($password)){
    $status->back('error',LANG_WARNING_PASSWORD_CANT_NOT_BE_NULL);
    exit;
  }
  if($password!=$password2)
  {
    $status->back('error',LANG_WARNING_PASSWORD_NOT_MATCH);
    exit;
  }
  $data['u_password'] = base64_encode($password);
}
if($type=='do_update'){
  if(!empty($password)){
    if($password!=$password2)
    {
      $status->back('error',LANG_WARNING_PASSWORD_NOT_MATCH);
      exit;
    }
    $data['u_password'] = base64_encode($password);
  }
}
if($type=='do_add' || $type=='do_update'){
  $data['u_name'] = $fs->valid($_POST['u_name'],'acc');  
  $data['u_cname'] = $fs->valid($_POST['u_cname'],'name');
}

switch ($type) {
  case 'add':
    $tpl->display('backend/sys_account_edit.tpl');
    break;
  case 'edit':
    $data = $account->getByID($u_id);
    $tpl->assign('data',$data);
    $q_str = $fs->valid($_GET['q'],'query');    
    $tpl->assign('q_str',$q_str);
    $tpl->display('backend/sys_account_edit.tpl');
    break;
  case 'do_add':  
    $check_rs = $account->getList('u_name desc',0,0,"u_name ='".$data['u_name']."'");
    //some error while using odbc to get total
    //if($check_rs['total']==0){
    if(count($check_rs['result'])==0){
      $uid = $account->insert($fs->sql_safe($data),true);
      if($uid){
        $status->go('sys_bookshelf.php?type=add&uid='.$uid,'success',LANG_MESSAGE_NEXT_STEP_CREATE_BOOKSHELF);
        exit;
      }
    }

    $status->back('error',LANG_ERROR_ADD_ACCOUNT_OCCUPIED);
    break;  
  case 'do_update':    
    if($account->update($u_id,$fs->sql_safe($data))){
    	if($data['u_password']!=''){
        $crypt = base64_decode($data['su_password']);
        $crypt = common::encryptString($crypt);
	
			  if(CONNECT_ECOCAT){
					$dbr->call_sp("call Sync_account('{$data['u_name']}','{$crypt}');");
			  }
	    }
      $q_str = $fs->valid($_POST['q'],'query');
      
      if(empty($q_str)){
        $status->go('sys_account.php','success',LANG_MESSAGE_UPDATE_SUCCESS);
      }else{
        $status->go('sys_account.php?type=search&q='.$q_str,'success',LANG_MESSAGE_UPDATE_SUCCESS);
      }
	  }else
      $status->back('error',LANG_ERROR_UPDATE_FAIL);
    break;
  case 'delete':
		//make sure don't have bookshelfs
		$data = $account->getBookshelfByUID($u_id);
		if(count($data)){
			$status->back('error',LANG_ERROR_DELETE_BELONG_BOOKSHELFS);
		}else{
			if($account->del($u_id))
			  $status->go('sys_account.php?page='.$page,'success',LANG_MESSAGE_DELETE_SUCCESS);
			else
			  $status->back('error',LANG_ERROR_DELETE_FAIL);			
		}
    break;
  case 'list':
  default:    
    require_once LIBS_PATH.'/page.class.php';	

    if($type=='search_instant'){
  
      $q_str = $fs->valid($_POST['q'],'query');
      $data = $account_manager->getBSManagerList('u_cname desc',0,0,"u_cname like '%".$q_str."%'");
      $url = 'sys_account.php?type=search&q='.$q_str;
      $tpl->assign('data',$data['result']);
      $tpl->assign('q_str',$q_str);
      $tpl->display('backend/sys_account_list_data.tpl');
 
    exit;
    }else if($type=='search'){
      $q_str = $fs->valid($_REQUEST['q'],'query');
      $data = $account_manager->getBSManagerList('u_cname desc',($page-1)*PER_PAGE,PER_PAGE,"u_cname like '%".$q_str."%'");
      $url = 'sys_account.php?type=search&q='.$q_str;
      $tpl->assign('q_str',$q_str);
    }else{
    	$data = $account_manager->getBSManagerList('u_cname desc',($page-1)*PER_PAGE,PER_PAGE);
    }
    $record=$data['total'];
    if($record>PER_PAGE){
      $pagebar=new page(PER_PAGE,$page,$record,$url);
      $tpl->assign('pagebar',$pagebar);
    }
    $tpl->assign('data',$data['result']);
    $tpl->assign('sys_account_list_data_html', $tpl->fetch('backend/sys_account_list_data.tpl'));
    $tpl->display('backend/sys_account_list.tpl');
    break;
}

