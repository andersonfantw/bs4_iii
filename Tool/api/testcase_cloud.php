<?PHP
/**************************************************************
api
1. create_account
2. create_bookshelf
3. create_user
4. del_user
5. get_bookshelf_list
6. login_user
7. request_token
8. set_account_status
9. set_password
10. set_user
11. sync
**************************************************************/

class testcase_cloud{
	const BR = '<br>';
	const CMD = '/usr/local/bin/curl -s %s %s/api/cloud/%s.php';
	var $token;
	var $_bsid;
	function __construct(){
	}
	function test(){
		$this->request_token();
		$this->create_account();
		$this->set_account_status();
		$this->get_bookshelf_list();
		$this->create_bookshelf();
		$this->get_bookshelf_list();
		$this->create_user();
		$this->login_user();
		$this->del_user();
    $this->_del_bookshelf();
    $this->_del_account();
	}
	private function create_account(){
		echo ' -create_account'.BR;
		$param=array('token'=>$this->token,
									'accountname'=>'ttii',
									'password'=>'ttii',
									'bsname'=>'TaipeiTokyo',
									'bs_number'=>'2');
		$p = $this->curlimplode($param);
		exec(sprintf(self::CMD,$p,HttpDomain,'create_account'),$output, $return_var);
    var_dump($output);
    echo BR;
	}
	private function create_bookshelf(){
	}
	private function create_user(){
		echo ' -create_user'.BR;
		$param=array('token'=>$this->token,
									'accountname'=>'ttii',
									'password'=>'ttii',
									'bsid'=>$this->_bsid[0],
									'subject'=>'plan,book_ge1');
		$p = $this->curlimplode($param);
		exec(sprintf(self::CMD,$p,HttpDomain,'create_user'),$output, $return_var);
		var_dump($output);
		echo BR;
	}
	private function del_user(){
	}
	private function get_bookshelf_list(){
    echo ' -get_bookshelf_list'.BR;
    $param=array('token'=>$this->token,
            'username'=>'ttii');
    $p = $this->curlimplode($param);
    exec(sprintf(self::CMD,$p,HttpDomain,'get_bookshelf_list'),$output, $return_var);
    $result = json_decode($output[0],TRUE);
    $this->_bsid = array();
    foreach($result as $row){
	    $lnetid = HostManager::decodeLNetID($row['bs_number']);
	    $this->_bsid[] = intval($lnetid['bsid']);
    }
    var_dump($this->_bsid);
    echo BR;
	}
	private function login_user(){
		echo ' -login_user'.BR;
		$param=array('token'=>$this->token,
									'ac'=>'ttii',
									'pw'=>md5('ttii'),
									'bs'=>$this->_bsid[0]);
		$p = $this->curlimplode($param);
		exec(sprintf(self::CMD,$p,HttpDomain,'login_user'),$output, $return_var);
		var_dump($output);
		echo BR;
	}
	private function request_token(){
		echo ' -request_token'.BR;
		$param=array('id'=>'wonderbox',
									'password'=>'g5@ga23');
		$p = $this->curlimplode($param);
		exec(sprintf(self::CMD,$p,HttpDomain,'request_token'),$output, $return_var);
    var_dump($output);
    echo BR;
		$this->token = $output[0];
	}
	private function set_account_status(){
	}
	private function set_password(){
	}
	private function curlimplode($param){
		$p = '';
		foreach($param as $name=>$value){
			$p .= sprintf(' -F %s=%s',$name,$value);
		}
		return $p;
	}

	private function _del_bookshelf(){
		echo ' -_del_bookshelf'.BR;
		global $db;
		$account = new account(&$db);
		$uid = $account->getUIDByAccountName('ttii');
		foreach($this->_bsid as $bsid){
			BookshelfManager::DeleteBookshelf($uid,$bsid);
		}
	}
	private function _del_account(){
		echo ' -_del_account'.BR;
		global $db;
		$account = new account(&$db);
		$uid = $account->getUIDByAccountName('ttii');
		$account->del($uid);
	}

}
?>                      