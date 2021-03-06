<?PHP
require_once dirname(__FILE__)."/init/config.php";
$init = new init('db','filter','tpl','ehttp');
$Reserved=array('list','user','forget','startup','expired','regist','promote','images','errorpage','plugin','hosts','scripts','languages','ga','api','backend','trial','subscribe','search','signout');

//global $adminid, $bs_code;
unset($adminid);
unset($bs_code);

$cmd = $fs->valid($_GET['cmd'],'cmd');
$f = $fs->valid($_GET['f'],'key');
$n = $fs->valid($_GET['n'],'name');

# /$account/$bsid/$device
$_acc = $fs->valid($_GET['account'],'acc');
$bs_code = (int)$fs->valid($_GET['bsid'],'id');
$device = $fs->valid($_GET['device'],'cmd');

$page = $fs->valid($_GET['page'],'cmd');
$func = $fs->valid($_GET['func'],'cmd');

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

switch($func){
	case 'manifest':
		$scandir = array('/backend','/css','/images','/languages','/plugin/blackboard',
											'/plugin/login','/plugin/meeting','/plugin/seminar','/scripts');
		$Manifest = new Manifest(APP_CACHE_VERSION);
		switch($page){
			case 'mobile':
				$Manifest->enable(MOBILE_CACHE);
				break;
			case 'desktop':
				$Manifest->enable(DESKTOP_CACHE);
				break;
		}
		foreach($scandir as $dir){
			$Manifest->scanDirectory($dir);
		}
		$Manifest->scanDirectory('/hosts','/[0-9]+\/[0-9]+\/uploadfiles\/s_.+/');
		$Manifest->output();
		exit;
		break;
	case 'js_setting':
		$Javascript = new Javascript();
		switch($page){
			case 'mobile':
				$Javascript->ajaxCache(MOBILE_CACHE);
				break;
			case 'desktop':
				$Javascript->ajaxCache(DESKTOP_CACHE);
				break;
		}
		$Javascript->output();
		exit;
		break;
}

if(!empty($_acc) && $_acc!='demo' && !in_array($_acc,$Reserved)){
	if(LicenseManager::chkAuth(MEMBER_SYSTEM,MemberSystemEnum::NAS_LDAP)){
		$_acc = LDAP_DOMAIN_PREFIX.$_acc;
	}
}

if(!empty($_acc) && !in_array($_acc,$Reserved)){
	if(!isset($_SESSION[SITE_PREFIX.$_acc]) || !isset($_SESSION['accmapping'.$bs_code])){
		$account = new account($db);
		$data = $account->getByName($_acc);
		$adminid = $data['u_id'];
		if(!empty($adminid)){
			//set uid, u_name mapping
			$_SESSION[SITE_PREFIX.$_acc] = $adminid;
			//set uid
			$_SESSION[SITE_PREFIX.'uid'] = $adminid;
			//set bsid, uid mapping
			$_SESSION['accmapping'.$bs_code] = $adminid;
		}
	}else{
		//get uid
		$adminid = $_SESSION[SITE_PREFIX.$_acc];
		$_SESSION[SITE_PREFIX.'uid'] = $_SESSION[SITE_PREFIX.$_acc];
	}

	if(empty($adminid)){
	  $ee->Error('404');
	}else{
		if($bs_code!='list'){
			//set bsid
			$_SESSION[SITE_PREFIX.'bsid'] = $bs_code;
			setcookie(SITE_PREFIX.'bsid',$bs_code,0,'/');
      
      $ConfigManager = new ConfigManager($adminid,$bs_code);
		}
		$str = PageMapping((isset($adminid)?intval($adminid):$_acc), 
											(isset($bs_code)?intval($bs_code):$bs_code),
											$device);
	}
}elseif(!empty($page)){
	if(in_array($func,array('curriculum','transcript','expired'))){
		$str = PageMapping($page,$func,'');
	}else{
		if($page=='user'){
			//check if login, if not redirect to bookshelf list
			$buid = bssystem::getLoginBUID();
			if(empty($buid)){
				header('Location: /');
			}
			$bookshelf_user = new bookshelf_user($db);
			$data = $bookshelf_user->getByName($func);
			if($data){
				$buid = $data['bu_id'];
				$str = PageMapping('user', intval($buid), $device);
			}else{
				$ee->Error('404');
			}
		}else{
			$str = PageMapping($page,$func,'');
		}
	}
}else{
	$str = PageMapping($_acc,$bs_code,$device);	
}

