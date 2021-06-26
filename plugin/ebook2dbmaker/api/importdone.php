<?PHP
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('db','tpl','inputxss','filter','ejson');

$bookid = $fs->valid($_REQUEST['bookid'],'key');
$rc = $fs->valid($_REQUEST['rc'],'id');
$errmsg = $fs->valid($_REQUEST['errmsg'],'content');

global $ee;
$book = new book(&$db);
$queue = new queue(&$db);
$data = $book->getByKey($bookid);
if(empty($data)){
	$ee->add('bookid',$bookid);
	$ee->Error('404.1001');
}
$bid = $data['b_id'];

$LogManager = new LogManager('importdown');
$str = sprintf('bkey=%s, rc=%u, bid=%u',$bookid,$rc,$bid);
$LogManager->event('api',$str);

if($rc=='0'){
	$book->update($bid,array('b_status'=>1));
	$queue->updateStatusByBID($bid,QueueStatusEnum::ImportSuccess);
	if(ENABLE_DECENTRALIZED){
		//http://127.0.0.1/webs@2/ebook/1/3/2620631476694321/book.html
		$result = preg_match('/^http:\/\/127\.0\.0\.1\/webs@2\/ebook(\/\d+\/\d+\/)([^\/]+\/)/',$data['webbook_link'],$matches);
		if($result){
			$path = HOST_PATH.$matches[1].'files/'.$matches[2];
			common::rrmbookdir($path);
		}
	}
}else{
	$book->update($bid,array('b_status'=>-1));
	$queue->updateStatusByBID($bid,QueueStatusEnum::Fail);
	//$ee->add('bookid',$bookid);
	//$ee->add('msg',$errmsg);
	//$ee->Error('500');
}

?>
