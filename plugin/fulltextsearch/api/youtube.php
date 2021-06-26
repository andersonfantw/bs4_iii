<?PHP
/*
https://www.youtube.com/watch?v=sNQZNx6JylQ
https://youtu.be/sNQZNx6JylQ


*/
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('db','tpl','inputxss','filter','getIP','ejson');

$u = $fs->valid($_GET['u'],'url');
$arr = array('//www.youtube.com/'=>'watch?v=','//youtu.be/'=>'youtu.be/');
$u1 = substr($u,0,30);
foreach($arr as $k=>$v){
	if(strpos($u1,$k)!==false){
		//make sure url is youtube
		list($s,$key) = explode($v,$u);
		//check subs file
		$cache = sprintf('%s/youtube_subs_%s',CACHE_PATH,$key);
		if(is_file($cache)){
			echo file_get_contents($cache);exit;
		}
	}
}
?>