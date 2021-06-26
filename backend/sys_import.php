<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','sysauth','tpl','inputxss','filter','status','ehttp');
$ImportManager = new ImportManager();

$m = $fs->valid($_GET['m'],'num');
$cmd = $fs->valid($_GET['cmd'],'cmd');
$path_parts = common::path_info($_SERVER['HTTP_REFERER']);
if(($path_parts['filename']=='sys_account') && empty($m)){
	switch($cmd){
		case 'export':
			$ImportManager->Export(ImportManagerModeEnum::MANAGER);
			exit;
			break;
		default:
			$m = ImportManagerModeEnum::MANAGER;
			header('LOCATION: sys_import.php?m='.$m);
			break;
	}
}elseif(empty($m)){
	switch($cmd){
		case 'import_user':
			$m = ImportManagerModeEnum::USER;
			break;
		case 'import_group':
			$m = ImportManagerModeEnum::GROUP;
			break;
		case 'export_user':
			$ImportManager->Export(ImportManagerModeEnum::USER);
			exit;
			break;
		case 'export_group':
			$ImportManager->Export(ImportManagerModeEnum::GROUP);
			exit;
			break;
	}
	header('LOCATION: sys_import.php?m='.$m);
}
$tpl->assign('mode',$m);
$tpl->display('backend/sys_import.tpl');
?>
