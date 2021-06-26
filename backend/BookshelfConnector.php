<?PHP
/*
第一階段：
1. 快速顯示書櫃
2. 使用者後台可以上書
第二階段：
*/
error_reporting(E_ALL & ~E_NOTICE);
//ini_set('display_errors','On');
//define('AutoEcocatUpdate',true);

require_once dirname(__FILE__).'/../init/config.php';
session_destroy();
require_once dirname(__FILE__).'/EcocatConnector.php';
$init = new init('db','filter','ejson');
$json = new Services_JSON();
$book = new book(&$db);
$bs = new BookshelfClient;

$cmd = $fs->valid($_GET['cmd'],'cmd');
switch($cmd){
  case 'getBook':
    $bs->getBook();
    break;
  case 'Dispatch':
		if(!LicenseManager::IsEcocatLicenseValid()){
			$ee->add('mag','License is expired! Click [yes] to see more infomation, click [cancel] to return page.');
			$ee->add('link','http://cloudbook.cyberhood.net/cloudbook/licensebuy_01.php?service_id='.wonderbox_id);
			$ee->ERROR('501');
	 	}
    $val = $bs->Dispatch();
    switch($val['type']){
			case 'itutor':
				$data = $val['msg'];
				$timestamp = $val['timestamp'];

				$uploadfile = ROOT_PATH.'/backend/images/itutor.png';
		  	$resize = array();
		  	$resize['s_'] = array('w'=>120,'h'=>120);
				$resize['m_'] = array('w'=>200,'h'=>260);
				$_adminid = bssystem::getLoginUID();
				$_bsid = common::getcookie('bs');
			  $val = common::insert_host_image($uploadfile,0,$resize,$_adminid,$_bsid);
				if($val['id']) $data['file_id'] = $val['id'];
				$book = new book(&$db);
				if(!$book->insert($fs->sql_safe($data))){
					$ee->ERROR('500.40');
				}
				$ee->Message('200');
		  	exit;
				break;
			case 'ecocat':
				echo $val['msg'];
				break;
    }
    break;
  case 'Convert':
    $bs->Convert();
    break;
  case 'ConvertProcess':
    $bs->ConvertProcess();
    break;
}


//echo $bs->getBook();
//echo $bs->getCategory();

class BookshelfClient
{
	public $BookshelfConnector_domain;
	public $BookshelfConnector_port;
	public $BookshelfConnector_path;
	public $Bookshelf_ID;
	public $BookshelfLogin_path;
	public $BookshelfEcocatUpdate_path;

  var $ecocat;

  function BookshelfClient()
  {
  	$_bsid = common:getcookie('bs');
  	list($this->BookshelfConnector_domain,$this->BookshelfConnector_port) = explode(':',LocalIPPort);
  	$this->BookshelfConnector_path = ECOCAT_PATH.'/ecopi/';
  	$this->Bookshelf_ID = (int)$_bsid;
  	$this->BookshelfLogin_path = '/backend/index.php?bs=0';
  	$this->BookshelfEcocatUpdate_path = '/backend/ecocat_update.php?type=ecocat&bs='.$this->Bookshelf_ID;

    $filename = $_FILES['uploadedFile']["name"];
    $sub = strrchr($filename, '.');
    if($sub!='.zip'){
	    $this->ecocat = new EcocatClient;
	    //如果access_token不存在就重新取得
	    //$_accesstoken = common::getcookie('access_token');
	    //if(empty($_accesstoken)){
	        $this->ecocat->RequestToken();
	        $this->ecocat->AccessToken();
	    //}else{
	    //    $this->ecocat->access_token = $_accesstoken;
	    //}
		}    
    //setcookie('adminuser','demo');
    //setcookie('admcheck',md5('demo'.CHECK_CODE));
  }

  public function Login()
  {
  	$_bsid = common::getcookie('bs');
    $id = (int)$_bsid;
    require_once LIBS_PATH."/database.class.php";
    $db = new Database(DB_SERVER ,DB_USER ,DB_PASS ,DB_DATABASE);
    $db->connect();
    $db->fetch_array_type=MYSQL_ASSOC;
    $db->query("SET NAMES 'utf8'");
		$account = new account(&$db);
		$data = $account->getAccountByBSID($id);
		
    $param = array("account"=>$data['u_name'],"password"=>$data['u_password']);
    $p = http_build_query($param);

    $method = "POST";
    $http_request = $method." ".$this->BookshelfLogin_path." HTTP/1.1\r\n";
    $http_request .= "Host:".$this->BookshelfConnector_domain.":".$this->BookshelfConnector_port."\r\n";
    $http_request .= "User-Agent: {$_SERVER['HTTP_USER_AGENT']}\r\n";
    $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $http_request .= "Content-Length:".strlen($p)." \r\n\r\n"; 

    $fp = fsockopen($this->BookshelfConnector_domain, $this->BookshelfConnector_port, &$errno, &$errstr, 10);
    if (!$fp) {
      echo "$errstr ($errno)<br>\n";exit;
    }else{
      fputs ($fp, $http_request.$p);
      while (!feof($fp)) {
	      $result .= fread($fp,32000);
      }
    }
    fclose ($fp);

    echo $result;
  }

