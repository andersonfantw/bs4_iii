<?PHP
require_once dirname(__FILE__).'/../../init/config.php';
$init = new init('db','filter','ejson');
$cmd = $fs->valid($_GET['cmd'],'cmd');

switch($cmd){
	case 'islogin':
		//check session['adminid']
		echo $_SESSION['adminid'];
		exit;
		break;
	case 'login':
		if($_POST){
			$bs_code = (int) $fs->valid($_POST['bs'],'id');
			$account=$fs->valid($_POST['ac'],'acc');
			$password=$fs->valid($_POST['pw'],'pwd');

			$AuthManager = new AuthManager();
			$rs = $AuthManager->validBSManager($account,$password);
	    if($rs){
				$uname = $rs['u_name'];
				$ucname = $rs['u_cname'];
				$uid = $rs['u_id'];
				BookshelfManager::BSManagerLogin($uid,$uname,$ucname);
				$bookshelf = new bookshelf(&$db);
				$data = $bookshelf->getByID($bs_code);
				$bsname = $data['bs_name'];
				$bsid = $data['bs_id'];
				BookshelfManager::BSManagerLoginCookie($bsid,$bsname);
				$ee->Message('200.70');
				exit;
	    }else{
	    	$ee->Warning('401.11');
	    	exit;
	    }
		}
		break;
	case 'logout':
		BookshelfManager::BSManagerLogout();
	  break;
	case 'setlang':
		$lang = $fs->valid($_POST['lang'],'key');
		$arr = array('zh-tw','zh-cn','vi','jp','en');
		if(!in_array($lang,$arr)) $lang='zh-tw';
		$_COOKIE['currentlang'] = $lang;
		break;
}
?>
