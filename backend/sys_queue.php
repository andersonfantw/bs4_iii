<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','sysauth','tpl','inputxss','filter','status','ealert');

$type = $fs->valid($_GET['type'],'cmd');
$queue = new queue(&$db);
if($type=='do_add' || $type=='do_update'){
}
$id = (int) $fs->valid($_REQUEST['id'],'id');
$page = (int) $fs->valid($_GET['page'],'num');
$page = ($page==0)?1:$page;

switch ($type) {
  case 'add':
  	$_lnettoken = common::makeToken();
  	$tpl->assign('lnettoken',$_lnettoken);
    $tpl->display('backend/sys_queue_add.tpl');
    break;
  case 'delete':
  	$data = $queue->getByID($id);
  	$key = $data['q_key'];
  	$book = new book(&$db);
		$book->delByKey($key);
		if($queue->del($id))
		  $status->go('sys_queue.php?page='.$page,'success',LANG_MESSAGE_DELETE_SUCCESS);
		else
		  $status->back('error',LANG_ERROR_DELETE_FAIL);
    break;
  case 'reloadlist':
		$data = $queue->getList('q_id asc',($page-1)*100,100,'isdelete=0 and status=0 and q_retry<3');
		$data1 = $queue->getList('q_id desc',0,1,'isdelete=0 and status>0 and status<100 and q_retry<3');
		if($data1['total']!='0'){
			array_unshift($data['result'],$data1['result'][0]);
		}
		for($i=0;$i<count($data['result']);$i++){
			$json = json_decode($data['result'][$i]['q_data'],true);

			$str = '';
			$tags = array();
			$arr = explode('!',$json['tags']);
			foreach($arr as $a){
				if(!empty($a)){
					list($p,$k,$v) = explode(',',$a);
					if(!empty($v)){
						$str.=sprintf('<br />%s=%s:%s',$p,$k,$v);
					}
				}
			}
			$str = substr($str,6);
			$data['result'][$i]['data'] = $str;
		}
		$tpl->assign('data',$data['result']);
    $tpl->display('backend/sys_queue_list_data.tpl');
  	break;
	case 'list':
	default:
		require_once LIBS_PATH.'/page.class.php';
		/*
		$data = $queue->getList('q_id desc',($page-1)*PER_PAGE,PER_PAGE,'isdelete=0 and status=0');
		for($i=0;$i<count($data['result']);$i++){
			$json = json_decode($data['result'][$i]['q_data'],true);

			$str = '';
			$tags = array();
			$arr = explode('!',$json['tags']);
			foreach($arr as $a){
				if(!empty($a)){
					list($p,$k,$v) = explode(',',$a);
					if(!empty($v)){
						$str.=sprintf('<br />%s=%s:%s',$p,$k,$v);
					}
				}
			}
			$str = substr($str,6);
			$data['result'][$i]['data'] = $str;
		}*/
		/*
    $record=$data['total'];
    if($record>PER_PAGE){
      $pagebar=new page(PER_PAGE,$page,$record,$url);
      $tpl->assign('pagebar',$pagebar);
    }*/
    $_lnettoken = common::makeToken();
  	$tpl->assign('lnettoken',$_lnettoken);
		//$tpl->assign('data',$data['result']);
    $tpl->assign('sys_queue_list_data_html', $tpl->fetch('backend/sys_queue_list_data.tpl'));
		$tpl->display('backend/sys_queue_list.tpl');
		break;
}
?>