	/*
	書櫃管理員的帳號(老師)
	accountid: 0: 書櫃管理員清單, (int)>0: 老師詳細資料
	*/
	public function getAccount()
	{
	}

	public function createAccount()
	{
	}

	public function modifyAccount()
	{
	}

	public function deleteAccount()
	{
	}

	/*
	書櫃使用者帳號(學生)
	cateid: 0: 書櫃所屬的使用者清單, (int)>0: 書櫃所屬的使用者詳細資料
	*/
	public function getUser($cateid)
	{
	}

	public function modifyUser()
	{
	}

	public function createUser()
	{
	}

	public function deleteUser()
	{
	}


	/*
	書櫃清單
	*/
	public function getBookshelf()
	{
	}

	/*
	accountid: (int)>0
	return bookshelfid: (int)
	*/
	public function createBookshekf()
	{
	}

	/*
	bookshelfid: (int)>0
	*/
	public function deleteBookshekf()
	{
	}
	
	/*
	書櫃中的分類
	bookshelfid: (int)>0
	*/
	public function getCategory()
	{
    $method = "POST";
    $cmd = "get_menu";
    $param = array("bs"=>Bookshelf_ID);
    $p = http_build_query($param);
    $xml=$this->Connect($method,$cmd,$p);
    $json_data = json_encode($xml);
    $this->User = $array = json_decode($json_data,TRUE);

    echo $json_data;
	}
	
	/*
	書櫃中分類中的書籍
	cateid: 0: 所有書籍, (int)>0: 分類中的書籍
	*/
	public function getBook()
	{
    $method = "POST";
    $cmd = "get_books";
    $param = array("bs"=>Bookshelf_ID,"buid"=>"-1");
    $p = http_build_query($param);
    $xml=$this->Connect($method,$cmd,$p);
    $json_data = json_encode($xml);
    $this->User = $array = json_decode($json_data,TRUE);

    echo $json_data;
	}

	public function Dispatch()
	{
		$filename = $_FILES['uploadedFile']["name"];
		$sub = strrchr($filename, '.');
		switch($sub){
			case '.zip':
				$current_timestamp = time();
				$_adminid = bssystem::getLoginUID();
				$_bsid = common::getcookie();
				$path = HostManager::getBookshelfBase(false,true,$_adminid,$_bsid).'/files/'.$current_timestamp.'/';
				mkdir($path);
				move_uploaded_file($_FILES['uploadedFile']['tmp_name'],$path.$current_timestamp.'.zip');

				$zipfile=$path.$current_timestamp.'.zip';
				common::unzip($zipfile,$path);
				unlink($path.$current_timestamp.'.zip');
				if(!is_dir($path)){
					$val['type']='itutor';
					$val['code']='500';
					$val['msg']='error';
					return $val;
				}
/*
				$zip = new ZipArchive;
				$res = $zip->open($path.$current_timestamp.'.zip');
				if ($res !== TRUE) {
					$val['type']='itutor';
					$val['code']='500';
					$val['msg']='error';
					return $val;
				}

/*
				$zip_root = $zip->getNameIndex(0);
				for($i = 0; $i < $zip->numFiles; $i++) {
			        	$zip->extractTo($path, array($zip->getNameIndex($i)));
					//echo $zip->getNameIndex($i).'<br />';
				}

				$zip->close();
				unlink($path.$current_timestamp.'.zip');
*/

				//rebuild index
				$itutor_file_type = array("demo.html","practice.html","test.html","tutorial.html");
				foreach($itutor_file_type as $v){
					if(is_file($path.$v)){
						$content = file_get_contents($path.$v);
						$arr = explode('<head>',$content);
						$str = $arr[0].'<head>';
						$str .= '<script src="/bs3/ga/ga.js" type="text/javascript"></script>';
						$str .= '<script src="/bs3/scripts/analyztis.js" type="text/javascript"></script>';
						$str .= $arr[1];
						file_put_contents($path.$v, $str);
					}
				}
				//set report path
				$report_path = $path.'player/params.js';
				if(is_file($report_path)){
					$pattern = '("report_value":"[^"]*")';
					$replacement = '"report_value":"/api/report.php"';
					$string = file_get_contents($report_path);
					file_put_contents($report_path,preg_replace($pattern, $replacement, $string));
				}

				$cid = $_GET['id'];
				$_adminid = bssystem::getLoginUID();
				$bsid = common::getcookie('bs');
				$data['b_name'] = substr($filename,0,-4);
				$data['webbook_link'] = LocalHost.HostManager::getBookshelfBase(false,false,$_adminid,$bsid).'/files/'.$current_timestamp.'/'.'index.html';
				$data['file_id']=$f_id;
				$data['bs_id']=$bsid;
				$data['c_id']=$cid;
				$data['webbook_show']=1;
				$data['ibook_show']=0;
				$data['b_status']=1;

				$val['timestamp'] = $current_timestamp;
				$val['type']='itutor';
				$val['code']='200';
				$val['msg']=$data;
				return $val;
				break;
			case '.pdf':
			case '.doc':
			case '.docx':
			case '.xls':
			case '.xlsx':
			case '.ppt':
			case '.pptx':
				$msg = $this->ecocat->Convert();
				$val['type']='ecocat';
				$val['code']='200';
				$val['msg']=$msg;
				return $val;
				break;
			default:
				echo 'This type of file is not support!';
				break;
		}

	}

