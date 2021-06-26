<?PHP
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('filter','db','ejson');

$status_arr=array(0=>QueueStatusEnum::ExceedMaxRetryTimes,
						-1=>QueueStatusEnum::Fail,
						-2=>QueueStatusEnum::MissingFile,
						-3=>QueueStatusEnum::IncorrectFilename,
						-4=>QueueStatusEnum::ConvertingTimeout,
						-7=>QueueStatusEnum::ExceedMaxRetryTimes,
						100=>QueueStatusEnum::ImportFail);

$content =<<<LOG
時間: %s
主機: %s
轉檔編號: %u
檔案名稱: %s
錯誤訊息: %s
LOG;

$UploadQueue = new UploadQueue();
$ini = new ini(&$db);
$queue = new queue(&$db);

$row = $ini->getByKey('uploadqueue','notice');
if(empty($row)){
	$qid=4664;
	$ini->insert(array('group'=>'uploadqueue','key'=>'notice','val'=>(string)$qid));
}else{
	$qid=$row['val'];
}

$data = $queue->getFailureList($qid);
foreach($data as $row){
	$status = $row['status'];
	$retry = $row['q_retry'];
	$qid = $row['q_id'];

	$qdata = $row['q_data'];
	$decode_arr = json_decode($qdata,true);
	$tagstr = $decode_arr['tags'];
	$arr = $UploadQueue->parseTagstr($tagstr);
	$pn = 'Not set!';
	if(array_key_exists('ProjectName',$arr)){
		$pn = $arr['ProjectName'];
	}elseif(array_key_exists('pn',$arr)){
		$pn = $arr['pn'];
	}
	if($status==0){
		$errmsg = $UploadQueue->getUploadQueueEnumString(QueueStatusEnum::ConvertingTimeout);
	}elseif($status>0 && $status<100){
		$errmsg = $UploadQueue->getUploadQueueEnumString(QueueStatusEnum::ConvertSuspend);
	}elseif($status==100){
		$errmsg = $UploadQueue->getUploadQueueEnumString(QueueStatusEnum::ImportFail);
	}elseif($status==QueueStatusEnum::ExceedMaxRetryTimes || $retry==3){
		$errmsg = $UploadQueue->getUploadQueueEnumString(QueueStatusEnum::ExceedMaxRetryTimes);
	}elseif($status<0){
		$errmsg = $UploadQueue->getUploadQueueEnumString($status);
	}

	$_subject = sprintf('[數位圖書館]檔案轉換失敗通知 - %s',$pn);
	$_content = sprintf($content,
								date('Y-m-d H:i:s'),
								$_SERVER['SERVER_ADDR'],
								$qid,
								$pn,
								$errmsg);
	$MailManager = new MailManager();
	$MailManager->setReplyTo(UPLOADQUEUE_ERROR_APPLYTO1);
	$MailManager->setReplyTo(UPLOADQUEUE_ERROR_APPLYTO2);
	$MailManager->setSubject($_subject);
	$MailManager->setContent($_content);
	$MailManager->setTargetID($qid);
	$MailManager->send();
	$ini->update('uploadqueue','notice',$qid);
}
?>
