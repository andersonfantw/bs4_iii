<?PHP
require_once dirname(__FILE__).'/../libs/nas.class.php';

class LDAP_Auth extends DB_Auth implements iAuthInterface{

	var $LDAPAgent;

  function __construct(){
  	global $db;
  	global $bs_code;
  	$this->LDAPAgent = new Nas();
  }

	public function isManagerLogin(){
		$_ecocatCMSnasAdminAuthId = common::getcookie('ecocatCMSnasAdminAuthId');
		return $this->LDAPAgent->isValid($_ecocatCMSnasAdminAuthId);
	}
	
	public function isBSManagerLogin(){
		$_ecocatCMSnasAuthId = common::getcookie('ecocatCMSnasAuthId');
		return $this->LDAPAgent->isValid($_ecocatCMSnasAuthId);
	}

	public function validAdmin(){
		global $db;
		/* index */
		//�t�κ޲z��admin, webadmin�n�J
		list($acc,$pwd) = func_get_args();
		$db_process = new db_process($db,'system_account','su_');

    $nasAuthId = $this->LDAPAgent->login($acc, $pwd);

    if($nasAuthId != ''){
    	$rs = $db_process->getByName($acc);	
    }
    return $rs;
	}

	public function validBSManager(){
		global $db;
		/* index */
		//�d�߬O�_�Q�]�w�����d�޲z���A���]�w�̴N�i�H�n�J
		list($acc,$pwd) = func_get_args();
		
		$account = new account($db);
/*
		if(!empty($account) && strpos(LDAP_EXCLUDE_ACCOUNT,$account)===false){
			$account = LDAP_DOMAIN_PREFIX.$account;
		}
*/
    $nasAuthId = $this->LDAPAgent->login($acc, $pwd);

    if($nasAuthId != ''){
    	$rs = $account->getByName($acc);
    }
    return $rs;
	}

	public function validUser(){
		global $db;
		global $bs_code;
		/* auth_check, book_auth_check */
		//login nas
		list($acc,$pwd) = func_get_args();
		//$account = LDAP_DOMAIN_PREFIX.$account;
		
		$group = new group($db);
		$booshelf_user = new bookshelf_user($db);
		$nasAuthId = $this->LDAPAgent->login($acc, $pwd);
		if(LicenseManager::chkAuth(MEMBER_MODE,MemberModeEnum::USER_LOGIN_VERIFY_LOOSE_MODE)){
			//loose mode
			if(isset($nasAuthId)){
				$data = $booshelf_user->getByName($acc);
				if(!$data){
					$userProfile = $this->LDAPAgent->getProfile();
					$data = array();
					$data['bu_name'] = $userProfile['account'];
					$data['bu_cname'] = $userProfile['username'];
					$data['bu_password'] = uniqid();
					//$data['g_id'] = $nasGroupGid;
					$data['last_login'] = 'now()';
					$bu_id = $booshelf_user->insert($data,true);
				}

				parent::validUser($acc);
			}
		}else{
			//strict mode
			//�ϥΪ̦b��Ʈw���A�N��s�ϥΪ̪��s�աA�ìd�߬O�_���v��
			//�ϥΪ̤��b��Ʈw���A�N�s�W�ϥΪ̡A�Ω��ݸs�աA�ìd�߬O�_���v��
			//�s�դ��@�w(�q�`���|)�Ѥj��x���w�����d��x
		  //update user's group
		  if($nasAuthId != ''){
		    $nasGroups = $this->LDAPAgent->listUserGroups('', $acc);
		    $nasGroupGid = Array();
		    //get group list
		    foreach ($nasGroups[0] as $group_unit) {
		    	$data = $group->getByName($group_unit);
					if($data){
						$nasGroupGid[] = $data['g_id'];
					}else{
						//insert the group which is not in db yet.
						$data = array();
						$data['g_name'] = $group_unit;
						$data['g_key'] = base64_encode($group_unit);
						$data['bs_id'] = $bs_code;
						$g_id = $group->insert($data,true);
						$nasGroupGid[] = $g_id;
					}
		    }
		    //create user if need(also update group info)
		    $data = $booshelf_user->getByName($acc);


		    if($data){
		    	$bu_id = $data['bu_id'];
		    	$group->update_group_user($nasGroupGid,$bu_id);
		    }else{
		    	$userProfile = $this->LDAPAgent->getProfile();
		    	$data = array();
		    	$data['bu_name'] = $userProfile['account'];
		    	$data['bu_cname'] = $userProfile['username'];
		    	$data['bu_password'] = uniqid();
		    	$data['g_id'] = $nasGroupGid;
		    	$data['last_login'] = 'now()';
		      $bu_id = $booshelf_user->insert($data,true);
		    }
		
		    //back to normal login path
		    $rs = parent::validUser($acc);
		  }
		}
	  return $rs;
	}

	public function validUserSingleLogin(){
		list($account) = func_get_args();
		return parent::validUserSingleLogin($account);
	}
}
?>
