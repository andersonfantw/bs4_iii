<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','auth','bookshelf_auth','tpl','inputxss','filter','status','ehttp');
require_once dirname(__FILE__).'/init.php';
/* require_once dirname(__FILE__).'/../api/nas.php'; */

$type = $fs->valid($_GET['type'],'cmd');
$bookshelf_user = new bookshelf_user($db);

$g_id = $fs->valid($_REQUEST['gid'],'id');
$bu_id = (int) $fs->valid($_REQUEST['id'],'id');

$password  = $fs->valid($_POST['bu_password'],'pwd');
$password2 = $fs->valid($_POST['bu_password2'],'pwd');

$page = (int) $fs->valid($_GET['page'],'num');
$page = ($page==0)?1:$page;

$AccountManager = new AccountManager();

if($type=='do_add'){
  if(empty($password)){
    $status->back('error',LANG_WARNING_ACCOUNT_CANT_NOT_BE_NULL);
    exit;
  }
  if($password!=$password2)
  {
    $status->back('error',LANG_WARNING_PASSWORD_NOT_MATCH);
    exit;
  }
  $data['bu_password'] = md5($password);
}
if($type=='do_update'){
  if(!empty($password)){
    if($password!=$password2)
    {
      $status->back('error',LANG_WARNING_PASSWORD_NOT_MATCH);
      exit;
    }
    $data['bu_password'] = md5($password);
  }
}
if($type=='do_add' || $type=='do_update'){
  $data['bu_name'] = $fs->valid($_POST['bu_name'],'acc');
  $data['bu_cname'] = $fs->valid($_POST['bu_cname'],'name');
}

switch ($type) {
  case 'add':
    $tpl->display('backend/bookshelf_user_edit.tpl');
    break;
  case 'edit':
    $data = $bookshelf_user->getByID($bu_id);
    $tpl->assign('data',$data);
    $tpl->display('backend/bookshelf_user_edit.tpl');
    break;
  case 'do_add':
    $check_rs = $bookshelf_user->getByName($data['bu_name']);
    if(!$check_rs){
			$data['g_id'] = $g_id;
			if($bookshelf_user->insert($fs->sql_safe($data))){
				$status->go('bookshelf_user.php?gid='.$g_id,'success',LANG_MESSAGE_ADD_SUCCESS);
			}else{
				$status->back('error',LANG_ERROR_ADD_FAIL);
			}
    }else{
       $status->back('error',LANG_ERROR_ADD_ACCOUNT_OCCUPIED);
    }
    break;
  case 'do_update':
    if($bookshelf_user->update($bu_id,$fs->sql_safe($data)))
      $status->go('bookshelf_user.php?gid='.$g_id.'&page='.$page,'success',LANG_MESSAGE_UPDATE_SUCCESS);
    else
      $status->back('error',LANG_ERROR_UPDATE_FAIL);
    break;
  case 'delete':
    if($bookshelf_user->del($bu_id,$g_id))
      $status->go('bookshelf_user.php?gid='.$g_id.'&page='.$page,'success',LANG_MESSAGE_DELETE_SUCCESS);
    else
      $status->back('error',LANG_ERROR_DELETE_FAIL);
    break;
  case 'list':
  case 'search':
  default:
    require_once LIBS_PATH.'/page.class.php';

    $group_data = $AccountManager->getGroup($g_id);
    
		$where = '';
		switch($type){
			case 'search':
			case 'search_top10':
				$q_str = $fs->valid($_POST['q'],'query');
				switch(DB_TYPE){
					case 'dbmaker':
						$where = sprintf("bu_name contain '%s'",$q_str);
						break;
					default:
						$where = sprintf("bu_name like '%%%s%%'",$q_str);
						break;
				}
				break;
			default:
				break;
		}

    $data = $AccountManager->getUserList('g_id asc',($page-1)*PER_PAGE,PER_PAGE,$where,$g_id);
    for($i=0;$i<count($data['result']);$i++){
    	$data['result'][$i]['key']=base64_encode(sprintf('username=%s&userid=%u',$data['result'][$i]['bu_name'],$data['result'][$i]['bu_id']));
    	$data['result'][$i]['careername'] = CareerMapping($data['result'][$i]['bu_career']);
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
    $tpl->display('backend/bookshelf_user_list.tpl');
    break;
}

function CareerMapping($id){
	$arr_career = array('not set',
		LANG_ACTIVECODE_CAREER_COLLEGE,
		LANG_ACTIVECODE_CAREER_SENIOR_HIGH_SCHOOL,
		LANG_ACTIVECODE_CAREER_JUNIOR_HIGH_SCHOOL,
		LANG_ACTIVECODE_CAREER_EDUCATORS,
		LANG_ACTIVECODE_CAREER_GOV,
		LANG_ACTIVECODE_CAREER_FOOD_MANUFACTURING,
		LANG_ACTIVECODE_CAREER_TEXTILE,
		LANG_ACTIVECODE_CAREER_PAPER,
		LANG_ACTIVECODE_CAREER_MANUFACTURING,
		LANG_ACTIVECODE_CAREER_INFORMATION,
		LANG_ACTIVECODE_CAREER_AUTOMOTIVE,
		LANG_ACTIVECODE_CAREER_TRANSPORTATION,
		LANG_ACTIVECODE_CAREER_RECYCLING,
		LANG_ACTIVECODE_CAREER_BUILDING,
		LANG_ACTIVECODE_CAREER_RESEARCH,
		LANG_ACTIVECODE_CAREER_DESIGNED
	);
	if(in_array($id,$arr_career)){
		return $arr_career[$id];
	}else{
		return 'not set!';
	}
}
