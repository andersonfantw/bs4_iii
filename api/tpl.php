<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','tpl','filter','ejson');
$type = $fs->valid($_GET['t'],'cmd');

switch($type){
	case 'HeaderAdminLogin':
		$_uid = bssystem::getLoginUID();
		if($_uid>0){
			$tplpath = VIEW_PATH.'include/header_adminlogin.tpl';
		}
		break;
	case 'HeaderUserLogin':
		$_buid = bssystem::getLoginBUID();
		if($_buid>0){
			$tplpath = VIEW_PATH.'include/header_userlogin.tpl';
		}
		break;
	case 'Header':
	default:
		$tplpath=VIEW_PATH.'include/header.tpl';
		break;
}
if(!empty($tplpath)){
	echo $tpl->fetch($tplpath);
}
?>
