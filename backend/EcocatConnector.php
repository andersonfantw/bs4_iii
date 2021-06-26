<?PHP
//ini_set('display_errors','On');
require_once dirname(__FILE__).'/../init/config.php';

class EcocatClient
{

		public $EcocatConnector_domain;
		public $EcocatConnector_port;
		public $EcocatConnector_path;
		public $EcocatConnector_key;
		public $EcocatConnector_pass;
		public $EcocatConnector_account;
		public $EcocatConnector_password;
		public $EcocatConnector_APIPath;
	
    public $request_token='';
    public $access_token='';
    public $Category=null;
    public $Book=null;
    public $User=null;
    public $process_id='';
    public $rate='';
    public $url='';

		public function EcocatClient()
		{
      $db = new Database(DB_SERVER ,DB_USER ,DB_PASS ,DB_DATABASE);
      $db->connect();
      $db->fetch_array_type=MYSQL_ASSOC;
      $db->query("SET NAMES 'utf8'");

			//$init = new init('db','tpl','inputxss','filter','status');
			$_bsid = common::getcookie('bs');
			$id = (int)$_bsid;
			
			$bookshelf = new bookshelf(&$db,'bookshelfs');
			$account = new account(&$db);
			$json = new Services_JSON();
			
			$data = $bookshelf->getList('bs_id desc',0,0,' bs_id='.$id);
			$arr_api = explode('/',$data['result'][0]['ecocat_api']);
			list($this->EcocatConnector_domain,$this->EcocatConnector_port) = explode(':',LocalIPPort);
			$this->EcocatConnector_path = ECOCAT_PATH.'/ecopi/';
			$this->EcocatConnector_key = $arr_api[3];
			$this->EcocatConnector_pass = $arr_api[4];
			$this->EcocatConnector_APIPath = LocalHost.$this->EcocatConnector_path.$this->EcocatConnector_key.'/'.$this->EcocatConnector_pass.'/';
			
			$data = $account->getAccountByBSID($id);
			$this->EcocatConnector_account = $data['u_name'];
			$this->EcocatConnector_password = base64_decode($data['u_password']).'lnetPAssw0rdttii';
		}

    public function RequestToken()
    {
      $method = "GET";
      $cmd = "request_token";
      $xml=$this->Connect($method,$cmd,'');
      $json_data = json_encode($xml);
      $array = json_decode($json_data,TRUE);
      //var_dump( $array);
      $token = $array['detail']['value'];
      
      setcookie('request_token',$token);
      $this->request_token=$token;
    }

    public function AccessToken()
    {
      $method = "POST";
      $cmd = "access_token";
      $param = array("username"=>$this->EcocatConnector_account,"password"=>$this->EcocatConnector_password,"request_token"=>$this->request_token);

      $p = http_build_query($param);
      $xml=$this->Connect($method,$cmd,$p);
      //var_dump( $xml);
      $json_data = json_encode($xml);
      $array = json_decode($json_data,TRUE);
      $token = $array['detail']['value'];

      setcookie('access_token',$token);
      $this->access_token=$token;

      return $json_data;
    }

    public function getCategory()
    {
      $method = "POST";
      $cmd = "category";
      $param = array("access_token"=>$this->access_token);
      $p = http_build_query($param);
      $xml=$this->Connect($method,$cmd,$p);
      $json_data = json_encode($xml);
      $this->Category = $array = json_decode($json_data,TRUE);

      return $json_data;
    }

    public function getBook()
    {
      $method = "POST";
      $cmd = "book";
      $param = array("access_token"=>$this->access_token);
      $p = http_build_query($param);
      $xml=$this->Connect($method,$cmd,$p);
      $json_data = json_encode($xml);
      $this->Book = $array = json_decode($json_data,TRUE);

      return $json_data;
    }

    public function getUser()
    {
      $method = "POST";
      $cmd = "user";
      $param = array("access_token"=>$this->access_token);
      $p = http_build_query($param);
      $xml=$this->Connect($method,$cmd,$p);
      $json_data = json_encode($xml);
      $this->User = $array = json_decode($json_data,TRUE);

      return $json_data;
    }
    
