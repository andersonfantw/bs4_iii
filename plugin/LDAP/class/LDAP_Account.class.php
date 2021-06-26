<?PHP
/*******************************************************************************************
1. Can't create same user name in nas.
2. Can't create same group name in nas / group name is unique.
3. 
*******************************************************************************************/
require_once dirname(__FILE__).'/../libs/nas.class.php';

class LDAP_Account extends DB_Account implements iAccountInterface{

	var $LDAPAgent;

  function __construct(){
  	global $db;
  	global $bs_code;
  	$this->LDAPAgent = new Nas();
  }

	public function getBSManagerList(){
		switch(func_num_args()){
			case 0:
				/* sys_bookshelf.add */
		    $userList = $this->LDAPAgent->listUsers();
		    
		    $account_data = Array(
		      'result' => Array(),
		      'total' => $userList[1] - 2 //Because we will remove admin and webadmin from user list
		    );
		
		    foreach($userList[0] as $nasUser){
		      if($nasUser['username'] == 'admin' || $nasUser['username'] == 'webadmin')
		        continue;
		
		      $data['result'][] = Array(
		        'u_id' => $nasUser['username'].'|'.$nasUser['fullname'],
		        'u_cname' => $nasUser['username'].'('.$nasUser['fullname'].')',
		        'u_name' => $nasUser['username'],
		        'u_password' => ''
		      );
		    }
				break;
			default:
				/* Shouldn't enable sys_account*/
				list($orderby,$limit_from,$offset,$where) = func_get_args();
				$data = parent::getBSManagerList($orderby,$limit_from,$offset,$where);
				break;
		}    
    return $data;
	}

	/* sys_bookshelf */
	public function SearchManagerAccount(){
		list($q_str) = func_get_args();
    $userList = $this->LDAPAgent->listUsers($authSid="", $lower="0", $upper="all", $filter=$q_str);

    foreach($userList[0] as $nasUser){
      if($nasUser['username'] == 'admin' || $nasUser['username'] == 'webadmin')
        continue;

      $data['result'][] = Array(
        'u_id' => $nasUser['username'].'|'.$nasUser['fullname'],
        'u_cname' => $nasUser['username'].'('.$nasUser['fullname'].')',
        'u_name' => $nasUser['username'],
        'u_password' => ''
      );
    }
		return $data;
	}

	public function getBSManagerUID(){
		global $db;
		switch(func_num_args()){
			case 1:
				/* sys_bookshelf */
				list($nasUserInfo) = func_get_args();
				list($name,$cname) = explode('|', $nasUserInfo);
				$account = new account(&$db);
				$row = $account->getByName($name);
				$u_id = $row['u_id'];

			  if(!$u_id){
			  	$data = array();
			  	$data['u_name'] = $name;
			  	$data['u_cname'] = $cname;
			  	$data['u_password'] = uniqid();
			  	$u_id = $account->insert($data,true);
			  }
			  break;
		}
	  return $u_id;
	}

	public function getGroupList(){
		switch(func_num_args()){
			case 1:
				/* sys_bookshelf */
				list($bsid) = func_get_args();
				$group_data = parent::getGroupList($bsid);
	
		    $Groups = $this->LDAPAgent->listGroups();
				break;
			default:
				/* group */
				list($orderby,$limit_from,$offset,$where) = func_get_args();
				$group_data = parent::getGroupList($orderby,$limit_from,$offset,$where);

				$Groups = $this->LDAPAgent->listGroups("",$limit_from,$limit_from+$offset);
				break;
		}
    //Prepare a group map for searching
    $group_indexing = Array();
    foreach ($group_data as $g_unit) {
      $group_indexing[$g_unit['g_name']] = $g_unit;
    }

    //Merge db and nas groups
    $group_nas = Array();
    foreach($Groups[0] as $g_name){
      if($group_indexing[$g_name]){
        $g_unit = $group_indexing[$g_name];
      }else{
        $g_unit = Array(
          'g_id' => base64_encode($g_name),
          'g_name' => $g_name,
          'bu_total' => 0
        );
      }
      $data['result'][] = $g_unit;
    }
	$data['total'] = count($data['result']);
    return $data;
	}

