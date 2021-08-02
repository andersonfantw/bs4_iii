<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','auth','bookshelf_auth','tpl','inputxss','filter','status','ehttp');
require_once dirname(__FILE__).'/init.php';

$type = $fs->valid($_GET['type'],'cmd');
$db_process = new db_process($db,'groups','g_');
$group = new group($db);
$AccountManager = new AccountManager();

/*if($type=='do_add'){
  if(empty($_POST['g_password'])){
    $status->back('error',LANG_WARNING_PASSWORD_CANT_NOT_BE_NULL);
    exit;
  }
  if($_POST['g_password']!=$_POST['g_password2'])
  {
    $status->back('error',LANG_WARNING_PASSWORD_NOT_MATCH);
    exit;
  }
  $data['g_password'] = md5($_POST['g_password']);
}
if($type=='do_update'){
  if(!empty($_POST['g_password'])){
    if($_POST['g_password']!=$_POST['g_password2'])
    {
      $status->back('error',LANG_WARNING_PASSWORD_NOT_MATCH);
      exit;
    }
    $data['g_password'] = md5($_POST['g_password']);
  }
}*/
if($type=='do_add' || $type=='do_update'){
  $data['g_name'] = $fs->valid($_POST['g_name'],'name');
  $cids = $fs->valid($_POST['c_id'],'idarray');
  $data['c_id'] = implode(',',$cids);
  /*$data['g_account'] = $fs->valid($_POST['g_account'],'acc');
  $data['bs_id'] = $fs->valid($bs_code,'id');*/

}

/*if($type=='do_add' && $group->getGroupByAccount($data['g_account'])){
	$status->back('error','帳號已存在');
      exit;
  }*/

$id =  $fs->valid($_REQUEST['id'],'id');
$key = $fs->valid($_REQUEST['key'],'key');
$page = (int) $fs->valid($_GET['page'],'num');
$page = ($page==0)?1:$page;


switch ($type) {
  case 'add':
    $category = new category($db);
    $cate_data = $category->getCategoryStructure();
    $tpl->assign('category',$cate_data);
    $tpl->display('backend/group_edit.tpl');
    break;
  case 'edit':
/*
    $group_category = $group->getCategoryByGID($id);
    $category = new category($db);
    $cate_data = $category->getCategoryStructure($group_category);

    $data = $db_process->getByID($id);
*/

    $data = $AccountManager->getGroup($id);
    $cate_data = $AccountManager->getCategoryStructure($id);

    $tpl->assign('data',$data);
    $tpl->assign('category',$cate_data);
    $tpl->display('backend/group_edit.tpl');
    break;
  case 'do_add':
    if($group->insert($fs->sql_safe($data)))
      $status->go('group.php','success',LANG_MESSAGE_ADD_SUCCESS);
    else
      $status->back('error',LANG_ERROR_ADD_FAIL);
    break;
  case 'do_update':
  	if(LicenseManager::chkAuth(MEMBER_SYSTEM,MemberSystemEnum::NAS_LDAP)){
  		$id=$key;
  	}
    if($AccountManager->updGroup($id,$fs->sql_safe($data),$bs_code)){
      $status->go('group.php?page='.$page,'success',LANG_MESSAGE_UPDATE_SUCCESS);
    }else
      $status->back('error',LANG_ERROR_UPDATE_FAIL);
    break;
  case 'delete':
		$data = $AccountManager->getUserList('g_id asc',($page-1)*PER_PAGE,PER_PAGE,$where,$id);
		if($data['total']==0){
			if($group->del($id))
				$status->go('group.php?page='.$page,'success',LANG_MESSAGE_DELETE_SUCCESS);
			else
				$status->back('error',LANG_ERROR_DELETE_FAIL);
		}else{
			$status->back('error',LANG_ERROR_DELETE_HAVE_INSITE_ITEM);
		}
    break;
  case 'list':
  case 'search':
  default:
    require_once LIBS_PATH.'/page.class.php';
    /*
    $category = new category($db);
    $cate_data = $category->getCategoryStructure();
    if($cate_data){
      foreach($cate_data as $cate){
        $cate_arr[$cate[c_id]]=$cate['c_name'];
      }
    }*/

		$where = '';
		switch($type){
			case 'search':
			case 'search_top10':
				$q_str = $fs->valid($_POST['q'],'query');
				switch(DB_TYPE){
					case 'dbmaker':
						$where = sprintf("g_name contain '%s'",$q_str);
						break;
					default:
						$where = sprintf("g_name like '%%%s%%'",$q_str);
						break;
				}
				break;
			default:
				break;
		}

    $data = $AccountManager->getGroupList('',($page-1)*PER_PAGE,PER_PAGE,$where,CENTRALIZE_MEMBER);
    for($i=0;$i<count($data['result']);$i++){
    	$data['result'][$i]['key']=base64_encode(sprintf('groupname=%s&gid=%u',$data['result'][$i]['g_name'],$data['result'][$i]['g_id']));
    }

    $record=$data['total'];
    if($record>PER_PAGE){
      $pagebar=new page(PER_PAGE,$page,$record,$url);
      $tpl->assign('pagebar',$pagebar);
    }
    $tpl->assign('data',$data['result']);
    $tpl->display('backend/group_list.tpl');
    break;
}
