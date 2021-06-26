<?PHP
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('filter','db','ejson');
$cmd = $fs->valid($_POST['cmd'],'cmd');
$site = $fs->valid($_POST['site'],'num'); //1:backend, 0:website

global $LicenseManager;

$convert_mode_code = ($site)?BACKEND_CONVERT_MODE:WEBSITE_CONVERT_MODE;

switch($cmd){
	case 'statuscode':
		$data = array();
		$LicenseManager = new LicenseManager();

		//The reason why only check CLOUD_PDF, is because CLOUD_PDF contant 
		$data['pdf'] = _getConvertAuth($convert_mode_code,ConvertModeEnum::CLOUD_PDF);
		//Does not check CLOUD_OFFICE, is because currently not support office cloud convert
		$data['doc'] = _getConvertAuth($convert_mode_code,ConvertModeEnum::ECOCAT_OFFICE);
		$data['ppt'] = _getConvertAuth($convert_mode_code,ConvertModeEnum::ECOCAT_OFFICE);
		$data['xls'] = _getConvertAuth($convert_mode_code,ConvertModeEnum::ECOCAT_OFFICE);
		$data['lbm_zip'] = _getConvertAuth($convert_mode_code,ConvertModeEnum::LBM_ZIP);
		$data['itu_zip'] = _getConvertAuth($convert_mode_code,ConvertModeEnum::ITUTOR_ZIP);
		$data['ebk'] = _getConvertAuth($convert_mode_code,ConvertModeEnum::EBK_V1);
		break;
}

$output = $json = new Services_JSON();
header('Content-Type: application/json; charset=utf-8');
echo $json->encode($data);
exit;

function _getConvertAuth($convert_mode_code, $mode){
	global $LicenseManager;
	$_localauth = $LicenseManager->chkConvertAuth($convert_mode_code,$mode);
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