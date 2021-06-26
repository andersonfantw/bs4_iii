<?PHP
// */15 * * * * /usr/local/bin/curl -sL http://127.0.0.1/plugin/fulltextsearch/api/batch.php
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('db','tpl','inputxss','filter','getIP','ejson');

$book = new book(&$db);
$SaveSubsManager = new SaveSubsManager();

$book->setCols(array('b_id','b_name','webbook_link'));
$book->setBType(BookTypeEnum::URL_Youtube);
$data = $book->getList();
$arr = array('//www.youtube.com/'=>'watch?v=','//youtu.be/'=>'youtu.be/');
$u1 = substr($u,0,30);
foreach($data['result'] as $r){
	foreach($arr as $k=>$v){
		if(strpos($r['webbook_link'],$k)!==false){
			list($s,$key) = explode($v,$r['webbook_link']);
			//check subs file
			$cache = sprintf('%s/youtube_subs_%s',CACHE_PATH,$key);
			if(!is_file($cache)){
				$arr_subs = $SaveSubsManager->getSubs($r['b_name'],$r['webbook_link']);
				$content = implode("\r\n",$arr_subs);
				file_put_contents($cache,$content);
			}
		}
	}
}
?>