<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','sysauth','tpl','inputxss','filter','status','ehttp');

$type = $fs->valid($_GET['type'],'cmd');

$group = new group(&$db);
$AccountManager = new AccountManager();

if($type=='do_add' || $type=='do_update'){
	$trial = $fs->valid($_POST['trial'],'bool');
	$limit = $fs->valid($_POST['limit'],'num');
	$data['g_data'] = json_encode(array('trial'=>$trial,'limit'=>$limit));
  $data['g_name'] = $fs->valid($_POST['g_name'],'name');
  $cids = $fs->valid($_POST['c_id'],'idarray');
  $data['c_id'] = implode(',',$cids);
}

$id =  $fs->valid($_REQUEST['id'],'id');
$key = $fs->valid($_REQUEST['key'],'key');
$page = (int) $fs->valid($_GET['page'],'num');
$page = ($page==0)?1:$page;


switch ($type) {
  case 'add':
    $category = new category(&$db);
    $cate_data = $category->getCategoryStructure(array(),true);
    $tpl->assign('category',$cate_data);
    $tpl->display('backend/sys_group_edit.tpl');
    break;
  case 'edit':
    $data = $AccountManager->getGroup($id);
    $cate_data = $AccountManager->getCategoryStructure($id,true);
    $gdata = json_decode(stripslashes($data['g_data']),true);
		if(is_array($gdata)){
			$data = array_merge($data,$gdata);
		}

    $tpl->assign('data',$data);
    $tpl->assign('category',$cate_data);
    $tpl->display('backend/sys_group_edit.tpl');
    break;
  case 'do_add':
    if($group->insert($fs->sql_safe($data)))
      $status->go('sys_group.php','success',LANG_MESSAGE_ADD_SUCCESS);
    else
      $status->back('error',LANG_ERROR_ADD_FAIL);
    break;
  case 'do_update':
  	if(LicenseManager::chkAuth(MEMBER_SYSTEM,MemberSystemEnum::NAS_LDAP)){
  		$id=$key;
  	}
    if($AccountManager->updGroup($id,$fs->sql_safe($data),$bs_code)){
      $status->go('sys_group.php?page='.$page,'success',LANG_MESSAGE_UPDATE_SUCCESS);
    }else
      $status->back('error',LANG_ERROR_UPDATE_FAIL);
    break;
  case 'delete':
  	$data = $AccountManager->getUserList('g_id asc',($page-1)*PER_PAGE,PER_PAGE,$where,$id);
  	if($data['total']==0){
	    if($group->del($id))
	      $status->go('sys_group.php?page='.$page,'success',LANG_MESSAGE_DELETE_SUCCESS);
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
    $category = new category(&$db);
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
    $tpl->display('backend/sys_group_list.tpl');
    break;
}
?>