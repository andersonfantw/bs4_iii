<?PHP
/*****************************************************************************
convert function table:
						ecocat_doc	ecocat_pdf	cloud_doc	cloud_pdf	zip,itu		ebk
frontsite		x							x						B						v				3				2
backend			A							v						B						v				v				2

1. install zebraoffice on server
2. install plugin
3. sells modul not define.
A. enable Ecocat license
B. enable Ecocat license, or by Bookshelf. Ecocat server upgrade.
*****************************************************************************/

class ConvertManager{
	var $isBackend;
	var $bsid;
	var $uid;
	var $tmpname;
	var $filename;
	var $key;
	function __construct($isBackend, $bsid){
		$this->isBackend=$isBackend;
		$this->bsid=$bsid;
	}
	public function setUploadfile($tmpname,$filename){
		$this->tmpname = $tmpname;
		$this->filename = $filename;
	}
	public function setKey($key){
		$this->key=$key;
	}
	public function Convert($cid,$spell='',$skin='',$language_type=''){
		global $db;
		global $ee;
		global $fs;

		$ErrorHandler = new ErrorHandler;
		$LicenseManager = new LicenseManager();
		if($this->isBackend){
			$code = BACKEND_CONVERT_MODE;
		}else{
			$code = WEBSITE_CONVERT_MODE;
		}

		if(!$_FILES && (empty($this->tmpname) || empty($this->filename))){
			$ee->ERROR('400.32');
		}
		if($_FILES){
			$this->tmpname = $_FILES['uploadedFile']['tmp_name'];
			$this->filename = $_FILES['uploadedFile']['name'];
		}

		$account = new account(&$db);
		$data = $account->getAccountByBSID($this->bsid);
		$uid = $data['u_id'];

		$path_parts = common::path_info($this->filename);
		$subname = strtolower($path_parts['extension']);

		//check filename
    $filename = $fs->valid($this->filename,'name');

		switch($subname){
			case 'doc':
			case 'docx':
			case 'ppt':
			case 'pptx':
			case 'xls':
			case 'xlsx':
			case 'pdf':
				if($subname=='pdf'){
					$ConvertModeEnum = ConvertModeEnum::ECOCAT_PDF;
					$ConvertModeEnum1 = ConvertModeEnum::CLOUD_PDF;
				}else{
					$ConvertModeEnum = ConvertModeEnum::ECOCAT_OFFICE;
					$ConvertModeEnum1 = ConvertModeEnum::CLOUD_OFFICE;
				}
				//check ecocat license
				//$_authcode = $LicenseManager->chkConvertAuth($code,ConvertModeEnum::ECOCAT_OFFICE);
				$_authcode = $LicenseManager->chkConvertAuth($code,$ConvertModeEnum);
				$arr = explode('.',$_authcode);
				if($arr[0]=='200'){
					$tmp_path = sys_get_temp_dir().'/'.$this->filename;
			 		$EcocatConnector = new EcocatConnector($this->bsid);
			 		$result = $EcocatConnector->Convert($this->tmpname,$this->filename,$spell,$skin,$language_type);
				  if(isset($result['message'])){
						$ee->add('msg',$result['message']);
						$ee->add('debug',$result['debug']);
						return $ee->ERROR('500.28');
				  }elseif(isset($result['process_id'])){
					 		$uploadfile = sys_get_temp_dir().'/'.$result['process_id'];
					 		//queue: /var/www/html/bs3/work/(timestamp), /tmp/(ecocatid)
					 		//upload: /tmp/(rand_str), /tmp/(ecocatid)
					 		rename($this->tmpname,$uploadfile);
				  }
			 		return $result;
				}else{
					$_authcode = $LicenseManager->chkConvertAuth($code,$ConvertModeEnum1);
					$arr = explode('.',$_authcode);
					if($arr[0]=='200'){
			  		$CloudConnector = new CloudConnector;
			  		$CloudConnector->Send();
			  		return $ee->Message('200');
					}else{
						//$ErrorHandler->ERROR('401.24','http://cloudbook.cyberhood.net/cloudbook/licensebuy_01.php?service_id='.wonderbox_id);
						$ee->add('link','http://www.ttii.com.tw/pg148.html');
						return $ee->ERROR('401.24');
					}
				}
				break;
			case 'itu':
			case 'zip':
				$tmp_path = sys_get_temp_dir().'/'.time().'.zip';//$this->filename;
				if(strpos($this->tmpname,sys_get_temp_dir())===false){
					rename($this->tmpname,$tmp_path);
				}else{
					move_uploaded_file($this->tmpname,$tmp_path);
				}

				//check zip format
				$userpath = HostManager::getBookshelfBase(false,true,$uid,$this->bsid);
				$BSFile_CODEC = new BSFile_CODEC;
				$format = $BSFile_CODEC->CheckFormat($tmp_path);
				@unlink($tmp_path);
				switch($format){
					case ConvertModeEnum::ECOCAT_ZIP:
					case ConvertModeEnum::ECOCAT211_ZIP:
					case ConvertModeEnum::ECOCAT304_ZIP:
					case ConvertModeEnum::Flipbuilder100_ZIP:
					case ConvertModeEnum::MCG_ZIP:
						$_authcode = $LicenseManager->chkConvertAuth($code,$format);
						$arr = explode('.',$_authcode);
						if($arr[0]=='406'){
							return $ee->Error($_authcode);
						}
						break;
					case ConvertModeEnum::ITUTOR_ZIP:
					case ConvertModeEnum::ITUTOR500_ZIP:
					case ConvertModeEnum::ITUTOR600_ZIP:
						$_authcode = $LicenseManager->chkConvertAuth($code,$format);
						$arr = explode('.',$_authcode);
						if($arr[0]=='406'){
							return $ee->Error($_authcode);
						}
						break;
					case ConvertModeEnum::UnknowZIP:
						return $ee->Error('406.30',false);
						break;
					default:
						return $ee->Error('406.31',false);
						break;
				}
				$result = $BSFile_CODEC->Decode($tmp_path,$userpath);
				if($result){
					$bid = $BSFile_CODEC->InsertDB($this->bsid, $uid, $cid, $path_parts['filename']);
					$ee->add('bid',$bid);
					return $ee->Message($_authcode,false);
				}else{
					return $ee->Error('500.43',false);
				}
				break;
			case 'ebk':
				$_authcode = $LicenseManager->chkConvertAuth($code,ConvertModeEnum::EBK_V1);
				$arr = explode('.',$_authcode);
				if($arr[0]=='406'){
					return $ee->Error('406.31',false);
				}
				//call ebk codec
				break;
			default:
				//Otherwise format
				return $ee->ERROR('406.30');
				break;
		}
	}
	public function ConvertProgress($cid,$process_id,$timestamp,$filename){
		global $db;

		//especially for ecocat
		$EcocatConnector = new EcocatConnector($this->bsid);
		$result = $EcocatConnector->Process($process_id,$timestamp);
		if($EcocatConnector->rate=='100'){
      $account = new account(&$db);
      $data = $account->getAccountByBSID($this->bsid);
			if(CONNECT_ECOCAT_IMPORT){
		  	$fromDir = ECOCAT_ROOT.'/lib/ecolab/export/'.$process_id;
		  	$BSFile_CODEC = new BSFile_CODEC($timestamp);
		  	$format = $BSFile_CODEC->CheckFormat($fromDir);
		  	$userpath = HostManager::getBookshelfBase(false,true,$data['u_id'],$this->bsid);
				$val = $BSFile_CODEC->Decode($fromDir,$userpath);
				if($val){
					$tmp_file = sys_get_temp_dir().'/'.$process_id;
					$path_parts=common::path_info($filename);
					$target_filename = $BSFile_CODEC->getUniquekey().'.'.$path_parts['extension'];

					// $tmp_file =  /tmp/(ecocatid)
					if(!empty($this->key)){
						$BSFile_CODEC->setUniquekey($this->key);
					}
					$BSFile_CODEC->setSource($tmp_file,$path_parts['extension']);
					$bid = $BSFile_CODEC->InsertDB($this->bsid, $data['u_id'], $cid, $path_parts['filename']);
					$result['detail']['bid'] = $bid;
				}
			}else{
				BookshelfManager::doEcocatUpdate('ecocat',$this->bsid,$data['u_id'],$cid,$process_id);
			}
		}
		return $result;
	}
}
?>
