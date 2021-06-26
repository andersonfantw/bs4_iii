<?PHP
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('db','tpl','inputxss','filter','ejson');

$cmd = $fs->valid($_REQUEST['cmd'],'cmd');
$type = $fs->valid($_POST['type'],'cmd');
$_buid = $fs->valid($_POST['buid'],'id');

$ini = new ini(&$db);
$output = new Services_JSON();
$json = new Services_JSON(SERVICES_JSON_ESCAPED_UNICODE);

switch($cmd){
	case 'validQuickSearch':
	case 'getQuickSearch':
		$data = $ini->getByKey('QuickSearch',$_buid);
		$str = '[]';
		if(!empty($data)){
			$str = $data['val'];
		}
		if($cmd=='validQuickSearch'){
			echo md5($str);
			exit;
		}
		header('Content-Type: application/json; charset=utf-8');
		echo $str;exit;
		break;
	case 'setQuickSearch':
		$str = $fs->valid($_POST['str'],'content');
		$str = htmlspecialchars_decode($str);
		$data = $ini->update('QuickSearch',array($_buid=>$str));
		if($data){
			$ee->Message('200');
		}
		break;
	case 'validSearchItemSetting':
	case 'getSearchItemSetting':
		$data = $ini->getByKey('SearchItemSetting',$_buid);
		$str = '[]';
		if(!empty($data)){
			$str = $data['val'];
		}
		if($cmd=='validSearchItemSetting'){
			echo md5($str);
			exit;
		}
		header('Content-Type: application/json; charset=utf-8');
		echo $str;exit;
		break;
	case 'setSearchItemSetting':
		$str = $fs->valid($_POST['str'],'content');
		$str = htmlspecialchars_decode($str);
		$data = $ini->update('SearchItemSetting',array($_buid=>$str));
		if($data){
			$ee->Message('200');
		}
		break;
}

?>
