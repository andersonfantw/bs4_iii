<?PHP
/*=====================================================================================
���Ѯ��d��ƪ����W�ٳ]�w
Group- (�s��)
GroupID		varchar(255): �s�ժ��N��
GroupName	varchar(255): �s�զW��

User- (�ϥΪ�)
UserID		varchar(255): �ϥΪ̥N���A�i�H�O�Ǹ��B�հȤH���s���B�άO������
UserName	varchar(255): �ϥΪ̩m�W
UserAccount	varchar(255): �ϥΪ̱b���A�������^��

UserGroup- (�ϥΪ̸s�ժ����s)
Group.GroupID
User.UserID

* Group�MUser�ϥ�API�άOWebservice���Ѫ̡A�C�ѧ�s�@���A�άO�i�H��ʥߧY��s�C

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

	//���ճs�u�����A�A�b�����W���SoapClient���s�u���e�A�ΩI�sfunction�^�Ǫ����
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

	//�w��Ʀ��ު�sso webservice
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
	
	//�s�ج�j(lhu)���ϥΪ̲M��webservice�A�x�s�����d�w�]���榡
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