    public function Convert()
    {
      $file_content = file_get_contents($_FILES['uploadedFile']['tmp_name']);
      $filename = $_FILES['uploadedFile']["name"];      

      $method = "POST";
      $cmd = "convert";
      //$param = array("access_token"=>$this->access_token);
      //$p = http_build_query($param);
      
      $fp = fsockopen($this->EcocatConnector_domain, $this->EcocatConnector_port, $errno, $errstr, 10);

      $boundary = md5(time());

      $http_request_file = "--".$boundary."\r\n";
      $http_request_file .= "Content-Disposition: form-data; name=\"convert_file\"; filename=\"".$filename."\"\r\n";
      $http_request_file .= "Content-Type: application/pdf\r\n";
      $http_request_file .= "Content-Transfer-Encoding: binary\r\n\r\n";
      $http_request_file .= $file_content."\r\n";

      $http_request_param = "--".$boundary."\r\n";
      $http_request_param .= "Content-Disposition: form-data; name=\"access_token\"\r\n\r\n".$this->access_token."\r\n";
      $http_request_param .= "--".$boundary."--\r\n";

      $http_request = $method." ".$this->EcocatConnector_APIPath.$cmd.".xml HTTP/1.1\r\n";
      $http_request .= "Host:".$this->EcocatConnector_domain.":".$this->EcocatConnector_port."\r\n";
      $http_request .= "User-Agent: {$_SERVER['HTTP_USER_AGENT']}\r\n";
      $http_request .= "Content-Type: multipart/form-data; boundary=".$boundary."\r\n";
      $http_request .= "Content-Length:".strlen($http_request_file.$http_request_param)."\r\n\r\n"; 
      if (!$fp) {
	      echo "$errstr ($errno)<br>\n";exit;
      }else{
	      fputs($fp, $http_request);
	      fputs($fp, $http_request_file);
              fputs($fp, $http_request_param);
	      while (!feof($fp)) {
		      $result .= fread($fp,32000);
	      }
      }
      fclose ($fp);
      //echo $http_request_file;
      //echo $result;
      //echo $http_request_param;

      $content = substr(strstr($result,"\r\n\r\n"),4);
      $xml = simplexml_load_string($content);
      if(empty($xml) || !empty($xml->error)){
	$data['msg']='get ecocat data failed';
	$data['code']='500';
	$json = json_encode($data);
	echo $json;
        exit;
      }

      $json_data = json_encode($xml);
      $array = json_decode($json_data,TRUE);
      $this->process_id = $array['detail']['process_id'];

      return $json_data;
    }

    public function ConvertProcess()
    {
      $process_id = $_GET['pid'];
      $timestamp = $_GET['t'];
      $method = "POST";
      $cmd = "convert_process";
      $param = array("access_token"=>$this->access_token,"process_id"=>$process_id);
      $p = http_build_query($param);
      $xml=$this->Connect($method,$cmd,$p);
      $json_data = json_encode($xml);
      $array = json_decode($json_data,TRUE);
      $this->rate = $array['detail']['rate'];
      $this->url = $array['detail']['url'];
      $array['detail']['timestamp'] = $timestamp;
      $array['detail']['process_id'] = $process_id;

      return json_encode($array);
    }

		public function DeleteBook($process_id)
		{
      $method = "POST";
      $cmd = "book_delete";
      $param = array("access_token"=>$this->access_token,"process_id"=>$process_id,"delete_type"=>"db");
			$p = http_build_query($param);
      $xml=$this->Connect($method,$cmd,$p);  
      $json_data = json_encode($xml);
      $array = json_decode($json_data,TRUE);
      
      return json_encode($array);
		}

/*
    function CreateUser()
    {
    }
    
    function DeleteUser()
    {
    }

    function CreateReader()
    {
    }

    function DeleteReader()
    {
    }
*/

    //connect server, and get xml(json) back;
    function Connect($method,$cmd,$p='')
    {
      $http_request = $method." ".$this->EcocatConnector_APIPath.$cmd.".xml HTTP/1.1\r\n";
      $http_request .= "Host:".$this->EcocatConnector_domain.":".$this->EcocatConnector_port."\r\n";
      $http_request .= "User-Agent: {$_SERVER['HTTP_USER_AGENT']}\r\n";
      $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
      $http_request .= "Content-Length:".strlen($p)."\r\n\r\n"; 

      $fp = fsockopen($this->EcocatConnector_domain, $this->EcocatConnector_port, $errno, $errstr, 10);

      if (!$fp) {
	      echo "$errstr ($errno)<br>\n";exit;
      }else{
	      fputs ($fp, $http_request.$p);
	      while (!feof($fp)) {
		      $result .= fread($fp,32000);
	      }
      }
      fclose ($fp);
      //echo $result;

      $content = substr(strstr($result,"\r\n\r\n"),4);
      $xml = simplexml_load_string($content);
      if(empty($xml) || !empty($xml->error)){
        echo '{"code":"500","msg":"get ecocat data failed."}';
        exit;
      }
      return $xml;

    }
}


/*
simplexml_load_file
file_get_contents

fsockopen(), fgets()
*/
?>
