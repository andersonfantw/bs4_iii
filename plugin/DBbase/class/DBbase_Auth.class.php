<?PHP
class DBbase_Auth implements iAuthInterface{
  function __construct(){
  	global $db;
  	global $bs_code;
  }

	public function validAdmin(){
		global $db;
		/* index */
		list($account,$password) = func_get_args();
    $sql =<<<SQL
select * 
from %ssystem_account 
where su_name = '%s' 
	and su_password='%s'
SQL;
		$sql = sprintf($sql,DB_PREFIX, $account,BASE64_ENCODE($password));
    $rs = $db->query_first($sql);

		if($rs){
			$su_name = $rs['su_name'];
			BookshelfManager::AdminLogin($su_name);

			//login record
			$login = new login(&$db);
			$uid = intval($rs['su_id']);
			$type = 's';
			$sessionid = session_id();
			$starttime = date("Y-m-d H:i:s");
			$login->insert($uid,$type,$sessionid,$starttime);
		}

    return $rs;
	}
	
	public function validBSManager(){
		global $db;
		/* index */
		switch(func_num_args()){
			case 1:
				list($account) = func_get_args();
		    $sql =<<<SQL
select * 
from %saccount 
where u_name = '%s'
SQL;
				$sql = sprintf($sql,DB_PREFIX, $account);
				break;
			case 2:
				list($account,$password) = func_get_args();
		    $sql =<<<SQL
select * 
from %saccount 
where u_name = '%s' 
	and u_password='%s'
SQL;
		    $sql = sprintf($sql,DB_PREFIX, $account,BASE64_ENCODE($password));
		}
    $rs = $db->query_first($sql);
		if($rs){
			$_SESSION['singlelogin'] = date("Y-m-d H:i:s");

			//set login session, cookie
			$uname = $rs['u_name'];
			$ucname = $rs['u_cname'];
			$uid = $rs['u_id'];
			BookshelfManager::BSManagerLogin($uid,$uname,$ucname);
			
			//login record
			$login = new login(&$db);
			$uid = intval($rs['u_id']);
			$type = 'a';
			$sessionid = session_id();
			$starttime = $_SESSION['singlelogin'];
			$login->insert($uid,$type,$sessionid,$starttime);
		}
    return $rs;
	}

	public function validUser(){
		global $db;
		global $bs_code;
		/* auth_check, book_auth_check */
		/* group join has a bug, need to be fixed (bu.g_id=bg.g_id) */
		$condition = '';
		if(LicenseManager::chkAuth(MEMBER_MODE,MemberModeEnum::INDIVIDUAL) && !empty($bs_code)){
			$condition = 'and bs_id='.$bs_code;
		}
		switch(func_num_args()){
			case 1:
				list($arg1) = func_get_args();
				if(common::validSessionID($arg1)){
					list($sid) = func_get_args();
					$login = new login(&$db);
					$rs = $login->getBySESSIONID($sid);
				}else{
					list($account) = func_get_args();
			  	$sql =<<<SQL
select * 
from bookshelf2_view_group_users v
where bu_name = '%s' %s
SQL;
					$sql = sprintf($sql, $account, $condition);
  				$rs = $db->query_first($sql);
				}
				break;
			case 2:
				list($account,$password) = func_get_args();
	  		$sql =<<<SQL
select * 
from bookshelf2_view_group_users v
where bu_name = '%s'
	and bu_password='%s' %s
SQL;
				$sql = sprintf($sql, $account,$password,$condition);
  				$rs = $db->query_first($sql);
				break;
			case 3:
				list($account,$password,$token) = func_get_args();

				if(!common::checkToken($token)){
				  $data['code'] = '501';
				  $data['msg'] = 'Permission denied';
				  echo $json->encode($data);exit;
				}

	  		$sql =<<<SQL
select * 
from bookshelf2_view_group_users v
where bu_name = '%s'
	and bu_password='%s'
SQL;
				$sql = sprintf($sql, $account,$password);
  				$rs = $db->query_first($sql);
				break;
		}

		if(!empty($rs)){
			$_SESSION['singlelogin'] = date("Y-m-d H:i:s");

			$bu_id = $rs['bu_id'];
			$acc = $rs['bu_name'];
			$cname = $rs['bu_cname'];
			BookshelfManager::UserLogin($bu_id,$acc,$cname);

			$buid = $rs['bu_id'];
			$bookshelf_user = new bookshelf_user(&$db);
			$bookshelf_user->setLastLogin($buid);

			$login = new login(&$db);
			$data1 = array();
			$uid = intval($buid);
			$type = 'u';
			$sessionid = session_id();
			$starttime = $_SESSION['singlelogin'];
			$login->insert($uid,$type,$sessionid,$starttime);
		}
  	return $rs;
	}

	public function validUserSingleLogin(){
		global $db;
		switch(func_num_args()){
			case 1:
				list($account) = func_get_args();
				break;
		}
		$date = $_SESSION['singlelogin'];
		$sql =<<<SQL
select last_login as singlelogin
from bookshelf2_bookshelf_users
where bu_name='%s'
	and last_login='%s';
SQL;
		$sql = sprintf($sql, $account, $date);
		$rs = $db->query_first($sql);
		return (!empty($rs));
	}
}
?>
