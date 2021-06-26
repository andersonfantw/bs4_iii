<?PHP
# dependence: common.class
class HostManager{
	function __constructer(){
	}
	public function getUserBase($isbackend=false,$syspath=false,$uid=0){
		global $bs_code;

		if(empty($uid)) $uid = bssystem::getUID($isbackend);

		$base = ($syspath)?HOST_PATH:'/hosts';
		$path = sprintf('%s/%u',$base,$uid);
		$check_base = HOST_PATH.sprintf('/%u/',$uid);
		
		if(!is_dir($check_base)){
			mkdir($check_base,0777);
		}
		return $path;
	}
	public function getBookshelfBase($isbackend=false,$syspath=false,$uid=0,$bsid=0){
		global $bs_code;

		if(empty($uid)) $uid = bssystem::getUID($isbackend);
		if(empty($bsid)) $bsid = bssystem::getBSID($isbackend);

		//make sure base path is exist.
		self::getUserBase($isbackend,$syspath,$uid);

		$base = ($syspath)?HOST_PATH:'/hosts';
		$path = sprintf('%s/%u/%u',$base,$uid,$bsid);
		$check_base = HOST_PATH.sprintf('/%u/%u/',$uid,$bsid);

		if(!is_dir($check_base)){
			mkdir($check_base,0777);
		}
		return $path;
	}

  public function lnetid($id,$bsid){
    if($id===0) return false;
    if($id>2176782335) return -1;
    if($bsid===0) return false;
    if($bsid>46655) return -1;
    $_id = base_convert($id,10,36);
    $_bsid = base_convert($bsid,10,36);
    $ret = common::get_wonderbox_id();
    if($ret['rc']===0){
            return sprintf('%s%06s%03s',$ret['wbox_id'],$_id,$_bsid);
    }
    return false;
  }

  public function decodeLNetID($lnetid){
  	$arr = unpack('a6wonderboxid/a6uid/a3bsid',$lnetid);
  	$arr['uid'] = base_convert($arr['uid'],36,10);
  	$arr['bsid'] = base_convert($arr['bsid'],36,10);
  	return $arr;
  }

	public function getBSCODE($id,$bsid){
		$err = '';
		if($id>2176782335) $err='uid reach max!';
		if($bsid>46655){
			if(!empty($err)) $err.=',';
			$err.='bsid reach max!';
		}
		if(!empty($err)) $err='('.$err.')';

		$wonderboxid = common::get_wonderbox_id();
		$acc_code = sprintf('%06s',base_convert($id,10,36));
		$bs_code = sprintf('%03s',base_convert($bsid,10,36));
		return $wonderboxid.$acc_code.$bs_code.$err;
	}

	function RemoveUserBase($uid,$pass){
		if($pass!='iamthepassword') return false;
		if($uid<=0) return false;
		if($bsid<=0) return false;
		$path = sprintf('%s/%u',HOST_PATH,$uid);
		return common::remove_directory($path);
	}

	function RemoveBookshelfBase($uid,$bsid,$pass){
		if($pass!='iamthepassword') return false;
		if($uid<=0) return false;
		if($bsid<=0) return false;
		$path = sprintf('%s/%u/%u',HOST_PATH,$uid,$bsid);
		return common::remove_directory($path);
	}

	public function getUserStorageByAccount($uid){
		$path = sprintf(sprintf('%s/%s/'),HOST_PATH,$uid);
		if(!is_dir($path)) return -1;
		exec(sprintf('du -s %s',$path),$size, $retCode);
		//example: 1627928 hosts/
		return intval($size);
	}
	public function getUserStorageByBookshelf($uid,$bsid){
		$path = sprintf(sprintf('%s/%s/%s/'),HOST_PATH,$uid,$bsid);
		if(!is_dir($path)) return -1;
		exec(sprintf('du -s %s',$path),$size, $retCode);
		return intval($size);
	}
}
?>
