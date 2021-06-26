<?PHP
function execStartup(){
	$info = LicenseManager::getSystemActiveInfo();
	if($info['active']){
		header('Location: '.WEB_URL.'/');exit;
	}
	if($_POST['cmd']=='enabled'){
		LicenseManager::registSystemActive(1);
		header('Location: '.WEB_URL.'/');exit;
	}
	$GLOBALS[GLOBAL_IDENTIFIER]["smarty"]->assign('activedate',date('Y/m/d'));
}
?>