	public function getGroup(){
		global $db;
		list($gkey) = func_get_args();
		$group = new group(&$db);
		return $group->getByKey($gkey);
	}

	public function getCategoryStructure(){
		global $db;
		list($id) = func_get_args();

		if(LicenseManager::chkAuth(MEMBER_SYSTEM,MemberSystemEnum::NAS_LDAP)){		
			$group = new group(&$db);
			$category = new category(&$db);

    	$checked_arr = $group->getCategoryByKey($id);
    	$data = $category->getCategoryStructure($checked_arr);
    }else{
    	$data = parent::getCategoryStructure($id);
    }

		return $data;
	}

	public function setBSGroup(){
		global $db;
		/* sys_bookshelf */
		list($bsid,$groups_arr) = func_get_args();
		$group = new group(&$db);

    foreach ($groups_arr as $key => $g_name) {
      if(substr($g_name, 0, 2) == '::'){
        $g_name = substr($g_name, 2);
        $data = $group->getByName($g_name);
        if($data){
        	$g_id = $data['g_id'];
        }else{
					$data = array();
					$data['g_name'] = $g_name;
					$data['g_key'] = base64_encode($g_name);
	        $g_id = $group->insert($data,true);
      	}
        $groups_arr[] = $g_id;
      }
    }
    $data = parent::setBSGroup($bsid,$groups_arr);
    return $data;
	}


	public function updGroup($g_key,$data){
		global $db;
		global $bs_code;
		$group = new group(&$db);
		if(empty($g_key)){
			return null;
		}
		$rs = $group->getByKey($g_key);
		if(!empty($rs)){
			$gid=$rs['g_id'];
			$rs=$group->update($gid,$data);
		}else{
			$data['bs_id'] = $bs_code;
			$rs = $group->insert($data);
		}

    return $rs;
	}


	public function getUserList(){
		global $db;
		switch(func_num_args()){
			case 4:
				/* bookshelf_user */
				list($orderby,$limit_from,$offset,$where) = func_get_args();
				break;
			case 5:
				list($orderby,$limit_from,$offset,$where,$gid) = func_get_args();
				if(!empty($where)){
					$where = " and g_key='".$gid."'";
				}
				break;
		}
		$group = new group(&$db);
		$group_data = $group->getByKey($gid);
		$data = parent::getUserList($orderby,$limit_from,$offset,$where);

    $startIndex = ($page-1)*PER_PAGE;
    $stopIndex = $startIndex+PER_PAGE-1;

    $group_nas = $this->LDAPAgent->listGroupUsers($authSid="", base64_decode($gid), $lower=$startIndex, $upper=$stopIndex);

    //Prepare user map
    $usermap = Array();
    foreach ($data['result'] as $user_unit)
    {
      $usermap[$user_unit['bu_name']] = $user_unit;
    }

    //Merge users
    $group_merge = Array('result' => Array(), 'total' => $group_nas[1]);
    foreach ($group_nas[0] as $username)
    {
      if(array_key_exists($username, $usermap))
      {
        $group_merge['result'][] = $usermap[$username];
      }
      else
      {
        $group_merge['result'][] = Array(
          'bu_id' => '',
          'bu_name' => $username,
          'bu_cname' => $username,
          'bu_password' => '',
          'g_id' => '',
          'last_login' => '0000-00-00 00:00:00'
        );
      }
    }

    //Get c_name
    foreach ($group_merge['result'] as $group_index => $user_unit)
    {
      $user_list = $this->LDAPAgent->listUsers($authSid="", $lower="0", $upper="all", $filter=$user_unit['bu_name']);
      foreach ($user_list[0] as $user_nas)
      {
        if($user_nas['username'] == $user_unit['bu_name'])
        {
          $group_merge['result'][$group_index]['bu_cname'] = $user_nas['fullname'];
          break;
        }
      }
    }

    $data = $group_merge;

    return $data;
	}
}
?>
