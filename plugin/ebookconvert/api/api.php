<?PHP
ini_set('memory_limit','5000M');
set_time_limit(1800);
require_once dirname(__FILE__).'/../../../init/config.php';
$init = new init('filter','db','ejson');

$cmd = $fs->valid($_GET['cmd'],'cmd');
$bsid = $fs->valid($_POST['bs'],'id');
$site = $fs->valid($_REQUEST['site'],'bool');
if(empty($cmd)) $cmd='format';

$output = $json = new Services_JSON();
switch($cmd){
	case 'GetSkinList':
		//param: bs
		$account = new account($db);
		$EcocatConnector = new EcocatConnector($bsid);
		$arr = $EcocatConnector->GetSkinList();
		if(isset($arr['detail']["message"])){
		        echo $json->encode($arr);exit;
		}
		//only one skin
		if(isset($arr['detail']['skin'])){
			$_arr = array_merge($arr['detail']);
			unset($arr['detail']['skin']);
			unset($arr['detail']['skin_image_url']);
			$arr['detail'] = array($_arr);
		}
		$row = $account->getAccountByBSID($bsid);
		$default = $row['u_ecocat_skin'];
		if(empty($row['u_ecocat_skin'])){
			$default=$arr['detail'][0]['skin'];	
		}
		for($i=0;$i<count($arr['detail']);$i++){
			$arr['detail'][$i]['skin_image_url'] = preg_replace("/http:\/\/127.0.0.1(:\d+){0,1}\//",'/',$arr['detail'][$i]['skin_image_url']);
		}
		$data = array('default'=>$default,'data'=>$arr);
		echo $json->encode($data);exit;
		break;
	case 'GetSpellList':
		//param: bs
		$account = new account($db);
		$EcocatConnector = new EcocatConnector($bsid);
		$array = $EcocatConnector->GetSpellList();
		$row = $account->getAccountByBSID($bsid);
		$default = $row['u_ecocat_spell'];
		if(empty($row['u_ecocat_skin'])){
			$default=2;
		}
		//map digit to word
		$array = array(1=>'right',2=>'left');
		$data = array('default'=>$array[$default],'data'=>array_keys($array));
		echo $json->encode($data);exit;
		break;
	case 'SetSkinSettings':
		$account = new account($db);
		$array=array('left'=>2,'right'=>1);
		$data = array();
		$data['u_ecocat_skin'] = $fs->valid($_POST['skin'],'filename');
		$spell = $fs->valid($_POST['spell'],'key');
		$data['u_ecocat_spell'] = $array[$spell];

		$uid = bssystem::getLoginUID();
		$rs=$account->update($uid,$data);
		if($rs){
			$ee->Message('200.43');
		}else{
			$ee->Error('500.41');
		}
		exit;
		break;
	case 'format':
		$bsid = $fs->valid($_GET['bs'],'id');
		$cate2 = $fs->valid($_GET['c'],'id');
		$spell = $fs->valid($_GET['spell'],'key');
		$skin = $fs->valid($_GET['skin'],'filename');
		$spell_mapping = array('right'=>1,'left'=>2);
		$language_type = $fs->valid($_GET['language_type'],'key');
		//param: bs, site, cate2
		$ConvertManager = new ConvertManager($site,$bsid);
		$result = $ConvertManager->Convert($cate2,$spell_mapping[$spell],$skin,$language_type);

		if(isset($result['process_id'])){
			$ee->add('detail',array('process_id'=>$result['process_id']));
			$ee->Message('200');
		}elseif(isset($result['bid'])){
			$ee->add('bid',$result['bid']);
			$ee->Message($result['code']);
 		}elseif(isset($result['message'])){
 			$ee->add('msg',$result['message']);
 			$ee->add('debug',$result['debug']);
 			$ee->Error('500');
		}
		break;
	case 'ConvertProcess':
		$cate2 = $fs->valid($_POST['c'],'id');
		$process_id = $fs->valid($_POST['pid'],'filename');
		$timestamp = $fs->valid($_POST['t'],'jstimestamp');
		$filename = $fs->valid($_POST['filename'],'filename');
		//param: bs, site, cate2
		$ConvertManager = new ConvertManager($site,$bsid);
		$result = $ConvertManager->ConvertProgress($cate2,$process_id,$timestamp,$filename);
		echo json_encode($result);
		break;
	case 'statuscode':
		//param: site 
		$convert_mode_code = ($site)?BACKEND_CONVERT_MODE:WEBSITE_CONVERT_MODE;
		$data = array();

		//The reason why only check CLOUD_PDF, is because CLOUD_PDF contant 
		$data['pdf'] = _getAuth($convert_mode_code,ConvertModeEnum::ECOCAT_PDF);
		//Does not check CLOUD_OFFICE, is because currently not support office cloud convert
		$data['doc'] = _getAuth($convert_mode_code,ConvertModeEnum::ECOCAT_OFFICE);
		$data['ppt'] = _getAuth($convert_mode_code,ConvertModeEnum::ECOCAT_OFFICE);
		$data['xls'] = _getAuth($convert_mode_code,ConvertModeEnum::ECOCAT_OFFICE);
		$data['lbm_zip'] = _getAuth($convert_mode_code,ConvertModeEnum::LBM_ZIP);
		$data['itu_zip'] = _getAuth($convert_mode_code,ConvertModeEnum::ITUTOR_ZIP);
		$data['ebk'] = _getAuth($convert_mode_code,ConvertModeEnum::EBK_V1);

		header('Content-Type: application/json; charset=utf-8');
		echo $json->encode($data);exit;
		break;
	default:
		break;
}

function _getAuth($convert_mode_code, $mode){
	$LicenseManager = new LicenseManager();
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
