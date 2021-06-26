<?PHP
	require_once dirname(__FILE__).'/../init/config.php';
	$init = new init('db','filter','ejson');
	$login = new login(&$db);
	global $db;
$ss = var_export($_SESSION,true);
file_put_contents('/var/www/html/logs/logincheck.log',sprintf("%s=login_check\nsession_id=%s\n",date('Y:m:d H:i:s'),session_id()),FILE_APPEND);
file_put_contents('/var/www/html/logs/logincheck.log',sprintf("line8=ss:%s\n",$ss),FILE_APPEND);

	$cmd = $fs->valid($_POST['cmd'],'cmd');
  //echo common::getcookie('buid');
	$data = array();
	$sessionid = session_id();
	$_buid = bssystem::getLoginBUID();
	$_uid = bssystem::getLoginUID();
	if(!empty($_uid)){
		$data['type'] = 'a';
		$data['id'] = $_uid;
		$data['name'] = bssystem::getLoginUAcc();

		$userid=$_uid;
		$type='a';
	}elseif(!empty($_buid)){
		$data['type'] = 'u';
		$data['id'] = $_buid;
		$data['name'] = bssystem::getLoginBUAcc();
		$data['uname'] = bssystem::getLoginBUName();
	  	switch($cmd){
  			case 'group':
  				$group = new group(&$db);
  				$data1 = $group->getListByBUID($_buid);
				$data['groups'] = $data1;
  			break;
	  	}

		$userid=$_buid;
		$type='u';
	}else{
		$data['type'] = '-';
		$data['id'] = 0;
		$data['name'] = '';

		$data1['bu_id']=0;

		$userid=0;
		$type='-';
	}

/*
	$_buid = bssystem::getLoginBUID();
	$_uid = bssystem::getLoginUID();
	if(!empty($_buid)){
		$userid=$_buid;
		$type='a';
	}elseif(!empty($_uid)){
		$userid=$_uid;
		$type='u';
	}else{
		$userid=0;
		$type='-';
	}
*/

	if(isset($_SESSION['notlogin']) && isset($_SESSION['notlogin_alive'])){
		$sec = time()-strtotime($_SESSION['notlogin_alive']);
		if($sec<600){	//expired in 10 mins
			$starttime = $_SESSION['notlogin'];
//var_dump(1,$starttime);
			$_SESSION['notlogin_alive'] = date("Y-m-d H:i:s");	//update alive time, count from now on
		}else{
			$starttime = date("Y-m-d H:i:s");
//var_dump(2,$starttime);
			$_SESSION['notlogin'] = $starttime;
		}
	}else if(isset($_SESSION['singlelogin']) && isset($_SESSION['singlelogin_alive'])){
		$sec = time()-strtotime($_SESSION['singlelogin_alive']);
		if($sec<1800){	//will be expired in 30 mins when close window.
			$starttime = $_SESSION['singlelogin'];
//var_dump(3,$starttime);
			$_SESSION['singlelogin_alive'] = date("Y-m-d H:i:s");	//update alive time, count from now on
		}else{
			$starttime = date("Y-m-d H:i:s");
//var_dump(4,$starttime);
			$_SESSION['singlelogin'] = $starttime;
		}
//var_dump($_SESSION['singlelogin']);
	}
//var_dump($userid,$type,$sessionid,$starttime);
	$rs = $login->hasLogin($userid,$type,$sessionid,$starttime);
//var_dump($rs);
/*
	if($rs===false){
		$starttime = date("Y-m-d H:i:s");
	}
*/

$ss = var_export($rs,true);
file_put_contents('/var/www/html/logs/logincheck.log',sprintf("line98=ss:%s\n",$ss),FILE_APPEND);
	if($rs){
		$starttime = $rs['start_time'];
file_put_contents('/var/www/html/logs/logincheck.log',sprintf("line101,updateByKey=userid:%s,type:%S,sessionid:%s,starttime:%s\n",$userid,$type,$sessionid,$starttime),FILE_APPEND);
		$login->updateByPKey($userid,$type,$sessionid,$starttime);
	}else{	//first coming bookshelf
		//reset singlelogin verify code
		//close window and still login, and open window over half hour;
		switch($data['type']){
			case 'u':
				unset($_SESSION['notlogin']);
				$bookshelf_user = new bookshelf_user(&$db);
				$bookshelf_user->setLastLogin($userid);
				break;
			case 'a':
				unset($_SESSION['notlogin']);
				break;
			case '-':
			default:
				if(isset($_SESSION['notlogin'])){
					$starttime = $_SESSION['notlogin'];
				}else{
					$starttime = date("Y-m-d H:i:s");
					$_SESSION['notlogin'] = $starttime;
				}
				$_SESSION['notlogin_alive'] = date("Y-m-d H:i:s");
				break;
		}
//var_dump(6,$starttime);
file_put_contents('/var/www/html/logs/logincheck.log',sprintf("line127,insert=userid:%s,type:%S,sessionid:%s,starttime:%s\n",$userid,$type,$sessionid,$starttime),FILE_APPEND);
		$login->insert($userid,$type,$sessionid,$starttime);
	}
$ss = var_export($data,true);
file_put_contents('/var/www/html/logs/logincheck.log',sprintf("line8=ss:%s\n",$ss),FILE_APPEND);
	echo json_encode($data);
?>
