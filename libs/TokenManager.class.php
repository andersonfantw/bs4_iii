<?PHP
class TokenManager{
	public function Login($token){
		global $db;

		$rs = TokenManager::isValid($token);
		if($rs){
			$_buid = intval($rs['bu_id']);
			$_acc = $rs['bu_name'];
			$_cname = $rs['bu_cname'];
			BookshelfManager::UserLogin($_buid,$_acc,$_cname);

			$_buid = intval($rs['bu_id']);

			$starttime = date("Y-m-d H:i:s");
			$_SESSION['singlelogin'] = $starttime;

			$bookshelf_user = new bookshelf_user($db);
			$bookshelf_user->setLastLogin($_buid);

			$login = new login($db);
			$login->insert($_buid,'u',$token,$starttime);
			return $rs;
		}
		return false;
	}

	public function isValid($token){
		global $db;
		
		$login = new login($db);
		$rs = $login->getBySESSIONID($token);
		if(empty($rs)){
			BookshelfManager::UserLogout();
			return false;
		}else{
			return $rs;
		}
	}
}
?>