<?php
class init{

  function __construct($param=NULL){
		self::_init();

    $arg_list = func_get_args();
    $arg_list[] = 'getIP';
    foreach($arg_list as $key=>$arg){
      if(is_array($arg)){
        $func=$arg[0];
        array_shift($arg);
        $pram=$arg;
      }else{
        $func=$arg;
        $pram='';
      }
      if(!method_exists($this,$func))continue;
      $this->$func($pram);
    }
  }

	function _init(){
		self::filter();
		global $fs;
		$_page = $fs->filter($_SERVER['PHP_SELF']);
		if($_page=='/index.php'){
			$_page = $fs->filter($_GET['page']);
			$_func = $fs->filter($_GET['func']);
			$acc = $fs->filter($_GET['account']);
			$bs_code = (int)$fs->filter($_GET['bsid']);
			if(!empty($_page)){
				if(!empty($_func)){
					$page=sprintf('/%s/%s/',$_page,$_func);
				}else{
					$page=sprintf('/%s/',$_page);
				}
			}else if(!empty($acc)){
				//trial, subscribe
				$page=sprintf('/%s/',$acc);
			}else{
				$page = '/';
			}
		}else{
			$page = $_page;
  		$method = $fs->filter($_GET['type'],'key');
  		switch($method){
  			case 'add':
  			case 'edit':
  			case 'delete':
  				break;
  			default:
  				$method = '';
  				break;
			}
		}
		if(!empty($method)){
			$page = $page.'|'.$method;
		}
		if(!(new LicenseManager)->chkAuth(MEMBER_MODE,
			MemberSystemFuncMapping::getMapping($page)))
		{
			$ErrorHandler = new ErrorHandler('header');
			$ErrorHandler->Error('404');
		}
	}

  function db(){
    global $db;
		switch(DB_TYPE){
			case 'mysql':
				$db = new Database(DB_SERVER ,DB_USER ,DB_PASS ,DB_DATABASE);
				$db->connect();
				$db->fetch_array_type=MYSQL_ASSOC;
				$db->query("SET NAMES 'utf8'");			
				break;
			case 'dbmaker':
				$db = new odbc(DB_SERVER ,DB_USER ,DB_PASS ,DB_DATABASE);
			        $db->connect();
			        $db->fetch_array_type=MYSQL_ASSOC;
				break;
		}

		if(CONNECT_ECOCAT){
        global $dbr;
        $dbr = new DatabaseI(DB_SLAVE_SERVER ,DB_SLAVE_USER ,DB_SLAVE_PASS ,DB_SLAVE_DATABASE, '', DB_SLAVE_SOCKET);
        $dbr->connect(true);
        $dbr->fetch_array_type=MYSQL_ASSOC;
        //$dbr->query("SET NAMES 'utf8'");
    }
  }

  function tpl(){
      require_once LIBS_PATH.'/Smarty/libs/Smarty.class.php';
      global $tpl;
      $tpl = new Smarty();
      $tpl->cache_dir = ROOT_PATH . '/cache/';
      $tpl->template_dir=ROOT_PATH.'/templates';
      $tpl->compile_dir = ROOT_PATH . '/templates_c/';
      $tpl->left_delimiter = '<{';
      $tpl->right_delimiter = '}>';
  }

  function file(){
      require_once LIBS_PATH.'/class.upload/class.upload.php';
  }

  function status(){
		require_once LIBS_PATH.'/status.class.php';
		global $status;
		$status = new status();
		global $tpl;
		$tpl->assign('status_desc',$status->get_status_desc());
		$tpl->assign('status_code',$status->get_status_code());
	}
	function filter(){
		require_once LIBS_PATH.'/filter_string.class.php';
		global $fs;
		$fs = new filter_string();
  }

  function inputxss(){
      require_once LIBS_PATH.'/removexss.func.php';
      foreach(Array('get','post','request','cookie') as $var_name){
          if($var_name=='get') $var=&$_GET;
          else if($var_name=='post') $var=&$_POST;
          else if($var_name=='request') $var=&$_REQUEST;
          else $var=&$_COOKIE;

          if(!is_array($var) || count($var)==0)
              continue;
          foreach($var as $key=>$value){
              if( is_array($var[$key]) && count($var[$key])>0){
                  foreach($var[$key] as $key2=>$value2){
                      $var[$key][$key2]=removexss($value2);
                  }
              }else
                  $var[$key]=removexss($value);
          }
      }
  }

