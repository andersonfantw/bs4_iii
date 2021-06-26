<?PHP
function execExpired(){
	$info = LicenseManager::getSystemActiveInfo();
	if($info['active']){
		header('Location: '.WEB_URL.'/');exit;
	}
}
?>