<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','sysauth','tpl','inputxss','filter','status','ehttp');
$type = $fs->valid($_GET['type'],'cmd');
$bookshelf = new bookshelf(&$db,'bookshelfs');
$bookshelf_share = new bookshelf_share(&$db,'bookshelf_share');
$bookshelf_share_source = new bookshelf_share_source(&$db,'bookshelf_share_source');

if($type=='do_add' || $type=='do_update'){
  $data['bss_ip'] = $fs->valid($_POST['bss_ip'],'ip');
  $data['bss_account'] = $fs->valid($_POST['bss_account'],'acc');
  $data['bss_password'] = $fs->valid($_POST['bss_password'],'pwd');
  $data['bs_id'] = (int) $fs->valid($_REQUEST['bs_id'],'id');
}

if($type=='source_do_add' || $type=='source_do_update'){
  $data['bsss_name'] = $fs->valid($_POST['bsss_name'],'name');
  $data['bsss_source'] = $fs->valid($_POST['bsss_source'],'ip');
  $data['bsss_account'] = $fs->valid($_POST['bsss_account'],'acc');
  $data['bsss_password'] = $fs->valid($_POST['bsss_password'],'pwd');
}

$id = (int) $fs->valid($_REQUEST['id'],'id');
$page = (int) $fs->valid($_GET['page'],'num');
$page = ($page==0)?1:$page;


