<?PHP
# dependence: HostManager.class
class BookshelfManager{
	function __construct(){
	}
	//Create accout of bookshelfs, also give username or password if not set.
	function CreateAccount($data){
		global $db;
		global $fs;
		$arr=array();

		$name = $data['u_name'];
		if(empty($name)){
			$arr['status']=false;
			$arr['msg'] = 'account name is empty!';
			return $arr;
		}
		$uid = $this->CheckAccount($name);
		if(!empty($uid)){
			$arr['status']=false;
			$arr['msg'] = 'this account name is occupied!';
			return $arr;
		}

		$DEFAULT=array();
		$DEFAULT['u_name']='';
		$DEFAULT['u_cname']='';
		$DEFAULT['u_password']='';
		
		foreach($DEFAULT as $key=>$val){
			if(!isset($data[$key])){
				switch($key){
					case 'u_name':
						//set a username for user
						break;
					case 'u_password':
						//set a password for user
						break;
					default:
						$data[$key]=$val;
						break;
				}
			}
		}
		
		//insert db.
		$account = new account($db);
		$uid = $account->insert($fs->sql_safe($data),true);
		
		//return result
		if(!$uid){
			$arr['status']=false;
			$arr['msg']='create account fail';
			return $arr;
		}
		
		$arr['status']=true;
		$arr['uid']=$uid;
		$arr['data']=$data;
		return $arr;
		
	}

	function CheckAccount($name){
		global $db;
		$account = new account($db);
		$data = $account->getByName($name);
		return $data['u_id'];
	}

	function CreateBookshelfFiles($data,$uid=0,$bsid=0){
		//create host folder
		$folders = array('/files/','/work/','/uploadfiles/');
		$userbase = HostManager::getBookshelfBase(false,true,$uid,$bsid);
		foreach($folders as $folder){
			if(!is_dir($userbase.$folder)){
				mkdir($userbase.$folder,0777);
			}
		}
	
		$ConfigManager = new ConfigManager($uid,$bsid);

		$json = array();
		$json['headerlink']=$data['bs_header_link'];
		$json['footer']='';
		$ConfigManager->SaveJSON('userbase',$json);

		$define = array();
		$define['TITLE']=$data['bs_title'];
		$define['FOOTER_TEXT']=$data['bs_footer_content'];
		$define['LIST_STATUS']=$data['bs_list_status'];
		$define['TYPE']=$data['bs_type'];
		$define['HEADER_LINK']=$data['bs_header_link'];
		$define['IBOOK_STATUS']=$data['is_ibook'];
		$define['WEBBOOK_STATUS']=$data['is_webbook'];
		$define['MEMBER']=$data['is_member'];
		if($data['is_member']==1){
			$define['NEWBOOK']=0;
			$define['ALLBOOK']=0;
		}else{
			$define['NEWBOOK']=1;
			$define['ALLBOOK']=1;
		}
		$ConfigManager->SaveDefine('userbase',$define);
	}

	//Create bookshelf, and relate to account.
	//Create all necessary config, css, json files.
	function CreateBookshelf($uid,$data){
		global $db;
		global $fs;
		$DEFAULT=array();
		$DEFAULT['bs_name']='My Bookshelf';
		$DEFAULT['bs_status']=1;
		$DEFAULT['bs_title']='My Bookshelf';
		$DEFAULT['bs_header']=0;
		$DEFAULT['bs_header_height']=0;
		$DEFAULT['bs_footer']=0;
		$DEFAULT['bs_footer_height']=0;
		$DEFAULT['bs_footer_content']='';
		$DEFAULT['is_member']=1;
		$DEFAULT['is_webbook']=1;
		$DEFAULT['is_ibook']=0;
		$DEFAULT['is_allbook']=0;
		$DEFAULT['is_newbook']=0;
		$DEFAULT['ecocat_api']='';
		$DEFAULT['bs_header_link']='';
		$DEFAULT['bs_key']='';
		$DEFAULT['bs_type']=0;
		$DEFAULT['bs_list_status']=1;

		//make sure not null columns all set.
		/*
		foreach($DEFAULT as $key=>$val){
			if(!isset($data[$key])){
				switch($key){
					default:
						$data[$key]=$val;
						break;
				}
			}
		}*/
		$_data = $data+$DEFAULT;

		$bookshelf = new bookshelf($db);
		$bs_id = $bookshelf->insert($fs->sql_safe($_data),$uid);
		if($bs_id){
			self::CreateBookshelfFiles($_data,$uid,$bs_id);

			if(empty($_data['bs_key'])){
				$account = new account($db);
				$data = $account->getAccountByBSID($bs_id);
				$data1 = array('bs_key'=>sprintf('%s%u',$_data['u_name'],$bs_id));
				$bookshelf->update($bs_id,$data1);
			}

			$arr=array();
			$arr['status']=true;
			$arr['bsid']=$bs_id;
			$arr['data']=$_data;
			return $arr;
		}

		$arr=array();
		$arr['status']=false;
		return $arr;
	}

