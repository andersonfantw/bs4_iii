<?PHP
/************************************************************
ec_enddate的時間內，所有功能全部開放。
ec_enddate都會有日期，如果試用期之內買了書櫃，則ec_enddate會放15日試用的到期日。
當ec_enddate到期，但是bs_num有值(有購買過書櫃)時，可使用ecocat外的所有功能。
如果買了Ecocat的授權，ec_enddate的日期會設定到期日。
************************************************************/
class LicenseManager{
	var $code='';
	function __construct(){
		$this->code = $this->_getLicenseFromServer();
	}

/************************************************************
回傳值:
第一行 return code : 0 成功  >0 失敗
第二行 錯誤訊息或是序號解碼之後的訊息
第三行 序號到期時間 (如果是有效期的序號回傳到期時間, 如果是永久序號, 第三行空白)


範例1: (有效期的序號)
0   -> 成功
a-c-0000-0000-0000-0000   -> 保留日後如果要包一些人數及功能, 再定義回傳的資料
2015-07-01 23:59:59  --> 序號到期時間

範例2: (有效期的序號已到期)
7   -> 失敗
Error: beta version time expired(7)!   -> 錯誤訊息
2015-05-31 23:59:59  --> 序號到期時間

範例3: (序號解碼錯誤, 例如網卡Mac換了)
2   -> 失敗
Error: decode fail(13)!   -> 錯誤訊息

範例4: 永久正式序號
0   -> 成功
a- -0000-0000-0000-0000   -> 保留日後如果要包一些人數及功能, 再定義回傳資料
************************************************************/
	public function GetLicenseFromCyberhood(){
		exec('/usr/local/koala/cloudbook/lnet_sncheck', $output, $return_var);
		//$output=array(7,'Error: beta version time expired(7)!','2015-05-31 23:59:59');
		//$output=array(2,'Error: decode fail(13)!');
		switch($output[0]){
			case 0:
				if(!isset($output[2])){
					$output[2]='3000-1-1';
				}
				return array(
					'code'=>'200',
					'msg'=>$output[2]
				);
				break;
			case 2:
				return array(
					'code'=>'500.43',
					'msg'=>$output[1]
				);
				break;
			case 7:
				return array(
					'code'=>'401.26',
					'msg'=>$output[1]
				);
				break;
			default:
				return array(
					'code'=>'500.43',
					'msg'=>''
				);
				break;
		}
	}

/******************************************
activemode:
-1: disable
0: none active/expired
1: trial
2: rent
3: active
******************************************/
	public function getSystemActiveInfo(){
		global $db;
		$val = LicenseManager::GetLicenseFromCyberhood();
		if($val['code']=='500.43'){
			$data['active']=false;
			$data['mode']=-1;
			return $data;
		}
		$ini = new ini(&$db);
		$rs = $ini->getByGroup('startup');
		$_ini = common::rs2ini($rs['result']);
		$data = array();
		if($rs['result']){
			switch($_ini['startup']['activemode']){
				case -1:
				case 0:		
					$data['active'] = false;
					$data['mode'] = $_ini['startup']['activemode'];
					break;
				case 1:
				case 2:
				case 3:
					if($_ini['startup']['activemode']==1){
						$_d1 = strtotime($_ini['startup']['activedate']);
						$d1 = strtotime('+30 day',$_d1);
					}else{
						$_d1 = $_ini['startup']['expiredate'];
						$d1 = strtotime($_d1);
					}
					$d2 = time();
					if($d1>$d2){
						$data['active'] = true;
						$data['mode'] = $_ini['startup']['activemode'];
					}else{
						$data['active'] = false;
						$_ini['startup']['activemode']=-1;
						$data['mode'] = $_ini['startup']['activemode'];
						$d = common::ini2rs($_ini);
						foreach($d as $r){
							$ini->update($r['group'],$r['key'],$r['val']);
						}
					}
					$data['date'] = $_d1;
					break;
			}
		}else{
			$data['active'] = false;
			$data['mode'] = 0;
			$data['date'] = '1900-1-1';
		}
		return $data;
	}
	public function registSystemActive($mode){
		global $db;
		global $fs;
		$ini = new ini(&$db);
		$rs = $ini->getByGroup('startup');
		$_ini = common::rs2ini($rs['result']);
		$_data = array('startup'=>array());
		$val = LicenseManager::GetLicenseFromCyberhood();

		switch($mode){
			case -1: //disable
				$_data['startup']['activemode'] = -1;
				break;
			case 0: //clear
				$_data['startup']['activemode'] = 0;
				$_data['startup']['activedate'] = date('Y-m-d H:i:s');
				break;
			case 1: //trial
				$_data['startup']['activemode'] = 1;
				$_data['startup']['activedate'] = date('Y-m-d H:i:s');

				break;
			case 2:
			case 3: //enable
				if($val['code']=='200'){
					$_data['startup']['activemode'] = 3;
					$_data['startup']['expiredate'] = '3000-1-1';
				}else{
					$_data['startup']['activemode'] = -1;
					$_data['startup']['expiredate'] = '3000-1-1';
				}
				break;
		}
		$d = common::ini2rs($_data);
		foreach($d as $data){
      $val = $ini->getByKey($data['group'],$data['key']);
      if($val){
				$ini->update($data['group'],$data['key'],$data['val']);
      }else{
				$ini->insert($data);
      }
		}
	}

