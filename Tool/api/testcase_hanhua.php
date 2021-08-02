<?PHP
/**************************************************************
api
1. create_user
2. lookup_userid
3. request_token
4. set_status
**************************************************************/
class testcase_hanhua{
	const BR = '<br>';
	const CMD = '/usr/local/bin/curl -s %s %s/api/hanhua/%s.php';
	var $token;
	function __construct(){
	}
	function test(){
		$this->request_token();
		$this->lookup_userid();
		$this->create_user();
		$this->lookup_userid();
		$this->_check_group_users();
		global $db;
		$bookshelf_user = new bookshelf_user($db);
		$data = $bookshelf_user->getByName('ttii');
		$bookshelf_user->del($data['bu_id'],0);
	}
	private function create_user(){
		echo ' -create_user'.BR;
		$param=array('token'=>$this->token,
								'userid'=>'ttii',
								'password'=>'ttii',
								'subject'=>'book_ge1,plan');
		$p = $this->curlimplode($param);
		exec(sprintf(self::CMD,$p,HttpDomain,'create_user'),$output, $return_var);
    var_dump($output);
    echo BR;
	}
	private function lookup_userid(){
		echo ' -lookup_userid'.BR;
		$param=array('token'=>$this->token,
								'userid'=>'ttii');
		$p = $this->curlimplode($param);
		exec(sprintf(self::CMD,$p,HttpDomain,'lookup_userid'),$output, $return_var);
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
	private function set_status(){
	}
	private function curlimplode($param){
		$p = '';
		foreach($param as $name=>$value){
			$p .= sprintf(' -F %s=%s',$name,$value);
		}
		return $p;
	}

	private function _check_group_users($name){
    global $db;
    $bookshelf_user = new bookshelf_user($db);
    $data = $bookshelf_user->getByName('ttii');
    echo 'buid='.$data['bu_id'].BR;
    $sql=<<<SQL
select * from bookshelf2_group_users where bu_id=%s
SQL;
    $sql = sprintf($sql, $data['bu_id']);
    $data = $db->get_results($sql);
    $num = count($data);
    echo 'Found '.$num.' record';
    foreach($data as $row) echo ','.$row['g_id'];
    echo BR;
    return $num;

	}
}
?>                                               