<?PHP
$bsid = bssystem::getBSID();
if(!empty($bsid)){
	$ConfigManager = new ConfigManager();
	$arr = array($ConfigManager->getDefineSyspath(),
								$ConfigManager->getDefineUserbase());
	foreach($arr as $cfg){
		if(is_file($cfg)){
		  include_once $cfg;
		}
	}
}
?>