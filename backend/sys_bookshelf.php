<?php
require_once dirname(__FILE__).'/../init/config.php';
/* require_once dirname(__FILE__).'/../api/nas.php'; */

$init = new init('db','sysauth','tpl','inputxss','filter','status','ehttp');
$type = $fs->valid($_GET['type'],'cmd');
$bookshelf = new bookshelf(&$db);
$ini = new ini(&$db);
$account = new account(&$db);
$AccountManager = new AccountManager();

if($type=='do_add' || $type=='do_update'){
  $data['bs_name'] = $fs->valid($_POST['bs_name'],'name');
  $data['bs_key'] = $fs->valid($_POST['bs_key'],'key');
  $inidata['expiredlink'] = $fs->valid($_POST['expiredlink'],'url');
  $data['giantviewsystem'] = (int) $fs->valid($_POST['giantviewsystem'],'bool');
  $data['giantviewchat'] = (int) $fs->valid($_POST['giantviewchat'],'bool');
  $data['mybookshelf'] = (int) $fs->valid($_POST['mybookshelf'],'bool');
  $u_id = $fs->valid($_POST['u_id'],'id');
}

$id = (int) $fs->valid($_REQUEST['id'],'id');
$page = (int) $fs->valid($_GET['page'],'num');
$page = ($page==0)?1:$page;

