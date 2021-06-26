<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','sysauth','tpl','inputxss','filter','status','ehttp');
/* require_once dirname(__FILE__).'/../api/nas.php'; */

$type = $fs->valid($_GET['type'],'cmd');
$bookshelf_user = new bookshelf_user(&$db);

$g_id = $fs->valid($_REQUEST['gid'],'id');
$bu_id = (int) $fs->valid($_REQUEST['id'],'id');
$page = (int) $_GET['page'];
$page = ($page==0)?1:$page;

$AccountManager = new AccountManager();

switch ($type) {
  case 'delete':
    if($bookshelf_user->del($bu_id,$g_id))
      $status->go('sys_bookshelf_user.php?gid='.$g_id.'&page='.$page,'success',LANG_MESSAGE_DELETE_SUCCESS);
    else
      $status->back('error',LANG_ERROR_DELETE_FAIL);
    break;
  case 'list':
  default:
    require_once LIBS_PATH.'/page.class.php';

    $group_data = $AccountManager->getGroup($g_id);
    
    $data = $AccountManager->getUserList('g_id asc',($page-1)*PER_PAGE,PER_PAGE,$where,$g_id);
    for($i=0;$i<count($data['result']);$i++){
    	$data['result'][$i]['key']=base64_encode(sprintf('username=%s&userid=%u',$data['result'][$i]['bu_name'],$data['result'][$i]['bu_id']));
    }

    $record=$data['total'];
    $tpl->assign('group_data',$group_data);
    $url=htmlentities($_SERVER['PHP_SELF']).'?gid='.$g_id;
    if($record>PER_PAGE){
    	$url = $_SERVER['PHP_SELF'].'?gid='.$g_id;
      $pagebar=new page(PER_PAGE,$page,$record,$url);
      $tpl->assign('pagebar',$pagebar);
    }
    $tpl->assign('data',$data['result']);
    $tpl->display('backend/sys_bookshelf_user_list.tpl');
    break;
}
?>