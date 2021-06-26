<?PHP
/*
BSManager login to use these api.
*/
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('db','filter','ejson');

$cmd = $fs->valid($_GET['cmd'],'cmd');

switch($cmd){
	case 'import':
		$bs_code = $fs->valid($_GET['bs'],'id');
		$mode = $fs->valid($_GET['m'],'num');

		//upload xls, and check type and data
		//if have errors, echo error rows.
		//if all correct, return excute code.
		$ScoreImportManager = new ScoreImportManager($bs_code,$mode);
		$ScoreImportManager->Import();
		break;
	case 'statuscode':
		$site = $fs->valid($_POST['site'],'num'); //1:backend, 0:website
		$mode_code = ($site)?BACKEND_IMPORT_MODE:WEBSITE_IMPORT_MODE;

		$data = array();
		$LicenseManager = new LicenseManager();

		$data['infoacer'] = _getAuth($mode_code,ScoreImportModeEnum::Infoacer_XLS);

		$output = $json = new Services_JSON();
		header('Content-Type: application/json; charset=utf-8');
		echo $json->encode($data);
		exit;
		break;
}

function _getAuth($convert_mode_code, $mode){
	global $LicenseManager;
	$_localauth = $LicenseManager->chkImportAuth($convert_mode_code,$mode);
	$arr = explode('.',$_localauth);
	if($arr[0]=='200'){
		$code = 1;
		$status = $_localauth;
	}else{
		$code = 0;
		$status = '401.'.$arr[1];
	}
	return array($code, $status);
}
?>