/*
config load seq:
/uid/bsid/sys_config.php: getDefineUserConfigbase()
/uid/bsid/bs_config.php: getDefineUserbase()
sys_config.php: getDefineSysConfigpath()
bs_config.php: getDefineSyspath()
*/
$arr_cfg = array();
list($folder,$page,$module) = explode(',',$str);
if(isset($module)){
	$info = LicenseManager::getSystemActiveInfo();
	if(!$info['active']){
		switch($info['mode']){
			case -1:
				$path = WEB_URL.'/expired/';
				if($module!='Expired'){
					header('Location: '.$path);
				}
				break;
			default:
				$path = WEB_URL.'/startup/';
				if($module!='Startup'){
					header('Location: '.$path);exit;
				}
				break;
		}
	}
	$ConfigManager = new ConfigManager();
	switch($module){
		case 'Search':
		case 'AdvSearch':
		case 'SearchResult':
		case 'Logout':
		case 'Startup':
		case 'Expired':
		case 'Promote':
		case 'RegistStep1':
		case 'RegistStep2':
		case 'RegistStep3':
			$params = array();
			$arr_cfg = array($ConfigManager->getDefineSyspath());
			break;
		case 'ForgetStep1':
		case 'ForgetStep2':
			$code = $fs->valid($_GET['param'],'key');
			$params = array($code);
			$arr_cfg = array($ConfigManager->getDefineSyspath());
			break;
		case 'ListByUser':
		case 'ExpiredCourse':
			$params = array($buid,$device);
			$arr_cfg = array();
			break;
		case 'ListAll':
			$params = array();
			$arr_cfg = array();
			break;
		case 'ListByAccount':
			$params = array($adminid);
			$arr_cfg = array($ConfigManager->getDefineSyspath());
			break;
		case 'Transcript':
		case 'Curriculum':
			$adminid = 0;
			$bs_code = 0;
			$params = array($adminid,$bs_code);
			break;
		case 'Trial':
		case 'Subscribe':
			$params = array($bs_code);
			$arr_cfg = array($ConfigManager->getDefineSyspath());
			break;
		case 'Desktop':
		case 'Bookshelf':
		case 'Guide':
		default:
/*
			if(common::validSessionID($token)){
				$login = new login($db);
				$rs = $login->getBySESSIONID($token);
				$bu_id = $rs['bu_id'];
				$_acc = $rs['bu_name'];
				$cname = $rs['bu_cname'];
				BookshelfManager::UserLogin($bu_id,$_acc,$cname);
			}
*/
			$token = $fs->valid($_POST['token'],'sessionid');
			$params = array($adminid,$bs_code,$token);
			$arr_cfg = array(
				//$ConfigManager->getDefineUserConfigbase(),
				$ConfigManager->getDefineUserbase(),
				$ConfigManager->getDefineSysConfigpath(),
				$ConfigManager->getDefineSyspath());
			$cv_acc = common::getcookie('acc');
			$sv_adminacc = bssystem::getLoginUAcc();
			if(isset($cv_acc) && isset($sv_adminacc)){
				//if($_SESSION['accmapping'.$bs_code]!=$_SESSION[SITE_PREFIX.'uid']){
				if($_acc!=$sv_adminacc){
					header('Location:'.WEB_URL.'/'.$cv_acc.'/');exit;
				}
			}
			break;
	}
}else{
	$ee->Error('404');
}

//Load host config
foreach($arr_cfg as $cfg){
	if(is_file($cfg)){
	  include_once $cfg;
	}else{
		echo 'Config file is missing! Please contact Administrator!';exit;
	}
}

//download
list($host,$port) = explode(':',$_SERVER['HTTP_HOST']);
if($cmd=='download'){
	//if(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST)==$host){
	if(isset($_SESSION['uid']) || ($buid>0)){
		$book = new book($db);
		$data = $book->getByKey($f);
		if(empty($data)){
			$ee->Error('404');
		}
		$path_parts = common::path_info($n);
		$filename = $data['b_name'];
		$subname = $path_parts['extension'];
	
		if(ENABLE_DECENTRALIZED){
			$bsid = $data['bs_id'];
			$bookshelf = new bookshelf($db);
			$data1 = $bookshelf->getByID($bsid);
			$uid = $data1['u_id'];
			$url = BookManager::setCyberhoodOrifileURL($filename.'.'.$subname,$f,false,$uid,$bsid);
			header('LOCATION: '.$url);
			exit;
		}else{
			$path_parts = common::path_info($n);
			$file = FILE_PATH.'/'.$f.'.'.$path_parts['extension'];
	
			$ConfigManager = new ConfigManager();
		  $_path = $ConfigManager->getDefineUserbase();
		  if(!empty($_path)){
		  	include_once $_path;
		  }
			//check auth
			if(MEMBER){
				$buid = bssystem::getLoginBUID();
				if(isset($buid)){
					common::download($file,$n);
				}
			}else{
				if(isset($_SESSION['uid'])){
					common::download($file,$n);
				}
			}
		}
	}
	$ee->Error('404');
}
if(empty($_SESSION['uid'])){
	$_SESSION['uid']=0; //for the prev book valid
}

$path = MODULE_PATH . $folder ."/". $module . ".php";
if (is_file($path) === true) {
    require_once($path);
}

