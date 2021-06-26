<?PHP
require_once dirname(__FILE__).'/../../init/config.php';
$init = new init('db','tpl','inputxss','filter','ejson');
$ConfigManager = new ConfigManager();
$_path = $ConfigManager->getDefineUserbase();
if(!empty($_path)){
	include_once $_path;
}
include_once $ConfigManager->getDefineSyspath();

$mapping = array(
	'activecode'=>'/regist/step1/',
	'curriculum'=>'/user/curriculum/',
	'course'=>'/user/[userid]/',
	'expired_course'=>'/user/expired/',
	'bs'=>'/[adminid]/[bsid]/',
	'mybs'=>'/[adminid]/[bsid]/my/',
	'transcript'=>'/[adminid]/[bsid]/transcript/',
	'website'=>APPWebsiteURL
);

$AuthManager = new AuthManager();

$cmd = $fs->valid($_POST['cmd'],'cmd');
$ac = $fs->valid($_POST['ac'],'acc');
$pw = $fs->valid($_POST['pw'],'pwd');
$token = session_id();

switch($cmd){
	case 'login':
		if(!empty($ac) && !empty($pw)){
			$pw = md5($pw);
			$rs = $AuthManager->validUser($ac,$pw);
			if(empty($rs)){
				BookshelfManager::UserLogout();
				$ee->add('alert',true);
				$ee->add('logout',true);
				$ee->Error('401.12');
			}else{
				$_SESSION['_device']='mobile';
				$ee->add('token',session_id());
				$ee->Message('200');
			}
		}else if(!empty($token)){
			$rs = TokenManager::isValid($token);
			if($rs){
				$_SESSION['_device']='mobile';
				TokenManager::Login($token);

				$ee->add('token',$token);
				$ee->Message('200');
			}else{
				BookshelfManager::UserLogout();
				$ee->add('alert',true);
				$ee->add('logout',true);
				$ee->Error('401.12');
			}
		}else{
			$ee->add('alert',true);
			$ee->Error('418.100');
		}
		break;
	case 'logout':
		unset($_SESSION['_device']);
		BookshelfManager::UserLogout();
		$ee->add('logout',true);
		$ee->Message('200');
		break;
	case 'activecode':
	case 'curriculum':
	case 'course':
	case 'expired_course':
	case 'bs':
	case 'mybs':
	case 'transcript':
	case 'website':
    $login = new login(&$db);
    $rs = $login->getBySESSIONID($token);
    $data = array();
    if(empty($rs)){
    	if($cmd!='activecode'){
	      BookshelfManager::UserLogout();
	      $ee->add('alert',true);
	      $ee->add('logout',true);
	      $ee->Error('401.12');
    	}
    }else{
      $bu_id = $rs['bu_id'];
      $acc = $rs['bu_name'];
      $cname = $rs['bu_cname'];
      BookshelfManager::UserLogin($bu_id,$acc,$cname);
    }
    $ee->add('link',$mapping[$cmd]);
    $ee->Message('200');
    break;
}
?>