switch ($type) {
  case 'add':
    $bookshelf_data = $bookshelf->getList('bs_id desc',($page-1)*PER_PAGE,PER_PAGE);
    $data['bss_id'] = $id;
    $tpl->assign('data',$data);
    $tpl->assign('bookshelf_data',$bookshelf_data['result']);
    $tpl->display('backend/sys_bookshelf_share_edit.tpl');
    break;
  case 'search_bookshelf':
    $q_str = $fs->valid($_POST['q'],'query');
    $bookshelf = new db_process(&$db,'bookshelfs','bs_');
    $bookshelf_data = $bookshelf->getList('bs_name desc',0,0,"bs_name like '%".$q_str."%'");
    $tpl->assign('bookshelf_data',$bookshelf_data['result']);
    $tpl->display('backend/sys_bookshelf_share_edit_bookshelf_data.tpl');
    break;
  case 'source_add':  
    $data['bsss_id'] = $id;
    $tpl->assign('data',$data);    
    $tpl->display('backend/sys_bookshelf_share_source_edit.tpl');
    break;
  /*case 'edit':
    $data = $bookshelf->getList('bs_id desc',($page-1)*PER_PAGE,PER_PAGE,' bs.bs_id='.$id);

    $account = new db_process(&$db,'account','u_');
    $account_data = $account->getList('u_cname desc');
    $tpl->assign('account_data',$account_data['result']);
    $tpl->assign('data',$data['result'][0]);
    $tpl->display('backend/sys_bookshelf_edit.tpl');
    break;*/
  case 'do_add':  
    $rs = $bookshelf_share->insert($fs->sql_safe($data));
    if($rs){
      $status->go('sys_bookshelf_share.php','success',LANG_MESSAGE_ADD_SUCCESS);
    }else
      $status->back('error',LANG_ERROR_ADD_FAIL);
    break;
  case 'source_do_add':      
    $rs = $bookshelf_share_source->insert($fs->sql_safe($data));
    if($rs){
      $status->go('sys_bookshelf_share.php','success',LANG_MESSAGE_ADD_SUCCESS);
    }else
      $status->back('error',LANG_ERROR_ADD_FAIL);
    break;
  case 'connection':
    $rs = $bookshelf_share_source->getByID($id);
    $url = $rs['bsss_source']."&ac=".$rs['bsss_account']."&pw=".md5($rs['bsss_password'])."&type=connnect_only";
    $content = @file_get_contents($url);
    if(strpos($http_response_header[0], "200")){
      if(empty($content))
        $data['bsss_status'] = 501;
      else{
        $data['bsss_status'] = 200;
        $data['books_count'] = $content;
      }
    }else if(strpos($http_response_header[0], "403")){
      $data['bsss_status'] = 403;
    }else if(strpos($http_response_header[0], "404")){
      $data['bsss_status'] = 404;
    }else if(strpos($http_response_header[0], "504")){
      $data['bsss_status'] = 504;
    }else{
      $data['bsss_status'] = 503;
    }
    
    $data['bsss_last_time'] = date("Y-m-d H:i:s");
    $bookshelf_share_source->update($id,$fs->sql_safe($data));
    if($data['bsss_status']==200)
      $status->go('sys_bookshelf_share.php','success',LANG_MESSAGE_CONNECT_SUCCESS);
    else
      $status->go('sys_bookshelf_share.php','error',LANG_ERROR_CONNECT_FAIL);
    break;
    case 'xml':
    $rs = $bookshelf_share_source->getByID($id);
    $url = $rs['bsss_source']."&ac=".$rs['bsss_account']."&pw=".md5($rs['bsss_password'])."&type=xml";
    $content = @file_get_contents($url);
    echo $content;exit;
  /*case 'do_update':
    if($bookshelf->update($id,$fs->sql_safe($data),$u_id))
      $status->go('sys_bookshelf.php?page='.$page,'success',LANG_MESSAGE_UPDATE_SUCCESS);
    else
      $status->back('error',LANG_ERROR_UPDATE_FAIL);
    break;
  case 'group':
    $group_data = $bookshelf->get_bookshelf_groups_structure($id);
    $tpl->assign('data',$group_data);
    $tpl->display('backend/sys_bookshelf_group.tpl');
    break;
  case 'do_group':
    $groups_arr = $_POST['groups'];
    if($bookshelf->update_bookshelf_groups($id,$groups_arr))
      $status->go('sys_bookshelf.php?page='.$page,'success',LANG_MESSAGE_SET_GROUP_SUCCESS);
    else
      $status->back('error',LANG_ERROR_SET_GROUP_FAIL);
    break;
  case 'disable':
    if($bookshelf->update_bookshelf_status($id,0))
      $status->go('sys_bookshelf.php?page='.$page,'success',LANG_MESSAGE_BOOKSHELF_INACTIVE_SUCCESS);
    else
      $status->back('error',LANG_ERROR_BOOKSHELF_INACTIVE_FAIL);
    break;
  case 'enable':
    if($bookshelf->update_bookshelf_status($id,1))
      $status->go('sys_bookshelf.php?page='.$page,'success',LANG_MESSAGE_BOOKSHELF_ACTIVE_SUCCESS);
    else
      $status->back('error',LANG_ERROR_BOOKSHELF_ACTIVE_FAIL);
    break;*/
  case 'delete':
    if($bookshelf_share->del($id))
      $status->go('sys_bookshelf_share.php?page='.$page,'success',LANG_MESSAGE_DELETE_SUCCESS);
    else
      $status->back('error',LANG_ERROR_DELETE_FAIL);
    break;
  case 'source_delete':
    if($bookshelf_share_source->del($id))
      $status->go('sys_bookshelf_share.php?page='.$page,'success',LANG_MESSAGE_DELETE_SUCCESS);
    else
      $status->back('error',LANG_ERROR_DELETE_FAIL);
    break;
  case 'list':
  default:    
    require_once LIBS_PATH.'/page.class.php'; 
    $data_source = $bookshelf_share_source->getList('bsss_id desc',($page-1)*PER_PAGE,PER_PAGE);
    $data = $bookshelf_share->getList('bss_id desc',($page-1)*PER_PAGE,PER_PAGE);
    $tpl->assign('data_share',$data['result']);
    $tpl->assign('data_share_source',$data_source['result']);
    $tpl->display('backend/sys_bookshelf_share_list.tpl');
    break;
}
