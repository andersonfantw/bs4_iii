<?php
require_once dirname(__FILE__).'/../init/config.php';
$init = new init('db','auth','bookshelf_auth','tpl','inputxss','filter','status','ehttp');
require_once dirname(__FILE__).'/init.php';

$m = $fs->valid($_GET['m'],'num');
$cmd = $fs->valid($_GET['cmd'],'cmd');
$path_parts = common::path_info($_SERVER['HTTP_REFERER']);
if($cmd=='export'){
	//export
	$ImportManager = new ImportManager($bs_code);
	switch($path_parts['filename']){
		case 'bookshelf_user':
			//return execute code
			$ImportManager->Export(ImportManagerModeEnum::USER);
			break;
		case 'group':
			$ImportManager->Export(ImportManagerModeEnum::GROUP);
			break;
		case 'book':
			$ImportManager->Export(ImportManagerModeEnum::BOOK);
			break;
		case 'category':
			$ImportManager->Export(ImportManagerModeEnum::CATEGORY);
			break;
	}
}else{
	if(empty($m)){
		switch($path_parts['filename']){
			case 'bookshelf_user':
				$m = ImportManagerModeEnum::USER;
				break;
			case 'group':
				$m = ImportManagerModeEnum::GROUP;
				break;
			case 'category':
				$m = ImportManagerModeEnum::CATEGORY;
				break;
			case 'book':
				$m = ImportManagerModeEnum::BOOK;
				break;
			case 'account':
				$m = ImportManagerModeEnum::MANAGER;
				break;
			default:
				header('LOCATION: index.php?op=logout');exit;
				break;
		}
		header('LOCATION: import.php?m='.$m);
	}
	$tpl->assign('mode',$m);
	$tpl->display('backend/import.tpl');
}
?>
