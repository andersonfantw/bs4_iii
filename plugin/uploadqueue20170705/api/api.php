<?PHP
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('db','tpl','inputxss','filter','ejson');

$cmd = $fs->valid($_REQUEST['cmd'],'cmd');
$key = $fs->valid($_POST['fileid'],'key');

$UploadQueue = new UploadQueue();
$UploadQueue->setSITE(SiteModeEnum::API);
$UploadQueue->setSpell(1);
switch($cmd){
	case 'login':
		$acc = $fs->valid($_POST['acc'],'acc');
		$pwd = $fs->valid($_POST['pwd'],'pwd');
		if(empty($acc) && empty($pwd)){
			$ee->Error('401.72');
		}
		if($acc==UPLOADQUEUE_ID && $pwd==UPLOADQUEUE_PWD){
			$token = common::makeToken();
			$ee->add('token',$token);
			$ee->Message('200');
			exit;
		}else{
			$ee->Error('401.72');
		}
		break;
	case 'upload':
	case 'update':
		validToken();

		if(!$_FILES){
			$ee->add('msg','[uploadfile]');
			$ee->Error('406.60');
		}
		if(empty($key)){
			$ee->add('msg','[fileid]');
			$ee->Error('406.60');
		}

		$tagroot = UPLOADQUEUE_TAG_ROOT;
		if(empty($tagroot)){
			$tagroot = 'root';
		}
			
		$UploadQueue->setTagRoot($tagroot);
		if(UPLOADQUEUE_SETBSID){
			$bsid = $fs->valid($_POST['bookshelfid'],'id');
			if(empty($bsid)){
				$ee->add('msg','[bookshelfid]');
				$ee->Error('406.60');							
			}
		}else{
			$bsid = UPLOADQUEUE_BSID_DEFAULT;
		}
		$UploadQueue->setBSID($bsid);
		if(UPLOADQUEUE_SETCID){
			$cid = $fs->valid($_POST['categoryid'],'id');
			if(!empty($cid)){
				$UploadQueue->setCID($cid);
			}
		}else{
			$pcate = UPLOADQUEUE_PARENT_CATE;
			$ccate = UPLOADQUEUE_CHILD_CATE;
			if(empty($pcate) || empty($ccate)){
				if($cmd=='upload'){
					$ee->add('msg',sprintf('System setting error! please contact system admin.'));
					$ee->Error('406.60');
				}
			}

			$ProjectName = $fs->valid($_POST[UPLOADQUEUE_PARENT_CATE],'name');
			$ProjectNameKey = $fs->valid($_POST[UPLOADQUEUE_PARENT_CATE.'Key'],'key');
			$ExamineYear = $fs->valid($_POST[UPLOADQUEUE_CHILD_CATE],'name');
			$ExamineYearKey = $fs->valid($_POST[UPLOADQUEUE_CHILD_CATE.'Key'],'key');

			$UploadQueue->setParentCate(UPLOADQUEUE_PARENT_CATE);
			$UploadQueue->setChildCate(UPLOADQUEUE_CHILD_CATE);				

			if(!empty($ProjectNameKey) && !empty($ProjectName)){
				$UploadQueue->setTag('ProjectName',$ProjectNameKey,$ProjectName);
			}else{
				if($cmd=='upload'){
					$ee->add('msg',sprintf('Missing [%s] or [%sKey]',UPLOADQUEUE_PARENT_CATE,UPLOADQUEUE_PARENT_CATE));
					$ee->Error('406.60');
				}
			}
			if(!empty($ExamineYearKey) && !empty($ExamineYear)){
				$UploadQueue->setTag('ExamineYear',$ExamineYearKey,$ExamineYear);
			}else{
				if($cmd=='upload'){
					$ee->add('msg',sprintf('Missing [%s] or [%sKey]',UPLOADQUEUE_CHILD_CATE,UPLOADQUEUE_CHILD_CATE));
					$ee->Error('406.60');
				}
			}

			$tags = explode(',',UPLOADQUEUE_TAG_SETTINGS);
			foreach($tags as $tag){
				$required = (strpos($tag,'*')===0);
				if($required){
					$tag = strstr($tag,1);
				}
				$tag = $fs->valid($tag,'name');
				$tagval = $fs->valid($_POST[$tag],'name');
				$tagkey = $fs->valid($_POST[$tag.'Key'],'key');
				if($required){
					if(empty($tagval) || empty($tagval)){
						if($cmd=='upload'){
							$ee->add('msg',sprintf('Missing [%s] or [%sKey]',$tag,$tag));
							$ee->Error('406.60');
						}
					}
					$UploadQueue->setTag($tag,$tagkey,$tagval);
				}else{
					if(!empty($tagval) && !empty($tagval)){
						$UploadQueue->setTag($tag,$tagkey,$tagval);
					}
				}
			}
		}

		$isSuccess = $UploadQueue->add($key,$_FILES['uploadfile']);
		if($isSuccess){
			$ee->Message('200');
		}else{
			$ee->Error('500');
		}
		break;
	case 'progress':
		validToken();

		$queue = new queue(&$db);
		$unprocess = $queue->getUnprocess();
		$heartbeat = $UploadQueue->checkHeartbeat();
		$ee->add('total',$unprocess['num']);
		if($unprocess['num']>0){
			if($heartbeat){
				$filename = $heartbeat['filename'];
				$rate = $heartbeat['rate'];
			}else{
				$filename = '';
				$rate = 0;
			}
			$ee->add('filename',$filename);
			$ee->add('rate',$rate);
		}
		$ee->Message('200');
		break;
	case 'chkstatus':
		validToken();

		if(empty($key)){
			$ee->add('msg','Missing parameter [fileid]');
			$ee->Error('406.60');
		}
		$queue = new queue(&$db);
		$data = $queue->getListByKey($key);
		if(empty($data)){
			$ee->add('key',$key);
			$ee->Error('404');
		}
		$row = $data[count($data)-1];
		if($row['q_retry']>=3){
			$ee->add('key',$key);
			$ee->Error('500.28');
		}else{
			$ee->add('key',$key);
			$ee->add('progress',$row['status']);
			switch(intval($row['status'])){
				case QueueStatusEnum::Success:
					$ee->add('msg','importing');
					$ee->Warning('102');
					break;
				case QueueStatusEnum::ImportSuccess:
					$bid = $row['b_id'];
					$book = new book(&$db);
					$data1 = $book->getByID($bid);
					$openurl = $data1['webbook_link'];
					$openurl = str_replace(HttpLocalIPPort,'',$data1['webbook_link']);
					$openurl = str_replace(LocalHost, '', $openurl);
					$ee->add('openurl',$openurl);
					$ee->Message('200');
					break;
				case QueueStatusEnum::Wait:
					$ee->add('retry',$row['q_retry']);
					$ee->add('msg','waiting in queue');
					$ee->Warning('102');
					break;
				case QueueStatusEnum::Fail:
					$ee->Warning('500');
					break;
				default:
					$ee->add('msg','converting');
					$ee->Warning('102');
					break;
			}
		}
		break;
	case 'delete':
		validToken();

		if(empty($key)){
			$ee->add('msg','Missing parameter [fileid]');
			$ee->Warning('406.60');
		}
		$result = $UploadQueue->delByKey($key);
		if($result){
			$ee->Message('200');
		}else{
			$ee->add('msg','converting');
			$ee->Warning('102');
		}
		break;
	case 'start':
		validToken();
		$UploadQueue = new UploadQueue();
		$UploadQueue->doNext();
		break;
	case 'openbook':
		validToken();
		$bookurl = $fs->valid($_GET['bookurl'],'path');
		BookshelfManager::UserLogin(0,'iii','iii');
		if(empty($_SESSION['uid'])){
			$_SESSION['uid']=0; //for the prev book valid
		}
		$bookurl = str_replace('webs@2/ebook/','webs@2/ebook/'.session_id().'/',$bookurl);
		header('location: '.$bookurl);
		break;
	case 'getBookshelfList':
		validToken();
		$bookshelf = new bookshelf(&$db);
		$data = $bookshelf->getList('bs_id desc',0,0,'bs_status=1 and bs_list_status=1');
		$arr = array();
		foreach($data['result'] as $row){
			$arr[] = array('id'=>$row['bs_id'],'name'=>$row['bs_name']);
		}
		echo json_encode($arr);
		break;
	case 'getCategoryList':
		validToken();
		global $bs_code;
		$bs_code = $fs->valid($_POST['bookshelfid'],'num');
		$category = new category(&$db);
		$data = $category->getCategoryStructure();
		$arr = array();
		foreach($data['result'] as $pcate){
			foreach($pcate['sub_category'] as $ccate){
				
				
				$arr[] = array('id'=>$ccate['c_id'],
												'name'=>sprintf('%s-%s',$pcate['c_name'],$ccate['c_name']));
			}
		}
		echo json_encode($arr);
		break;
}

function validToken(){
	global $fs;
	global $ee;
	$token = $fs->valid($_REQUEST['token'],'lnettoken');
	if(empty($token)){
		$ee->Error('401.70');
	}else{
		$valid = common::checkToken($token,3600);
		if(!$valid){
			$ee->Error('401.74');
		}
	}
}

?>
