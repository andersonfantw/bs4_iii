<?PHP
class bssystem{
	public function isSysBackend(){
		return (strpos($_SERVER['PHP_SELF'],'/backend/sys_')===0);
	}
	public function isBackend(){
		if(bssystem::isSysBackend()) return false;
		return (strpos($_SERVER['PHP_SELF'],'/backend/')===0);
	}
	public function isAdminLogin(){
		$_sysuser = common::getcookie('sysuser');
		return ($_sysuser=='admin');
	}
	public function isWebadminLogin(){
		$_sysuser = common::getcookie('sysuser');
		return ($_sysuser=='webadmin');
	}
	public function getSysLoginName(){
		return common::getcookie('sysuser');
	}

	public function isManagerLogin(){
		return isset($_SESSION['adminid']);
	}
	
	public function getBSID($site=0){
		//api cannot identify backend by isBackend()
		$bsid = $_SESSION[SITE_PREFIX.'bsid'];
		if(!isset($bsid)){
			$bsid = common::getcookie(SITE_PREFIX.'bsid');
		}
/*
		if(bssystem::isBackend() || $site==1){
			$bsid=$_COOKIE['bs'];
		}else{
		  $bsid = $_SESSION[SITE_PREFIX.'bsid'];
		}
*/
		return intval($bsid);
	}
	public function getUID($site=0){
		$uid = $_SESSION[SITE_PREFIX.'uid'];
		if(!isset($uid) || $uid!=$_SESSION['adminid']){
			global $db;
			$account = new account($db);
			$bsid = bssystem::getBSID();
			$uid = $account->getUIDByBSID($bsid);
		}
/*
		if(bssystem::isBackend() || $site==1){
			$uid = $_SESSION['adminid'];
		}else{
		  $uid = $_SESSION[SITE_PREFIX.'uid'];
		}
*/
		return intval($uid);
	}
	public function getLoginUID(){
		$uid = $_SESSION['adminid'];
		return intval($uid);
	}
	public function getLoginUName(){
		return $_SESSION['adminname'];
	}
	public function getLoginUAcc(){
		return $_SESSION['adminacc'];
	}
	public function getLoginBUID(){
		return intval($_SESSION['buid']);
	}
	public function getLoginBUName(){
		return $_SESSION['name'];
	}
	public function getLoginBUAcc(){
		return $_SESSION['acc'];
	}	
}
?>