  function getIP(){
      global $USER_IP;
      // Get some headers that may contain the IP address
      $SimpleIP = (isset($REMOTE_ADDR) ? $REMOTE_ADDR : getenv("REMOTE_ADDR"));

      $TrueIP = (isset($HTTP_X_FORWARDED_FOR) ? $HTTP_X_FORWARDED_FOR : getenv("HTTP_X_FORWARDED_FOR"));
      if ($TrueIP == "") $TrueIP = (isset($HTTP_X_FORWARDED) ? $HTTP_X_FORWARDED : getenv("HTTP_X_FORWARDED"));
      if ($TrueIP == "") $TrueIP = (isset($HTTP_FORWARDED_FOR) ? $HTTP_FORWARDED_FOR : getenv("HTTP_FORWARDED_FOR"));
      if ($TrueIP == "") $TrueIP = (isset($HTTP_FORWARDED) ? $HTTP_FORWARDED : getenv("HTTP_FORWARDED"));
      $GetProxy = ($TrueIP == "" ? "0":"1");

      if ($GetProxy == "0"){
          $TrueIP = (isset($HTTP_VIA) ? $HTTP_VIA : getenv("HTTP_VIA"));
          if ($TrueIP == "") $TrueIP = (isset($HTTP_X_COMING_FROM) ? $HTTP_X_COMING_FROM : getenv("HTTP_X_COMING_FROM"));
          if ($TrueIP == "") $TrueIP = (isset($HTTP_COMING_FROM) ? $HTTP_COMING_FROM : getenv("HTTP_COMING_FROM"));
          if ($TrueIP != "") $GetProxy = "2";
      };

      if ($TrueIP == $SimpleIP) $GetProxy = "0";
      // Return the true IP if found, else the proxy IP with a 'p' at the begining
      switch ($GetProxy){
          case '0':
              // True IP without proxy
              $USER_IP = $SimpleIP;
              break;
          case '1':
              $b = preg_match ("^([0-9]{1,3}\.){3,3}[0-9]{1,3}", $TrueIP, $IP_array);
              if ($b && (count($IP_array)>0)){
                  // True IP behind a proxy
                  $USER_IP = $IP_array[0];
              }else{
                  // Proxy IP
                  $USER_IP = $SimpleIP;
              };
              break;
          case '2':
              // Proxy IP
              $USER_IP = $SimpleIP;
      };

  }

	function auth(){
	  $adminuser=common::getcookie('adminuser');
	  $admincheck=common::getcookie('admcheck');
	  $_adminid = bssystem::getLoginUID();
	  $isValid = (md5($adminuser.CHECK_CODE)==$admincheck) && isset($_adminid);
//var_dump($_COOKIE,$_adminid,$isValid,md5($adminuser.CHECK_CODE),$admincheck);exit;
	  if(LicenseManager::chkAuth(MEMBER_SYSTEM,MemberSystemEnum::NAS_LDAP)){
	  	if($isValid){
			require_once PLUGIN_PATH.'/LDAP/class/LDAP_Auth.class.php';
	  		$Auth = new LDAP_Auth();
  			$isValid = $Auth->isManagerLogin();
  		}
	  }
	  if(!$isValid){
		echo "<script>alert('".LANG_ERROR_LOGIN_AGAIN."');window.location.href='index.php?op=login';</script>";
		exit;
	  }
	}

  function sysauth(){
    $adminuser=common::getcookie('sysuser');
    $admincheck=common::getcookie('syscheck');
		$isValid = (md5($adminuser.CHECK_CODE)==$admincheck);
		if(LicenseManager::chkAuth(MEMBER_SYSTEM,MemberSystemEnum::NAS_LDAP)){
		  	if($isValid){
				require_once PLUGIN_PATH.'/LDAP/class/LDAP_Auth.class.php';
		  		$Auth = new LDAP_Auth();
	  			$isValid = $Auth->isManagerLogin();
	  		}
		}
		if(!$isValid){
      echo "<script>alert('".LANG_ERROR_LOGIN_AGAIN."');window.location.href='index.php?op=system_login';</script>";
      exit;
    }
  }

	function bookshelf_auth(){
	  global $bs_code;
	  $bs_code=common::getcookie('bs');
	  $bscheck=common::getcookie('bscheck');
	  if(md5($bs_code.CHECK_CODE)!=$bscheck){
			echo "<script>alert('".LANG_ERROR_NO_AUTH."');window.location.href='index.php?op=login';</script>";
			exit;
	  }
	}

	//set error response to header
	function ehttp(){
		global $ee;
		require_once LIBS_PATH.'/ErrorHandler.class.php';
		$ee = new ErrorHandler('header');
	}
	//write error msg to log
	function elog(){
		global $ee;
		require_once LIBS_PATH.'/ErrorHandler.class.php';
		$ee = new ErrorHandler('log');
	}
	//response json format error message
	function ejson(){
		global $ee;
		require_once LIBS_PATH.'/ErrorHandler.class.php';
		$ee = new ErrorHandler('json');
	}
	function enone(){
		global $ee;
		require_once LIBS_PATH.'/ErrorHandler.class.php';
		$ee = new ErrorHandler('none');
	}
}
?>