	/*
	轉換書籍，直接上傳到設定的分類
	bookshelfid: (int)>0
	cateid: (int)>0
	*/
	public function Convert()
	{
		return $this->ecocat->Convert();
	}

  public function ConvertProcess()
  {
    $json_data = $this->ecocat->ConvertProcess();
    
    $array = json_decode($json_data,TRUE);
    if(($array['detail']['rate']=='100') && !isset($_SESSION[$array['detail']['process_id']]))
    {
      //$this->Login();
      $_SESSION[$array['detail']['process_id']]=1;
      $msg = $this->do_Ecocat_Update($array['detail']['process_id']);
      $array['detail']['ecocat_update_msg'] = $msg;
      echo json_encode($array);
    }else{
      echo $json_data;
    }
  }

  function do_Ecocat_Update($processid)
  {
    $subcid = $_GET['id'];
    $method = "POST";
    $boundary = md5(time());

    $cookie='';
    foreach($_COOKIE as $key => $val){
			$cookie .= ';'.$key.'='..urlencode($val);
    }
    if($cookie) $cookie=substr($cookie,1);
/*
    $http_request_param = "--".$boundary."\r\n";
    $http_request_param .= "Content-Disposition: form-data; name=\"cid\"\r\n\r\n".$subcid."\r\n";
    $http_request_param .= "--".$boundary."--\r\n";
*/
    $http_request = $method." ".$this->BookshelfEcocatUpdate_path."&cid=".$subcid."&processid=".$processid." HTTP/1.1\r\n";
    $http_request .= "Host:".$this->BookshelfConnector_domain.":".$this->BookshelfConnector_port."\r\n";
    $http_request .= "User-Agent: {$_SERVER['HTTP_USER_AGENT']}\r\n";
    $http_request .= "Cookie: ".$cookie."\r\n";
    $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $http_request .= "Content-Length:".strlen($http_request_param)."\r\n\r\n"; 

    $fp = fsockopen($this->BookshelfConnector_domain, $this->BookshelfConnector_port, &$errno, &$errstr, 10);
    if (!$fp) {
      echo "$errstr ($errno)<br>\n";exit;
    }else{
      fputs($fp, $http_request);
      //fputs($fp, $http_request_param);
      while (!feof($fp)) {
	      $result .= fread($fp,32000);
      }
    }
    fclose ($fp);
    return $result;
  }

  //connect server, and get xml(json) back;
  function Connect($method,$cmd,$p='')
  {
    $http_request = $method." ".$this->BookshelfConnector_APIPath.$cmd.".php HTTP/1.1\r\n";
    $http_request .= "Host:".$this->BookshelfConnector_domain.":".$this->BookshelfConnector_port."\r\n";
    $http_request .= "User-Agent: {$_SERVER['HTTP_USER_AGENT']}\r\n";
    $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $http_request .= "Content-Length:".strlen($p)."\r\n\r\n"; 

    $fp = fsockopen($this->BookshelfConnector_domain, $this->BookshelfConnector_port, &$errno, &$errstr, 10);

    if (!$fp) {
      echo "$errstr ($errno)<br>\n";exit;
    }else{
      fputs ($fp, $http_request.$p);
      while (!feof($fp)) {
	      $result .= fread($fp,32000);
      }
    }

    fclose ($fp);
    $arr = preg_split("/\\r\\n\\r\\n/",$result);
    $json_data = json_decode($arr[1],TRUE);
    return $json_data;
  }
}
?>
