<?PHP
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','auth','bookshelf_auth','tpl','inputxss','filter','status','ehttp');
require_once dirname(__FILE__).'/init.php';

global $bs_code;

$type = $fs->valid($_GET['type'],'cmd');
$activecode = new activecode(&$db);
$ActiveCodeManager = new ActiveCodeManager();

if($type=='do_add'){
	$data['ac_code'] = $fs->valid($_POST['activecode'],'lnettoken');
	$data['ac_term'] = (int)$fs->valid($_POST['term'],'num');
	$data['bs_id'] = (int) $fs->valid($bs_code,'id');
	$username = $fs->valid($_POST['username'],'name');
	$gid = $fs->valid($_POST['g_id'],'idarray');
	$data['arr_gid'] = $gid;
	$gids = implode(',',$gid);
	$strjson = json_encode(array('username'=>$username,'gid'=>$gids));
	$data['ac_data'] = $strjson;
}

$id = $fs->valid($_REQUEST['id'],'id');
$page = (int) $fs->valid($_GET['page'],'num');
$page = ($page==0)?1:$page;

switch ($type) {
	case 'add':
		$AccountManager = new AccountManager();
		$code = $ActiveCodeManager->getCode();
		//$data = $AccountManager->getGroupList($bs_code);
		if(!LicenseManager::chkAuth(MEMBER_MODE,MemberModeEnum::CENTRALIZE)){
			$condition = sprintf('bs_id=%d',$bs_code);
		}
		$group = new group(&$db);
		$data = $group->getList('',0,0,$condition);

  	$tpl->assign('activecdoe',$code);
		$tpl->assign('data',$data['result']);
		$tpl->display('backend/activecode_edit.tpl');
		break;
  case 'do_add':
    if($ActiveCodeManager->isValid($data['ac_code'])){
	    if($activecode->insert($data)){
	      $status->go('activecode.php?page='.$page,'success',LANG_MESSAGE_UPDATE_SUCCESS);
	    }else
	      $status->back('error',LANG_ERROR_UPDATE_FAIL);
	  }else{
	  	$status->back('error',LANG_ERROR_UPDATE_FAIL);
	  }
  	break;
  case 'delete':
  	if($activecode->del($id))
      $status->go('activecode.php?page='.$page,'success',LANG_MESSAGE_DELETE_SUCCESS);
    else
      $status->back('error',LANG_ERROR_DELETE_FAIL);
    break;
  	break;
  case 'search':
  case 'list':
  default:
  	require_once LIBS_PATH.'/page.class.php';
  	$group = new group(&$db);
  	if(!LicenseManager::chkAuth(MEMBER_MODE,MemberModeEnum::CENTRALIZE)){
  		$condition = sprintf(' and bs_id=%d',$bs_code);
  	}
  	if($type=='search'){
	  	$q_str = $fs->valid($_POST['q'],'query');
	  	$data = $activecode->getList('createdate desc',($page-1)*PER_PAGE,PER_PAGE,"ac_code like '%".$q_str."%'".$condition);
	  	$url = $_SERVER['PHP_SELF'].'?type=search&q='.$q_str;
  	}else{
	  	$data = $activecode->getList('createdate desc',($page-1)*PER_PAGE,PER_PAGE,'1=1'.$condition);
		}

		for($i=0;$i<count($data['result']);$i++){
			$json = json_decode($data['result'][$i]['ac_data'],TRUE);
			$arr_gid = explode(',',$json['gid']);
			$plan = '';
			foreach($arr_gid as $gid){
				$data1 = $group->getByID($gid);
				$plan .= ','.$data1['g_name'];
			}
			if(!empty($plan)) $plan = substr($plan,1);
			$data['result'][$i]['ac_data'] = sprintf('%s - %s %s(%s)',$json['username'],$plan,$data['result'][$i]['bu_name'],$data['result'][$i]['bu_cname']);
			
			$month = intval($data['result'][$i]['ac_term']);
			$day = $month * ActiveCodeManager::days_in_a_month;
			if(empty($data['result'][$i]['registdate'])){
				$data['result'][$i]['expireddate']='';
			}else{
				$timestamp = strtotime(sprintf('%s +%u day',$data['result'][$i]['registdate'],$day));
				$data['result'][$i]['expireddate'] = date('Y-m-d H:i:s',$timestamp);
			}
  	}

    $record=$data['total'];
    if($record>PER_PAGE){
      $pagebar=new page(PER_PAGE,$page,$record,$url);
      $tpl->assign('pagebar',$pagebar);
    }
    
  	$tpl->assign('data',$data['result']);
  	$tpl->display('backend/activecode_list.tpl');
		break;
}
?>
