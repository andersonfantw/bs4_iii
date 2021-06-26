<?PHP
/*=====================================================================================
提供書櫃資料的欄位名稱設定
Group- (群組)
GroupID		varchar(255): 群組的代號
GroupName	varchar(255): 群組名稱

User- (使用者)
UserID		varchar(255): 使用者代號，可以是學號、校務人員編號、或是身份證
UserName	varchar(255): 使用者姓名
UserAccount	varchar(255): 使用者帳號，必須為英文

UserGroup- (使用者群組的關連)
Group.GroupID
User.UserID

* Group和User使用API或是Webservice提供者，每天更新一次，或是可以手動立即更新。

=====================================================================================*/

define('WEBSERVICE_URL_SIGNIN','http://eportal.lhu.edu.tw/eip/ldapService.do?wsdl');
define('WEBSERVICE_URL_LIST','http://140.131.1.63:3211/LKG2_oHnBfij/LKG2Get.asmx?wsdl');

define('WEBSERVICE_SIGNIN_TestID','ttii');
define('WEBSERVICE_SIGNIN_TestPWD','1qaz2wsx');

define('WEBSERVICE_LIST_KEY','DE444E4E35F943F9D8DF21D7740D603F8CFF8DB4F1970849');
define('WEBSERVICE_LIST_Iv','1BC73ADFB7D926B5');

class Services_WebService{
	var $msg = '';

	var $params_signin;
	var $params_list;

	function Services_WebService(){
		$this->params_signin = array(
				'in0' => WEBSERVICE_SIGNIN_TestID,
				'in1' => WEBSERVICE_SIGNIN_TestPWD
			);
		$this->params_list = array(
				'Key'=>WEBSERVICE_LIST_KEY,
				'Iv'=>WEBSERVICE_LIST_Iv
			);
	}

	//測試連線的狀態，在頁面上顯示SoapClient的連線內容，及呼叫function回傳的資料
	function ConnectTest(){
		try{
			$client = new SoapClient(WEBSERVICE_URL_SIGNIN);
			$handle_signin = $client->authUser($this->params_signin);

			echo '<br /><br />======== client_signin ========<br /><br />';
			print_r($this->client_signin);
			echo '<br /><br />======== params_signin ========<br /><br />';
			print_r($this->params_signin);
			echo '<br /><br />======== handle_signin ========<br /><br />';
			print_r($handle_signin);

		}catch(Exception $e){
			echo 'Can not connect sign webservice';
		}

		try{
			$client = new SoapClient(WEBSERVICE_URL_LIST);
			$handle_list = $client->getUseriew($this->params_list);

			echo '<br /><br />======== client_list ========<br /><br />';
			print_r($this->client_list);
			echo '<br /><br />======== params_list ========<br /><br />';
			print_r($this->params_list);
			echo '<br /><br />======== handle_list ========<br /><br />';
			print_r($handle_list);

		}catch(Exception $e){
			echo 'Can not connect list webservice';
		}
	}

	//德瑞數位科技的sso webservice
	function isLogin(){
		try{
			$client = new SoapClient(WEBSERVICE_URL_SIGNIN);
		}catch(Exception $e){
			$this->msg = 'Soap Fail';
			return false;
		}
		$val = $client->verifySessionId(session_id());
		return $val;
	}
	function Login($id, $pwd){
		$this->params_signin = array(
				'in0' => $id,
				'in1' => $pwd
			);
		try{
			$client = new SoapClient(WEBSERVICE_URL_SIGNIN);
		}catch(Exception $e){
			$this->msg = 'Soap Fail';
			return false;
		}
		$val = $client->authUser($this->params_signin);
		return $val;
	}
	
	//龍華科大(lhu)的使用者清單webservice，儲存為書櫃預設的格式
	function lhu_SaveGroupList(){
		var $data = Array();
		try{
			$client = new SoapClient(WEBSERVICE_URL_LIST);
		}catch(Exception $e){
			$this->msg = 'Soap Fail';
			return false;
		}
		$val = $client->getGrpview($this->params_list);
		$xml = simplexml_load_string($val->any);
		if(!$xml){
			$this->msg = 'XML format error';
			return false;			
		}
		foreach($xml->diffgr->NewDataSet->Table as $key => $v){
			$data[$key] = $v;
		}
		$json = json_encode($data);
		file_set_contents(ROOT_PATH.'/cache/grouplist.json',$json);
		return true;
	}

	function lhu_SaveUserList(){
		var $data = Array();
		try{
			$client = new SoapClient(WEBSERVICE_URL_LIST);
		}catch(Exception $e){
			$this->msg = 'Soap Fail';
			return false;
		}
		$val = $client->getUserview($this->params_list);
		$xml = simplexml_load_file($val->any);
		if(!$xml){
			$this->msg = 'XML format error';
			return false;			
		}
		foreach($xml->diffgr->NewDataSet->Table as $key => $v){
			$data[$key] = $v;
		}
		$json = json_encode($data);
		file_set_contents(ROOT_PATH.'/cache/userlist.json',$json);
		return true;
	}

	function SaveGroupList(){
		var $data = Array();
		try{
			$client = new SoapClient(WEBSERVICE_URL_LIST);
		}catch(Exception $e){
			$this->msg = 'Soap Fail';
			return false;
		}
		$val = $client->getGrpview($this->params_list);
		$xml = simplexml_load_file($val);
		if(!$xml){
			$this->msg = 'XML format error';
			return false;			
		}
		foreach($xml->any->diffgr->NewDataSet->Table as $key => $v){
			$data[$key] = $v;
		}
		$json = json_encode($data);
		file_set_contents(ROOT_PATH.'/cache/grouplist.json',$json);
		return true;
	}

	function SaveUserList(){
		var $data = Array();
		try{
			$client = new SoapClient(WEBSERVICE_URL_LIST);
		}catch(Exception $e){
			$this->msg = 'Soap Fail';
			return false;
		}
		$val = $client->getUserview($this->params_list);
		$xml = simplexml_load_file($val);
		if(!$xml){
			$this->msg = 'XML format error';
			return false;			
		}
		foreach($xml->any->diffgr->NewDataSet->Table as $key => $v){
			$data[$key] = $v;
		}
		$json = json_encode($data);
		file_set_contents(ROOT_PATH.'/cache/userlist.json',$json);
		return true;
	}

	function GetGroupList(){
		if(!file_exists(ROOT_PATH.'/cache/userlist.json')){
			lhu_SaveGroupList();
			//SaveGroupList();
		}
		$json = file_get_contents(ROOT_PATH.'/cache/grouplist.json');
		if(!$json){
			$this->msg='File is not exist';
			return false;
		}
		$array = json_decode($json,TRUE);
		return $array;
	}

	function GetUserList(){
		if(!file_exists(ROOT_PATH.'/cache/userlist.json')){
			lhu_SaveUserList();
			//SaveUserList();
		}
		$json = file_get_contents(ROOT_PATH.'/cache/userlist.json');
		if(!$json){
			$this->msg='File is not exist';
			return false;
		}
		$array = json_decode($json,TRUE);
		return $array;
	}

}
?>