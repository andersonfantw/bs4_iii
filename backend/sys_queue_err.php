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
  case 'delete':
  	$data = $queue->getByID($id);
  	$key = $data['q_key'];
  	$book = new book(&$db);
		$book->delByKey($key);
		if($queue->del($id))
		  $status->go('sys_queue_err.php?page='.$page,'success',LANG_MESSAGE_DELETE_SUCCESS);
		else
		  $status->back('error',LANG_ERROR_DELETE_FAIL);
    break;
  case 'list':
  default:
  	require_once LIBS_PATH.'/page.class.php';
		//$data = $queue->getList('q_id desc',($page-1)*PER_PAGE,PER_PAGE,"(isdelete=1 and status!=200) or (isdelete=0 and (status<0 or q_retry=3 or (status=100 and timestampdiff('m',editdate,now())>60)))");
  	$queue->setType(QueueTypeEnum::MailList);
  	$data = $queue->getList('q_id desc',($page-1)*PER_PAGE,PER_PAGE,'');
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
    $record=$data['total'];
    $url = 'sys_queue_err.php';
    if($record>PER_PAGE){
      $pagebar=new page(PER_PAGE,$page,$record,$url);
      $tpl->assign('pagebar',$pagebar);
    }
		$tpl->assign('data',$data['result']);
    $tpl->assign('sys_queue_list_data_html', $tpl->fetch('backend/sys_queue_errlist_data.tpl'));
		$tpl->display('backend/sys_queue_errlist.tpl');
  	break;
}
?>