	function DeleteBookshelf($uid,$bsid){
		global $db;
		if(HostManager::RemoveBookshelfBase($uid,$bsid,'iamthepassword')){
			$bookshelf = new bookshelf($db);
			if($bookshelf->del($bsid)){
				return true;
			}
		}
		return false;
	}

	public function countBookshelfUsersByBookshelf($bsid){
		global $db;
		$bookshelf = new bookshelf($db);
		$arr = $bookshelf->getUserByBookshelf($bsid);
		return count($arr);
	}

	public function countBookshelfUsersByAccount($uid){
		global $db;
		$bookshelf = new bookshelf($db);
		$arr = $bookshelf->getUserByAccount($uid);
		return count($arr);
	}

	function CreateCate(){
	}

	function create_cover($file_path,$uid=0,$bsid=0){
		if(isset($file_path)){
			if($file_path!=''){
		  	$resize = array();
		  	$resize['m_'] = array('w'=>200,'h'=>260);
		  	$resize['s_'] = array('w'=>120,'h'=>120);		
				return common::insert_host_image($file_path,0,$resize,$uid,$bsid);
			}
		}
		return 0;
	}
	
	function update_cover($file_path,$db_file_id,$uid=0,$bsid=0){
		if(isset($file_path)){
			if($file_path!=''){
		  	$resize = array();
		  	$resize['m_'] = array('w'=>200,'h'=>260);
		  	$resize['s_'] = array('w'=>120,'h'=>120);		
				return common::insert_host_image($file_path,$db_file_id,$resize,$uid,$bsid);
			}
		}
		return 0;
	}

	function create_banner($file_path,$uid=0,$bsid=0){
		if(isset($file_path)){
			if($file_path!=''){
				return common::insert_host_image($file_path,$uid,$bsid);
			}
		}
		return 0;
	}
	
	function update_banner($file_path,$db_file_id,$uid=0,$bsid=0){
		if(isset($file_path)){
			if($file_path!=''){
				return common::insert_host_image($file_path,0,$uid,$bsid);
			}
		}
		return 0;
	}

	function set_bookshelf_self_share($bs_id,$bs_name){
		global $db;
		global $fs;
		$bookshelf_share = new bookshelf_share($db);
		$bookshelf_share_source = new bookshelf_share_source($db);

    $bss_data['bss_ip'] = '127.0.0.1';
    $bss_data['bss_account'] = 'share';
    $bss_data['bss_password'] = 'share';
    $bss_data['bs_id'] = $bs_id;
    $rs = $bookshelf_share->insert($fs->sql_safe($bss_data));

    $bsss_data['bsss_name'] = $bs_name;
    $bsss_data['bsss_source'] = 'http://127.0.0.1:9999/bs3/api/datasource.php?bs='.$bs_id;
    $bsss_data['bsss_account'] = 'share';
    $bsss_data['bsss_password'] = 'share';
    $rs = $bookshelf_share_source->insert($fs->sql_safe($bsss_data));
    
    return $rs;
	}

	function AdminLogin($su_name){
		if($su_name){
			$_SESSION['sysuser']=$su_name;
    	setcookie('sysuser',$su_name);
    	setcookie('syscheck',md5($su_name.CHECK_CODE));
    	return true;
   }
   return false;
	}