switch ($type) {
  case 'add':
  	$ini = new ini(&$db);
  	$bs_number = $ini->getByKey('system','bs_number');
    $bs_max = $bs_number['val'];
    $bookshelf_data = $bookshelf->getList('bs_id desc',($page-1)*PER_PAGE,PER_PAGE);
    $bookshelf_total = $bookshelf_data['total'];

    if($bookshelf_total >= $bs_max){
      $status->back('error',sprintf(LANG_WARNING_REACHMAX,$bookshelf_total));
      exit;
    }

		$account_data = $AccountManager->getBSManagerList();

    $tpl->assign('account_data',$account_data['result']);
    $tpl->assign('bookshelf_account_data_html', $tpl->fetch('backend/sys_bookshelf_edit_account_data.tpl'));
    $tpl->display('backend/sys_bookshelf_edit.tpl');
    break;
  case 'search_bookshelf_account':
    $q_str = $fs->valid($_POST['q'],'query');
    $account_data = $AccountManager->SearchManagerAccount($q_str);

    $tpl->assign('account_data',$account_data['result']);
    //$tpl->assign('bookshelf_account_data_html', $tpl->fetch('backend/sys_bookshelf_edit_account_data.tpl'));
    $tpl->display('backend/sys_bookshelf_edit_account_data.tpl');
    break;
  case 'edit':
    $data = $bookshelf->getByID($id);
    $inidata = $ini->getByGroup('bookshelf'.$id);
    $inidata = common::rs2ini($inidata['result']);
    if(!empty($inidata)){
	    $data = array_merge($data, $inidata['bookshelf'.$id]);
    }

    $account = new account(&$db);
    $account_data = $account->getList('u_cname desc');
    $q_str = $fs->valid($_GET['bookshelf_q'],'query');
    $tpl->assign('q_str',$q_str);
    $tpl->assign('account_data',$account_data['result'][0]);
    $tpl->assign('data',$data);
    $tpl->assign('bookshelf_account_data_html', $tpl->fetch('backend/sys_bookshelf_edit_account_data.tpl'));
    $tpl->display('backend/sys_bookshelf_edit.tpl');
    break;
  case 'do_add':
    $data['is_member'] = (int) $fs->valid($_POST['is_member'],'bool');
    $data['is_webbook'] = 1;
    if($data['is_member']!=1){
      $data['is_allbook'] = 1;
      $data['is_newbook'] = 1;
    }
		$u_id = $fs->valid($_POST['u_id'],'id');
		$u_id = $AccountManager->getBSManagerUID($u_id);

    $data['bs_title'] = $data['bs_name'];
    $val = BookshelfManager::CreateBookshelf($u_id,$data);
    if($val['status']){
    	$ini->update('bookshelf'.$id,$inidata);

			$define=array();
			$define['BSGiantviewSystem'] = $data['giantviewsystem'];
			$define['BSGiantviewChat'] = $data['giantviewchat'];
			$define['ENABLE_MYBOOKSHELF'] = $data['mybookshelf'];
			$ConfigManager = new ConfigManager($u_id,$id);
			$ConfigManager->SaveDefine('userconfig',$define);
    	if($data['is_member']==1 && LicenseManager::chkAuth(MEMBER_MODE,MemberModeEnum::CENTRALIZE_ASSIGN)){
    		$status->go('sys_bookshelf.php?type=group&id='.$val['bsid'],'success',LANG_WARNING_SET_GROUP);
			}else{
				$status->go('sys_bookshelf.php','success',LANG_MESSAGE_ADD_SUCCESS);
			}
    }else
      $status->back('error',LANG_ERROR_ADD_FAIL);
    break;
  case 'do_update':
    if($bookshelf->update($id,$fs->sql_safe($data))){
    	$ini->update('bookshelf'.$id,$inidata);
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
        $status->go('sys_bookshelf.php?page='.$page,'success',LANG_MESSAGE_UPDATE_SUCCESS);
      }else{
        $status->go('sys_bookshelf.php?page='.$page.'&type=search&q='.$q_str,'success',LANG_MESSAGE_UPDATE_SUCCESS);
      }
    }
    else
      $status->back('error',LANG_ERROR_UPDATE_FAIL);
    break;
  case 'do_gowebsite':
  	$_uid = (int) $fs->valid($_GET['uid'],'id');
  	$_acc = $fs->valid($_GET['acc'],'acc');
  	$_uname = $fs->valid($_GET['name'],'name');
  	$_bsid = (int) $fs->valid($_GET['bsid'],'id');
  	BookshelfManager::BSManagerLogout();
  	BookshelfManager::BSManagerLogin($_uid,$_acc,$_uname);
	//BookshelfManager::BSManagerLoginCookie($_bsid,'iii');
  	//BookshelfManager::UserLogout();
  	//BookshelfManager::UserLogin(12, 'c1053', '袁建仁');
	//echo sprintf('<script>document.location.href="%s/%s/%s/";</script>',WEB_URL,str_replace(LDAP_DOMAIN_PREFIX,'',$_acc),$_bsid);
  	header(sprintf('Location:%s/%s/%s/',WEB_URL,str_replace(LDAP_DOMAIN_PREFIX,'',$acc),$bsid));
	exit;
  	break;
  case 'group':
  	$group_data = $AccountManager->getGroupList($id);

    $q_str = $fs->valid($_GET['bookshelf_q'],'query');
    $tpl->assign('q_str',$q_str);
    $tpl->assign('data',$group_data);
    $tpl->display('backend/sys_bookshelf_group.tpl');
    break;
  case 'do_group':
    $groups_arr = $fs->valid($_POST['groups'],'idarray');
		$result = $AccountManager->setBSGroup($id,$groups_arr);

    if($result)
      $status->go('sys_bookshelf.php?page='.$page,'success',LANG_MESSAGE_SET_GROUP_SUCCESS);
    else
      $status->back('error',LANG_ERROR_SET_GROUP_FAIL);
    break;
  case 'disable':
    if($bookshelf->update_status($id,0))
      $status->go('sys_bookshelf.php?page='.$page,'success',LANG_MESSAGE_BOOKSHELF_INACTIVE_SUCCESS);
    else
      $status->back('error',LANG_ERROR_BOOKSHELF_INACTIVE_FAIL);
    break;
  case 'enable':
    if($bookshelf->update_status($id,1))
      $status->go('sys_bookshelf.php?page='.$page,'success',LANG_MESSAGE_BOOKSHELF_ACTIVE_SUCCESS);
    else
      $status->back('error',LANG_ERROR_BOOKSHELF_ACTIVE_FAIL);
    break;
  case 'delete':
    $bs_data = $bookshelf->getByID($id);
    $share_num = (int) $bs_data['share_num'];
    if($share_num>0){
			$status->back('error',LANG_WARNING_DELETE_SHARE_BOOKSHELF_FIRST);
			exit;
    }
    /******delete setting files*********/
    $uid = $bs_data['u_id'];
    BookshelfManager::DeleteBookshelf($uid,$id);
    /*******get ecocat api url**********/
    if(CONNECT_ECOCAT){
        $dbr->call_sp("call DeleteAPI({$id});");
    }

    /*******get ecocat api url**********/
    if($bookshelf->del($id))
      $status->go('sys_bookshelf.php?page='.$page,'success',LANG_MESSAGE_DELETE_SUCCESS);
    else
      $status->back('error',LANG_ERROR_DELETE_FAIL);
    break;
  case 'list':
  default:
    require_once LIBS_PATH.'/page.class.php';

    if($type=='search_instant'){
      $q_str = $fs->valid($_POST['q'],'query');
      $data = $bookshelf->getList('bs_id desc',0,0,"bs_name like '%".$q_str."%'");
      $url = 'sys_bookshelf.php?type=search&q='.$q_str;
      $tpl->assign('data',$data['result']);
      $tpl->assign('q_str',$q_str);
      $tpl->display('backend/sys_bookshelf_list_data.tpl');
    	exit;
    }else if($type=='search'){
      $q_str = $fs->valid($_REQUEST['q'],'query');
      $data = $bookshelf->getList('bs_id desc',($page-1)*PER_PAGE,PER_PAGE,"bs_name like '%".$q_str."%'");
      $url = 'sys_bookshelf.php?type=search&q='.$q_str;
      $tpl->assign('q_str',$q_str);
    }else{
      $data = $bookshelf->getList('bs_id desc',($page-1)*PER_PAGE,PER_PAGE);
    }
    $record=$data['total'];
    if($record>PER_PAGE){
      $pagebar=new page(PER_PAGE,$page,$record,$url);
      $tpl->assign('pagebar',$pagebar);
    }
    $tpl->assign('data',$data['result']);
    $tpl->assign('bookshelf_list_data_html', $tpl->fetch('backend/sys_bookshelf_list_data.tpl'));
    $tpl->display('backend/sys_bookshelf_list.tpl');
    break;
}
