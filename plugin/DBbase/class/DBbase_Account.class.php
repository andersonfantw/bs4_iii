<?PHP
class DBbase_Account implements iAccountInterface{
  
  function __construct(){
  }

	public function getBSManagerList(){
		global $db;
		$account = new db_process(&$db,'account','u_');
		switch(func_num_args()){
			case 0:
				/* sys_bookshelf.add */
				$data = $account->getList('u_name asc, u_cname asc','','','');		
				break;
			default:
				/* sys_account */
				list($orderby,$limit_from,$offset,$where) = func_get_args();
    		$data = $account->getList($orderby,$limit_from,$offset,$where);
				break;
		}
    return $data;
	}

	/* sys_bookshelf */
	function SearchManagerAccount(){
		global $db;
		list($str) = func_get_args();
		$account = new db_process(&$db,'account','u_');
		$data = $account->getList('u_name asc, u_cname asc',0,0,"u_cname like '%".$str."%'");
		return $data;
	}

	/* sys_bookshelf */
	public function getBSManagerUID(){
		global $db;
		switch(func_num_args()){
			case 1:
				list($uid) = func_get_args();
				break;
		}
		return $uid;
	}

	public function getGroupList(){
		global $db;
		switch(func_num_args()){
			case 1:
				/* sys_bookshelf */
				list($bsid) = func_get_args();
				$bookshelf = new bookshelf(&$db);
				$data = $bookshelf->get_bookshelf_groups_structure($bsid);
				break;
			default:
				/* group */
				list($orderby,$limit_from,$offset,$where) = func_get_args();
				$group = new group(&$db);
				$data = $group->getList($orderby,$limit_from,$offset,$where);
				break;
		}
		return $data;
	}

	public function getGroup(){
		global $db;
		list($gid) = func_get_args();
		$group = new group(&$db);
		return $group->getByID($gid);
	}

	public function getCategoryStructure(){
		global $db;
		switch(func_num_args()){
			case 1:
				list($id) = func_get_args();
				$group = new group(&$db);
				$category = new category(&$db);

				$checked_arr = $group->getCategoryByGID($id);
				$data = $category->getCategoryStructure($checked_arr);
				return $data;
				break;
			case 2:
				list($id,$all) = func_get_args();
				$group = new group(&$db);
				$category = new category(&$db);

				$checked_arr = $group->getCategoryByGID($id);
				$data = $category->getCategoryStructure($checked_arr,$all);
				return $data;
				break;

		}
	}

	public function setBSGroup(){
		global $db;
		/* sys_bookshelf */
		list($bsid,$groups_arr) = func_get_args();
		$bookshelf = new bookshelf(&$db);
		$data = $bookshelf->update_bookshelf_groups($bsid,$groups_arr);
		return $data;
	}

	public function updGroup($g_id,$data,$bsid){
		global $db;
		global $bs_code;
		$group = new group(&$db);
		$rs=$group->update($g_id,$data);
		
		return $rs;
	}

	public function getUserList(){
		global $db;
		switch(func_num_args()){
			case 1:
				/* bookshelf_user, select by group_key */
				list($group_key) = func_get_args();
				$bookshelf_user = new bookshelf_user(&$db);
				$data = $bookshelf_user->getList('bu_cname desc','','',"g_id like '%".$group_key."%'");
				break;
			case 4:
				list($orderby,$limit_from,$offset,$where) = func_get_args();
				$bookshelf_user = new bookshelf_user(&$db);
    		$data = $bookshelf_user->getList($orderby,$limit_from,$offset,$where);
				break;
			case 5:
				/* bookshelf_user */
				list($orderby,$limit_from,$offset,$where,$gid) = func_get_args();
        if(empty($where)){
                $where = 'g_id='.$gid;
        }else{
                $where = $where.' and g_id='.$gid;
        }

				$bookshelf_user = new bookshelf_user(&$db);
    		$data = $bookshelf_user->getList($orderby,$limit_from,$offset,$where);
				break;
		}
    return $data;
	}
}
?>