$module_func = 'exec'.str_replace('_','',$module);
if (is_callable($module_func)) {
	call_user_func_array($module_func,$params);
	$path = VIEW_PATH.$folder.'/'.$page.'.html';
	if (is_file($path) === true) {
		$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->display($path);
	}
}

function PageMapping($adminid,$bsid,$device){
	$t_uid = (empty($adminid))?'':gettype($adminid);
	$t_bsid = (empty($bsid))?'':gettype($bsid);
	$t_device = (empty($device))?'':$device;

	if($t_uid=='string') $t_uid=$adminid;
	if($t_bsid=='string') $t_bsid=$bsid;
	if($t_device=='string') $t_deviced=$device;

	$key = sprintf('%s,%s,%s',$t_uid,$t_bsid,$t_device);
	//folder,php,module
	if($_SESSION['_device']=='mobile' || !empty($_POST['token'])){
		$mapping = array(',,'		=>'list,mobi_list,ListAll',
				'list,,'						=>'list,mobi_list,ListAll',
				'user,integer,'			=>'list,mobi_list,ListByUser',
				'user,curriculum,'	=>'page,curriculum,Curriculum',
				'user,transcript,'	=>'page,transcript,Transcript',
				'user,expired,'			=>'list,mobi_expired,ExpiredCourse',
				'integer,,'					=>'list,mobi_list,ListByAccount',
				'integer,list,'			=>'list,mobi_list,ListByAccount',
				'integer,integer,'	=>'desktop,mobi_desktop,Desktop',
				'integer,integer,bs'=>'bookshelf,bookshelf,Bookshelf',
				'integer,integer,my'=>'desktop,mobi_desktop_my,Desktop',
				//'search,,'					=>'page,search,Search',
				'search,,'					=>'search,search,Search',
				'search,adv,'				=>'search,advsearch,AdvSearch',
				'search,list,'			=>'search,searchresult,SearchResult',
				'search,manual,'		=>'search,manual,Manual',
				'signout,,'					=>'search,logout,Logout',
				'startup,,'					=>'startup,startup,Startup',
				'expired,,'					=>'startup,expired,Expired',
				'integer,integer,guide'				=>'page,guide,Guide',
				'integer,integer,transcript'	=>'page,transcript,Transcript',
				'integer,integer,curriculum'	=>'page,curriculum,Curriculum',
				'trial,integer,'		=>'page,trial,Trial',
				'subscribe,integer,'=>'page,subscribe,Subscribe',
				'promote,,'					=>'promote,promote,Promote',
				'regist,step1,'			=>'regist,registstep1,RegistStep1',
				'regist,step2,'			=>'regist,registstep2,RegistStep2',
				'regist,step3,'			=>'regist,registstep3,RegistStep3',
				'forget,step1,'			=>'forget,forgetstep1,ForgetStep1',
				'forget,step2,'			=>'forget,forgetstep2,ForgetStep2');
				//'signout,,'					=>'page,logout,Logout');
	}else{
		$mapping = array(',,'		=>'list,list,ListAll',
				'list,,'						=>'list,list,ListAll',
				'user,integer,'			=>'list,list,ListByUser',
				'user,curriculum,'	=>'page,curriculum,Curriculum',
				'user,transcript,'	=>'page,transcript,Transcript',
				'user,expired,'			=>'list,expired,ExpiredCourse',
				'integer,,'					=>'list,list,ListByAccount',
				'integer,list,'			=>'list,list,ListByAccount',
				'integer,integer,'	=>'desktop,desktop,Desktop',
				'integer,integer,bs'=>'bookshelf,bookshelf,Bookshelf',
				//'search,,'					=>'page,search,Search',
				'search,,'					=>'search,search,Search',
				'search,adv,'				=>'search,advsearch,AdvSearch',
				'search,list,'			=>'search,searchresult,SearchResult',
				'search,manual,'		=>'search,manual,Manual',
				'signout,,'					=>'search,logout,Logout',
				'startup,,'					=>'startup,startup,Startup',
				'expired,,'					=>'startup,expired,Expired',
				'integer,integer,guide'				=>'page,guide,Guide',
				'integer,integer,transcript'	=>'page,transcript,Transcript',
				'integer,integer,curriculum'	=>'page,curriculum,Curriculum',
				'trial,integer,'		=>'page,trial,Trial',
				'subscribe,integer,'=>'page,subscribe,Subscribe',
				'promote,,'					=>'promote,promote,Promote',
				'regist,step1,'			=>'regist,registstep1,RegistStep1',
				'regist,step2,'			=>'regist,registstep2,RegistStep2',
				'regist,step3,'			=>'regist,registstep3,RegistStep3',
				'forget,step1,'			=>'forget,forgetstep1,ForgetStep1',
				'forget,step2,'			=>'forget,forgetstep2,ForgetStep2');
				//'signout,,'					=>'page,logout,Logout');
	}
	return $mapping[$key];
}
?>
