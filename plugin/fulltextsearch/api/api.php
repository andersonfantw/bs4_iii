<?PHP
/*
RewriteRule hosts/(.*)/(.*)/files/(.*)/data/fulltextsearch.txt /plugin/fulltextsearch/api/api.php?uid=$1&bsid=$2&bkey=$3 [L]
*/
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('db','tpl','inputxss','filter','getIP','ejson');

$uid = $fs->valid($_GET['uid'],'id');
$bsid = $fs->valid($_GET['bsid'],'id');
$bkey = $fs->valid($_GET['bkey'],'key');

$key_path = sprintf('%s/%u/%u/files/%s/data/search_key.csv',HOST_PATH,$uid,$bsid,$bkey);
$pnt_path = sprintf('%s/%u/%u/files/%s/data/search_pnt.csv',HOST_PATH,$uid,$bsid,$bkey);

$content_key = file_get_contents($key_path);
$content_pnt = file_get_contents($pnt_path);

$arr_key = explode("\r\n",$content_key);
$arr_pnt = explode("\r\n",$content_pnt);

if(count($arr_key)!=count($arr_pnt)){
	$ee->Error('406');
}

$book = new book(&$db);
$row = $book->getByKey($bkey);

$result = array();
if(!empty($row)){
	$str = sprintf('0;0;0;0;1;%s',$row['b_name']);
	if(!empty($row['b_description'])) $str .= '-'.$row['b_description'];
	$result[] = $str;
}
for($i=0;$i < count($arr_key); $i++){
	if($arr_pnt[$i]!='' && $arr_key[$i]!=''){
		$result[] = sprintf("%s;%s",$arr_pnt[$i],$arr_key[$i]);
	}
}

$str = implode("\n",$result);
echo $str;
?>

