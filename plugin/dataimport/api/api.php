<?PHP
/*
BSManager login to use these api.
*/
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('db','filter','ejson');
$ErrorHandler = new ErrorHandler;

$cmd = $fs->valid($_GET['cmd'],'cmd');

switch($cmd){
	case 'import':
		$bs_code = $fs->valid($_GET['bs'],'id');
		$mode = $fs->valid($_GET['m'],'num');

		//upload xls, and check type and data
		//if have errors, echo error rows.
		//if all correct, return excute code.
		$ImportManager = new ImportManager($bs_code,$mode);
		$ImportManager->Import();
		break;
	case 'statuscode':
		$site = $fs->valid($_POST['site'],'num'); //1:backend, 0:website
		$mode_code = ($site)?BACKEND_IMPORT_MODE:WEBSITE_IMPORT_MODE;

		$data = array();
		$LicenseManager = new LicenseManager();

		//The reason why only check CLOUD_PDF, is because CLOUD_PDF contant 
		$data['book'] = _getAuth($mode_code,ImportModeEnum::BOOK_ZIP);
		//Does not check CLOUD_OFFICE, is because currently not support office cloud convert
		$data['cate'] = _getAuth($mode_code,ImportModeEnum::CATE_XLS);
		$data['user'] = _getAuth($mode_code,ImportModeEnum::USER_XLS);
		$data['group'] = _getAuth($mode_code,ImportModeEnum::GROUP_XLS);
		$data['admin'] = _getAuth($mode_code,ImportModeEnum::MANAGER_XLS);

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
		if($mode===ConvertModeEnum::EBK_V1){
			$code = -1;
			$status = '406.31';
		}else{
			$code = 0;
			$status = '401.'.$arr[1];
		}
	}
	return array($code, $status);
}
?>