	function SSOLogin($uid,$acc){
		$_sysuser = common::getcookie('sysuser');
		$_syscheck = common::getcookie('syscheck');
		setcookie('adminuser',$_sysuser);
		setcookie('admcheck',$_syscheck);
		setcookie('adminid',$uid,0,WEB_URL.'/backend');
		$_SESSION['adminid']=$uid;
		$_SESSION['adminacc']=$acc;
		$_SESSION['adminname']=$acc;

		BookshelfManager::UserLogin(0,$acc,$acc);
	}

	function BSManagerLogin($uid,$acc,$name){
		setcookie('adminuser',$acc,0,WEB_URL.'/backend');
		setcookie('admcheck',md5($acc.CHECK_CODE),0,WEB_URL.'/backend');
		setcookie('adminid',$uid,0,WEB_URL.'/backend');
		$_SESSION['uid']=$uid;
		$_SESSION['adminid']=$uid;
		$_SESSION['adminacc']=$acc;
		$_SESSION['adminname']=$name;
		
		$_SESSION[SITE_PREFIX.'uid'] = $uid;
//var_dump($_COOKIE,$_SESSION);exit;
		BookshelfManager::UserLogin(0,$acc,$name);
	}

	function BSManagerLoginCookie($bsid,$bsname=''){
		setcookie('bs',$bsid,0);
		setcookie('bscheck',md5($bsid.CHECK_CODE));
		if(!empty($bsname)) setcookie('bsname',$bsname,0);
		
		$_SESSION[SITE_PREFIX.'bsid'] = $bsid;
	}

	function UserLogin($buid=0, $acc, $name){
    	setcookie('acc',$acc,0,WEB_URL.'/');
    	setcookie('buid',$buid,0,WEB_URL.'/');
    	$_SESSION['acc'] = $acc;
    	$_SESSION['buid']= $buid;
    	$_SESSION['name']= $name;

    	$t = date("Y-m-d H:i:s");
    	$_SESSION['singlelogin'] = $t;
    	$_SESSION['singlelogin_alive'] = $t;
    	unset($_SESSION['notlogin']);
    	unset($_SESSION['notlogin_alive']);
	}

	function SysLogout(){
	  setcookie('sysuser',null,time() - 3600,WEB_URL.'/backend');
	  setcookie('syscheck',null,time() - 3600,WEB_URL.'/backend');
	}

	function BSManagerLogout(){
		global $db;
		$login = new login($db);

		$uid = bssystem::getLoginUID();
		$type='a';
		$sessionid = session_id();
		$login->updateByPKey($uid,$type,$sessionid,$starttime);

		//logout backend
	  setcookie('bs',null,time() - 3600,WEB_URL.'/backend');
	  setcookie('bsid',null,time() - 3600,WEB_URL.'/backend');
	  setcookie('bscheck',null,time() - 3600,WEB_URL.'/backend');
	  setcookie('bsname',null,time() - 3600,WEB_URL.'/backend');
	  setcookie('adminuser',null,time() - 3600,WEB_URL.'/backend');
	  setcookie('admcheck',null,time() - 3600,WEB_URL.'/backend');
		unset($_SESSION['adminid']);
		unset($_SESSION['adminacc']);
		unset($_SESSION['adminname']);
		$_SESSION['uid'] = 0;
		
		BookshelfManager::UserLogout();
	}

	function UserLogout(){
		global $db;
		$login = new login($db);

		$buid = bssystem::getLoginBUID();
		$type='u';
		$sessionid = session_id();
		$login->updateByPKey($buid,$type,$sessionid,$starttime);

		//logout website
   	setcookie('acc',$null,time() - 3600,WEB_URL.'/');
   	setcookie('buid',$null,time() - 3600,WEB_URL.'/');
   	unset($_SESSION['acc']);
	  unset($_SESSION['buid']);
	  unset($_SESSION['name']);

  	unset($_SESSION['singlelogin']);
  	unset($_SESSION['singlelogin_alive']);
  	$t = date("Y-m-d H:i:s");
  	$_SESSION['notlogin'] = $t;
  	$_SESSION['notlogin_alive'] = $t;
  	$_SESSION['uid'] = 0;
	}

