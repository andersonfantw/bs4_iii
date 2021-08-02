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

$UploadQueue = new UploadQueue();
$ini = new ini($db);
$queue = new queue($db);

$row = $ini->getByKey('uploadqueue','notice');
if(empty($row)){
	$qid=4716;
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
	if(array_key_exists($status,$status_arr)){
		$_status = $status_arr[$status];
		switch($status){
			case 0:
				$queue->del($qid);
				break;
		}
	}else{
		$_status=QueueStatusEnum::ConvertSuspend;
		//delete suspend item
		$queue->del($qid);
	}
	$UploadQueue->mail($_status,$qid,$pn);
	$ini->update('uploadqueue','notice',$qid);
}
?>
