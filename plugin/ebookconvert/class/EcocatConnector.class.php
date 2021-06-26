<?PHP
/***************************************************************
communication with ecocat api
***************************************************************/
//error_reporting(E_ALL & ~E_NOTICE);
//ini_set('display_errors','On');

class EcocatConnector
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

		public function EcocatConnector($bsid)
		{
			//restore data
			//1. if token over 1min then expire
			//2. restore data: id,pwd,AccessToken
			//prepare data
			$f_retrieve_data=1;
			/*
			if(isset($_SESSION['EcocatToken'])){
				$str = common::decryptString($_SESSION['EcocatToken']);
				list($this->access_token,$this->EcocatConnector_account,$time) = explode('|',$str);
				//check time
				if(time()-intval($time)<600){
					$f_retrieve_data=0;
				}
			}*/
			
			if($f_retrieve_data){
	      global $db;
			
				$bookshelf = new bookshelf(&$db);
				$account = new account(&$db);
				$json = new Services_JSON();

				list($this->EcocatConnector_domain,$this->EcocatConnector_port) = explode(':',LocalIPPort);
				$this->EcocatConnector_path = ECOCAT_PATH.'/ecopi/';
				if(CONNECT_ECOCAT_IMPORT){
					$this->EcocatConnector_key = EcocatConnector_Default_Key;
					$this->EcocatConnector_pass = EcocatConnector_Default_Pass;
					$this->EcocatConnector_account = EcocatConnector_Default_Account;
					$this->EcocatConnector_password = EcocatConnector_Default_Password;
				}else{
					$data = $bookshelf->getByID($bsid);
					$arr_api = explode('/',$data['ecocat_api']);
					$this->EcocatConnector_key = $arr_api[3];
					$this->EcocatConnector_pass = $arr_api[4];
			
					$data = $account->getAccountByBSID($bsid);
					$this->EcocatConnector_account = $data['u_name'];
					$this->EcocatConnector_password = base64_decode($data['u_password']);
				}
				$this->EcocatConnector_APIPath = HttpLocalIPPort.$this->EcocatConnector_path.$this->EcocatConnector_key.'/'.$this->EcocatConnector_pass.'/';
				
				$this->RequestToken();
				$this->AccessToken();
			}
		}

    private function RequestToken()
    {
      $method = "GET";
      $cmd = "request_token";
      $xml=$this->Connect($method,$cmd,'');
      $json_data = json_encode($xml);
      $array = json_decode($json_data,TRUE);
      //var_dump( $array);
      $token = $array['detail']['value'];

      $this->request_token=$token;
      return $token;
    }

    private function AccessToken()
    {
      $method = "POST";
      $cmd = "access_token";
      $param = array("username"=>$this->EcocatConnector_account,"password"=>$this->EcocatConnector_password.'lnetPAssw0rdttii',"request_token"=>$this->request_token);

      $p = http_build_query($param);
      $xml=$this->Connect($method,$cmd,$p);
      //var_dump( $xml);
      $json_data = json_encode($xml);
      $array = json_decode($json_data,TRUE);
      $token = $array['detail']['value'];

			$str = sprintf('%s|%s|%s',$token,$this->EcocatConnector_account,time());
			$_SESSION['EcocatToken']=common::encryptString($str);

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
    
    public function Convert($tmp_name,$filename,$spell='',$skin='',$language_type='')
    {
    	global $ee;
      $file_content = file_get_contents($tmp_name);

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
      if(!empty($spell)){
	      $http_request_param .= "--".$boundary."\r\n";
	      $http_request_param .= "Content-Disposition: form-data; name=\"spell\"\r\n\r\n".$spell."\r\n";
			}
      if(!empty($skin)){
      	$http_request_param .= "--".$boundary."\r\n";
	      $http_request_param .= "Content-Disposition: form-data; name=\"skin\"\r\n\r\n".$skin."\r\n";
			}
      if(!empty($language_type)){
      	$http_request_param .= "--".$boundary."\r\n";
	      $http_request_param .= "Content-Disposition: form-data; name=\"language_type\"\r\n\r\n".$language_type."\r\n";
			}
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
      //echo $http_request;
      //echo $result;
      //echo $http_request_param;

      $content = substr(strstr($result,"\r\n\r\n"),4);
      $xml = simplexml_load_string($content);

      if(empty($xml)){
      	$ee->Error('204.71');
      }elseif(!empty($xml->error)){
				$ee->Error('406.71');
      }

      $json_data = json_encode($xml);
      $array = json_decode($json_data,TRUE);
      $this->process_id = $array['detail']['process_id'];

        if(array_key_exists('detail',$array)){
              return $array['detail'];
        }else{
                return $array['error'];
        }
    }

    public function Process($process_id,$timestamp)
    {
      $method = "POST";
      $cmd = "convert_process";
      $param = array("access_token"=>$this->access_token,"process_id"=>$process_id);
      $p = http_build_query($param);
      $xml=$this->Connect($method,$cmd,$p);
      $json_data = json_encode($xml);
      $array = json_decode($json_data,TRUE);
      $this->rate = $array['detail']['rate'];
      $this->url = $array['detail']['url'];

        if(array_key_exists('detail',$array)){
      $array['detail']['timestamp'] = $timestamp;
      $array['detail']['process_id'] = $process_id;
      }else{
          $array['error']['timestamp'] = $timestamp;
        $array['error']['process_id'] = $process_id;
      }

      return $array;
    }

		public function DeleteBook($process_id)
		{
			$this->RequestToken();
			$this->AccessToken();
      $method = "POST";
      $cmd = "book_delete";
      $param = array("access_token"=>$this->access_token,"process_id"=>$process_id,"delete_type"=>"db");
			$p = http_build_query($param);
      $xml=$this->Connect($method,$cmd,$p);  
      $json_data = json_encode($xml);
      $array = json_decode($json_data,TRUE);
      
      return $array;
		}

		public function GetSkinList(){
			$this->RequestToken();
			$this->AccessToken();
      $method = "POST";
      $cmd = "skin_list";
      $param = array("access_token"=>$this->access_token);
			$p = http_build_query($param);
      $xml=$this->Connect($method,$cmd,$p);  
      $json_data = json_encode($xml);
      $array = json_decode($json_data,TRUE);

      return $array;
		}

		public function GetSpellList(){
			//workable function, disable for performace issue
/*
			$this->RequestToken();
			$this->AccessToken();
      $method = "POST";
      $cmd = "spell";
      $param = array("access_token"=>$this->access_token);
			$p = http_build_query($param);
      $xml=$this->Connect($method,$cmd,$p);  
      $json_data = json_encode($xml);
      $array = json_decode($json_data,TRUE);
*/
      $array = array('left'=>2,'right'=>1);

      return $array;
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
    	global $ee;
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
      //echo $http_request;
      //echo $result;

      $content = substr(strstr($result,"\r\n\r\n"),4);
      $xml = simplexml_load_string($content);
      if(empty($xml)){
      	$ee->Error('204.71');
      }elseif(!empty($xml->error)){
				$ee->Error('406.71');
      }
      return $xml;

    }
}
?>