	private function _getLicenseFromServer(){
		return '200.25';
		$ret = common::get_wonderbox_id();
    if($ret['rc']){
    	return false;
    }
		$json = HttpClient::quickPost(
			'http://cloudbook.cyberhood.net/api/get_user_license.php',
			array('service_id'=>$ret['wbox_id']));
		if(!$json){
			return false;
		}
		$array = json_decode($json,TRUE);
		switch($array['user_type']){
			case '0':
				if(empty($array['try_enddate'])){
					return '406.62';
				}
        if(strtotime($array['try_enddate'])-time()>0){
        	return '200.24';
        }else{
        	return '401.24';
        }
				break;
			case '1':
				if(empty($array['ec_enddate'])){
					return '406.62';
				}
				if(empty($array['bs_num'])){
					return '406.62';
				}
				if(strtotime($array['ec_enddate'])-time()>0){
		      return '200.25';
		    }else{
					if(intval($array['bs_num'])>0){
						return '200.26';
					}elseif(intval($array['bs_num'])==0){
						return '401.25';
					}
		    }
				return '406.62';
				break;
		}
	}
	
	function getLicenseFromServer(){
		return $this->code;
	}

	//return value
	//{"status":"0","errmsg":"","ec_enddate":"yyyy-mm-dd"}
	function IsEcocatLicenseValid(){
		$ret = common::get_wonderbox_id();
    if($ret['rc']){
    	return false;
    }
		$json = HttpClient::quickPost(
			'http://cloudbook.cyberhood.net/api/get_user_license.php',
			array('service_id'=>$ret['wbox_id']));
		if(!$json){
			return false;
		}
		$array = json_decode($json,TRUE);
		/*if(empty($array['user_type'])){
			return false; //system param err
		}*/
		switch($array['user_type']){
			case '0':
		    if(!empty($array['try_enddate'])){
		            if(strtotime($array['try_enddate'])-time()>0){
		                    return true;
		            }
		    }
				break;
			case '1':
				if(empty($array['ec_enddate'])){
					return false;
				}
		    if(strtotime($array['ec_enddate'])-time()>0)
		    {
		            return true;
		    }
				break;
		}
		return false;
	}

	function IsBookshelfLicenseValid(){
		return true;
		$ret = common::get_wonderbox_id();
    if($ret['rc']){
    	return false;
    }
		$json = HttpClient::quickPost(
			'http://cloudbook.cyberhood.net/api/get_user_license.php',
			array('service_id'=>$ret['wbox_id']));
		if(!$json){
			return false;
		}
		$array = json_decode($json,TRUE);
		/*if(empty($array['user_type'])){
			return false; //system param err
		}*/
		switch($array['user_type']){
			case '0':
		    if(!empty($array['try_enddate'])){
		            if(strtotime($array['try_enddate'])-time()>0){
		                    return true;
		            }
		    }
				break;
			case '1':
				if(empty($array['ec_enddate'])){
					return false;
				}
		    if(strtotime($array['ec_enddate'])-time()>0)
		    {
		            return true;
		    }
				if(intval($array['bs_num'])>0)
				{
					return true;
				}
				break;
		}
		return false;
	}

	//return: bool
	public function chkAuth($code,$mod){
		return (($code&$mod)>0);
	}
	
	public function chkConvertAuth($code,$mod){
		$_code = $code & $mod;
		if($this->chkAuth($_code,ConvertModeEnum::ECOCAT_PDF)
		|| $this->chkAuth($_code,ConvertModeEnum::ECOCAT_OFFICE)
		|| $this->chkAuth($_code,ConvertModeEnum::ECOCAT_OFFICE)
		|| $this->chkAuth($_code,ConvertModeEnum::ECOCATCMS211)
		|| $this->chkAuth($_code,ConvertModeEnum::ECOCATCMS304)){
			if(CONNECT_ECOCAT){
				return '200.20';
			}else{
				return '406.30';
			}
		}elseif($this->chkAuth($_code,ConvertModeEnum::ECOCAT_ZIP)
		||	$this->chkAuth($_code,ConvertModeEnum::ECOCAT211_ZIP)
		||	$this->chkAuth($_code,ConvertModeEnum::ECOCAT304_ZIP)
		||	$this->chkAuth($_code,ConvertModeEnum::LBM_ZIP)){
			return '200.20';
		}elseif($this->chkAuth($_code,ConvertModeEnum::ITUTOR_ZIP)
		||	$this->chkAuth($_code,ConvertModeEnum::ITUTOR500_ZIP)){
			return '200.22';
		}elseif($this->chkAuth($_code,ConvertModeEnum::Flipbuilder100_ZIP)){
			return '200.20';
		}elseif($this->chkAuth($_code,ConvertModeEnum::MCG_ZIP)){
			return '200.20';
		}elseif($this->chkAuth($_code,ConvertModeEnum::CLOUD_PDF)
		||	$this->chkAuth($_code,ConvertModeEnum::CLOUD_OFFICE)){
			return '401.23';
		}elseif($this->chkAuth($_code,ConvertModeEnum::EBK_V1)){
			return '406.30';
		}else{
			return '406.30';
		}
	}

	public function chkImportAuth($code,$mod){
		switch($code & $mod){
			case ImportManagerModeEnum::BOOK:
				return '200.204';
				break;
			case ImportManagerModeEnum::CATEGORY:
				return '200.203';
				break;
			case ImportManagerModeEnum::USER:
				return '200.201';
				break;
			case ImportManagerModeEnum::GROUP:
				return '200.202';
				break;
			case ImportManagerModeEnum::MANAGER:
				return '200.205';
				break;
			default:
				return '406.30';
				break;
		}
	}	
}
?>
