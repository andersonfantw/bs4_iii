<?PHP
function execRegistStep3(){
	global $db;
	global $tpl;
	if($_SESSION['regist_code_valid']!='step2valid'){
		$path = WEB_URL.'/regist/step1/';
		header('Location: '.$path);
	}

	$browser = new browser();
	$_SESSION['_device'] = $browser::detect('ua_type');
	$activecode = new activecode(&$db);
	$data = $activecode->getByID($_SESSION['regist_code']);
	$bookshelf_user = new bookshelf_user(&$db);
	$data1 = $bookshelf_user->getByID($data['bu_id']);
/*
	$account = new account(&$db);
	$bsid = $data['bs_id'];
	$data1 = $account->getAccountByBSID($bsid);
	$accountname = $data1['u_name'];
	$path = WEB_URL.sprintf('/%s/%s/',$accountname,$bsid);
*/
	$path = WEB_URL.sprintf('/user/%s/',$data1['bu_name']);
/*
	$hidebutton=false;
	if(empty($bsid)){
		$hidebutton=true;
	}
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('hidebutton',$hidebutton);
*/
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('path',$path);
	
	$header=VIEW_PATH.'include/header_regist.tpl';
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('header_html', $tpl->fetch($header));
}
?>