  function callEcocatUpdate($processid,$subcid)
  {
    $method = "POST";
    $boundary = md5(time());

		list($BookshelfConnector_domain,$BookshelfConnector_port) = explode(':',LocalIPPort);
    $BookshelfEcocatUpdate_path = '/backend/ecocat_update.php?type=ecocat&bs='.bssystem::getBSID();

    $cookie='';
    foreach($_COOKIE as $key => $val){
			$cookie .= ';'.$key.'='.urlencode($val);
    }
    if($cookie) $cookie=substr($cookie,1);
/*
    $http_request_param = "--".$boundary."\r\n";
    $http_request_param .= "Content-Disposition: form-data; name=\"cid\"\r\n\r\n".$subcid."\r\n";
    $http_request_param .= "--".$boundary."--\r\n";
*/
    $http_request = $method." ".$BookshelfEcocatUpdate_path."&cid=".$subcid."&processid=".$processid." HTTP/1.1\r\n";
    $http_request .= "Host:".$BookshelfConnector_domain.":".$BookshelfConnector_port."\r\n";
    $http_request .= "User-Agent: {$_SERVER['HTTP_USER_AGENT']}\r\n";
    $http_request .= "Cookie: ".$cookie."\r\n";
    $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $http_request .= "Content-Length:".strlen($http_request_param)."\r\n\r\n"; 

    $fp = fsockopen($BookshelfConnector_domain, $BookshelfConnector_port, $errno, $errstr, 10);
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

  function doEcocatUpdate($type,$bs_code,$uid,$cid='',$processid='',$bsss_id=''){
		global $db;
		global $fs;

		$db_ecocat_book_arr = array();
		$book = new book($db);

		//file_put_contents('/tmp/ecocat.xml',file_get_contents(ECOCAT_API_URL));
		if($type=='share_bs'){
		  $bookshelf_share_source = new bookshelf_share_source($db,'bookshelf_share_source');
		  $rs = $bookshelf_share_source->getByID($bsss_id);
		  $url = $rs['bsss_source']."&ac=".$rs['bsss_account']."&pw=".md5($rs['bsss_password'])."&type=xml";
		  $xml = simplexml_load_file($url);

			//get ecocat books in db
			//$rs = $book->getAllList(0,'','','',$bs_code,$type,'',$bsss_id);
			$book->reset();
			$book->setBSID($bs_code);
			$book->setShareID($bsss_id);
			$rs = $book->getList('',0,0,'');
		}else{
		  //file_put_contents('/tmp/ecocat.xml',file_get_contents(ECOCAT_API_URL));
		  $bookshelf = new bookshelf($db);
		  $rsb = $bookshelf->getByID($bs_code);
		  if(substr($rsb['ecocat_api'],0,7)=='http://'){
		  	$xml = simplexml_load_file($rsb['ecocat_api']);
		  }else{
		  	$xml = simplexml_load_file(HttpLocalIPPort.ECOCAT_PATH.$rsb['ecocat_api']);
		  }
		
			//get ecocat books in db
			//$rs = $book->getAllList(0,'','','',$bs_code,'ecocat','');
			$book->reset();
			$book->setType('ecocat');
			$book->setBSID($bs_code);
			$rs = $book->getList('',0,0,'');
		}
		
		foreach($rs['result'] as $key=>$val)
		{
		  if($type=='share_bs')
		    $db_ecocat_book_arr[$val['share_bs_id']] = $val;
		  else
		    $db_ecocat_book_arr[$val['ecocat_id']] = $val;
		}
		
		//$xml = simplexml_load_file('ecocat.xml');
		if(empty($xml) || !empty($xml->error)){
		  echo "get ecocat data failed";
		  exit;
		}
		$json = json_encode($xml);
		$array = json_decode($json,TRUE);
		$ecocat = $array['detail'];
		
		if(empty($ecocat)){
		  echo "no book update!";
		  exit;
		}
		
		if(empty($ecocat[0]['process_id'])){
			$tmp_arr = $ecocat;
			unset($ecocat);
			$ecocat[0] = $tmp_arr;
		}
		
		foreach($ecocat as $key=>$val)
		{
		  unset($data);
		  $data['b_name'] = $val['book_name'];
		  $data['ibook_link'] = $val['pdf_path'];
		  $data['webbook_link'] = $val['book_url'];
		
		  //ecocat?????????ADB??????(?????????e?????????g???qecocat?????J???L)???B???W???[?????? => DB???????????????????????????y???????????????W???[????????????
		  //ecocat?????????ADB??????(?????????e?????????g???qecocat?????J???L)???B???U???[?????? => DB???????????????????????????y???????????????U???[????????????
		  if(array_key_exists($val['process_id'],$db_ecocat_book_arr)){
		    //??????s?????????y?????????
		    if(!$book->update($db_ecocat_book_arr[$val['process_id']]['b_id'],$fs->sql_safe($data)))
		      echo "update book infomation failed";
		    unset($db_ecocat_book_arr[$val['process_id']]);
		
		  }else{  //ecocat?????????ADB???S?????? => ???s???W?????????y??????DB???B???]???w?????????W???[
		    $data['b_status'] = '1';
		    if($type=='share_bs'){
		    	//???????????????????????decocat_id???????O??????bsss_id
		      $data['share_bs_id'] = $val['process_id'];
		      $data['ecocat_id'] = $bsss_id;
		    }else{
		      $data['ecocat_id'] = $val['process_id'];
		    }
			  $data['bs_id'] = $bs_code;
		
		    /***********file upload*************/
		    //download image
		    $image_url = str_replace(LocalHost,'',$val['image_url']);
		    if(substr($image_url,0,7)=='http://'){
		    	file_put_contents(ROOT_PATH.'/'.FILE_UPLOAD_PATH.'tmp_image',file_get_contents($image_url));
		    }else{
		    	file_put_contents(ROOT_PATH.'/'.FILE_UPLOAD_PATH.'tmp_image',file_get_contents(ROOT_PATH.HostManager::getBookshelfBase(false,false,$uid,$bs_code).$image_url));
		    }
		
				sleep(1);
		
		    //upload image and make thumbs
		    $uploadfile = ROOT_PATH.'/'.FILE_UPLOAD_PATH.'tmp_image';
		  	$resize = array();
		  	$resize['m_'] = array('w'=>200,'h'=>260);
		  	$resize['s_'] = array('w'=>120,'h'=>120);
		  	$val1 = common::insert_host_image($uploadfile,$data['file_id'],$resize,$uid,$bs_code);
		  	if($val1['id']) $data['file_id'] = $val1['id'];
		    @unlink(ROOT_PATH.'/'.FILE_UPLOAD_PATH.'tmp_image');
		
		    /***********file upload*************/
		    if($processid==$val['process_id'] && isset($cid)) $data['c_id'] = $cid;
		
				$b_id = $book->insert($fs->sql_safe($data));
		    if($b_id){
		    	if(REFLECTION_GAME){
			    	$game_reflection = new game_reflection($db);
			    	$isGameReflectionBS = $game_reflection->isGameReflectionBS($bs_code);
				    if($isGameReflectionBS){
				    	$nextseq = $game_reflection->getNextMapSeq($bs_code);
				    	if($nextseq>0){
				    		file_put_contents('log',"$bs_code,$b_id,$nextseq");
				    		$game_reflection->insert_bookref($bs_code,$b_id,$nextseq);
				    	}
				    }
			  	}
		  	}else{	
		      echo "add book failed";
		    }
			  
				unset($val);
		  }
		}
		//ecocat???S??????(???U???[???F)???ADB??????(?????????e?????????g???qecocat?????J???L)???B???W???[?????? => DB???????????????????????????y???n???????????U???[
		//ecocat???S??????(???U???[???F)???ADB??????(?????????e?????????g???qecocat?????J???L)???B???U???[?????? => DB???????????????????????????y???????????????U???[????????????
		if(sizeof($db_ecocat_book_arr)!=0){
		  foreach($db_ecocat_book_arr as $key=>$val){
		    unset($data);
		    $data['b_status'] = '0';
		    if(!$book->update($val['b_id'],$fs->sql_safe($data)))
		      echo "unpublish book failed";
		  }
		}
		

  }
}
?>
